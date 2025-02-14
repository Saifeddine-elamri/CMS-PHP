<?php
namespace App\Core;
use App\Core\Exception;

class Template
{
    private $templateDir;
    private $cacheDir;
    private $sections = [];
    private $layout = null; // Ajoute la déclaration de la propriété $layout

    public function __construct($templateDir = 'app/Views', $cacheDir = 'app/Core/Cache')
    {
        $this->templateDir = $templateDir;
        $this->cacheDir = $cacheDir;
    }

    public function render($template, $variables = [])
    {
        $compiledFile = $this->compileTemplate($template);

        // Extraction des variables pour les utiliser dans le template
        extract($variables);
        ob_start();
        include $compiledFile;
        return ob_get_clean();
    }

    // Définir un layout (fichier parent)
    public function extend($layout)
    {
        $this->layout = $layout;
    }

    // Démarrer une section
    public function startSection($name)
    {
        ob_start();  // Commence à capturer le contenu
        $this->sections[$name] = true;
    }

    // Finir une section
    public function endSection()
    {
        $content = ob_get_clean();  // Récupère le contenu de la section
        $this->sections[] = $content;
    }

    // Récupérer une section spécifique
    public function yieldSection($name)
    {
        return isset($this->sections[$name]) ? $this->sections[$name] : null;
    }

    private function compileTemplate($template)
    {
        $templateFile = $this->templateDir . '/' . $template . '.tpl.php';
        $cacheFile = $this->cacheDir . '/' . md5($template) . '.php';

        // Compiler seulement si le template a été modifié
        if (!file_exists($cacheFile) || filemtime($templateFile) > filemtime($cacheFile)) {
            $content = file_get_contents($templateFile);

            // Supprimer les commentaires : {{-- Commentaire --}}
            $content = preg_replace('/\{\{\-\-(.*?)\-\-\}\}/s', '', $content);

            // Compiler les variables : {{ $variable }}
            $content = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<?php echo htmlspecialchars($1, ENT_QUOTES, "UTF-8"); ?>', $content);

            // Compiler la directive @extends('layout')
            $content = preg_replace_callback('/@extends\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) {
                // Récupérer le layout à utiliser
                $layout = $matches[1];
                
                // Remplacer la directive @extends par l'inclusion directe du fichier de layout
                return '<?php include "Views/' . addslashes($layout) . '.php"; ?>';
            }, $content);
                    

            // Compiler la directive @section('sectionName')
            $content = preg_replace_callback('/@section\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) {
                // Démarrer une section
                $sectionName = $matches[1];
                return '<?php $this->startSection("' . addslashes($sectionName) . '"); ?>';
            }, $content);

            // Compiler la directive @endsection
            $content = preg_replace('/@endsection/', '<?php $this->endSection(); ?>', $content);
            // Compiler la directive @if
            $content = preg_replace_callback('/@if\s*\(((?:[^()]|\((?:[^()]|\([^()]*\))*\))*)\)/', function ($matches) {
                // On récupère la condition à l'intérieur des parenthèses
                $condition = $matches[1];
                
                // Transformation directe de la condition en PHP
                return '<?php if (' . $condition . '): ?>';
            }, $content);
            
            // Compiler la directive @elseif
            $content = preg_replace_callback('/@elseif\s*\(((?:[^()]|\((?:[^()]|\([^()]*\))*\))*)\)/', function ($matches) {
                // On récupère la condition à l'intérieur des parenthèses
                $condition = $matches[1];
            
                // On transforme directement la condition en code PHP
                return '<?php elseif (' . $condition . '): ?>';
            }, $content);

            // Compiler la directive @elseif
            $content = preg_replace_callback('/@elif\s*\(((?:[^()]|\((?:[^()]|\([^()]*\))*\))*)\)/', function ($matches) {
                // On récupère la condition à l'intérieur des parenthèses
                $condition = $matches[1];
            
                // On transforme directement la condition en code PHP
                return '<?php elseif (' . $condition . '): ?>';
            }, $content);
            
            // Compiler la directive @else (pas besoin de parenthèses ici, mais on la transforme en PHP)
            $content = preg_replace('/@else/', '<?php else: ?>', $content);

            // Compiler la directive @endif (pas besoin de parenthèses ici, mais on la transforme en PHP)
            $content = preg_replace('/@endif/', '<?php endif; ?>', $content);


            // Compiler les boucles : @foreach, @endforeach
            $content = preg_replace_callback('/@foreach\s*\((.*?)\)/', function ($matches) {
                // Vérification pour la syntaxe de foreach
                $condition = $matches[1];
                // Ici, tu pourrais ajouter des validations supplémentaires si nécessaire
                return '<?php foreach (' . $condition . '): ?>';
            }, $content);
            $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

            // Compiler les boucles : @for, @endfor
            $content = preg_replace_callback('/@for\s*\((.*?)\)/', function ($matches) {
                // Vérification pour la syntaxe de for
                $condition = $matches[1];
                return '<?php for (' . $condition . '): ?>';
            }, $content);
            $content = preg_replace('/@endfor/', '<?php endfor; ?>', $content);

            // Compiler les inclusions : @include('fichier')
            $content = preg_replace_callback('/@include\s*\(\s*[\'"](.+?)[\'"]\s*\)/', function ($matches) {
                // Assurer la gestion des erreurs pour l'inclusion de fichiers
                $filePath = addslashes($matches[1]);
                if (file_exists($filePath)) {
                    return '<?php include("' . $filePath . '"); ?>';
                } else {
                    throw new Exception("Le fichier à inclure n'existe pas : " . $filePath);
                }
            }, $content);

            // Retourner le contenu transformé

            file_put_contents($cacheFile, $content);
        }

        return $cacheFile;
    }
}
?>

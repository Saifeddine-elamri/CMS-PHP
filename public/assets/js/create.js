    document.addEventListener('DOMContentLoaded', function () {
        const fileDropArea = document.querySelector('.file-drop-area');
        const fileInput = document.querySelector('.file-input');
        const fileMsg = document.querySelector('.file-msg');

        // Gestion de l'événement "dragover"
        fileDropArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            fileDropArea.classList.add('dragover');
        });

        // Gestion de l'événement "dragleave"
        fileDropArea.addEventListener('dragleave', function () {
            fileDropArea.classList.remove('dragover');
        });

        // Gestion de l'événement "drop"
        fileDropArea.addEventListener('drop', function (e) {
            e.preventDefault();
            fileDropArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateFileMessage(files[0]);
            }
        });

        // Gestion de l'événement "change" pour le fichier sélectionné
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                updateFileMessage(fileInput.files[0]);
            }
        });

        // Fonction pour mettre à jour le message
        function updateFileMessage(file) {
            fileMsg.textContent = file.name;
            fileDropArea.classList.add('has-file');
        }
    });

<?php 
namespace App\Middleware;

use App\Core\Session;

class RoleMiddleware {

    // Handle the admin role check
    public static function handleAdmin() {
        // Start the session to verify if the user is authenticated
        Session::start();

        // If the user is not logged in, redirect to the login page
        if (!Session::isAuthenticated()) {
            // Save the requested URL in the session before redirection
            Session::set('redirect_url', $_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit();
        }

        // Retrieve the user's role from the session
        $userRole = Session::get('user_role'); // Assuming the role is stored in the session

        // If the user's role is not 'admin', redirect to a 404 or unauthorized page
        if ($userRole !== 'admin') {
            header('Location: /404'); // You can redirect to a custom "Access Denied" page or 404 page
            exit();
        }
    }
}

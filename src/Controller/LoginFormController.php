<?php

declare(strict_types=1);    // Declares that the file will use strict types

namespace Alura\Mvc\Controller;    // Defines the namespace of the file to be imported

class LoginFormController implements Controller{    // Defines the class LoginFormController that implements the Controller interface
    public function processaRequisicao(): void{    // Defines the method processaRequisicao that returns void
        if($_SESSION['logado']){    // If the session is logged in
            header('Location: /');    // Redirects to the home page
            return;    // Returns nothing
        }
        require_once __DIR__ . '/../../views/login-form.php';    // Requires the login-html.php file
    }
}
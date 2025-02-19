<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;
class LogoutController implements Controller{
    public function processaRequisicao(): void{
        session_destroy();  // Destroi a sessão
        header('Location: /login');    // Redireciona para a página de login
        exit();
    }
}
<?php

declare(strict_types=1);

use Alura\Mvc\Controller\{
    Controller,
    DeleteVideoController,
    EditVideoController,
    Error404Controller,
    NewVideoController,
    VideoFormController,
    VideoListController,
    LoginFormController,
    DeleteImageVideoController,
    JsonVideosListController,
    NewJsonVideoController,
    LogoutController,
};
use Alura\Mvc\Repository\VideoRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$dbPath = __DIR__ . '/../banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");
$videoRepository = new VideoRepository($pdo);

$routes = require_once __DIR__ . '/../config/routes.php';   // Importa o arquivo de rotas

// Receber a rota e o método HTTP, se não existir, atribuir um valor padrão, nesse caso, a raiz "/"
$pathInfo = $_SERVER['PATH_INFO'] ?? '/'; // Recebe a rota
$httpMethod = $_SERVER['REQUEST_METHOD'];   // Recebe o método HTTP. GET, POST, PUT, DELETE

// Verificar se o usuário está logado
// Sempre que eu quiser verificar se o usuário está logado, eu sempre preciso iniciar a sessão
// ou te-la iniciada em outro lugar
// Verifica se foi enviado um cookie de sessão, se não, cria um novo.
if (session_status() === PHP_SESSION_NONE) {
    session_start();    // O session_start() NO FRONT CONTROLLER, SEMPRE DEVE SER A PRIMEIRA COISA A SER EXECUTADA
}
session_regenerate_id();    // Regenera o id da sessão, para evitar ataques de sessão

$isLoginRoute = $pathInfo === '/login'; // Verifica se a rota é a de login se sim, não precisa verificar se o usuário está logado
// se não for feito isso, ele será redirecionado para a página de login em LOOP INFINITO, se não estiver logado
// Abaixo, verifica se o usuário está logado e se a rota é diferente de login
if(!array_key_exists('logado', $_SESSION) && !$isLoginRoute){   //Valide se o usuário está logado e se a rota é diferente de login
    // Se o usuário não estiver "logado" na sessão, redireciona para a página de login
    header('Location: /login');
    return;
}

// Se já existir, ele pega o cookie e continua a sessão

// Regenerar o id da sessão
if (isset($_SESSION['logado'])) {   // Se o usuário estiver logado
    $originalInfo = $_SESSION['logado'];    // Atribui a variável $originalInfo, o valor da sessão
    unset($_SESSION['logado']); // Remove o valor da sessão
    session_regenerate_id();    // Regenera o id da sessão, para evitar ataques de sessão
    $_SESSION['logado'] = $originalInfo;
}    // Regenera o id da sessão, para evitar ataques de sessão

// VERIFIACAR NA DOCUMENTAÇÃO DO PHP, COMO FAZER ISSO DE FORMA SEGURA USANDO OS PARAMETROS CORRETOS
// Warning: Currently, session_regenerate_id does not handle an unstable network well, e.g. Mobile and WiFi network. 
// Therefore, you may experience a lost session by calling session_regenerate_id.

// Verificar se a rota existe no array de rotas
$key = "$httpMethod|$pathInfo"; // Cria uma chave para buscar a rota no array de rotas
if (array_key_exists($key, $routes)) {  // Verifica se a chave existe no array de rotas
    $controllerClass = $routes["$httpMethod|$pathInfo"];    // Se existir, atribui a classe do controller a variável $controllerClass

    $controller = new $controllerClass($videoRepository);   // Instancia o controller
} else {    // Se a rota não existir, redirecionar para a página de erro 404
    $controller = new Error404Controller();
}
/** @var Controller $controller */
$controller->processaRequisicao();

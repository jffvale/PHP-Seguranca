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
session_start();    // Verifica se foi enviado um cookie de sessão, se não, cria um novo.
// Se já existir, ele pega o cookie e continua a sessão

$isLoginRoute = $pathInfo === '/login'; // Verifica se a rota é a de login se sim, não precisa verificar se o usuário está logado
// se não for feito isso, ele será redirecionado para a página de login em loop infinito, se não estiver logado
if(!array_key_exists('logado', $_SESSION) && !$isLoginRoute){
    header('Location: /login');
    return;
}

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

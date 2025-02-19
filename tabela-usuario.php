<?php

declare(strict_types=1);    // Declaração de tipos estritos

// Inclui o arquivo de autoload do Composer
$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT,
    password TEXT
);');

// Exemplo: php cria-usuario.php "email@exemplo.com" "senha123456". Usuário e senha passados como argumentos, cadastrar no banco de dados
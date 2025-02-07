<?php

declare(strict_types=1);    // Declaração de tipos estritos

// Inclui o arquivo de autoload do Composer
$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$pdo->exec('CREATE TABLE videos(
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    url TEXT, 
    title TEXT, 
    image_path TEXT
    );');
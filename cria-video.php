<?php

declare(strict_types=1);

$dbPath = __DIR__ . '/banco.sqlite';

if (!file_exists($dbPath)) {
    die("Erro: O arquivo do banco de dados não existe.\n");
}

$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($argc < 4) {
    die("Uso: php cria-video.php <url> <title> <>image_path\n");
}

// Para criar via php, usar o comando: php cria-video.php url title image_path
// Exemplo: php cria-video.php "https://www.youtube.com/watch?v=1" "Video 1" "images/video1.png"

// Receber os argumentos
$url = $argv[1];  // O $arqv[1] é o primeiro argumento passado pelo usuário
$title = $argv[2];   // O $arqv[2] é o segundo argumento passado pelo usuário
$imagePath = $argv[3];   // O $arqv[3] é o terceiro argumento passado pelo usuário

try {
    $sql = 'INSERT INTO videos (url, title, image_path) VALUES (?, ?, ?);';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $url);
    $statement->bindValue(2, $title);
    $statement->bindValue(3, $imagePath);

    if ($statement->execute()) {
        echo "Vídeo criado com sucesso!\n";
    } else {
        echo "Erro ao criar o vídeo.\n";
    }
} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage() . "\n");
}
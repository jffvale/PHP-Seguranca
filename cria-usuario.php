<?php
declare(strict_types=1);

$dbPath = __DIR__ . '/banco.sqlite';

if (!file_exists($dbPath)) {
    die("Erro: O arquivo do banco de dados não existe.\n");
}

$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Verificar se o usuário passou os argumentos corretamente
if ($argc < 3) {
    die("Uso: php cria-usuario.php <email> <senha>\n");
}

// Para criar via php, usar o comando: php cria-usuario.php email senha
// Exemplo: php cria-usuario.php "email@exemplo.com" "senha123456". Usuário e senha passados como argumentos, cadastrar no banco de dados

// Receber os argumentos
$email = $argv[1];  // O $arqv[1] é o primeiro argumento passado pelo usuário
$password = $argv[2];   // O $arqv[2] é o segundo argumento passado pelo usuário
// usar algoritmo de hash para senha
// $hash = password_hash($password, PASSWORD_DEFAULT); // PASSWORD_DEFAULT é um algoritmo seguro, mas temos mais modernos
$hash = password_hash($password, PASSWORD_ARGON2ID); // PASSWORD_ARGON2ID é um algoritmo mais moderno e seguro

try {
    $sql = 'INSERT INTO users (email, password) VALUES (?, ?);';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $email);
    $statement->bindValue(2, $hash);

    if ($statement->execute()) {
        echo "Usuário criado com sucesso!\n";
    } else {
        echo "Erro ao criar o usuário.\n";
    }
} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage() . "\n");
}
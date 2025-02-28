<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

class LoginController implements Controller{

    private \PDO $pdo;

    public function __construct(){  // Constructor to create a new PDO object
    // Buscar o usuário no DB pelo email (somente email)
    // Se o usuário não existir, redirecionar para a página de login
    $dbPath = __DIR__ . '/../../banco.sqlite';
    $this->pdo = new \PDO("sqlite:$dbPath");    // \PDO is a class from the global namespace
    }

    public function processaRequisicao(): void{
        // Receber o email do formulário de login com o método POST e um filtro de validação de email
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);  // Receives the email from the form created in login-form.php
        
        // Receber o password do formulário de login com o método POST sem filtro de validação de email
        $password = filter_input(INPUT_POST, 'password');  // Receives the password from the form created in login-form.php

        $sql = 'SELECT * FROM users WHERE email = ?';   // SQL query to select the user from the DB
        // Preparar a query para execução
        $statement = $this->pdo->prepare($sql);  // Prepares the query to be executed
        $statement->bindValue(1, $email); // Binds the value of the email to the query (único parâmetro da query)
        $statement->execute();   // Executes the query

        // Receber o usuário do DB
        $userData = $statement->fetch(\PDO::FETCH_ASSOC); // Fetches the user from the DB
        // Para comparar a senha do usuário com a senha do formulário, usamos a função password_verify se a senha estiver correta
        // Se a senha não estiver correta a função password_verify retorna valor vazio (?? '' - no final do code)
        $correctPassword = password_verify($password, $userData['password'] ?? ''); // Compares the password from the form with the password from the DB
        
        // Verificar se a senha precisa ser revalidada
        if(password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)){    // Verifies if the password needs to be rehashed, ponto para adicionar o novo algoritmo de hash a ser testado
            $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?'); // Prepara a query para atualizar a senha do usuário
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2ID)); // Atualiza a senha do usuário com o algoritmo PASSWORD_ARGON2ID
            $statement->bindValue(2, $userData['id']); // Atualiza a senha do usuário com o id do usuário
            $statement->execute();   // Executa a query
        }
        if ($correctPassword) {
            $_SESSION['logado'] = true; // Cria uma variável de sessão para indicar que o usuário está logado
            // Como agora eu tenho uma variável de sessão, eu posso verificar se o usuário está logado ou não em qualquer página que eu quiser
            // Se a senha estiver correta, redirecionar para a página de vídeos
            header('Location: /');
        } else {
            // Se a senha estiver incorreta, redirecionar para a página de login
            header('Location: /login?sucesso=0');   // Redirects to the login page com um parâmetro de sucesso igual a 0
        }
        // Tem que testar se o usuário e o password estão, ambos corretos. Não só o usuário, pois é uma 
        // questão de segurança não informar se o usuário está correto ou não.
    }

    /** IDEALMENTE, DEVERÍAMOS: (Criar posteriormente)
     * Criar uma classe de usuário para representar o usuário
     * Criar um repositório de usuário para buscar o usuário no DB
     */
}
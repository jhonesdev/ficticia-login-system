<?php

require('connection.php');

class auth
{

    private $conn;

    function __construct()
    {
        $this->conn = new connection();
    }

    function login()
    {
        $this->conn->connect();
        $user = $this->conn->connection()->prepare("SELECT * FROM `users` WHERE `username` = ?");        
        $user->bind_param('s', $_POST['username']);
        $user->execute();
        $user = $user->get_result();
        $user = $user->fetch_assoc();
        $this->conn->disconnect();
        if (!empty($user) && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['logged']['id'] = $user['id'];
            $_SESSION['logged']['name'] = $user['name'];
            $_SESSION['logged']['username'] = $user['username'];
            return json_encode(['message' => 'authenticated.']);
        } else {
            http_response_code(401);
            return json_encode(['message' => 'Usuário ou senha incorreto.']);
        }
    }

    function logout()
    {
        session_destroy();
        header('Location: /index.html');
    }
}

session_start();

$auth = new auth();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        echo $auth->login();
        break;

    case 'GET':
        $auth->logout();
        break;

    default:
        http_response_code(404);
        break;
}

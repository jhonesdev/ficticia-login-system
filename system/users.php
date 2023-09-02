<?php

require('connection.php');

class users
{

    private $conn;

    function __construct()
    {
        $this->conn = new connection();
    }

    function store()
    {
        if (empty($_POST['name'])) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor informe o nome do usuário.']);
        }

        if (empty($_POST['username'])) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor informe o e-mail do usuário.']);
        }

        if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor digite um e-mail válido.']);
        }

        if (strlen($_POST['password']) < 8) {
            http_response_code(400);
            return json_encode(['message' => 'A Senha deve conter no minimo 8 digitos.']);
        }

        $specialChars = preg_match('@[^\w]@', $_POST['password']);
        if (!$specialChars) {
            http_response_code(400);
            return json_encode(['message' => 'A Senha deve conter pelo menos um caracter especial.']);
        }

        preg_match_all('!\d+\.*\d*!', $_POST['password'], $passNumbers);
        if (!empty($passNumbers)) {
            foreach ($passNumbers[0] as $numbers) {
                $numbers = str_split($numbers);
                if (sizeof(array_diff_assoc(range(min($numbers), max($numbers)), $numbers)) == 0 && count($numbers) > 2) {
                    http_response_code(400);
                    return json_encode(['message' => 'A senha não deve conter uma sequencia de numeros.']);
                }
            }
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $this->conn->connect();

        try {

            $user = $this->conn->connection()->prepare('SELECT * FROM users WHERE username = ?');
            $user->bind_param('s', $_POST['username']);
            $user->execute();
            $user = $user->get_result();
            if (!empty($user->fetch_assoc())) {
                http_response_code(400);
                return json_encode(['message' => 'E-mail já cadastrado.']);
            }

            $user = $this->conn->connection()->prepare('INSERT INTO users (name, username, password) VALUES (?, ?, ?)');
            $user->bind_param('sss', $_POST['name'], $_POST['username'], $password);
            $user->execute();

            $this->conn->disconnect();

            unset($_SESSION['first_access']);

            return json_encode(['message' => 'Cadastro realizado com sucesso.']);
        } catch (\Throwable $th) {
            http_response_code(500);
            return json_encode(['message' => 'Ocorreu um erro inesperado. Tente novamente.']);
        }
    }

    function update()
    {
        parse_str(file_get_contents("php://input"), $params);

        if (empty($params['id'])) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor informe um usuário.']);
        }

        if (empty($params['name'])) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor informe o nome do usuário.']);
        }

        if (empty($params['username'])) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor informe o e-mail do usuário.']);
        }

        if (!filter_var($params['username'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor digite um e-mail válido.']);
        }

        if (!empty($params['password'])) {
            if (strlen($params['password']) < 8) {
                http_response_code(400);
                return json_encode(['message' => 'A Senha deve conter no minimo 8 digitos.']);
            }

            $specialChars = preg_match('@[^\w]@', $params['password']);
            if (!$specialChars) {
                http_response_code(400);
                return json_encode(['message' => 'A Senha deve conter pelo menos um caracter especial.']);
            }

            preg_match_all('!\d+\.*\d*!', $params['password'], $passNumbers);
            if (!empty($passNumbers)) {
                foreach ($passNumbers[0] as $numbers) {
                    $numbers = str_split($numbers);
                    if (sizeof(array_diff_assoc(range(min($numbers), max($numbers)), $numbers)) == 0 && count($numbers) > 2) {
                        http_response_code(400);
                        return json_encode(['message' => 'A senha não deve conter uma sequencia de numeros.']);
                    }
                }
            }

            $password = password_hash($params['password'], PASSWORD_DEFAULT);
        }

        $this->conn->connect();

        try {

            $user = $this->conn->connection()->prepare('SELECT * FROM users WHERE id = ?');
            $user->bind_param('i', $params['id']);
            $user->execute();
            $user = $user->get_result();
            $user = $user->fetch_assoc();
            if (empty($user)) {
                return json_encode(['message' => 'Usuário não cadastrado.']);
            }

            if (empty($password)) {
                $password = $user['password'];
            }

            $checkEmail = $this->conn->connection()->prepare('SELECT * FROM users WHERE username = ? AND id <> ?');
            $checkEmail->bind_param('si', $params['username'], $params['id']);
            $checkEmail->execute();
            $checkEmail = $checkEmail->get_result();
            $checkEmail = $checkEmail->fetch_assoc();
            if (!empty($checkEmail)) {
                http_response_code(400);
                return json_encode(['message' => 'E-mail já cadastrado.']);
            }

            $user = $this->conn->connection()->prepare('UPDATE `users` SET `name` = ?, `username` = ?, `password` = ? WHERE id = ?');
            $user->bind_param('sssi', $params['name'], $params['username'], $password, $params['id']);
            $user->execute();

            $this->conn->disconnect();

            return json_encode(['message' => 'Usuario alterado com sucesso.']);
        } catch (\Throwable $th) {
            http_response_code(500);
            $this->conn->disconnect();
            return json_encode(['message' => 'Ocorreu um erro inesperado. Tente novamente.']);
        }
    }

    function delete()
    {
        parse_str(file_get_contents("php://input"), $params);

        if (empty($params['id'])) {
            http_response_code(400);
            return json_encode(['message' => 'Por favor informe um usuário.']);
        }

        $this->conn->connect();

        try {

            $user = $this->conn->connection()->prepare('SELECT * FROM users WHERE id = ?');
            $user->bind_param('i', $params['id']);
            $user->execute();
            $user = $user->get_result();
            $user = $user->fetch_assoc();
            if (empty($user)) {
                return json_encode(['message' => 'Usuário não cadastrado.']);
            }

            $user = $this->conn->connection()->prepare('DELETE FROM `users` WHERE id = ?');
            $user->bind_param('i', $params['id']);
            $user->execute();

            $this->conn->disconnect();

            return json_encode(['message' => 'Usuario excluido com sucesso.']);
        } catch (\Throwable $th) {
            http_response_code(500);
            $this->conn->disconnect();
            return json_encode(['message' => 'Ocorreu um erro inesperado. Tente novamente.']);
        }
    }

    function getAll()
    {
        $this->conn->connect();
        $users = $this->conn->connection()->query("SELECT * FROM users");
        $users = $users->fetch_all(MYSQLI_ASSOC);
        $this->conn->disconnect();

        if (empty($users)) {
            $_SESSION['first_access'] = true;
            return json_encode(["data" => []]);
        }

        if (empty($_SESSION['logged'])) {
            http_response_code(401);
            return json_encode(['message' => 'unauthenticated.']);
        }

        return json_encode(["data" => $users]);
    }
}

session_start();

$users = new users();

$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'GET' && (empty($_SESSION['logged']) && empty($_SESSION['first_access']))) {
    http_response_code(401);
    echo json_encode(['message' => 'unauthenticated.']);
    exit;
}

switch ($method) {
    case 'GET':
        echo $users->getAll();
        break;

    case 'POST':
        echo $users->store();
        break;

    case 'PUT':
        echo $users->update();
        break;

    case 'DELETE':
        echo $users->delete();
        break;
}

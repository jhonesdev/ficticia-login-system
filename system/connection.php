<?php

class connection
{

    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $connection;

    function __construct()
    {
        $conData = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/.env');
        $this->host = $conData["DBHOST"];
        $this->user = $conData["DBUSER"];
        $this->pass = $conData["DBPASS"];
        $this->dbname = $conData["DBNAME"];
        mysqli_report(MYSQLI_REPORT_ALL);
    }

    function connect()
    {
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erro ao se conectar ao banco de dados."]);
            exit;            
        }
    }

    function connection()
    {
        return $this->connection;
    }

    function disconnect()
    {
        mysqli_close($this->connection);
    }
}

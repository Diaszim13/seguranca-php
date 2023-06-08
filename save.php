<?php


$_SERVER = $_ENV['$_SERVER'];
$_DB_NAME = $_ENV['$_DB_NAME'];
$_USERNAME = $_ENV['$_USERNAME'];
$_PASSWORD = $_ENV['$_PASSWORD'];
$con = null;

try {
    $con = pg_connect("host=$_SERVER user=$_USERNAME 
                        password=$_PASSWORD dbname=$_DB_NAME");
} catch (Exception $e) {
    die("A conexão com o banco de dados falhou: " . $con->connect_error);
}


// Verifica se os dados foram submetidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Pega os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    
    // Faça algo com os dados, como salvá-los em um banco de dados ou enviá-los por email
    // ...
    
    // Exibe uma mensagem de sucesso
    echo 'Dados recebidos com sucesso!';
}
?>

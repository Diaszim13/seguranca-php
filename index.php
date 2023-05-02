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

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['password'])) {

    $password = $_POST['password'];
}

if (!empty($username) && !empty($password)) {
     // esse select não tem proteção nenhuma podendo assim ser possivel inserir no final da senha a segunte string '  OR 1 = 1 ' resultando em um retorno true em todos os casos
    $sql_select = "select * from usuario where usuario = " . $username . ' AND senha = ' . $password; 

    // este exemplo vai usar o pg_select tornando muito dificil de ser usado o sql injection
    $sql_select = pg_select($con, 'usuarios', array('usuario' => $username, 'password' => $password));


    $sql_select = "SELECT usuario from usuario where usuario LIKE '$username' and senha LIKE '$password';";



    try {
        $result = pg_query($con, $sql_select);
        if (pg_num_rows($result) == 1) {
            echo 'Logou';
        } else {
            echo 'Usuario ou senha estão incorretos';
        }
    
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}

pg_close($con);
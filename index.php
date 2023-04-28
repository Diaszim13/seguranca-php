<?php
$_SERVER = "localhost";
$_DB_NAME = "projseguranca";
$_USERNAME = "root";
$_PASSWORD = "132465";
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
    $sql_select = "SELECT usuario from usuario where usuario LIKE '%$username%'";

    try {
        $result = pg_query($con, $sql_select);
        if (pg_num_rows($result) == 0) {
            $sql_insert = "INSERT INTO usuario(id_usuario, usuario, senha) VALUES (nextval('public.seq_usuario'), '$username', '$password')";
            if (pg_query($con, $sql_insert)) {
                echo 'Cadastro';
            };
        } else {
            echo 'Ja cadastradoo';
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }


}

pg_close($con);
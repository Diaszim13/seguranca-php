<?php
$_SERVER = "localhost";
$_DB_NAME = "proj-seguranca";
$_USERNAME = "postgres";
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
    $sql_select = "SELECT usuario from usuario where usuario LIKE '$username' and senha LIKE '$password'";

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
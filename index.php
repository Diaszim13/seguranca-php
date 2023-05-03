<?php

$_SERVER = "localhost";
$_DB_NAME = "projseguranca";
$_USERNAME = "postgres";
$_PASSWORD = "1234";
$con = null;

session_start();

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
    //$sql_select = "select * from usuario where usuario = " . $username . ' AND senha = ' . $password; 

    // este exemplo vai usar o pg_select tornando muito dificil de ser usado o sql injection
    //$sql_select = pg_select($con, 'usuario', array('usuario' => $username, 'senha' => $password));


    $sql_select = "SELECT id_usuario from usuario where usuario LIKE '$username' and senha LIKE '$password'";



    try {
        $result = pg_query($con, $sql_select);
        $registro = pg_fetch_assoc($result);
        $id = $registro["id_usuario"];
        $sql_select_usuario = "SELECT firstname FROM perfil WHERE id_usuario = " . $id;
        $result2 = pg_query($con, $sql_select_usuario);
        $registro2 = pg_fetch_assoc($result2);
        $usuario = $registro2["firstname"];
        echo 'aqui';
        if (pg_num_rows($result) == 1) {
            $_SESSION['username'] = $usuario;
            header("Location: home.php");
            exit();
        } else {
            echo 'Usuario ou senha estão incorretos';
        }
    
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}

pg_close($con);
<?php

$_SERVER = "localhost";
$_DB_NAME = "projseguranca";
$_USERNAME = "postgres";
$_PASSWORD = "132465";
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
    $query = "SELECT * FROM usuario WHERE usuario='$username' AND senha=$password"; // para acessar com qual quer usuario '132' OR 1=1

    // MODO SEGURO
    // $username = pg_escape_string($username); // aqui vai tratar a string para não deixar adicionar comandos
    // echo $password;

    // $password = pg_escape_string($password);
    // $query = "SELECT * FROM usuario WHERE usuario='$username' AND senha='$password'";
    // $result = pg_query($con, $query);

    // $query = pg_prepare($con, "selc", "SELECT * FROM usuario WHERE usuario = $1 AND senha = $2");
    // $result = pg_execute($con, "selc", array($username, $password));
    // echo $password;

    // try {
    // }catch (PDOException $e)
    // {
    //     echo $e->getMessage();
    // }


    try {
        $result = pg_query($con, $query);
        if(pg_num_rows($result) >= 1)
        {
            $registro = pg_fetch_assoc($result);
            $id = $registro["id_usuario"];
            $sql_select_usuario = "SELECT firstname FROM perfil WHERE id_usuario = " . $id;
            $result2 = pg_query($con, $sql_select_usuario);
            $registro2 = pg_fetch_assoc($result2);
            $usuario = $registro2["firstname"];
            if (pg_num_rows($result2) >= 1) {
                $_SESSION['id_usuario'] = $id;
                $_SESSION['username'] = $usuario;
                header("Location: profile.php");
                exit();
            } else {
                echo 'Usuario nao encontrado';
            }
        } else {
            echo 'Usuario ou senha estão incorretos';
        }
    
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}

pg_close($con);
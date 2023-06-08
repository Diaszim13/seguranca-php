<?php

$_SERVER = "localhost";
$_DB_NAME = "projseguranca";
$_USERNAME = "postgres";
$_PASSWORD = "132465";
$con = null;


try {
    echo $_SERVER;
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

if (isset($_POST['firstname'])) {
    $firstname = $_POST['firstname'];
}
if (isset($_POST['lastname'])) {

    $lastname = $_POST['lastname'];
}
if (isset($_POST['email'])) {

    $email = $_POST['email'];
}
if (isset($_POST['cpf'])) {

    $cpf = $_POST['cpf'];
}
if (isset($_POST['superscription'])) {

    $superscription = $_POST['superscription'];
}
if (isset($_POST['neighborhood'])) {

    $neighborhood = $_POST['neighborhood'];
}
if (isset($_POST['num'])) {

    $num = $_POST['num'];
}

$sql_select = "SELECT usuario from usuario where usuario LIKE '" . $username . "'";

    try {
        $result = pg_query($con, $sql_select);
        if (pg_num_rows($result) == 0) {
            $sql_insert = "INSERT INTO usuario(id_usuario, usuario, senha) VALUES (nextval('public.seq_usuario'), '$username', '$password') RETURNING id_usuario";
            $r_insert_usuario = pg_query($con, $sql_insert);
            if ($r_insert_usuario) {
                $id = pg_fetch_result($r_insert_usuario, 0);
                if (!empty($firstname) && !empty($email) && !empty($cpf) && !empty($id)) {
                   try {
                        $sql = "INSERT INTO perfil(id_perfil, id_usuario, firstname, lastname, email, cpf, superscription, neighborhood, num)
                                    VALUES (nextval('public.seq_perfil'), $id, '$firstname', '$lastname', '$email', '$cpf', '$superscription', '$neighborhood', '$num')";
                        $r_insert_perfil = pg_query($con, $sql);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    if ($r_insert_perfil) {
                        header("Location: index.html");
                        exit();
                    }
                } else {
                    echo "Os campos nome, e-mail e cpf são obrigatórios.";
                }
            };
        } else {
            echo 'O nome do usuario já está cadastrado';
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }



pg_close($con);
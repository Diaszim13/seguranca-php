<?php
$_SERVER = "localhost";
$_DB_NAME = "projseguranca";
$_USERNAME = "postgres";
$_PASSWORD = "132465";
$con = null;


try {
    $con = pg_connect("host=$_SERVER user=$_USERNAME 
password=$_PASSWORD dbname=$_DB_NAME");
} catch (Exception $e) {
    die("A conexão com o banco de dados falhou: " . $con->connect_error);
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

if (!empty($firstname) && !empty($email) && !empty($cpf)) {
    try {
        $sql = "INSERT INTO perfil(firstname, lastname, email, cpf, superscription, neighborhood, num)
                    VALUES ('$firstname', '$lastname', '$email', '$cpf', '$superscription', '$neighborhood', '$num')";
        pg_query($con, $sql);
    } catch (Exception $e) {
        echo $e->getMessage();
    }


} else {
    echo "Os campos nome, e-mail e cpf são obrigatórios.";
}

pg_close($con);
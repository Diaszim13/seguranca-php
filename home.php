<?php

$ENV = parse_ini_file('.env');

$_SERVER = $ENV['SERVER'];
$_DB_NAME = $ENV['DB_NAME'];
$_USERNAME = $ENV['USERNAME'];
$_PASSWORD = $ENV['PASSWORD'];
$con = null;

session_start();

try {
    $con = pg_connect("host=$_SERVER user=$_USERNAME 
                        password=$_PASSWORD dbname=$_DB_NAME");
} catch (Exception $e) {
    die("A conexão com o banco de dados falhou: " . $con->connect_error);
}

$text = $_SESSION['username'];
//$deal_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
if (isset($_SESSION['id'])) {
    $sql_select_usuario = "SELECT cpf FROM perfil WHERE id_usuario = " . $_SESSION['id'];
    $result2 = pg_query($con, $sql_select_usuario);
    $registro2 = pg_fetch_assoc($result2);
    $cpf = $registro2["cpf"];

    if ($cpf . $_SESSION['id'] . $_SERVER["REMOTE_HOST"] != $_SESSION['validator']) {
        echo $cpf . $_SESSION['id'] . $_SERVER["REMOTE_HOST"]; - $_SESSION['validator'];
    }

} else {
    echo $_SESSION['id'];
    //header("Location: index.php");
    //exit;
}


?>
<!DOCTYPE html>
<html lang="pt-br"> 
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    </head>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <div class="container-fluid">
      
        <div class="card m-5">

              <?php 
              echo 'Olá ' . $text;
              ?>
        </div>
        </div>
    </body>
</html>


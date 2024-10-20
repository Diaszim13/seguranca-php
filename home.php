<?php

$ENV = parse_ini_file('.env');

$SERVER = $ENV['SERVER1'];
$_DB_NAME = $ENV['DB_NAME'];
$_USERNAME = $ENV['USERNAME'];
$_PASSWORD = $ENV['PASSWORD'];
$con = null;

session_start();

$ipaddress = '';
if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
else if(isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
else
    $ipaddress = 'UNKNOWN';
echo $ipaddress;

try {
    $con = pg_connect("host=$SERVER user=$_USERNAME 
                        password=$_PASSWORD dbname=$_DB_NAME");
} catch (Exception $e) {
    die("A conexão com o banco de dados falhou: " . $con->connect_error);
}

if (isset($_SESSION['username'])) {
    $text = $_SESSION['username'];
} else {
    header("Location: index.html");
}
$deal_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
if (isset($_SESSION['id'])) {
    $sql_select_usuario = "SELECT cpf FROM perfil WHERE id_usuario = " . $_SESSION['id'];
    $result2 = pg_query($con, $sql_select_usuario);
    $registro2 = pg_fetch_assoc($result2);
    $cpf = $registro2["cpf"];

    if ($cpf . $_SESSION['id'] . $ipaddress != $_SESSION['validator']) {
        echo $cpf . $_SESSION['id'] . $ipaddress . '-' . $_SESSION['validator'];
    }

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
            <div class="row p-5">
            <?php 
                echo 'Olá ' . $deal_text;
            ?>
              <a class="btn btn-primary p-2 w-25 m-2" href="profile.php" type="button"> <i class="bi bi-arrow-bar-right"></i> </a>
              </div>
        </div>
        </div>
    </body>
</html>


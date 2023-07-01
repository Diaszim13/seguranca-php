<?php
$ENV = parse_ini_file('.env');

$_SERVER = $ENV['SERVER'];
$_DB_NAME = $ENV['DB_NAME'];
$_USERNAME = $ENV['USERNAME'];
$_PASSWORD = $ENV['PASSWORD'];
$con = null;

session_start();
/*aqui eu crio uma sessao */
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

/* aQUI DEFINO AS OPÇÕES DE SEGURANÇA */
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);

session_set_save_handler($customSessionHandler);

/*
Use HTTPS: Certifique-se de que sua aplicação esteja sendo executada em uma conexão segura HTTPS. Isso ajuda a proteger a sessão 
contra ataques de interceptação de rede.

Valide a origem da sessão: Armazene e verifique informações de identificação da sessão, como o endereço IP do usuário e o agente do usuário (User-Agent). Compare essas informações durante a autenticação do usuário 
para garantir que a sessão esteja sendo usada pelo usuário correto.
Defina tempo limite
adequado: Configure um tempo limite adequado para a sessão, usando a configuração session.gc_maxlifetime no arquivo php.ini. Isso ajuda 
a limitar a exposição de sessões ativas.
*/ 

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


if (isset($_SERVER["REMOTE_ADDR"])) {
    $remoteHost = $_SERVER['REMOTE_ADDR']; //$_SERVER["REMOTE_HOST"];
} else {
    $remoteHost = "nada";
}

try {
    // $con = pg_connect("host=$_SERVER1 user=$_USERNAME 
    //                     password=$_PASSWORD dbname=$_DB_NAME");

    //CONECTAR NO BANCO USANDO PDO 
    $pdo = new PDO($_SERVER1, $_USERNAME, $_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("A conexão com o banco de dados falhou: " . $e->getMessage());
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['password'])) {

    $password = $_POST['password'];
}

if (!empty($username) && !empty($password)) {
    //$query = "SELECT * FROM usuario WHERE usuario= " . $username . " AND senha = " .$password; // para acessar com qual quer usuario '132' OR 1=1

    // MODO SEGURO
    $username = pg_escape_string($username); // aqui vai tratar a string para não deixar adicionar comandos
    echo $password;

    //USANDO PREPARE STATEMEND
    $sql = 'SELECT * from usuario where usuario = $user AND senha = $pass'; // $1 e $2 são os parametros que serão passados para o prepare
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('$user', $username);
    $stmt->bindValue('$pass', $password);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //AQUI VAI VALIDAR SE O RESULTADO VEIO COMO UM BOOL
    $valid = filter_var($result, FILTER_VALIDATE_BOOL);
    if($valid === false)
    {
        echo "usuario ou senha errados";
    }

    $password = pg_escape_string($password); // AQUI VAI TIRAR OS ESPAÇOS DA SENHA
    $query = "SELECT * FROM usuario WHERE usuario='$username' AND senha='$password'";
    $result = pg_query($con, $query);

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
        $registro = pg_fetch_assoc($result);
        $senhaRecuperada = $registro["senha"];
        if(pg_num_rows($result) >= 1 && password_verify($password, $senhaRecuperada))
        {
            $id = $registro["id_usuario"];
            $sql_select_usuario = "SELECT firstname, cpf FROM perfil WHERE id_usuario = " . $id;
            $result2 = pg_query($con, $sql_select_usuario);
            $registro2 = pg_fetch_assoc($result2);
            $usuario = $registro2["firstname"];
            $cpf = $registro2["cpf"];
            if (pg_num_rows($result2) >= 1) {
                $_SESSION['id_usuario'] = $id;
                $_SESSION['username'] = $usuario;
                echo $remoteHost;
                $_SESSION['id'] = $id;
                $_SESSION["validator"] = $cpf . $id . $remoteHost;
                header("Location: home.php");
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
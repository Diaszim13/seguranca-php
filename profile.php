<?php
$ENV = parse_ini_file('.env');

$SERVER = $ENV['SERVER1'];
$_DB_NAME = $ENV['DB_NAME'];
$_USERNAME = $ENV['USERNAME'];
$_PASSWORD = $ENV['PASSWORD'];
$con = null;
session_start();
$text = $_SESSION['username'];
//$deal_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');



try {
    $con = pg_connect("host=$SERVER user=$_USERNAME 
                        password=$_PASSWORD dbname=$_DB_NAME");
} catch (Exception $e) {
    die("A conexão com o banco de dados falhou: " . $con->connect_error);
}

echo json_encode($_SESSION);
$id_usuario = $_SESSION['id_usuario'];
function createNote($con, $data, $id_usuario)
{
    $insert_notas = pg_query($con, "INSERT INTO nota (user_id, nota) VALUES ('".$id_usuario."', '".$data['nota']."')");
    if($insert_notas){
        echo "Nota inserida com sucesso!";
    }

}

//Fazer um botao para chamar essa query e inserir a nota





// if($_POST['nome'])
// {
//     $nome = $_POST['nome'];
// }

// if($_POST['email'])
// {
//     $email = $_POST['email'];
// }

// if($_POST['telefone'])
// {
//     $telefone = $_POST['telefone'];
// }

// if($_POST['data-nascimento'])
// {
//     $data_nascimento = $_POST['data-nascimento'];
// }

//mostre exemplos de como se proteger de sql injection com php
// $sql_insert_user = 'INSERT INTO profiles (user_id, nome, email, telefone, data_nascimento) VALUES (' . $user->id . ', ' . $nome . ', ' . $email . ', ' . $telefone . ', ' . $data_nascimento . ')';


// $sql_update_user = 'UPDATE profiles SET nome = ' . $nome . ', email = ' . $email . ', telefone = ' . $telefone . ', data_nascimento = ' . $data_nascimento . ' WHERE user_id = ' . $user->id;

// $sql_select_user = 'SELECT * from profiles WHERE user_id = ' . $user->id;
?>

<style>
    .modal-body {
        background-color: white;
    }
</style>

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

              <h1>Perfil do usuário: <?php echo $text ?></h1>
              <img src="./th-2674479128" class="rounded mx-auto d-block" style="width: 150px;" alt="Avatar" />
            <table class="table bg-white">
                <tbody>
                <?php /* AQUI VAI PEGAR AS NOTAS DESSE USUARIO ESPECIFICO */
                        $query = pg_query($con, "SELECT * FROM nota n
                        inner join perfil p ON (p.id_usuario = n.user_id)
                        WHERE user_id = ". $id_usuario);
                        ?>
                    <tr>
                        <div class="btn-toolbar">
                            <button type="button" data-bs-toggle="modal" data-bs-target=".modal">
                                <i class="bi bi-bookmark-plus"></i>
                            </button>
                        </div>
                        </tr>
                <tr>
                    <th>Primeiro Nome:</th>
                    <th>email:</th>
                    <th>CPF:</th>
                    <th>Nota:</th>
                </tr>
                    <?php 
                        foreach(pg_fetch_all($query) as $i => $row)
                        {
                            echo '<tr>';
                            echo '<td>' .$row['firstname'] . '</td>';    
                            echo '<td>' .$row['email'] . '</td>';    
                            echo '<td>' .$row['cpf'] . '</td>';    
                            echo '<td>' .$row['nota'] . '</td>';    
                            echo '</tr>';
                        }
                        ?>
            </tbody>
        </table>
    </div>
            </div>
            <div class="modal" tabindex="-1">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Adicionar nota</p>
                    <div class="row">
                        <div class="col">
                        <form action='createNote({$con}, {nota}, {id_usuario})'>
                            <input type="text" id="nota" name="nota" placeholder="Nome task">
                            <select name="user_id">
                            <?php
                        $query = pg_query($con, "SELECT usuario FROM usuario");
                        echo pg_fetch_all($query);
                        foreach(pg_fetch_all($query) as $row)
                        {
                            echo '<option name="id_usuario" value="'.$row["usuario"].'">'.$row["usuario"].'</option>';    
                        }
                        ?>
                        </select>
                    </form>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                </div>
              </div>
    </body>
</html>

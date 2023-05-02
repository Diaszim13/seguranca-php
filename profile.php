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
// $sql_select_user = 'SELECT * from profiles WHERE user_id = ' . $user->id;
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

              <h1>Perfil do usuário</h1>
              <img src="./th-2674479128" class="rounded mx-auto d-block" style="width: 150px;" alt="Avatar" />
            <table class="table">
                <tbody>
                    <tr>
                        <button type="button" data-bs-toggle="modal" data-bs-target=".modal">
                            <i class="bi bi-bookmark-plus"></i>
                          </button>
                        </tr>
                <tr>
                    <td>Nome:</td>
                    <td id="nome"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td id="email"></td>
                </tr>
                <tr>
                    <td>Telefone:</td>
                    <td id="telefone"></td>
                </tr>
                <tr>
                    <td>Data de nascimento:</td>
                    <td id="data-nascimento"></td>
                </tr>
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
                            <input type="text" placeholder="Nome task">
                            <select name="user_id">
                            <?php
                        $query = pg_query($con, "SELECT usuario FROM usuario");
                        echo pg_fetch_all($query);
                        foreach(pg_fetch_all($query) as $row)
                        {
                            echo '<option value="'.$row["usuario"].'">'.$row["usuario"].'</option>';    
                        }
                        ?>    
                        </select>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
    </body>
</html>
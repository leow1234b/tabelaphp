<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Biblioteca</title>

</head>

<body class="flex" style="flex-wrap: wrap">

    <?php 

        session_start();

        if (!isset($_SESSION["usuario"])) {
           $_SESSION["usuario"] = "";
        }

        if (!isset($_SESSION["cpf"])) {
            $_SESSION["cpf"] = "";
        }

        require_once "banco.php";  
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['botao'])) {

            switch ($_POST['botao']){

                case 'login':
                            
                    echo "<div class=\"fundo-login\">

                            <div class=\"login\">

                                <button class=\"close\" onclick=\"closeLogin()\" > X </button>";

                                    require_once "./src/forms/form-login.php";

                    echo        "<button class=\"buttom-new\"> Don't have an account? CREATE ONE </button>
                                
                            </div>

                        </div>";
                    
                    break;

                case 'Minhas_locações':

                    header('Location: locacoes.php');
                    exit;

                    break;

                case 'Pagina_de_ADM':

                    header('Location: administrador.php');
                    exit;

                    break;

                case 'logout':

                    $_SESSION["usuario"] = null;
                    $_SESSION["cpf"] = null;

                    break;

            }

        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cpf']) && isset($_POST['senha'])){

            $cpf = $_POST['cpf'] ?? null;
            $senha = $_POST['senha'] ?? null;

            if (verificarCpf($cpf, $senha)==true) {
                $_SESSION["usuario"] = getUsuario($cpf, $senha);
                $_SESSION["cpf"] = $cpf;
            }
 
        }

    ?> 

    <header class="flex borda-teste" style="justify-content: space-between">

        <div class="div-header flex">

            <h2 class="logo flex"> " BIBLIOTECA PHP " </h2>

        </div>

        <form method="post" class="div-header flex" style="justify-content: space-between">

            <?php

                if ($_SESSION["locacoes"] !== null || $_SESSION["locacoes"] !== "") {
                    echo "

                    <div class=\"dropdown\">
                        <button class=\"butao-dropdown flex dropdown-toggle\" type=\"button\">LOCAÇÕES</button>
                        <div class=\"dropdown-content\" id=\"locacoes-list\" style=\"width: 200%;\">
                    ";

                    foreach ($_SESSION['locacoes'] as $locacao) {
                        $livros = listarLivros($locacao);
                        foreach ($livros as $livro) {
                            echo "
                                <div class=\"lista-locacao flex\">
                                    <div class=\"locacao-capa flex\">
                                        <img src='" . htmlspecialchars($livro['capa']) . "' alt='Capa do livro'>
                                    </div>
                                    <div class=\"locacao-titulo\" style=\"flex-wrap: wrap\">
                                        <p class=\"titulo\">". htmlspecialchars($livro['titulo']) ."</p>
                                        <button class=\"butao-remover\" type=\"button\">Remover</button>
                                    </div>
                                </div>
                            ";
                        }
                    }

                    echo "

                            <div>

                                <button class=\"butao-locacao\" type=\"button\">Efetuar locação</button>

                            </div>

                        </div>

                    </div>";

                }

                if ($_SESSION["usuario"] == null || $_SESSION["usuario"] == "") {
                    echo '<button class="butao flex" type="submit" name="botao" value="login">LOGIN</button>';
                } else {
                    if ($_SESSION["usuario"] == "ADM") {
                        echo "
                        <div class=\"dropdown\">

                            <button class=\"butao-dropdown flex dropdown-toggle\" type=\"button\">ADM</button>

                            <div class=\"dropdown-content\" style=\"background-color: #f1f1f1;\">

                                <button type=\"submit\" name=\"botao\" value=\"Pagina_de_ADM\">Pagina de ADM</button>
                                <button type=\"submit\" name=\"botao\" value=\"Minhas_locações\">Minhas locações</button>
                                <button type=\"submit\" name=\"botao\" value=\"logout\">logout</button>

                            </div>

                        </div>";

                    } else {
                        echo "
                        <div class=\"dropdown\">

                            <button class=\"butao-dropdown flex dropdown-toggle\" type=\"button\">" . $_SESSION["usuario"] . "</button>

                            <div class=\"dropdown-content\" style=\"background-color: #f1f1f1;\">

                                <button type=\"submit\" name=\"botao\" value=\"Minhas_locações\">Minhas locações</button>
                                <button type=\"submit\" name=\"botao\" value=\"logout\">logout</button>

                            </div>

                        </div>";
                    }
                }
            ?>

        </form>


    </header>

    <section class="flex borda-teste" style="flex-wrap: wrap; justify-content: space-evenly;">

        <?php

            $q = "SELECT capa, PK_id_livro FROM livros";
            $resp = $banco->query($q);

            while ($obj = $resp->fetch_object()) { 

                echo "<div class=\"livro\" style=\"background-image: url($obj->capa) ;\">

                    <form method=\"POST\" action=\"\" class=\"locacao\">

                        <input type=\"hidden\" name=\"id\" value=\"$obj->PK_id_livro\">
                    
                        <button class=\"locacao\" type=\"submit\">

                            " . $obj->PK_id_livro . "

                        </button>

                    </form>
                
                </div>";

            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])){

                addLivro($_POST['id']);

            }
            
            function addLivro($id){

                if (!isset($_SESSION['locacoes'])){
                    $_SESSION['locacoes'] = [];
                }

                if (!in_array($id, $_SESSION['locacoes'])){
                    $_SESSION['locacoes'][] = $id;
                }
            }

        ?>

    </section>

    <script src="scripts.js"></script>
    
</body>

</html>
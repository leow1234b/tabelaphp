<?php 

    $password  = "home13"; // colocar a senha do mySQL se tiver
    $dbname  = "db_biblioteca_php"; // nome do banco

    $banco = new mysqli("localhost", "root", $password, $dbname);

    //verificando conexão
    if ($banco->connect_error){
        die("connection failed: " . $banco->connect_error);
    }



    //  FUNÇÃO PARA VERIFICAR SE O CPF EXISTE
    function verificarCpf(string $cpf) : bool{
        global $banco;

        $q = "SELECT * FROM usuarios WHERE cpf='$cpf'";
        $busca = $banco->query($q);

        if($busca->num_rows == 0){
            return false;
        }else{
            return true;
        }

    }

    //  FUNÇÕES PARA USUÁRIOS

    //  FUNÇÃO PARA PEGAR O NOME DO USUÁRIO
    function getUsuario(string $cpf, string $senha) : string{
        global $banco;

        $q = "SELECT nome FROM usuarios WHERE cpf='$cpf' AND senha='$senha'";
        $busca = $banco->query($q);

        $obj = $busca->fetch_object();

        return $obj->nome;

    }

    //  FUNÇÃO PARA CADASTRAR UM NOVO USUÁRIO


    //  FUNÇÃO PARA DELETAR UM USUÁRIO
    function deletarUsuario(string $cpf, bool $debug = true) : void {
        global $banco;

        $q = "DELETE FROM usuarios WHERE cpf='$cpf'";
        $busca = $banco->query($q);

        if ($debug) {
            echo"
            <div class=\"fundo-debug\" id=\"debugMessage\">

                <div class=\"debug\">

                    <h3> Usuario deletado com sucesso! </h3>
                                    
                </div>

            </div>"; 
        }

    } 

    //  FUNÇÃO PARA ALTERAR UM USUÁRIO
    function alterarUsuario(string $cpf, string $telefone, string $email, string $endereco, string $senha, bool $debug = true) : void {
        global $banco;

        $q = "UPDATE usuarios SET Telefone = '$telefone', Email = '$email', Endereço = '$endereco', senha = '$senha' WHERE cpf = '$cpf'";
        $busca = $banco->query($q);

        if ($debug) {
            echo"
            <div class=\"fundo-debug\" id=\"debugMessage\">

                <div class=\"debug\">

                    <h3> Usuario alterado com sucesso! </h3>
                                    
                </div>

            </div>"; 
        }

    }

    //  Função listar os usuários
    function listarUsuarios() {
        global $banco;
        $result = $banco->query("SELECT id, nome, cpf, email FROM usuarios");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "ID: " . $row["id"] . " - Nome: " . $row["nome"] . " - CPF: " . $row["cpf"] . " - Email: " . $row["email"] . "<br>";
            }
        } else {
            echo "Nenhum usuário encontrado.";
        }
    }



    //  FUNÇÕES PARA LIVROS
    //  essas funçoes devem possuir verificadores para garantir que apenas um ADMIN possa efetuar essas funções.

    //  FUNÇÃO PARA CADASTRAR UM NOVO LIVRO
    function cadastrarLivro(string $titulo, string $autor, string $editora, int $quantidade, string $categoria, bool $debug = true) : void {
        global $banco;
    
        $q = "SELECT PK_id_categoria FROM categorias WHERE categoria='$categoria'";
        $busca = $banco->query($q);
    
        if ($busca->num_rows > 0){ 
            $obj = $busca->fetch_object();
            $FK_id_categoria = $obj->PK_id_categoria;
    
            if (isset($_FILES["capa"]) && $_FILES["capa"]["error"] == 0) {
                $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $titulo);
                $ext = pathinfo($_FILES["capa"]["name"], PATHINFO_EXTENSION);
    
                $newfilename = $filename . "." . $ext;
                $fileurl = "./src/img/" . $newfilename;
    
                if (move_uploaded_file($_FILES["capa"]["tmp_name"], $fileurl)) {

                    $q = "INSERT INTO livros (titulo, autor, editora, quantidade, capa, FK_id_categoria) VALUES ('$titulo', '$autor', '$editora', '$quantidade', '$fileurl', '$FK_id_categoria')";
                    $busca = $banco->query($q);
                    if ($busca) {
                        if ($debug) {
                            echo"
                            <div class=\"fundo-debug\" id=\"debugMessage\">

                                <div class=\"debug\">

                                    <h3> Livro cadastrado com sucesso! </h3>
                                    
                                </div>

                            </div>";
                        }
                    } else {
                        if ($debug) {
                            echo"
                            <div class=\"fundo-debug\" id=\"debugMessage\">

                                <div class=\"debug\">

                                    <h3> Erro ao cadastrar o livro: . $banco->error; </h3>
                                    
                                </div>

                            </div>";
                        }
                    }
                } else {
                    if ($debug) {
                        echo"
                        <div class=\"fundo-debug\" id=\"debugMessage\">

                            <div class=\"debug\">

                                <h3> Erro ao fazer upload da imagem. </h3>
                                
                            </div>

                        </div>";
                    }
                }
            } else {
                if ($debug) {
                    echo"
                    <div class=\"fundo-debug\" id=\"debugMessage\">

                        <div class=\"debug\">

                            <h3> Erro: Nenhum arquivo de imagem selecionado ou ocorreu um erro no upload. </h3>
                            
                        </div>

                    </div>";
                }
            }
        } else {
            if ($debug) {
                echo"
                <div class=\"fundo-debug\" id=\"debugMessage\">

                    <div class=\"debug\">

                        <h3> Categoria não encontrada. </h3>
                        
                    </div>

                </div>";
            }
        }
    }

    //  FUNÇÃO PARA DELETAR UM LIVRO
    function deletarLivro(string $titulo, bool $debug = true) : void {
        global $banco;

        $q = "DELETE FROM livros WHERE titulo='$titulo'";
        $busca = $banco->query($q);

        if ($debug) {
            echo"
            <div class=\"fundo-debug\" id=\"debugMessage\">

                <div class=\"debug\">

                    <h3> Livro deletado com sucesso! </h3>
                                    
                </div>

            </div>"; 
        }

    } 
    
    function buscarLivro($adminID, $idLivro) {
        global $banco;

        if(!is_null($adminID)){
           $q = "SELECT idLivro , editora, titulo, autor, quantidade FROM livros WHERE livro='$idLivro'";
           $busca = $banco->query($q);

           return $busca;
        }
    }

    function listarLivros(int $id){
        global $banco;

        $q = "SELECT titulo, capa FROM livros WHERE PK_id_livro = '$id'";
        $busca = $banco->query($q);

        return $busca;

    }



    //  FUNÇÕES PARA CATEGORIAS
    //  essas funçoes devem possuir verificadores para garantir que apenas um ADMIN possa efetuar essas funções.

    //  FUNÇÃO PARA CADASTRAR UMA NOVA CATEGORIA
    function cadastrarCategoria(string $categoria, bool $debug = true) : void {
        global $banco;

        $q = "INSERT INTO categoria (categoria) VALUES ($categoria)";
        $busca = $banco->query($q);

        if ($debug) {
            echo"
            <div class=\"fundo-debug\" id=\"debugMessage\">

                <div class=\"debug\">

                    <h3> Categoria cadastrada com sucesso! </h3>
                                    
                </div>

            </div>"; 
        }

    }

    //  FUNÇÃO PARA DELETAR UMA CATEGORIA
    function deletarCategoria(string $categoria, bool $debug = true) : void {
        global $banco;

        $q = "DELETE FROM categorias WHERE categoria='$categoria'";
        $busca = $banco->query($q);

        if ($debug) {
            echo"
            <div class=\"fundo-debug\" id=\"debugMessage\">

                <div class=\"debug\">

                    <h3> Categoria deletado com sucesso! </h3>
                                    
                </div>

            </div>"; 
        }

    }
    

    //  FUNÇÕES PARA LOCAÇÕES

    //  essas funçoes devem possuir verificadores para garantir que as locações sejam efetuadas apenas com um usuario logado.

    //  criar função para efetuar uma locação
    //  criar função para efetuar uma devolução
    //  criar função para lista as devolução

    function verificarUsuarioLogado() {
        session_start();
        return isset($_SESSION['usuario_logado']);
    
    
    }
    //  FUNÇÃO PARA EFETUAR UMA LOCAÇÃO
    function efetuarLocacao(int $idUsuario, int $idLivro, bool $debug = true) : void {
        global $banco;
    
        if (!verificarUsuarioLogado()) {
            echo "Nenhum usuário logado. Efetue o login para continuar
        }

    }

    
    // FUNÇÃO PARA EFETUAR UMA DEVOLUÇÃO
    function efetuarDevolucao(int $idLocacao, bool $debug = true) : void {
        global $banco;
    
        if (!verificarUsuarioLogado()) {
            echo "Nenhum usuário logado.";
            return;
        }
    
        $dataDevolucao = date("Y-m-d H:i:s");
    
        $q = "UPDATE locacoes SET dataDevolucao = '$dataDevolucao' WHERE PK_id_locacao = '$idLocacao'";
        $busca = $banco->query($q);
    
        if ($busca) {
            if ($debug) {
                echo"
                <div class=\"fundo-debug\" id=\"debugMessage\">
    
                    <div class=\"debug\">
    
                        <h3> Devolução efetuada com sucesso! </h3>
                        
                    </div>
    
                </div>";
            }
        } else {
            if ($debug) {
                echo"
                <div class=\"fundo-debug\" id=\"debugMessage\">
    
                    <div class=\"debug\\"> 
    
                        <h3> Erro ao efetuar a devolução: . $banco->error; </h3>
                        
                    </div>
    
                </div>";
            }
        }
    }
   
    function listarLocacao(int $id) {
        global $banco;
    
        $q = "SELECT PK_id_locacao, idUsuario, idLivro, dataLocacao, dataDevolucao FROM locacoes WHERE PK_id_locacao = '$id'";
        $busca = $banco->query($q);
    
        if ($busca->num_rows > 0) {
            $locacao = $busca->fetch_assoc();
            return $locacao;
        } else {
            return null; // Retorna null se a locação não for encontrada
        }
    }
  
    // FUNÇÃO PARA LISTAR UMA LOCAÇÃO ESPECÍFICA
    function listarLocacao(int $id) {
        global $banco;
    
        $q = "SELECT PK_id_locacao, idUsuario, idLivro, dataLocacao, dataDevolucao FROM locacoes WHERE PK_id_locacao = '$id'";
        $busca = $banco->query($q);
    
        if ($busca->num_rows > 0) {
            $locacao = $busca->fetch_assoc();
            return $locacao;
        } else {
            return null; // Retorna null se a locação não for encontrada
        }
    }
    
   
    // FUNÇÃO PARA LISTAR TODAS AS DEVOLUÇÕES
    function listarDevolucoes() {
        global $banco;
    
        $q = "SELECT PK_id_locacao, idUsuario, idLivro, dataLocacao, dataDevolucao FROM locacoes WHERE dataDevolucao IS NOT NULL";
        $busca = $banco->query($q);
    
        if ($busca->num_rows > 0) {
            while ($row = $busca->fetch_assoc()) {
                echo "ID Locação: " . $row["PK_id_locacao"] . " - ID Usuário: " . $row["idUsuario"] . " - ID Livro: " . $row["idLivro"] . " - Data Locação: " . $row["dataLocacao"] . " - Data Devolução: " . $row["dataDevolucao"] . "<br>";
            }
        } else {
            echo "Nenhuma devolução encontrada.";
        }
    }
    
     
    
    
?>
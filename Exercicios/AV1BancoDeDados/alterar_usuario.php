<?php
   include "conexao.php";
   $idUsuario = 0;
   if (isset($_GET['id'])) { $idUsuario = (int) $_GET['id']; }
   if (isset($_POST['id'])) { $idUsuario = (int) $_POST['id']; }

   if (isset($_GET['dados'])) {
       $nomeAtual = "";
       $emailAtual = "";

       $resultado = mysqli_query($conexao, "SELECT nome, email FROM usuarios WHERE id = $idUsuario");
       $linha = mysqli_fetch_assoc($resultado);
       if ($linha) {
           $nomeAtual = $linha['nome'];
           $emailAtual = $linha['email'];
       }

       echo $nomeAtual . ";" . $emailAtual;
       exit;
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $nome = str_replace(array(";","\r","\n")," ",trim($_POST["nome"]));
       $email = str_replace(array(";","\r","\n")," ",trim($_POST["email"]));

       if ($nome == "" || $email == "") {
           $msg = "Preencha o nome e o e-mail";
       } else {
           $nome = mysqli_real_escape_string($conexao, $nome);
           $email = mysqli_real_escape_string($conexao, $email);

           $sql = "UPDATE usuarios SET nome = '$nome', email = '$email' WHERE id = $idUsuario";
           if (mysqli_query($conexao, $sql)) {
               $msg = "Usuário alterado com sucesso!";
           } else {
               $msg = "Erro ao alterar o usuário";
           }
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Alterar Usuário</title></head>
<body>
<h1>Alterar Usuário</h1>
<form id="formulario">
   <input type="hidden" name="id" id="id" value="<?php echo $idUsuario; ?>">
   Nome: <input type="text" name="nome" id="nome" size="50"><br><br>
   E-mail: <input type="text" name="email" id="email" size="50"><br><br>
   <button type="button" onclick="salvar()">Salvar Alterações</button>
</form>
<p id="msg"></p>
<br><a href="listar_usuarios.php">Voltar para a lista</a>

<script>
var id = document.getElementById("id").value;

fetch("alterar_usuario.php?dados=1&id=" + encodeURIComponent(id))
    .then(function (resposta) { return resposta.text(); })
    .then(function (texto) {
        var dados = texto.split(";");
        document.getElementById("nome").value = dados[0];
        document.getElementById("email").value = dados[1];
    });

function salvar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("alterar_usuario.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
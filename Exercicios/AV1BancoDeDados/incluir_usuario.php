<?php
   include "conexao.php";
   $msg = "";

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $nome = str_replace(array(";","\r","\n")," ",trim($_POST["nome"]));
       $email = str_replace(array(";","\r","\n")," ",trim($_POST["email"]));

       if ($nome == "" || $email == "") {
           $msg = "Preencha o nome e o e-mail!";
       } else {
           $nome = mysqli_real_escape_string($conexao, $nome);
           $email = mysqli_real_escape_string($conexao, $email);

           $sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";
           if (mysqli_query($conexao, $sql)) {
               $msg = "Usuário incluído com sucesso!";
           } else {
               $msg = "Erro ao incluir o usuário";
           }
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Incluir Usuário</title></head>
<body>
<h1>Incluir Usuário</h1>
<form id="formulario">
   Nome: <input type="text" name="nome" size="50"><br><br>
   E-mail: <input type="text" name="email" size="50"><br><br>
   <button type="button" onclick="enviar()">Incluir Usuário</button>
</form>
<p id="msg"></p>
<br><a href="listar_usuarios.php">Listar Usuários</a> | <a href="index.html">Voltar para o Menu</a>

<script>
function enviar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("incluir_usuario.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
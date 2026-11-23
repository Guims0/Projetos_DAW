<?php
   include "conexao.php";
   $msg = "";

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $enunciado = str_replace(array(";","\r","\n")," ",trim($_POST["enunciado"]));
       $resposta = str_replace(array(";","\r","\n")," ",trim($_POST["resposta"]));

       if ($enunciado == "" || $resposta == "") {
           $msg = "Preencha a pergunta e a resposta!!!";
       } else {
           $enunciado = mysqli_real_escape_string($conexao, $enunciado);
           $resposta = mysqli_real_escape_string($conexao, $resposta);

           mysqli_query($conexao, "INSERT INTO perguntas (tipo, enunciado) VALUES ('T', '$enunciado')");
           $idPergunta = mysqli_insert_id($conexao);

           mysqli_query($conexao, "INSERT INTO respostas (idpergunta, resposta, certa) VALUES ($idPergunta, '$resposta', 1)");

           $msg = "Pergunta incluida com sucesso";
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Incluir pergunta de texto</title></head>
<body>
<h1>Incluir pergunta de texto</h1>
<form id="formulario">
   Pergunta: <input type="text" name="enunciado" size="80"><br><br>
   Resposta esperada: <input type="text" name="resposta" size="80"><br><br>
   <button type="button" onclick="enviar()">Incluir Pergunta</button>
</form>
<p id="msg"></p>
<br><a href="index.html">Voltar</a>

<script>
function enviar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("incluir_pergunta_texto.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
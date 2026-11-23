<?php
   include "conexao.php";
   $idPergunta = 0;
   if (isset($_GET['id'])) { $idPergunta = (int) $_GET['id']; }
   if (isset($_POST['id'])) { $idPergunta = (int) $_POST['id']; }

   if (isset($_GET['dados'])) {
       $enunciadoAtual = "";
       $respostaAtual = "";

       $resultado = mysqli_query($conexao, "SELECT enunciado FROM perguntas WHERE id = $idPergunta");
       $linha = mysqli_fetch_assoc($resultado);
       if ($linha) {
           $enunciadoAtual = $linha['enunciado'];
       }

       $resultado = mysqli_query($conexao, "SELECT resposta FROM respostas WHERE idpergunta = $idPergunta");
       $linha = mysqli_fetch_assoc($resultado);
       if ($linha) {
           $respostaAtual = $linha['resposta'];
       }

       echo $enunciadoAtual . ";" . $respostaAtual;
       exit;
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $enunciado = str_replace(array(";","\r","\n")," ",trim($_POST["enunciado"]));
       $resposta = str_replace(array(";","\r","\n")," ",trim($_POST["resposta"]));

       if ($enunciado == "" || $resposta == "") {
           $msg = "Preencha a pergunta e a resposta";
       } else {
           $enunciado = mysqli_real_escape_string($conexao, $enunciado);
           $resposta = mysqli_real_escape_string($conexao, $resposta);

           mysqli_query($conexao, "UPDATE perguntas SET enunciado = '$enunciado' WHERE id = $idPergunta");
           mysqli_query($conexao, "UPDATE respostas SET resposta = '$resposta' WHERE idpergunta = $idPergunta");

           $msg = "Pergunta alterada com sucesso";
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Alterar pergunta de texto</title></head>
<body>
<h1>Alterar pergunta de texto</h1>
<form id="formulario">
   <input type="hidden" name="id" id="id" value="<?php echo $idPergunta; ?>">
   Pergunta: <input type="text" name="enunciado" id="enunciado" size="80"><br><br>
   Resposta esperada: <input type="text" name="resposta" id="resposta" size="80"><br><br>
   <button type="button" onclick="salvar()">Salvar Alterações</button>
</form>
<p id="msg"></p>
<br><a href="index.html">Voltar</a>

<script>
var id = document.getElementById("id").value;

fetch("alterar_pergunta_texto.php?dados=1&id=" + encodeURIComponent(id))
    .then(function (resposta) { return resposta.text(); })
    .then(function (texto) {
        var dados = texto.split(";");
        document.getElementById("enunciado").value = dados[0];
        document.getElementById("resposta").value = dados[1];
    });

function salvar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("alterar_pergunta_texto.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
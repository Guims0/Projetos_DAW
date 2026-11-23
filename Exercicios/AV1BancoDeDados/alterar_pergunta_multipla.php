<?php
   include "conexao.php";
   $idPergunta = 0;
   if (isset($_GET['id'])) { $idPergunta = (int) $_GET['id']; }
   if (isset($_POST['id'])) { $idPergunta = (int) $_POST['id']; }

   if (isset($_GET['dados'])) {
       $enunciadoAtual = "";
       $respostasAtuais = array("", "", "", "");
       $certaAtual = -1;

       $resultado = mysqli_query($conexao, "SELECT enunciado FROM perguntas WHERE id = $idPergunta");
       $linha = mysqli_fetch_assoc($resultado);
       if ($linha) {
           $enunciadoAtual = $linha['enunciado'];
       }

       $resultado = mysqli_query($conexao, "SELECT resposta, certa FROM respostas WHERE idpergunta = $idPergunta ORDER BY id LIMIT 4");
       $indice = 0;
       while ($linha = mysqli_fetch_assoc($resultado)) {
           $respostasAtuais[$indice] = $linha['resposta'];
           if ($linha['certa'] == 1) {
               $certaAtual = $indice;
           }
           $indice++;
       }

       echo $enunciadoAtual . ";" . implode(";", $respostasAtuais) . ";" . $certaAtual;
       exit;
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $enunciado = str_replace(array(";","\r","\n")," ",trim($_POST["enunciado"]));
       $respostas = $_POST["resposta"];
       $certa = "";
       if (isset($_POST["certa"])) {
           $certa = $_POST["certa"];
       }

       $preenchido = true;
       for ($i = 0; $i < 4; $i++) {
           $respostas[$i] = str_replace(array(";","\r","\n")," ",trim($respostas[$i]));
           if ($respostas[$i] == "") {
               $preenchido = false;
           }
       }

       if ($enunciado == "" || !$preenchido || $certa === "") {
           $msg = "Preencha o enunciado, as 4 respostas e marque a resposta certa";
       } else {
           $enunciado = mysqli_real_escape_string($conexao, $enunciado);
           mysqli_query($conexao, "UPDATE perguntas SET enunciado = '$enunciado' WHERE id = $idPergunta");

           $resultado = mysqli_query($conexao, "SELECT id FROM respostas WHERE idpergunta = $idPergunta ORDER BY id LIMIT 4");
           $indice = 0;
           while ($linha = mysqli_fetch_assoc($resultado)) {
               $ehCerta = 0;
               if ($certa == $indice) {
                   $ehCerta = 1;
               }
               $idResp = $linha['id'];
               $textoResposta = mysqli_real_escape_string($conexao, $respostas[$indice]);
               mysqli_query($conexao, "UPDATE respostas SET resposta = '$textoResposta', certa = $ehCerta WHERE id = $idResp");
               $indice++;
           }

           $msg = "Pergunta alterada com sucesso";
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Alterar pergunta de multipla escolha</title></head>
<body>
<h1>Alterar pergunta de multipla escolha</h1>
<form id="formulario">
   <input type="hidden" name="id" id="id" value="<?php echo $idPergunta; ?>">
   Pergunta: <input type="text" name="enunciado" id="enunciado" size="80"><br><br>
   Respostas (marque a certa):<br><br>
   
   <input type="radio" name="certa" value="0"> A) <input type="text" name="resposta[]" size="60"><br>
   <input type="radio" name="certa" value="1"> B) <input type="text" name="resposta[]" size="60"><br>
   <input type="radio" name="certa" value="2"> C) <input type="text" name="resposta[]" size="60"><br>
   <input type="radio" name="certa" value="3"> D) <input type="text" name="resposta[]" size="60"><br><br>
   
   <button type="button" onclick="salvar()">Salvar Alterações</button>
</form>
<p id="msg"></p>
<br><a href="index.html">Voltar</a>

<script>
var id = document.getElementById("id").value;

fetch("alterar_pergunta_multipla.php?dados=1&id=" + encodeURIComponent(id))
    .then(function (resposta) { return resposta.text(); })
    .then(function (texto) {
        var dados = texto.split(";");
        var campos = document.getElementsByName("resposta[]");
        var radios = document.getElementsByName("certa");

        document.getElementById("enunciado").value = dados[0];
        for (var i = 0; i < 4; i++) {
            campos[i].value = dados[i + 1];
        }
        if (dados[5] != "-1") {
            radios[dados[5]].checked = true;
        }
    });

function salvar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("alterar_pergunta_multipla.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
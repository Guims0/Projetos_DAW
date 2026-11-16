<?php
   $idPergunta = "";
   if (isset($_GET['id'])) { $idPergunta = $_GET['id']; }
   if (isset($_POST['id'])) { $idPergunta = $_POST['id']; }

   if (isset($_GET['dados'])) {
       $enunciadoAtual = "";
       $respostaAtual = "";

       $arqPergunta = fopen("perguntas.txt","r");
       while (!feof($arqPergunta)) {
           $linha = fgets($arqPergunta);
           if ($linha === false) break;
           $colunaDados = explode(";", $linha);
           if (trim($colunaDados[0]) == $idPergunta) {
               $enunciadoAtual = trim($colunaDados[2]);
           }
       }
       fclose($arqPergunta);

       $arqResposta = fopen("respostas.txt","r");
       while (!feof($arqResposta)) {
           $linha = fgets($arqResposta);
           if ($linha === false) break;
           $colunaDados = explode(";", $linha);
           if (trim($colunaDados[1]) == $idPergunta) {
               $respostaAtual = trim($colunaDados[2]);
           }
       }
       fclose($arqResposta);

       echo $enunciadoAtual . ";" . $respostaAtual;
       exit;
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $enunciado = str_replace(array(";","\r","\n")," ",trim($_POST["enunciado"]));
       $resposta = str_replace(array(";","\r","\n")," ",trim($_POST["resposta"]));

       if ($enunciado == "" || $resposta == "") {
           $msg = "Preencha a pergunta e a resposta";
       } else {
           $todasLinhasPerguntas = array();
           $arqPergunta = fopen("perguntas.txt","r");
           while (!feof($arqPergunta)) {
               $linha = fgets($arqPergunta);
               if ($linha === false || trim($linha) == "") break;
               
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[0]) == $idPergunta) {
                   $todasLinhasPerguntas[] = $idPergunta . ";T;" . $enunciado . "\n";
               } else {
                   $todasLinhasPerguntas[] = $linha;
               }
           }
           fclose($arqPergunta);

           $arqPergunta = fopen("perguntas.txt","w");
           foreach ($todasLinhasPerguntas as $l) {
               fwrite($arqPergunta, $l);
           }
           fclose($arqPergunta);

           $todasLinhasRespostas = array();
           $arqResposta = fopen("respostas.txt","r");
           while (!feof($arqResposta)) {
               $linha = fgets($arqResposta);
               if ($linha === false || trim($linha) == "") break;
               
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[1]) == $idPergunta) {
                   $idResp = trim($colunaDados[0]);
                   $todasLinhasRespostas[] = $idResp . ";" . $idPergunta . ";" . $resposta . ";1\n";
               } else {
                   $todasLinhasRespostas[] = $linha;
               }
           }
           fclose($arqResposta);

           $arqResposta = fopen("respostas.txt","w");
           foreach ($todasLinhasRespostas as $l) {
               fwrite($arqResposta, $l);
           }
           fclose($arqResposta);

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
   <input type="hidden" name="id" id="id" value="<?php echo htmlspecialchars($idPergunta); ?>">
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
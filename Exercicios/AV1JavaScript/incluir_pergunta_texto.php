<?php
   $msg = "";

   if (!file_exists("perguntas.txt")) {
       $arqPergunta = fopen("perguntas.txt","w") or die("erro ao criar arquivo");
       $linha = "id;tipo;enunciado\n";
       fwrite($arqPergunta, $linha);
       fclose($arqPergunta);
   }
   if (!file_exists("respostas.txt")) {
       $arqResposta = fopen("respostas.txt","w") or die("erro ao criar arquivo");
       $linha = "id;idpergunta;resposta;certa\n";
       fwrite($arqResposta, $linha);
       fclose($arqResposta);
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $enunciado = str_replace(array(";","\r","\n")," ",trim($_POST["enunciado"]));
       $resposta = str_replace(array(";","\r","\n")," ",trim($_POST["resposta"]));

       if ($enunciado == "" || $resposta == "") {
           $msg = "Preencha a pergunta e a resposta!!!";
       } else {
           $idPergunta = 1;
           $arqPergunta = fopen("perguntas.txt","r") or die("erro ao abrir arquivo");
           $linha = fgets($arqPergunta); 
           while (!feof($arqPergunta)) {
               $linha = fgets($arqPergunta);
               if ($linha === false) break;
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[0]) >= $idPergunta) {
                   $idPergunta = trim($colunaDados[0]) + 1;
               }
           }
           fclose($arqPergunta);

           $idResposta = 1;
           $arqResposta = fopen("respostas.txt","r") or die("erro ao abrir arquivo");
           $linha = fgets($arqResposta); 
           while (!feof($arqResposta)) {
               $linha = fgets($arqResposta);
               if ($linha === false) break;
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[0]) >= $idResposta) {
                   $idResposta = trim($colunaDados[0]) + 1;
               }
           }
           fclose($arqResposta);

           $arqPergunta = fopen("perguntas.txt","a") or die("erro ao criar arquivo");
           $linha = $idPergunta . ";T;" . $enunciado . "\n";
           fwrite($arqPergunta, $linha);
           fclose($arqPergunta);

           $arqResposta = fopen("respostas.txt","a") or die("erro ao criar arquivo");
           $linha = $idResposta . ";" . $idPergunta . ";" . $resposta . ";1\n";
           fwrite($arqResposta, $linha);
           fclose($arqResposta);

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
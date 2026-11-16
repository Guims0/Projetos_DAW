<?php
   $idPergunta = "";
   if (isset($_GET['id'])) { $idPergunta = $_GET['id']; }

   if (isset($_GET['dados'])) {
       $achou = false;
       $tipo = "";
       $enunciado = "";
       $respostas = array();

       if ($idPergunta != "" && file_exists("perguntas.txt")) {
           $arqPergunta = fopen("perguntas.txt","r");
           while (!feof($arqPergunta)) {
               $linha = fgets($arqPergunta);
               if ($linha === false) break;
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[0]) == $idPergunta) {
                   $achou = true;
                   $tipo = trim($colunaDados[1]);
                   $enunciado = trim($colunaDados[2]);
               }
           }
           fclose($arqPergunta);
       }

       if (!$achou) {
           echo "<p>Pergunta não encontrada.</p>";
           exit;
       }

       $arqResposta = fopen("respostas.txt","r");
       while (!feof($arqResposta)) {
           $linha = fgets($arqResposta);
           if ($linha === false) break;
           $colunaDados = explode(";", $linha);
           if (trim($colunaDados[1]) == $idPergunta) {
               $respostas[] = $colunaDados;
           }
       }
       fclose($arqResposta);

       $letras = array("A", "B", "C", "D");

       if ($tipo == "M") {
           echo "<p><b>Tipo:</b> Multipla escolha</p>";
       } else {
           echo "<p><b>Tipo:</b> Texto</p>";
       }
       echo "<p><b>Pergunta:</b> " . htmlspecialchars($enunciado) . "</p>";
       echo "<p><b>Respostas:</b></p>";

       $i = 0;
       foreach ($respostas as $r) {
           $texto = htmlspecialchars(trim($r[2]));
           if ($tipo == "M") {
               echo $letras[$i] . ") ";
           }
           if (trim($r[3]) == "1") {
               echo "<b>" . $texto . " (certa)</b><br>";
           } else {
               echo $texto . "<br>";
           }
           $i++;
       }
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Pergunta</title></head>
<body>
<h1>Pergunta</h1>
<input type="hidden" id="id" value="<?php echo htmlspecialchars($idPergunta); ?>">
<div id="conteudo"></div>
<br><a href="listar_perguntas.php">Voltar para a lista de perguntas</a>

<script>
var id = document.getElementById("id").value;

fetch("listar_pergunta.php?dados=1&id=" + encodeURIComponent(id))
    .then(function (resposta) { return resposta.text(); })
    .then(function (texto) {
        document.getElementById("conteudo").innerHTML = texto;
    });
</script>
</body>
</html>
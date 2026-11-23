<?php
   include "conexao.php";
   $idPergunta = 0;
   if (isset($_GET['id'])) { $idPergunta = (int) $_GET['id']; }

   if (isset($_GET['dados'])) {
       $resultado = mysqli_query($conexao, "SELECT tipo, enunciado FROM perguntas WHERE id = $idPergunta");
       $pergunta = mysqli_fetch_assoc($resultado);

       if (!$pergunta) {
           echo "<p>Pergunta não encontrada.</p>";
           exit;
       }

       $tipo = $pergunta['tipo'];
       $letras = array("A", "B", "C", "D");

       if ($tipo == "M") {
           echo "<p><b>Tipo:</b> Multipla escolha</p>";
       } else {
           echo "<p><b>Tipo:</b> Texto</p>";
       }
       echo "<p><b>Pergunta:</b> " . htmlspecialchars($pergunta['enunciado']) . "</p>";
       echo "<p><b>Respostas:</b></p>";

       $resultado = mysqli_query($conexao, "SELECT resposta, certa FROM respostas WHERE idpergunta = $idPergunta ORDER BY id");
       $i = 0;
       while ($r = mysqli_fetch_assoc($resultado)) {
           $texto = htmlspecialchars($r['resposta']);
           if ($tipo == "M") {
               echo $letras[$i] . ") ";
           }
           if ($r['certa'] == 1) {
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
<input type="hidden" id="id" value="<?php echo $idPergunta; ?>">
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
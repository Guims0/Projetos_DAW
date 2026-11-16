<?php
   if (isset($_GET['lista'])) {
       $respostas = array();

       if (file_exists("respostas.txt")) {
           $arqResposta = fopen("respostas.txt","r");
           $linha = fgets($arqResposta);
           while (!feof($arqResposta)) {
               $linha = fgets($arqResposta);
               if ($linha === false) break;
               if (trim($linha) == "") continue;
               $c = explode(";", $linha);
               $respostas[trim($c[1])][] = $c;
           }
           fclose($arqResposta);
       }

       if (!file_exists("perguntas.txt")) { exit; }

       $letras = array("A", "B", "C", "D");
       $arqPergunta = fopen("perguntas.txt","r");
       $linha = fgets($arqPergunta);
       while (!feof($arqPergunta)) {
           $linha = fgets($arqPergunta);
           if ($linha === false) break;
           if (trim($linha) == "") continue;
           $c = explode(";", $linha);
           $id = trim($c[0]);
           $tipo = trim($c[1]);

           if ($tipo == "M") {
               $tipoNome = "Multipla escolha";
               $pagina = "alterar_pergunta_multipla.php";
           } else {
               $tipoNome = "Texto";
               $pagina = "alterar_pergunta_texto.php";
           }

           echo "<tr>";
           echo "<td>" . $id . "</td>";
           echo "<td>" . $tipoNome . "</td>";
           echo "<td>" . htmlspecialchars(trim($c[2])) . "</td>";
           echo "<td>";
           if (isset($respostas[$id])) {
               $i = 0;
               foreach ($respostas[$id] as $r) {
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
           }
           echo "</td>";
           echo "<td><a href='listar_pergunta.php?id=" . $id . "'>Ver</a> | ";
           echo "<a href='" . $pagina . "?id=" . $id . "'>Alterar</a> | ";
           echo "<button onclick='excluir(" . $id . ")'>Excluir</button></td>";
           echo "</tr>";
       }
       fclose($arqPergunta);
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Perguntas</title></head>
<body>
<h1>Perguntas</h1>
<p id="msg"></p>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Tipo</th><th>Pergunta</th><th>Respostas</th><th>Ações</th></tr>
    <tbody id="lista"></tbody>
</table>
<br><a href="index.html">Voltar para o Menu</a>

<script>
function carregar() {
    fetch("listar_perguntas.php?lista=1")
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("lista").innerHTML = texto;
        });
}

function excluir(id) {
    fetch("excluir_pergunta.php?id=" + encodeURIComponent(id))
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
            carregar();
        });
}

carregar();
</script>
</body>
</html>
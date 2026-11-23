<?php
   include "conexao.php";

   if (isset($_GET['lista'])) {
       $respostas = array();

       $resultado = mysqli_query($conexao, "SELECT idpergunta, resposta, certa FROM respostas ORDER BY id");
       while ($linha = mysqli_fetch_assoc($resultado)) {
           $respostas[$linha['idpergunta']][] = $linha;
       }

       $letras = array("A", "B", "C", "D");

       $resultado = mysqli_query($conexao, "SELECT id, tipo, enunciado FROM perguntas ORDER BY id");
       while ($linha = mysqli_fetch_assoc($resultado)) {
           $id = $linha['id'];
           $tipo = $linha['tipo'];

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
           echo "<td>" . htmlspecialchars($linha['enunciado']) . "</td>";
           echo "<td>";
           if (isset($respostas[$id])) {
               $i = 0;
               foreach ($respostas[$id] as $r) {
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
           }
           echo "</td>";
           echo "<td><a href='listar_pergunta.php?id=" . $id . "'>Ver</a> | ";
           echo "<a href='" . $pagina . "?id=" . $id . "'>Alterar</a> | ";
           echo "<button onclick='excluir(" . $id . ")'>Excluir</button></td>";
           echo "</tr>";
       }
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
<?php
   include "conexao.php";

   if (isset($_GET['lista'])) {
       $resultado = mysqli_query($conexao, "SELECT id, nome, email FROM usuarios ORDER BY id");

       while ($linha = mysqli_fetch_assoc($resultado)) {
           $id = $linha['id'];
           echo "<tr>";
           echo "<td>" . $id . "</td>";
           echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
           echo "<td>" . htmlspecialchars($linha['email']) . "</td>";
           echo "<td><a href='alterar_usuario.php?id=" . $id . "'>Alterar</a> | ";
           echo "<button onclick='excluir(" . $id . ")'>Excluir</button></td>";
           echo "</tr>";
       }
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Usuários</title></head>
<body>
<h1>Usuários</h1>
<p id="msg"></p>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Ações</th></tr>
    <tbody id="lista"></tbody>
</table>
<br><a href="incluir_usuario.php">Incluir Usuário</a> | <a href="index.html">Voltar para o Menu</a>

<script>
function carregar() {
    fetch("listar_usuarios.php?lista=1")
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("lista").innerHTML = texto;
        });
}

function excluir(id) {
    fetch("excluir_usuario.php?id=" + encodeURIComponent(id))
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
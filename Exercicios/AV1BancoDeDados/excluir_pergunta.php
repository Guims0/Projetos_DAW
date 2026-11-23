<?php
include "conexao.php";
$msg = "";
$idPergunta = 0;
if (isset($_GET['id'])) { $idPergunta = (int) $_GET['id']; }

if ($idPergunta > 0) {
    mysqli_query($conexao, "DELETE FROM respostas WHERE idpergunta = $idPergunta");
    mysqli_query($conexao, "DELETE FROM perguntas WHERE id = $idPergunta");

    $msg = "Pergunta e respostas excluídas com sucesso!";
}
echo $msg;
?>
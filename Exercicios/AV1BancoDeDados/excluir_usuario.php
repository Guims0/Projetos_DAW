<?php
include "conexao.php";
$msg = "";
$idUsuario = 0;
if (isset($_GET['id'])) { $idUsuario = (int) $_GET['id']; }

if ($idUsuario > 0) {
    if (mysqli_query($conexao, "DELETE FROM usuarios WHERE id = $idUsuario")) {
        $msg = "Usuário excluído com sucesso!";
    } else {
        $msg = "Erro ao excluir o usuário";
    }
}
echo $msg;
?>
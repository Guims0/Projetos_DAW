<?php
$msg = "";
$idUsuario = "";
if (isset($_GET['id'])) { $idUsuario = $_GET['id']; }

if ($idUsuario != "") {
    $todasLinhas = array();
    $arq = fopen("usuarios.txt", "r");
    while (!feof($arq)) {
        $linha = fgets($arq);
        if ($linha === false || trim($linha) == "") break;
        $colunaDados = explode(";", $linha);
        if (trim($colunaDados[0]) != $idUsuario || trim($colunaDados[0]) == "id") {
            $todasLinhas[] = $linha;
        }
    }
    fclose($arq);

    $arq = fopen("usuarios.txt", "w");
    foreach ($todasLinhas as $l) {
        fwrite($arq, $l);
    }
    fclose($arq);

    $msg = "Usuário excluído com sucesso!";
}
echo $msg;
?>
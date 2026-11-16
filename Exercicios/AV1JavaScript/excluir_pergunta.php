<?php
$msg = "";
$idPergunta = "";
if (isset($_GET['id'])) { $idPergunta = $_GET['id']; }

if ($idPergunta != "") {
    $todasLinhasPerguntas = array();
    $arqPergunta = fopen("perguntas.txt", "r");
    while (!feof($arqPergunta)) {
        $linha = fgets($arqPergunta);
        if ($linha === false || trim($linha) == "") break;
        $colunaDados = explode(";", $linha);
        if (trim($colunaDados[0]) != $idPergunta || trim($colunaDados[0]) == "id") {
            $todasLinhasPerguntas[] = $linha;
        }
    }
    fclose($arqPergunta);

    $arqPergunta = fopen("perguntas.txt", "w");
    foreach ($todasLinhasPerguntas as $l) {
        fwrite($arqPergunta, $l);
    }
    fclose($arqPergunta);

    $todasLinhasRespostas = array();
    $arqResposta = fopen("respostas.txt", "r");
    while (!feof($arqResposta)) {
        $linha = fgets($arqResposta);
        if ($linha === false || trim($linha) == "") break;
        $colunaDados = explode(";", $linha);
        if (trim($colunaDados[1]) != $idPergunta || trim($colunaDados[0]) == "id") {
            $todasLinhasRespostas[] = $linha;
        }
    }
    fclose($arqResposta);

    $arqResposta = fopen("respostas.txt", "w");
    foreach ($todasLinhasRespostas as $l) {
        fwrite($arqResposta, $l);
    }
    fclose($arqResposta);

    $msg = "Pergunta e respostas excluídas com sucesso!";
}
echo $msg;
?>
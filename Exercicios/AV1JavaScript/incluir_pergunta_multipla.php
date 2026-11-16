<?php

    $msg = "";

    if (!file_exists("perguntas.txt")) {
        $arqPergunta = fopen("perguntas.txt","w") or die("erro ao criar arquivo");
        $linha = "id;tipo;enunciado\n";
        fwrite($arqPergunta,$linha);
        fclose($arqPergunta);
    }
    if (!file_exists("respostas.txt")) {
        $arqResposta = fopen("respostas.txt","w") or die("erro ao criar arquivo");
        $linha = "id;idpergunta;resposta;certa\n";
        fwrite($arqResposta,$linha);
        fclose($arqResposta);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $enunciado = str_replace(array(";","\r","\n")," ",trim($_POST["enunciado"]));
        $respostas = $_POST["resposta"];
        $certa = "";
        if (isset($_POST["certa"])) {
            $certa = $_POST["certa"];
        }

        $preenchido = true;
        for ($i = 0; $i < 4; $i++) {
            $respostas[$i] = str_replace(array(";","\r","\n")," ",trim($respostas[$i]));
            if ($respostas[$i] == "") {
                $preenchido = false;
            }
        }

        if ($enunciado == "" || !$preenchido || $certa === "") {
            $msg = "Preencha o enunciado, as 4 respostas e marque a resposta certa";
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
            $linha = $idPergunta . ";M;" . $enunciado . "\n";
            fwrite($arqPergunta,$linha);
            fclose($arqPergunta);

            $arqResposta = fopen("respostas.txt","a") or die("erro ao criar arquivo");
            for ($i = 0; $i < 4; $i++) {
                $ehCerta = 0;
                if ($certa == $i) {
                    $ehCerta = 1;
                }
                $linha = ($idResposta + $i) . ";" . $idPergunta . ";" . $respostas[$i] . ";" . $ehCerta . "\n";
                fwrite($arqResposta,$linha);
            }
            fclose($arqResposta);

            $msg = "Pergunta incluida com sucesso";
        }
        echo $msg;
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Incluir pergunta de multipla escolha</title>
</head>
<body>
<h1>Incluir pergunta de multipla escolha</h1>

<form id="formulario">
    Pergunta: <input type="text" name="enunciado" size="80">
    <br><br>
    marque a resposta certa:
    <br><br>
    <input type="radio" name="certa" value="0"> A) <input type="text" name="resposta[]" size="60"><br>
    <input type="radio" name="certa" value="1"> B) <input type="text" name="resposta[]" size="60"><br>
    <input type="radio" name="certa" value="2"> C) <input type="text" name="resposta[]" size="60"><br>
    <input type="radio" name="certa" value="3"> D) <input type="text" name="resposta[]" size="60"><br>
    <br>
    <button type="button" onclick="enviar()">Incluir Pergunta</button>
</form>

<p id="msg"></p>

<br>
<a href="index.html">Voltar</a>

<script>
function enviar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("incluir_pergunta_multipla.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>

</body>
</html>
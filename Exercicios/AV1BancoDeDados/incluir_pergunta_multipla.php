<?php
    include "conexao.php";
    $msg = "";

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
            $enunciado = mysqli_real_escape_string($conexao, $enunciado);

            mysqli_query($conexao, "INSERT INTO perguntas (tipo, enunciado) VALUES ('M', '$enunciado')");
            $idPergunta = mysqli_insert_id($conexao);

            for ($i = 0; $i < 4; $i++) {
                $ehCerta = 0;
                if ($certa == $i) {
                    $ehCerta = 1;
                }
                $textoResposta = mysqli_real_escape_string($conexao, $respostas[$i]);
                mysqli_query($conexao, "INSERT INTO respostas (idpergunta, resposta, certa) VALUES ($idPergunta, '$textoResposta', $ehCerta)");
            }

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
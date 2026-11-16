<?php
   $idPergunta = "";
   if (isset($_GET['id'])) { $idPergunta = $_GET['id']; }
   if (isset($_POST['id'])) { $idPergunta = $_POST['id']; }

   if (isset($_GET['dados'])) {
       $enunciadoAtual = "";
       $respostasAtuais = array("", "", "", "");
       $certaAtual = -1;

       $arqPergunta = fopen("perguntas.txt","r");
       while (!feof($arqPergunta)) {
           $linha = fgets($arqPergunta);
           if ($linha === false) break;
           $colunaDados = explode(";", $linha);
           if (trim($colunaDados[0]) == $idPergunta) {
               $enunciadoAtual = trim($colunaDados[2]);
           }
       }
       fclose($arqPergunta);

       $arqResposta = fopen("respostas.txt","r");
       $indice = 0;
       while (!feof($arqResposta)) {
           $linha = fgets($arqResposta);
           if ($linha === false) break;
           $colunaDados = explode(";", $linha);
           
           if (trim($colunaDados[1]) == $idPergunta && $indice < 4) {
               $respostasAtuais[$indice] = trim($colunaDados[2]);
               if (trim($colunaDados[3]) == "1") {
                   $certaAtual = $indice;
               }
               $indice++;
           }
       }
       fclose($arqResposta);

       echo $enunciadoAtual . ";" . implode(";", $respostasAtuais) . ";" . $certaAtual;
       exit;
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
           $todasLinhasPerguntas = array();
           $arqPergunta = fopen("perguntas.txt","r");
           while (!feof($arqPergunta)) {
               $linha = fgets($arqPergunta);
               if ($linha === false || trim($linha) == "") break;
               
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[0]) == $idPergunta) {
                   $todasLinhasPerguntas[] = $idPergunta . ";M;" . $enunciado . "\n";
               } else {
                   $todasLinhasPerguntas[] = $linha;
               }
           }
           fclose($arqPergunta);

           $arqPergunta = fopen("perguntas.txt","w");
           foreach ($todasLinhasPerguntas as $l) {
               fwrite($arqPergunta, $l);
           }
           fclose($arqPergunta);

           $todasLinhasRespostas = array();
           $arqResposta = fopen("respostas.txt","r");
           $indiceControle = 0;
           
           while (!feof($arqResposta)) {
               $linha = fgets($arqResposta);
               if ($linha === false || trim($linha) == "") break;
               
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[1]) == $idPergunta && $indiceControle < 4) {
                   $ehCerta = 0;
                   if ($certa == $indiceControle) {
                       $ehCerta = 1;
                   }
                   $idResp = trim($colunaDados[0]); 
                   $todasLinhasRespostas[] = $idResp . ";" . $idPergunta . ";" . $respostas[$indiceControle] . ";" . $ehCerta . "\n";
                   $indiceControle++;
               } else {
                   $todasLinhasRespostas[] = $linha;
               }
           }
           fclose($arqResposta);

           $arqResposta = fopen("respostas.txt","w");
           foreach ($todasLinhasRespostas as $l) {
               fwrite($arqResposta, $l);
           }
           fclose($arqResposta);

           $msg = "Pergunta alterada com sucesso";
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Alterar pergunta de multipla escolha</title></head>
<body>
<h1>Alterar pergunta de multipla escolha</h1>
<form id="formulario">
   <input type="hidden" name="id" id="id" value="<?php echo htmlspecialchars($idPergunta); ?>">
   Pergunta: <input type="text" name="enunciado" id="enunciado" size="80"><br><br>
   Respostas (marque a certa):<br><br>
   
   <input type="radio" name="certa" value="0"> A) <input type="text" name="resposta[]" size="60"><br>
   <input type="radio" name="certa" value="1"> B) <input type="text" name="resposta[]" size="60"><br>
   <input type="radio" name="certa" value="2"> C) <input type="text" name="resposta[]" size="60"><br>
   <input type="radio" name="certa" value="3"> D) <input type="text" name="resposta[]" size="60"><br><br>
   
   <button type="button" onclick="salvar()">Salvar Alterações</button>
</form>
<p id="msg"></p>
<br><a href="index.html">Voltar</a>

<script>
var id = document.getElementById("id").value;

fetch("alterar_pergunta_multipla.php?dados=1&id=" + encodeURIComponent(id))
    .then(function (resposta) { return resposta.text(); })
    .then(function (texto) {
        var dados = texto.split(";");
        var campos = document.getElementsByName("resposta[]");
        var radios = document.getElementsByName("certa");

        document.getElementById("enunciado").value = dados[0];
        for (var i = 0; i < 4; i++) {
            campos[i].value = dados[i + 1];
        }
        if (dados[5] != "-1") {
            radios[dados[5]].checked = true;
        }
    });

function salvar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("alterar_pergunta_multipla.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
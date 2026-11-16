<?php
   $idUsuario = "";
   if (isset($_GET['id'])) { $idUsuario = $_GET['id']; }
   if (isset($_POST['id'])) { $idUsuario = $_POST['id']; }

   if (isset($_GET['dados'])) {
       $nomeAtual = "";
       $emailAtual = "";
       $arq = fopen("usuarios.txt","r");
       while (!feof($arq)) {
           $linha = fgets($arq);
           if ($linha === false) break;
           $colunaDados = explode(";", $linha);
           if (trim($colunaDados[0]) == $idUsuario) {
               $nomeAtual = trim($colunaDados[1]);
               $emailAtual = trim($colunaDados[2]);
           }
       }
       fclose($arq);
       echo $nomeAtual . ";" . $emailAtual;
       exit;
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $nome = str_replace(array(";","\r","\n")," ",trim($_POST["nome"]));
       $email = str_replace(array(";","\r","\n")," ",trim($_POST["email"]));

       if ($nome == "" || $email == "") {
           $msg = "Preencha o nome e o e-mail";
       } else {
           $todasLinhas = array();
           $arq = fopen("usuarios.txt","r");
           while (!feof($arq)) {
               $linha = fgets($arq);
               if ($linha === false || trim($linha) == "") break;
               
               $colunaDados = explode(";", $linha);
               if (trim($colunaDados[0]) == $idUsuario) {
                   $todasLinhas[] = $idUsuario . ";" . $nome . ";" . $email . "\n";
               } else {
                   $todasLinhas[] = $linha;
               }
           }
           fclose($arq);

           $arq = fopen("usuarios.txt","w");
           foreach ($todasLinhas as $l) {
               fwrite($arq, $l);
           }
           fclose($arq);

           $msg = "Usuário alterado com sucesso!";
       }
       echo $msg;
       exit;
   }
?>
<!DOCTYPE html>
<html>
<head><title>Alterar Usuário</title></head>
<body>
<h1>Alterar Usuário</h1>
<form id="formulario">
   <input type="hidden" name="id" id="id" value="<?php echo htmlspecialchars($idUsuario); ?>">
   Nome: <input type="text" name="nome" id="nome" size="50"><br><br>
   E-mail: <input type="text" name="email" id="email" size="50"><br><br>
   <button type="button" onclick="salvar()">Salvar Alterações</button>
</form>
<p id="msg"></p>
<br><a href="listar_usuarios.php">Voltar para a lista</a>

<script>
var id = document.getElementById("id").value;

fetch("alterar_usuario.php?dados=1&id=" + encodeURIComponent(id))
    .then(function (resposta) { return resposta.text(); })
    .then(function (texto) {
        var dados = texto.split(";");
        document.getElementById("nome").value = dados[0];
        document.getElementById("email").value = dados[1];
    });

function salvar() {
    var dados = new FormData(document.getElementById("formulario"));

    fetch("alterar_usuario.php", { method: "POST", body: dados })
        .then(function (resposta) { return resposta.text(); })
        .then(function (texto) {
            document.getElementById("msg").innerText = texto;
        });
}
</script>
</body>
</html>
<?php
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $matricula = $_POST["matricula"];
        $nome = $_POST["nome"];
        $cpf = $_POST["cpf"];
        $endereco = $_POST["endereco"];

        $arqProfessores = fopen("professores.txt","r") or die("erro ao abrir arquivo");
        $arqProfessoresNovo = fopen("professores_novo.txt","w") or die("erro ao abrir arquivo");

        $linha = fgets($arqProfessores);
        fwrite($arqProfessoresNovo,$linha);

        while(!feof($arqProfessores)) {
            $linha = fgets($arqProfessores);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[0]) == trim($matricula)) {
                $linha = $matricula . ";" . $nome . ";" . $cpf . ";" . $endereco . "\n";
            }
            fwrite($arqProfessoresNovo,$linha);
        }
        fclose($arqProfessores);
        fclose($arqProfessoresNovo);

        unlink("professores.txt");
        rename("professores_novo.txt","professores.txt");

        $msg = "Deu certo!";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Alterar Professor</title>
</head>
<body>
<p><?php echo $msg ?></p>
<br>
<a href="listar_professores.php">Voltar para a lista</a>
</body>
</html>
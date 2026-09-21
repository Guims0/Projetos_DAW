<?php
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $matricula = $_POST["matricula"];

        $arqProfessores = fopen("professores.txt","r") or die("erro ao abrir arquivo");
        $arqProfessoresNovo = fopen("professores_novo.txt","w") or die("erro ao abrir arquivo");

        $linha = fgets($arqProfessores);
        fwrite($arqProfessoresNovo,$linha);

        while(!feof($arqProfessores)) {
            $linha = fgets($arqProfessores);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[0]) != trim($matricula)) {
                fwrite($arqProfessoresNovo,$linha);
            }
        }
        fclose($arqProfessores);
        fclose($arqProfessoresNovo);

        unlink("professores.txt");
        rename("professores_novo.txt","professores.txt");

        $msg = "Professor excluido com sucesso!";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Excluir Professor</title>
</head>
<body>
<p><?php echo $msg ?></p>
<br>
<a href="listar_professores.php">Voltar para a lista</a>
</body>
</html>
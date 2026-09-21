<?php
    $matricula = "";
    $nome = "";
    $cpf = "";
    $endereco = "";

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["matricula"])) {
        $matricula = $_GET["matricula"];
        $arqProfessores = fopen("professores.txt","r") or die("erro ao abrir arquivo");
        $linha = fgets($arqProfessores); 
        while(!feof($arqProfessores)) {
            $linha = fgets($arqProfessores);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[0]) == trim($matricula)) {
                $nome = $colunaDados[1];
                $cpf = $colunaDados[2];
                $endereco = trim($colunaDados[3]);
                break;
            }
        }
        fclose($arqProfessores);
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Excluir Professor</title>
</head>
<body>
<h2>Excluir Professor</h2>
<?php if (isset($_GET["matricula"]) && $nome != "") { ?>
    <p>Confirma a exclusao do professor abaixo?</p>
    <p>
        Matricula: <?php echo $matricula ?><br>
        Nome: <?php echo $nome ?><br>
        CPF: <?php echo $cpf ?><br>
        Endereco: <?php echo $endereco ?>
    </p>
    <form action="excluir_professor.php" method="POST">
        <input type="hidden" name="matricula" value='<?php echo $matricula ?>'>
        <input type="submit" value="Excluir Professor">
    </form>
<?php } ?>
<br>
<a href="listar_professores.php">Voltar para a lista</a>
</body>
</html>
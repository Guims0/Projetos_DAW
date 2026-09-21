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
<title>Alterar Professor</title>
</head>
<body>
<h2>Alterar Professor</h2>
<form action="alterar_professor.php" method="POST">
    Matricula: <input type="text" name="matricula" value='<?php echo $matricula ?>' readonly>
    <br><br>
    Nome: <input type="text" name="nome" value='<?php echo $nome ?>'>
    <br><br>
    CPF: <input type="text" name="cpf" value='<?php echo $cpf ?>'>
    <br><br>
    Endereco: <input type="text" name="endereco" value='<?php echo $endereco ?>'>
    <br><br>
    <input type="submit" value="Alterar Professor">
</form>
<br>
<a href="listar_professores.php">Voltar para a lista</a>
</body>
</html>
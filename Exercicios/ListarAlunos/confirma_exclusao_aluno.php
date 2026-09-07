
Confirmação de exclusão de aluno · PHP
<?php
 
    $matricula = "";
    $nome = "";
    $email = "";
 
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["matricula"])) {
        $matricula = $_GET["matricula"];
 
        $arqAluno = fopen("alunos.txt","r") or die("erro ao abrir arquivo");
        $linha = fgets($arqAluno); 
        while (!feof($arqAluno)) {
            $linha = fgets($arqAluno);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[0]) == trim($matricula)) {
                $nome = trim($colunaDados[1]);
                $email = trim($colunaDados[2]);
                break;
            }
        }
        fclose($arqAluno);
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Excluir Aluno</title>
</head>
<body>
<h1>Excluir Aluno</h1>
 
<?php if ($nome != "") { ?>
    <p>Confirma a exclusao do aluno abaixo?</p>
    <p>
        Matricula: <?php echo $matricula ?><br>
        Nome: <?php echo $nome ?><br>
        Email: <?php echo $email ?>
    </p>
    <form action="excluir_aluno.php" method="POST">
        <input type="hidden" name="matricula" value='<?php echo $matricula ?>'>
        <input type="submit" value="Excluir Aluno">
    </form>
<?php } else { ?>
    <p>Aluno nao encontrado.</p>
<?php } ?>
 
<br>
<a href="listar_alunos.php">Voltar para a listagem</a>
 
</body>
</html>
 

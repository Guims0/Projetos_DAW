
Formulário alterar aluno · PHP
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
<title>Alterar Aluno</title>
</head>
<body>
<h1>Alterar Aluno</h1>
 
<?php if ($nome != "") { ?>
    <form action="alterar_aluno.php" method="POST">
        Matricula: <input type="text" name="matricula"
                    value='<?php echo $matricula ?>' readonly>
        <br><br>
        Nome: <input type="text" name="nome"
                    value='<?php echo $nome ?>'>
        <br><br>
        Email: <input type="text" name="email"
                    value='<?php echo $email ?>'>
        <br><br>
        <input type="submit" value="Alterar Aluno">
    </form>
<?php } else { ?>
    <p>Aluno nao encontrado.</p>
<?php } ?>
 
<br>
<a href="listar_alunos.php">Voltar para a listagem</a>
 
</body>
</html>
 

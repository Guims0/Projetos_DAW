
Incluir aluno · PHP
<?php
 
    $msg = "";
 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $matricula = $_POST["matricula"];
        $nome = $_POST["nome"];
        $email = $_POST["email"];
 
        if (!file_exists("alunos.txt")) {
            $arqAluno = fopen("alunos.txt","w") or die("erro ao criar arquivo");
            $linha = "matricula;nome;email\n";
            fwrite($arqAluno,$linha);
            fclose($arqAluno);
        }
        $arqAluno = fopen("alunos.txt","a") or die("erro ao criar arquivo");
        $linha = $matricula . ";" . $nome . ";" . $email . "\n";
        fwrite($arqAluno,$linha);
        fclose($arqAluno);
 
        $msg = "Aluno incluido com sucesso!!!";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Incluir Aluno</title>
</head>
<body>
<h1>Incluir Aluno</h1>
 
<form action="incluir_aluno.php" method="POST">
    Matricula: <input type="text" name="matricula">
    <br><br>
    Nome: <input type="text" name="nome">
    <br><br>
    Email: <input type="text" name="email">
    <br><br>
    <input type="submit" value="Incluir Aluno">
</form>
 
<p><?php echo $msg ?></p>
 
<br>
<a href="listar_alunos.php">Voltar para a listagem</a>
 
</body>
</html>
 

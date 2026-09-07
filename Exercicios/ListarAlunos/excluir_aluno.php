
Excluir aluno · PHP
<?php
 
    $msg = "";
 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $matricula = $_POST["matricula"];
 
        $arqAluno = fopen("alunos.txt","r") or die("erro ao abrir arquivo");
        $arqAlunoNovo = fopen("alunos_novo.txt","w") or die("erro ao abrir arquivo");
 
        $linha = fgets($arqAluno); 
        fwrite($arqAlunoNovo,$linha);
 
        while (!feof($arqAluno)) {
            $linha = fgets($arqAluno);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[0]) != trim($matricula)) {
                fwrite($arqAlunoNovo,$linha);
            }
        }
        fclose($arqAluno);
        fclose($arqAlunoNovo);
 
        unlink("alunos.txt");
        rename("alunos_novo.txt","alunos.txt");
 
        $msg = "Aluno excluido com sucesso!!!";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Excluir Aluno</title>
</head>
<body>
<h1>Excluir Aluno</h1>
 
<p><?php echo $msg ?></p>
 
<br>
<a href="listar_alunos.php">Voltar para a listagem</a>
 
</body>
</html>
 

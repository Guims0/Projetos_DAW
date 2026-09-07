
Listar alunos · PHP
<?php
 
    if (!file_exists("alunos.txt")) {
        $arqAluno = fopen("alunos.txt","w") or die("erro ao criar arquivo");
        $linha = "matricula;nome;email\n";
        fwrite($arqAluno,$linha);
        fclose($arqAluno);
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Listar Alunos</title>
</head>
<body>
<h1>Listar Alunos</h1>
 
<table border="1">
    <tr>
        <th>Matricula</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Acoes</th>
    </tr>
    <?php
        $arqAluno = fopen("alunos.txt","r") or die("erro ao abrir arquivo");
        $linha = fgets($arqAluno); 
        while (!feof($arqAluno)) {
            $linha = fgets($arqAluno);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            $matricula = trim($colunaDados[0]);
            $nome = trim($colunaDados[1]);
            $email = trim($colunaDados[2]);
 
            echo "<tr>";
            echo "<td>" . $matricula . "</td>";
            echo "<td>" . $nome . "</td>";
            echo "<td>" . $email . "</td>";
            echo "<td>";
            ?>
                <form action="form_alterar_aluno.php" method="GET" style="display:inline">
                    <input type="hidden" name="matricula" value="<?php echo $matricula ?>">
                    <input type="submit" value="Alterar">
                </form>
                <form action="confirma_exclusao_aluno.php" method="GET" style="display:inline">
                    <input type="hidden" name="matricula" value="<?php echo $matricula ?>">
                    <input type="submit" value="Excluir">
                </form>
            <?php
            echo "</td>";
            echo "</tr>";
        }
        fclose($arqAluno);
    ?>
</table>
 
<br>
<a href="incluir_aluno.php">Incluir Aluno</a>
 
</body>
</html>
 

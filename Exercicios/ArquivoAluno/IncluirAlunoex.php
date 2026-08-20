<?php
$msg = ""; 
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"];
    $curso = $_POST["curso"];
    
   
    echo "nome: " . $nome . " matricula: " . $matricula . " curso: " . $curso;
   

   if (!file_exists("alunos.txt")) {
       $arqAluno = fopen("alunos.txt","w") or die("erro ao criar arquivo");
       $linha = "nome;matricula;curso\n";
       fwrite($arqAluno,$linha);
       fclose($arqAluno);
   }
   
  
   $arqAluno = fopen("alunos.txt","a") or die("erro ao criar arquivo");
   $linha = $nome . ";" . $matricula . ";" . $curso . "\n";
   fwrite($arqAluno,$linha);
   fclose($arqAluno);
   
   $msg = "Concluido. Aluno salvo com sucesso.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Incluir Aluno</title>
</head>
<body>
    <h1>Criar Novo Aluno</h1>
    
  
    <form action="IncluirAlunoex.php" method="POST">
        Nome: <input type="text" name="nome" required>
        <br><br>
        Matrícula: <input type="text" name="matricula" required>
        <br><br>
        Nome do Curso: <input type="text" name="curso" required>
        <br><br>
        <input type="submit" value="Criar Novo Aluno">
    </form>
    
    <p><?php echo $msg; ?></p>
    <br>
</body>
</html>
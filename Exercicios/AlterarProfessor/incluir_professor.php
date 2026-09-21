<?php
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $matricula = $_POST["matricula"];
        $nome = $_POST["nome"];
        $cpf = $_POST["cpf"];
        $endereco = $_POST["endereco"];

        if (!file_exists("professores.txt")) {
            $arqProfessores = fopen("professores.txt","w") or die("erro ao criar arquivo");
            $linha = "matricula;nome;cpf;endereco\n";
            fwrite($arqProfessores,$linha);
            fclose($arqProfessores);
        }
        
        $arqProfessores = fopen("professores.txt","a") or die("erro ao criar arquivo");
        $linha = $matricula . ";" . $nome . ";" . $cpf . ";" . $endereco . "\n";
        fwrite($arqProfessores,$linha);
        fclose($arqProfessores);
        
        $msg = "Deu certo!";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Incluir Professor</title>
</head>
<body>
<h2>Incluir Professor</h2>
<form action="incluir_professor.php" method="POST">
    Matricula: <input type="text" name="matricula">
    <br><br>
    Nome: <input type="text" name="nome">
    <br><br>
    CPF: <input type="text" name="cpf">
    <br><br>
    Endereco: <input type="text" name="endereco">
    <br><br>
    <input type="submit" value="Incluir Professor">
</form>

<p><?php echo $msg ?></p>
<br>
<a href="listar_professores.php">Listar Professores</a>
</body>
</html>

Gerenciar disciplinas · PHP
<?php
    $nome = "";
    $sigla = "";
    $carga = "";
    $msg = "";
 
    $acao = isset($_GET["acao"]) ? $_GET["acao"] : "incluir";
 
    if ($acao == "incluir" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST["nome"];
        $sigla = $_POST["sigla"];
        $carga = $_POST["carga"];
        echo "nome: " . $nome . " sigla: " . $sigla . " carga: " . $carga;
 
        if (!file_exists("disciplinas.txt")) {
            $arqDisc = fopen("disciplinas.txt","w") or die("erro ao criar arquivo");
            $linha = "nome;sigla;carga\n";
            fwrite($arqDisc,$linha);
            fclose($arqDisc);
        }
        $arqDisc = fopen("disciplinas.txt","a") or die("erro ao criar arquivo");
        $linha = $nome . ";" . $sigla . ";" . $carga . "\n";
        fwrite($arqDisc,$linha);
        fclose($arqDisc);
        $msg = "Deu tudo certo!!!";
    }
 
    if ($acao == "pedeAlterar" && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["sigla"])) {
        $sigla = $_GET["sigla"];
        echo " sigla: " . $sigla;
        $arqDisc = fopen("disciplinas.txt","r") or die("erro ao abrir arquivo");
        $linha = fgets($arqDisc);
        while(!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[1]) == trim($sigla)) {
                $nome = $colunaDados[0];
                $carga = trim($colunaDados[2]);
                break;
            }
        }
        fclose($arqDisc);
        $msg = "Deu tudo certo!!!";
    }
 
    if ($acao == "alterar" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $sigla = $_POST["sigla"];
        $nome = $_POST["nome"];
        $carga = $_POST["carga"];
 
        $arqDisc = fopen("disciplinas.txt","r") or die("erro ao abrir arquivo");
        $arqDiscNovo = fopen("disciplinas_novo.txt","w") or die("erro ao abrir arquivo");
 
        $linha = fgets($arqDisc);
        fwrite($arqDiscNovo,$linha);
 
        while(!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[1]) == trim($sigla)) {
                $linha = $nome . ";" . $sigla . ";" . $carga . "\n";
            }
            fwrite($arqDiscNovo,$linha);
        }
        fclose($arqDisc);
        fclose($arqDiscNovo);
 
        unlink("disciplinas.txt");
        rename("disciplinas_novo.txt","disciplinas.txt");
 
        $msg = "Deu tudo certo!!!";
    }
 
 
    if ($acao == "pedeExcluir" && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["sigla"])) {
        $sigla = $_GET["sigla"];
        echo " sigla: " . $sigla;
        $arqDisc = fopen("disciplinas.txt","r") or die("erro ao abrir arquivo");
        $linha = fgets($arqDisc); 
        while(!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[1]) == trim($sigla)) {
                $nome = $colunaDados[0];
                $carga = trim($colunaDados[2]);
                break;
            }
        }
        fclose($arqDisc);
        $msg = "Deu tudo certo!!!";
    }
 
  
    if ($acao == "excluir" && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $sigla = $_POST["sigla"];
 
        $arqDisc = fopen("disciplinas.txt","r") or die("erro ao abrir arquivo");
        $arqDiscNovo = fopen("disciplinas_novo.txt","w") or die("erro ao abrir arquivo");
 
        $linha = fgets($arqDisc);
        fwrite($arqDiscNovo,$linha);
 
        while(!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            if (trim($colunaDados[1]) != trim($sigla)) {
                fwrite($arqDiscNovo,$linha);
            }
        }
        fclose($arqDisc);
        fclose($arqDiscNovo);
 
        unlink("disciplinas.txt");
        rename("disciplinas_novo.txt","disciplinas.txt");
 
        $msg = "Disciplina excluida com sucesso!!!";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Gerenciar Disciplinas</title>
</head>
<body>
<h1>Gerenciar Disciplinas</h1>
 
<ul>
    <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir">Incluir Disciplina</a></li>
    <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?acao=listar">Listar Disciplinas</a></li>
    <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?acao=pedeAlterar">Alterar Disciplina</a></li>
    <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?acao=pedeExcluir">Excluir Disciplina</a></li>
</ul>
<br>
 
<?php if ($acao == "incluir") { ?>
    <h2>Criar Nova Disciplina</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=incluir" method="POST">
        Nome: <input type="text" name="nome">
        <br><br>
        Sigla: <input type="text" name="sigla">
        <br><br>
        Carga Horaria: <input type="text" name="carga">
        <br><br>
        <input type="submit" value="Criar Nova Disciplina">
    </form>
<?php } ?>
 
<?php if ($acao == "listar") { ?>
    <h2>Listar Disciplinas</h2>
    <table border="1">
        <tr><th>nome</th><th>sigla</th><th>carga</th></tr>
        <?php
           $arqDisc = fopen("disciplinas.txt","r") or die("erro ao abrir arquivo");
            $linha = fgets($arqDisc); 
           while(!feof($arqDisc)) {
                $linha = fgets($arqDisc);
                if ($linha === false) break;
                $colunaDados = explode(";", $linha);
                echo "<tr><td>" . $colunaDados[0] . "</td>" .
                    "<td>" . $colunaDados[1] . "</td>" .
                    "<td>" . $colunaDados[2] . "</td></tr>";
            }
           fclose($arqDisc);
        ?>
    </table>
<?php } ?>
 
<?php if ($acao == "pedeAlterar") { ?>
    <h2>Alterar Disciplina</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <input type="hidden" name="acao" value="pedeAlterar">
        Sigla da disciplina: <input type="text" name="sigla">
        <br><br>
        <input type="submit" value="Buscar">
    </form>
 
    <?php if (isset($_GET["sigla"])) { ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=alterar" method="POST">
        Nome: <input type="text" name="nome"
                    value='<?php echo $nome ?>'>
        <br><br>
        Sigla: <input type="text" name="sigla"
                    value='<?php echo $sigla ?>' readonly>
        <br><br>
        Carga Horaria: <input type="text" name="carga"
                value='<?php echo $carga ?>'>
        <br><br>
        <input type="submit" value="Alterar Disciplina">
    </form>
    <?php } ?>
<?php } ?>
 
<?php if ($acao == "pedeExcluir") { ?>
    <h2>Excluir Disciplina</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <input type="hidden" name="acao" value="pedeExcluir">
        Sigla da disciplina: <input type="text" name="sigla">
        <br><br>
        <input type="submit" value="Buscar">
    </form>
 
    <?php if (isset($_GET["sigla"]) && $nome != "") { ?>
    <p>Confirma a exclusao da disciplina abaixo?</p>
    <p>
        Nome: <?php echo $nome ?><br>
        Sigla: <?php echo $sigla ?><br>
        Carga Horaria: <?php echo $carga ?>
    </p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?acao=excluir" method="POST">
        <input type="hidden" name="sigla" value='<?php echo $sigla ?>'>
        <input type="submit" value="Excluir Disciplina">
    </form>
    <?php } ?>
<?php } ?>
 
<p><?php echo $msg ?></p>
<br>
</body>
</html>
 

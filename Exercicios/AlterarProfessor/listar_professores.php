<!DOCTYPE html>
<html>
<head>
<title>Listar Professores</title>
</head>
<body>
<h2>Listar Professores</h2>

<a href="incluir_professor.php">Incluir Novo Professor</a>
<br><br>

<table border="1">
    <tr><th>matricula</th><th>nome</th><th>cpf</th><th>endereco</th><th>acoes</th></tr>
    <?php
        $arqProfessores = fopen("professores.txt","r") or die("erro ao abrir arquivo");
        $linha = fgets($arqProfessores); 
        while(!feof($arqProfessores)) {
            $linha = fgets($arqProfessores);
            if ($linha === false) break;
            $colunaDados = explode(";", $linha);
            echo "<tr><td>" . $colunaDados[0] . "</td>" .
                 "<td>" . $colunaDados[1] . "</td>" .
                 "<td>" . $colunaDados[2] . "</td>" .
                 "<td>" . $colunaDados[3] . "</td>" .
                 "<td><form action='mostrar_alterar_professor.php' method='GET' style='display:inline;'><input type='hidden' name='matricula' value='" . trim($colunaDados[0]) . "'><input type='submit' value='Alterar'></form> " .
                 "<form action='mostrar_excluir_professor.php' method='GET' style='display:inline;'><input type='hidden' name='matricula' value='" . trim($colunaDados[0]) . "'><input type='submit' value='Excluir'></form></td></tr>";
        }
        fclose($arqProfessores);
    ?>
</table>
</body>
</html>
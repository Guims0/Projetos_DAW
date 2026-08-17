<?php
$v1 = $_GET["a"];
$v2 = $_GET["b"];
$op = $_GET["op"];
$result = "";

switch ($op) {
    case "+":
        $result = $v1 + $v2;
        break;
    case "-":
        $result = $v1 - $v2;
        break;
    case "x":
        $result = $v1 * $v2;
        break;
    case "/":
        if ($v2 != 0) {
            $result = $v1 / $v2;
        } else {
            $result = "Erro: Divisao por zero nao e permitida.";
        }
        break;
    default:
        $result = "Erro: Operador invalido. Use +, -, * (multiplicacao) ou / (divisao).";
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Calculadora</title>
</head>
<body>
    <?php echo "<h1>Resultado: $result</h1>"; ?>
</body>
</html>
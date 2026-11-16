<?php
header("Content-Type: application/json; charset=UTF-8");

$v1 = $_GET["a"];
$v2 = $_GET["b"];
$op = $_GET["op"];
$result = "";

if (!is_numeric($v1) || !is_numeric($v2)) {
    $result = "Erro: Digite apenas numeros em a e b.";
} else {
    switch ($op) {
        case "+":
            $result = $v1 + $v2;
            break;
        case "-":
            $result = $v1 - $v2;
            break;
        case "*":
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
            $result = "Erro: Operador invalido. Use +, -, * ou / .";
            break;
    }
}

echo json_encode(["resultado" => $result]);
?>
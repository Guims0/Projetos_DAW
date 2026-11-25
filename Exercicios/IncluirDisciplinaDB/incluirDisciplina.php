<?php
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "faeterj3dawmanha";
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($servidor,$username,$senha,$database);
if ($conn->connect_error) {
    die("Conexao falhou, avise o administrador do sistema");
}
$resultado = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sigla = $_GET["sigla"];
    $nome = $_GET["nome"];
    $cargaHoraria = $_GET["cargaHoraria"];
    $comandoSQL = "INSERT INTO `disciplinas`(`sigla`, `nome`, `carga_horaria`) VALUES ('$sigla','$nome','$cargaHoraria')";
    $resultado = $conn->query($comandoSQL);
}
$retorno = json_encode($resultado);
echo $retorno;
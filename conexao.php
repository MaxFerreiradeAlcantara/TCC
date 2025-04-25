<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "tcc";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die ("conexão falhou: " .
    $conn->connect_error);
}
?>
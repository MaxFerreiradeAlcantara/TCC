<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    $sql = "SELECT senha FROM usuarios WHERE nome = ? AND matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nome, $matricula);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($senha_hash);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {

            header("Location: telainicial.html");
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

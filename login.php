<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    $query = "SELECT id, senha FROM usuarios WHERE nome = ? AND matricula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nome, $matricula);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id_usuario, $senha_hash);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['usuario_id'] = $id_usuario;
            $_SESSION['nome'] = $nome;
            header("Location: dashboard.php");
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

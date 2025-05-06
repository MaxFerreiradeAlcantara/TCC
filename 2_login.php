<?php
include '0_conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT senha, nome_completo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            // Sucesso no login
            echo json_encode(["status" => "success", "message" => "Bem-vindo(a), " . $usuario['nome_completo'] . "!"]);
        } else {
            // Senha incorreta
            echo json_encode(["status" => "error", "message" => "Senha incorreta."]);
        }
    } else {
        // Email não encontrado
        echo json_encode(["status" => "error", "message" => "E-mail não cadastrado."]);
    }

    $stmt->close();
    $conn->close();
}
?>

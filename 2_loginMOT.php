<?php
include '0_conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Debug: Verificar se o valor do usuário está correto
    echo "Usuário: " . $usuario . "<br>";

    $stmt = $conn->prepare("SELECT * FROM motoristas WHERE usuario_motorista = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $motorista = $resultado->fetch_assoc();
        
        // Debug: Verificar a senha recebida e a senha armazenada
        echo "Senha digitada: " . $senha . "<br>";
        echo "Senha armazenada: " . $motorista['senha'] . "<br>";

        if (password_verify($senha, $motorista['senha'])) {
            session_start();
            $_SESSION['motorista_id'] = $motorista['id_motorista'];
            $_SESSION['nome_motorista'] = $motorista['nome_completo'];

            header("Location: 3_telainicialMOT.html");
            exit;
        } else {
            echo "<script>alert('Senha incorreta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Motorista não encontrado.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

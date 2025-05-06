<?php
include '0_conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM administradores WHERE usuario_adm = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $admin = $resultado->fetch_assoc();

        // Verifica a senha digitada com o hash salvo
        if (password_verify($senha, $admin['senha'])) {
            session_start();
            $_SESSION['adm_id'] = $admin['id_administrador'];
            $_SESSION['nome_adm'] = $admin['nome_completo'];
            $_SESSION['nivel_acesso'] = $admin['nivel_acesso'];

            header("Location: 3_telainicialADM.html");
            exit;
        } else {
            echo "<script>alert('Senha incorreta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Administrador não encontrado.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

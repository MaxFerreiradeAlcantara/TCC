<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Certifique-se que o autoload do Composer está no seu projeto

include '0_conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $celular = $_POST['celular'];
    $matricula = $_POST['matricula'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $prefixo = "202571421";

    $resultado = $conn->query("SELECT MAX(codigo_usuario) as max_codigo FROM usuarios WHERE codigo_usuario LIKE '{$prefixo}%'");

    if ($resultado && $row = $resultado->fetch_assoc()) {
        $ultimoCodigo = $row['max_codigo'];
        $sequencial = $ultimoCodigo ? (int)substr($ultimoCodigo, -4) + 1 : 1;
    } else {
        $sequencial = 1;
    }

    $codigo_usuario = $prefixo . str_pad($sequencial, 4, "0", STR_PAD_LEFT);

    $stmt = $conn->prepare("INSERT INTO usuarios (codigo_usuario, nome_completo, cpf, celular, matricula, email, senha) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $codigo_usuario, $nome, $cpf, $celular, $matricula, $email, $senha);

    try {
        $stmt->execute();

        // ENVIAR EMAIL
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Ex: smtp.gmail.com
            $mail->SMTPAuth = true;
            $mail->Username = 'maxcontourofc@gmail.com'; // Seu e-mail
            $mail->Password = 'ejcg jrls awdy qneq'; // Sua senha de app ou SMTP
            $mail->SMTPSecure = 'tls'; // ou 'ssl'
            $mail->Port = 587; // ou 465 para SSL

            // Remetente e destinatário
            $mail->setFrom('seuemail@seudominio.com', 'Max Tur');
            $mail->addAddress($email, $nome);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Cadastro realizado com sucesso';
            $mail->Body = "Olá <strong>$nome</strong>,<br><br>
                          Seu cadastro foi realizado com sucesso.<br>
                          Seu código de usuário é: <strong>$codigo_usuario</strong>.<br><br>
                          Atenciosamente,<br>Equipe do Max Tur.";

            $mail->send();

        } catch (Exception $e) {
            // Você pode registrar o erro ou apenas seguir
        }

        echo json_encode(["status" => "success", "message" => "Cadastro realizado com sucesso! Verifique seu e-mail."]);

    } catch (mysqli_sql_exception $e) {
        $erro = $e->getMessage();

        if (str_contains($erro, "cpf")) {
            $mensagem = "Este CPF já está cadastrado.";
        } elseif (str_contains($erro, "email")) {
            $mensagem = "Este e-mail já está cadastrado.";
        } elseif (str_contains($erro, "matricula")) {
            $mensagem = "Esta matrícula já está cadastrada.";
        } else {
            $mensagem = "Erro ao cadastrar: " . $erro;
        }

        echo json_encode(["status" => "error", "message" => $mensagem]);
    }

    $stmt->close();
    $conn->close();
}
?>

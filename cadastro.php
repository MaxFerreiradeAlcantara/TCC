<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $curso = $_POST['curso'];
    $cidade = $_POST['cidade'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $cidade_query = "SELECT id FROM cidades WHERE nome = ?";
    $stmt = $conn->prepare($cidade_query);
    $stmt->bind_param("s", $cidade);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $stmt->close();
        $insert_cidade = "INSERT INTO cidades (nome) VALUES (?)";
        $stmt = $conn->prepare($insert_cidade);
        $stmt->bind_param("s", $cidade);
        $stmt->execute();
        $cidade_id = $stmt->insert_id; 
    } else {
    
        $stmt->bind_result($cidade_id);
        $stmt->fetch();
    }

    $insert_usuario = "INSERT INTO usuarios (nome, matricula, email, data_nascimento, curso_id, cidade_id, senha) 
                       VALUES (?, ?, ?, ?, (SELECT id FROM cursos WHERE nome = ?), ?, ?)";
    $stmt = $conn->prepare($insert_usuario);
    $stmt->bind_param("sssssss", $nome, $matricula, $email, $data_nascimento, $curso, $cidade_id, $senha);
    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }

    $stmt->close();
}
?>

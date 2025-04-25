<?php
include 'conexao.php';

$sql = "SELECT usuarios.*, cidades.nome AS cidade_nome 
        FROM usuarios 
        JOIN cidades ON usuarios.cidade_id = cidades.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Email</th>
                <th>Data de Nascimento</th>
                <th>Curso</th>
                <th>Cidade</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["nome"] . "</td>
                <td>" . $row["matricula"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["data_nascimento"] . "</td>
                <td>" . $row["curso"] . "</td>
                <td>" . $row["cidade_nome"] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "Nenhum resultado encontrado.";
}

$conn->close();
?>

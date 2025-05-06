-- Tabela: usuarios
CREATE TABLE usuarios (
    id_usuarios INT(20) AUTO_INCREMENT PRIMARY KEY,
    codigo_usuario VARCHAR(20) NOT NULL UNIQUE,
    nome_completo VARCHAR(100) NOT NULL,
    cpf VARCHAR(15) NOT NULL UNIQUE,
    celular VARCHAR(16) NOT NULL,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: enderecos
CREATE TABLE enderecos (
    id_enderecos INT(20) AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT(20) NOT NULL,
    rua VARCHAR(100) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: perfil
CREATE TABLE perfil (
    id_perfil INT(20) AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT(20) NOT NULL,
    foto_url TEXT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: mensalidades
CREATE TABLE mensalidades (
    id_mensalidades INT(20) AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT(20) NOT NULL,
    mes_referente DATE NOT NULL,
    pago BOOLEAN NOT NULL DEFAULT FALSE,
    data_pagamento DATETIME NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 
CREATE TABLE wp_eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    descricao TEXT,
    tipo ENUM('longa', 'curta'),
    data_inicio DATE,
    data_fim DATE,
    tema_id INT,
    subtema_id INT,
    FOREIGN KEY (tema_id) REFERENCES wp_temas(id),
    FOREIGN KEY (subtema_id) REFERENCES wp_subtemas(id)
);

CREATE TABLE wp_temas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255)
);
CREATE TABLE wp_subtemas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS wp_responsaveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    area_atuacao VARCHAR(50) NOT NULL,
    evento_id INT,
    FOREIGN KEY (evento_id) REFERENCES wp_eventos(id)
);

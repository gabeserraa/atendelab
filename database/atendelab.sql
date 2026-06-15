CREATE TABLE pessoas (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nome VARCHAR(100),
    documento VARCHAR(20)UNIQUE,
    telefone VARCHAR(20),
    curso VARCHAR(100),
    periodo VARCHAR(100),
    status VARCHAR(100)
);

CREATE TABLE tipos_atendimentos (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

CREATE TABLE atendimentos (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    pessoa_id INT,
    tipo_atendimento_id INT,
    usuario_id INT,
    data_atendimento DATE,
    hora_atendimento TIME,
    descricao TEXT,
    observacao TEXT,
    status ENUM('aberto', 'em_andamento', 'finalizado', 'cancelado') DEFAULT 'aberto',
    criado_em TIMESTAMP,

    CONSTRAINT fk_atendimento_pessoa
        FOREIGN KEY (pessoa_id)
        REFERENCES pessoas(id),

    CONSTRAINT fk_atendimento_tipo
        FOREIGN KEY (tipo_atendimento_id)
        REFERENCES tipos_atendimentos(id),

    CONSTRAINT fk_atendimento_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
);
-- Jiu-Jitsu
INSERT INTO modalidade_molde (id_modalidade_molde, mod_nome, mod_desc)
VALUES (
    1,
    'Jiu-Jitsu',
    'É uma arte marcial e esporte de combate focado em luta no chão'
);

INSERT INTO graduacao_molde
(gradu_nome_cor, gradu_grau, gradu_meta, gradu_ordem, id_modalidade_molde)
VALUES
('Cinza e Branca', 0, 30, 1, 1),
('Cinza', 0, 30, 2, 1),
('Cinza e Preta', 0, 30, 3, 1),
('Amarela e Branca', 0, 30, 4, 1),
('Amarela', 0, 30, 5, 1),
('Amarela e Preta', 0, 30, 6, 1),
('Laranja e Branca', 0, 30, 7, 1),
('Laranja', 0, 30, 8, 1),
('Laranja e Preta', 0, 30, 9, 1),
('Verde e Branca', 0, 30, 10, 1),
('Verde', 0, 30, 11, 1),
('Verde e Preta', 0, 30, 12, 1),
('Branca', 0, 30, 13, 1),
('Azul', 0, 30, 14, 1),
('Roxa', 0, 30, 15, 1),
('Marrom', 0, 30, 16, 1),
('Preta', 0, 30, 17, 1);

-- Karatê
INSERT INTO modalidade_molde (id_modalidade_molde, mod_nome, mod_desc)
VALUES (
    2,
    'Karatê',
    'É uma arte marcial japonesa e método de defesa pessoal cujo nome significa "caminho das mãos vazias'
);

INSERT INTO graduacao_molde
(gradu_nome_cor, gradu_grau, gradu_meta, gradu_ordem, id_modalidade_molde)
VALUES
('Branca', 1, 30, 1, 2),
('Amarela', 1, 30, 2, 2),
('Vermelha', 1, 30, 3, 2),
('Laranja', 1, 30, 4, 2),
('Verde', 1, 30, 5, 2),
('Roxa', 1, 30, 6, 2),
('Marrom', 1, 30, 7, 2),
('Preta', 1, 30, 8, 2);
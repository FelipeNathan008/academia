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
CREATE DATABASE IF NOT EXISTS reurb;
USE reurb;

-- 1. TABELA: Selagem de Lotes (Dados do Terreno)
CREATE TABLE IF NOT EXISTS selagem_lotes (
    id_submissao BIGINT PRIMARY KEY,
    uuid VARCHAR(50) NOT NULL,
    rua_zona_setor VARCHAR(100),
    numero_lote VARCHAR(20),
    endereco_oficial_completo TEXT,
    tipo_ocupacao_lote VARCHAR(50),
    qtd_domicilios_total INT DEFAULT 1,
    nome_selador VARCHAR(100),
    data_formulario DATE,
    data_hora_submissao DATETIME,
    versao VARCHAR(50),
    INDEX idx_uuid (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABELA: Domicílios (Viculada ao Lote e Identificada pelo Selo)
CREATE TABLE IF NOT EXISTS domicilios (
    numero_selo VARCHAR(30) PRIMARY KEY, -- Ex: ALF-E-0049-0004
    id_submissao_pai BIGINT NOT NULL,
    index_kobo INT,
    nome_entrevistado VARCHAR(150),
    nome_principal_morador VARCHAR(150),
    telefone VARCHAR(20),
    cpf VARCHAR(14),
    casado_uniao_estavel VARCHAR(10),
    uso_predominante VARCHAR(100),
    tipo_ocupacao_imovel VARCHAR(100),
    numero_pavimentos INT,
    localizacao_domicilio VARCHAR(50),
    acesso_independente VARCHAR(10),
    area_lote_m2 DECIMAL(10,2),
    comprovante_endereco VARCHAR(10),
    foto_comprovante_endereco VARCHAR(255), -- novo campo [12]
    foto_fachada VARCHAR(255), -- campo alterardo [25]
    foto_selo VARCHAR(255),  -- campo alterardo [27]
    foto_ocupacao VARCHAR(255), -- novo campo [29]
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    altitude DECIMAL(8, 2),
    precisao DECIMAL(8, 2), -- novo campo [24]
    FOREIGN KEY (id_submissao_pai) REFERENCES selagem_lotes(id_submissao) ON DELETE CASCADE,
    INDEX idx_cpf (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABELA: Caracterização Física e Vulnerabilidade (1:1 com Domicilio)
CREATE TABLE IF NOT EXISTS caracterizacao_vulnerabilidade (
    id_submissao BIGINT PRIMARY KEY,
    uuid VARCHAR(50) NOT NULL,
    codigo_selo VARCHAR(30) NOT NULL,
    foto_selo VARCHAR(255), -- novo campo [4]
    quantidade_comodos INT,
    comodos_improvisados_dormitorio VARCHAR(10),
    mais_de_3_por_dormitorio VARCHAR(10),
    faltam_camas VARCHAR(10),
    possui_banheiro VARCHAR(10),
    numero_banheiros INT,
    revestimento_ceramico_banheiro VARCHAR(10),
    louca_sanitaria_banheiro VARCHAR(10),
    problemas_estruturais_observados VARCHAR(255), -- novo campo [14]
    problemas_infiltracao VARCHAR(255), -- novo campo [19]
    existe_comodo_com_mofo VARCHAR(10), -- novo campo [24]
    comodos_com_mofo VARCHAR(255), -- novo campo [25]
    -- Infraestrutura e Materiais (Campos consolidados/booleanos do Kobo)
    paredes_materiais VARCHAR(100),
    paredes_condicao VARCHAR(50),
    cobertura_materiais VARCHAR(100),
    cobertura_condicao VARCHAR(50),
    piso_materiais VARCHAR(100),
    piso_condicao VARCHAR(50),
    -- Instalações e Serviços
    energia_acesso VARCHAR(100),
    energia_condicao_instalacao VARCHAR(50),
    energia_condicao_instalacao_internas VARCHAR(255), -- novo campo [50]
    agua_acesso VARCHAR(100),
    materiais_componencatorios_observados VARCHAR(255), -- novo campo [65]
    instalacoes_hidrosanitarias_disponives VARCHAR(255), -- novo campo [71]
    condicoes_hidrosanitarias VARCHAR(255), -- novo campo [77]
    esgotamento_sanitario VARCHAR(100),
    coleta_lixo VARCHAR(100),
    frequencia_coleta_lixo VARCHAR(100), -- novo campo [80]
    existe_drenagem VARCHAR(10), -- novo campo [81]
    pavimentacao_rua VARCHAR(10),
    -- Vulnerabilidade de Saúde e Mobilidade
    pcd_no_domicilio VARCHAR(50),
    quantidade_pcd INT DEFAULT 0,
    pessoas_mobilidade_reduzida VARCHAR(50),
    pessoas_cadeirantes VARCHAR(50),
    pessoas_doencas_respiratorias VARCHAR(50),
    quantidade_afetados_respiratorio INT DEFAULT 0,
    hove_acidentes_domesticos VARCHAR(255),
    ocorrencia_inundacao VARCHAR(50),
    frequencia_inundacao VARCHAR(50),-- novo campo [117]
    altura_agua_inundacao VARCHAR(50),-- novo campo [118]
    quando_chove VARCHAR(50),-- novo campo [119]
    sensacao_seguranca_rua INT,
    possui_iluminacao_publica VARCHAR(10),-- novo campo [124]
    possui_equipamentos_lazer VARCHAR(10),-- novo campo [126]
    possui_equipamentos_saude VARCHAR(10),-- novo campo [127]
    possui_transporte_publico VARCHAR(10), -- novo campo [128]
    principal_meio_locomocao VARCHAR(100), -- novo campo [129]
    ha_escada VARCHAR(200),-- novo campo [106]
    ha_comodos_sem_janela VARCHAR(10),-- novo campo [107]
    ha_calcada_na_rua VARCHAR(10),-- novo campo [109]
    imovel_terreno_tem_rachaduras VARCHAR(10),-- novo campo [110]
    paredes_embarrigadas VARCHAR(10),-- novo campo [111]
    ha_postes_arvores_inclinados VARCHAR(10), -- novo campo [112]
    houve_deslizamentos VARCHAR(10),-- novo campo [113]
    onde_deslizamento VARCHAR(255),-- novo campo [114]
    quando_deslizamento VARCHAR(50),-- novo campo [115]
    responsavel_coleta VARCHAR(255), -- novo campo [130]
    data_registro DATE,
    FOREIGN KEY (codigo_selo) REFERENCES domicilios(numero_selo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABELA: Cadastro Sociojurídico (Titulares e Renda - 1:1 com Domicilio)
CREATE TABLE IF NOT EXISTS cadastro_sociojuridico (
    id_submissao BIGINT PRIMARY KEY,
    uuid VARCHAR(50) NOT NULL,
    codigo_selo VARCHAR(30) NOT NULL,
    foto_selo VARCHAR(255), -- novo campo [4]
    -- Responsável 1
    r1_nome VARCHAR(150) NOT NULL,
    r1_rg VARCHAR(30),
    r1_foto_rg VARCHAR(255), -- novo campo [9]
    r1_cpf VARCHAR(14),
    r1_foto_cpf VARCHAR(255), -- novo campo [12]
    r1_naturalidade VARCHAR(100),
    r1_data_nascimento DATE,
    r1_estado_civil VARCHAR(50),
    r1_profissao VARCHAR(100),
    r1_escolaridade VARCHAR(100),
    r1_pcd VARCHAR(10),
    r1_especifiacao_pcd VARCHAR(255), -- novo campo [23]
    r1_telefone VARCHAR(20),
    -- Composição Familiar e Renda
    numero_residentes INT DEFAULT 1,
    renda_mensal_titular_1 DECIMAL(10,2) DEFAULT 0.00,
    renda_mensal_titular_2 DECIMAL(10,2) DEFAULT 0.00,
    renda_outras_fontes DECIMAL(10,2) DEFAULT 0.00,
    cadunico_nis VARCHAR(20),
    numero_nis  VARCHAR(20), -- novo campo [58]
    recebe_beneficio_social VARCHAR(10),
    beneficios_detalhe TEXT, -- Guarda as flags do tipo Bolsa Família, Prato Cheio, etc.
    -- Condições de Ocupação Legal
    reacao_com_imovel VARCHAR(50), -- ex: Próprio
    forma_aquisicao VARCHAR(50),  -- ex: Ocupação
    tempo_ocupacao VARCHAR(50),    -- ex: 5 anos ou mais
    foto_comprovante_ocupacao_2022 VARCHAR(255), -- novo campo [88]
    foto_comprovante_ocupacao_2023 VARCHAR(255), -- novo campo [86]
    foto_comprovante_ocupacao_2024 VARCHAR(255), -- novo campo [84]
    foto_comprovante_ocupacao_2025 VARCHAR(255), -- novo campo [82]
    foto_comprovante_ocupacao_2026 VARCHAR(255), -- novo campo [80]
    paga_iptu VARCHAR(10),
    -- Flags de Declarações REURB
    assinou_unica_propriedade VARCHAR(10),
    foto_declaracao_unica_propriedade VARCHAR(255), -- novo campo [92]
    assinou_ocupacao_mansa_pacifica VARCHAR(10),
    foto_declaracao_ocupacao_mansa_pacifica VARCHAR(255), -- novo campo [95]
    assinou_veracidade VARCHAR(10),
    foto_declaracao_veracidade VARCHAR(255), -- novo campo [98]
    assinou_lgpd VARCHAR(10),
    foto_declaracao_lgpd VARCHAR(255), -- novo campo [101]
    nome_cadastrador VARCHAR(100),
    data_registro DATE,
    FOREIGN KEY (codigo_selo) REFERENCES domicilios(numero_selo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
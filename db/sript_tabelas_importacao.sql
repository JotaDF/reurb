CREATE DATABASE IF NOT EXISTS reurb_dorothy_stang;
USE reurb_dorothy_stang;

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
    versao VARCHAR(20),
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
    foto_fachada_url TEXT,
    foto_selo_url TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    altitude DECIMAL(8, 2),
    FOREIGN KEY (id_submissao_pai) REFERENCES selagem_lotes(id_submissao) ON DELETE CASCADE,
    INDEX idx_cpf (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABELA: Caracterização Física e Vulnerabilidade (1:1 com Domicilio)
CREATE TABLE IF NOT EXISTS caracterizacao_vulnerabilidade (
    id_submissao BIGINT PRIMARY KEY,
    uuid VARCHAR(50) NOT NULL,
    codigo_selo VARCHAR(30) NOT NULL,
    quantidade_comodos INT,
    comodos_improvisados_dormitorio VARCHAR(10),
    mais_de_3_por_dormitorio VARCHAR(10),
    faltam_camas VARCHAR(10),
    possui_banheiro VARCHAR(10),
    numero_banheiros INT,
    revestimento_ceramico_banheiro VARCHAR(10),
    louca_sanitaria_banheiro VARCHAR(10),
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
    agua_acesso VARCHAR(100),
    esgotamento_sanitario VARCHAR(100),
    coleta_lixo VARCHAR(100),
    pavimentacao_rua VARCHAR(10),
    -- Vulnerabilidade de Saúde e Mobilidade
    pcd_no_domicilio VARCHAR(50),
    quantidade_pcd INT DEFAULT 0,
    pessoas_doencas_respiratorias VARCHAR(50),
    quantidade_afetados_respiratorio INT DEFAULT 0,
    ocorrencia_inundacao VARCHAR(50),
    sensacao_seguranca_rua INT,
    data_registro DATE,
    FOREIGN KEY (codigo_selo) REFERENCES domicilios(numero_selo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABELA: Cadastro Sociojurídico (Titulares e Renda - 1:1 com Domicilio)
CREATE TABLE IF NOT EXISTS cadastro_sociojuridico (
    id_submissao BIGINT PRIMARY KEY,
    uuid VARCHAR(50) NOT NULL,
    codigo_selo VARCHAR(30) NOT NULL,
    -- Responsável 1
    r1_nome VARCHAR(150) NOT NULL,
    r1_rg VARCHAR(30),
    r1_cpf VARCHAR(14),
    r1_naturalidade VARCHAR(100),
    r1_data_nascimento DATE,
    r1_estado_civil VARCHAR(50),
    r1_profissao VARCHAR(100),
    r1_escolaridade VARCHAR(100),
    r1_pcd VARCHAR(10),
    r1_telefone VARCHAR(20),
    -- Composição Familiar e Renda
    numero_residentes INT DEFAULT 1,
    renda_mensal_titular_1 DECIMAL(10,2) DEFAULT 0.00,
    renda_mensal_titular_2 DECIMAL(10,2) DEFAULT 0.00,
    renda_outras_fontes DECIMAL(10,2) DEFAULT 0.00,
    cadunico_nis VARCHAR(20),
    recebe_beneficio_social VARCHAR(10),
    beneficios_detalhe TEXT, -- Guarda as flags do tipo Bolsa Família, Prato Cheio, etc.
    -- Condições de Ocupação Legal
    reacao_com_imovel VARCHAR(50), -- ex: Próprio
    forma_aquisicao VARCHAR(50),  -- ex: Ocupação
    tempo_ocupacao VARCHAR(50),    -- ex: 5 anos ou mais
    paga_iptu VARCHAR(10),
    -- Flags de Declarações REURB
    assinou_unica_propriedade VARCHAR(10),
    assinou_ocupacao_mansa_pacifica VARCHAR(10),
    assinou_veracidade VARCHAR(10),
    assinou_lgpd VARCHAR(10),
    nome_cadastrador VARCHAR(100),
    data_registro DATE,
    FOREIGN KEY (codigo_selo) REFERENCES domicilios(numero_selo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
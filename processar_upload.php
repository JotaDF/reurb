<?php
/**
 * Script de Importação Manual via Upload de Arquivo Único
 * Ano: 2026
 */

// 1. Configurações de Conexão
$host     = 'mysql';
$dbname   = 'reurb';
$user     = 'reurb';
$password = 'reurb#2020';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    responderJSON(false, "Erro na conexão com o banco de dados: " . $e->getMessage());
}

// 2. Validação da Requisição HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderJSON(false, "Método de requisição inválido.");
}

$tipoImportacao = $_POST['tipo_importacao'] ?? '';
if (!isset($_FILES['arquivo_csv']) || $_FILES['arquivo_csv']['error'] !== UPLOAD_ERR_OK) {
    responderJSON(false, "Falha no envio do arquivo. Verifique o tamanho limite do PHP.");
}

$caminhoTemporario = $_FILES['arquivo_csv']['tmp_name'];

// 3. Roteamento baseado no Tipo de Importação selecionado pelo usuário
switch ($tipoImportacao) {
    case 'selagem':
        importarSelagem($caminhoTemporario, $pdo);
        break;
    case 'domicilios':
        importarDomicilios($caminhoTemporario, $pdo);
        break;
    case 'caracterizacao':
        importarCaracterizacao($caminhoTemporario, $pdo);
        break;
    case 'sociojuridico':
        importarSociojuridico($caminhoTemporario, $pdo);
        break;
    default:
        responderJSON(false, "Tipo de importação inválido ou não selecionado.");
}

// =========================================================================
// FUNÇÕES DE PROCESSAMENTO INDIVIDUAL (Executam isoladamente por arquivo)
// =========================================================================

function importarSelagem($arquivo, $pdo) {
    if (($handle = fopen($arquivo, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ","); // Pula cabeçalho
        
        $sql = "INSERT INTO selagem_lotes 
                (id_submissao, uuid, rua_zona_setor, numero_lote, endereco_oficial_completo, tipo_ocupacao_lote, qtd_domicilios_total, nome_selador, data_formulario, data_hora_submissao, versao)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE uuid=VALUES(uuid);";
        $stmt = $pdo->prepare($sql);
        
        $linhas = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $stmt->execute([
                $data[8], $data[9], $data[0], $data[1], $data[2], $data[3],
                empty($data[4]) ? 1 : $data[4], $data[6], $data[7],
                str_replace('T', ' ', $data[10]), $data[15]
            ]);
            $linhas++;
        }
        fclose($handle);
        responderJSON(true, "Sucesso! Foram importados/atualizados {$linhas} registros de Selagem.");
    }
    responderJSON(false, "Não foi possível abrir o arquivo enviado.");
}

function importarDomicilios($arquivo, $pdo) {
    if (($handle = fopen($arquivo, "r")) !== FALSE) {
        // Ignora as duas linhas de cabeçalho comuns nessa aba do Kobo
        fgetcsv($handle, 2000, ",");
        fgetcsv($handle, 2000, ",");

        $sql = "INSERT INTO domicilios 
                (numero_selo, id_submissao_pai, index_kobo, nome_entrevistado, nome_principal_morador, telefone, cpf, casado_uniao_estavel, uso_predominante, tipo_ocupacao_imovel, numero_pavimentos, localizacao_domicilio, acesso_independente, area_lote_m2, comprovante_endereco, foto_fachada_url, foto_selo_url, latitude, longitude, altitude)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE numero_selo=numero_selo;";
        $stmt = $pdo->prepare($sql);

        $linhas = 0;
        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
            if (empty($data[31])) continue; // Pula linhas vazias

            $stmt->execute([
                $data[31], $data[34], $data[32], $data[0], $data[1], $data[2],
                str_replace(['.', '-'], '', $data[3]), $data[4], $data[5], $data[6],
                $data[7], $data[8], $data[9], $data[10], $data[11], $data[26],
                $data[28], $data[21], $data[22], $data[23]
            ]);
            $linhas++;
        }
        fclose($handle);
        responderJSON(true, "Sucesso! Foram importados/atualizados {$linhas} Domicílios vinculados.");
    }
    responderJSON(false, "Não foi possível abrir o arquivo enviado.");
}

function importarCaracterizacao($arquivo, $pdo) {
    if (($handle = fopen($arquivo, "r")) !== FALSE) {
        fgetcsv($handle, 3000, ",");

        $sql = "INSERT INTO caracterizacao_vulnerabilidade 
                (id_submissao, uuid, codigo_selo, quantidade_comodos, comodos_improvisados_dormitorio, mais_de_3_por_dormitorio, faltam_camas, possui_banheiro, numero_banheiros, revestimento_ceramico_banheiro, louca_sanitaria_banheiro, paredes_materiais, paredes_condicao, cobertura_materiais, cobertura_condicao, piso_materiais, piso_condicao, energia_acesso, energia_condicao_instalacao, agua_acesso, esgotamento_sanitario, coleta_lixo, pavimentacao_rua, pcd_no_domicilio, quantidade_pcd, pessoas_doencas_respiratorias, quantidade_afetados_respiratorio, ocorrencia_inundacao, sensacao_seguranca_rua, data_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE id_submissao=id_submissao;";
        $stmt = $pdo->prepare($sql);

        $linhas = 0;
        while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) {
            $stmt->execute([
                $data[108], $data[109], $data[3], $data[6], $data[7], $data[8], $data[9],
                $data[10], $data[11], $data[12], $data[13], $data[26], $data[32], $data[33],
                $data[40], $data[41], $data[48], $data[49], $data[57], $data[58], $data[81],
                $data[82], $data[85], $data[86], $data[93], $data[100], $data[103], $data[111],
                $data[119], $data[107]
            ]);
            $linhas++;
        }
        fclose($handle);
        responderJSON(true, "Sucesso! Caracterização Complementar de {$linhas} imóveis adicionada.");
    }
    responderJSON(false, "Não foi possível abrir o arquivo enviado.");
}

function importarSociojuridico($arquivo, $pdo) {
    if (($handle = fopen($arquivo, "r")) !== FALSE) {
        fgetcsv($handle, 4000, ",");

        $sql = "INSERT INTO cadastro_sociojuridico 
                (id_submissao, uuid, codigo_selo, r1_nome, r1_rg, r1_cpf, r1_naturalidade, r1_data_nascimento, r1_estado_civil, r1_profissao, r1_escolaridade, r1_pcd, r1_telefone, numero_residentes, renda_mensal_titular_1, renda_mensal_titular_2, renda_outras_fontes, cadunico_nis, recebe_beneficio_social, beneficios_detalhe, reacao_com_imovel, forma_aquisicao, tempo_ocupacao, paga_iptu, assinou_unica_propriedade, assinou_ocupacao_mansa_pacifica, assinou_veracidade, assinou_lgpd, nome_cadastrador, data_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE id_submissao=id_submissao;";
        $stmt = $pdo->prepare($sql);

        $linhas = 0;
        while (($data = fgetcsv($handle, 4000, ",")) !== FALSE) {
            $beneficios = [];
            if ($data[59] == '1') $beneficios[] = "Bolsa Família";
            if ($data[60] == '1') $beneficios[] = "BPC";
            if ($data[65] == '1') $beneficios[] = "Prato Cheio";
            $beneficios_string = implode(', ', $beneficios);

            $stmt->execute([
                $data[114], $data[115], $data[3], $data[7], $data[8],
                str_replace(['.', '-'], '', $data[11]), $data[14], $data[15], $data[16],
                $data[19], $data[20], $data[21], $data[22], $data[53], (float)$data[54],
                (float)$data[55], (float)$data[56], $data[58], $data[57], $beneficios_string,
                $data[70], $data[71], $data[75], $data[86], $data[87], $data[90], $data[93],
                $data[96], $data[99], $data[100]
            ]);
            $linhas++;
        }
        fclose($handle);
        responderJSON(true, "Sucesso! Cadastro Sociojurídico de {$linhas} famílias importado.");
    }
    responderJSON(false, "Não foi possível abrir o arquivo enviado.");
}

// Helper para padronizar o retorno da API para a interface do usuário
function responderJSON($sucesso, $mensagem) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
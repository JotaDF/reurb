<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
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
        fgetcsv($handle, 1000, ","); // Pula o cabeçalho
        
        $sql = "INSERT INTO selagem_lotes 
                (id_submissao, uuid, rua_zona_setor, numero_lote, endereco_oficial_completo, tipo_ocupacao_lote, qtd_domicilios_total, nome_selador, data_formulario, data_hora_submissao, versao)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE uuid=VALUES(uuid);";
        $stmt = $pdo->prepare($sql);
        
        $linhas = 0;
        //var_dump($handle);
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            //print_r($data); // Debug: Exibe o conteúdo da linha lida
             /* for ($i = 0; $i < count($data); $i++) {
                echo "[$i] " . $data[$i] . "<br/>";
            }*/
            // CORREÇÃO: Ignora a linha se o ID estiver vazio, nulo ou não for numérico
            if (!isset($data[8]) || trim($data[8]) === '' || !is_numeric($data[8])) {
                continue; // Pula para a próxima linha sem quebrar o script
            }

            // Formatação segura da data/hora de submissão
            $dataHora = isset($data[10]) ? str_replace('T', ' ', $data[10]) : null;
            $stmt->execute([
                $data[8],                          // id_submissao (_id)
                $data[9]  ?? null,                 // uuid (_uuid)
                $data[0]  ?? null,                 // rua_zona_setor
                $data[1]  ?? null,                 // numero_lote
                $data[2]  ?? null,                 // endereco_oficial_completo
                $data[3]  ?? null,                 // tipo_ocupacao_lote
                (empty($data[4]) ? 1 : $data[4]),  // qtd_domicilios_total
                $data[6]  ?? null,                 // nome_selador
                $data[7]  ?? null,                 // data_formulario
                $dataHora,                         // data_hora_submissao
                $data[15] ?? null                  // versao (__version__)
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
        while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) {
           /* for ($i = 0; $i < count($data); $i++) {
                echo "[$i] " . $data[$i] . "<br/>";
            }*/
            if (empty($data[32])) continue; // Pula linhas vazias

            $stmt->execute([
                $data[31], $data[35], $data[32], $data[0], $data[1], $data[2],
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
        // Pula a linha de cabeçalho tratando o escape para evitar avisos do PHP
        fgetcsv($handle, 4000, ",", '"', "\\");
       
        $sql = "INSERT INTO caracterizacao_vulnerabilidade 
                (id_submissao, uuid, codigo_selo, quantidade_comodos, comodos_improvisados_dormitorio, mais_de_3_por_dormitorio, faltam_camas, possui_banheiro, numero_banheiros, revestimento_ceramico_banheiro, louca_sanitaria_banheiro, paredes_materiais, paredes_condicao, cobertura_materiais, cobertura_condicao, piso_materiais, piso_condicao, energia_acesso, energia_condicao_instalacao, agua_acesso, esgotamento_sanitario, coleta_lixo, pavimentacao_rua, pcd_no_domicilio, quantidade_pcd, pessoas_doencas_respiratorias, quantidade_afetados_respiratorio, ocorrencia_inundacao, sensacao_seguranca_rua, data_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE id_submissao=id_submissao;";
        $stmt = $pdo->prepare($sql);

        $linhas = 0;
        while (($data = fgetcsv($handle, 4000, ";", '"', "\\")) !== FALSE) {
            if ($linhas==0) {
                $linhas++;
                continue;
            }  // Pula a primeira linha de dados (que é o cabeçalho) para evitar erros de importação
            /*for ($i = 0; $i < count($data); $i++) {
                echo "[$i] " . $data[$i] . "<br/>";
            }*/
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
        
        // 1. Limpa o BOM (Byte Order Mark) se existir
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // 2. Pula a linha do cabeçalho original com escape adequado
        fgetcsv($handle, 4000, ",", '"', "\\");

        $sql = "INSERT INTO cadastro_sociojuridico 
                (id_submissao, uuid, codigo_selo, r1_nome, r1_rg, r1_cpf, r1_naturalidade, r1_data_nascimento, r1_estado_civil, r1_profissao, r1_escolaridade, r1_pcd, r1_telefone, numero_residentes, renda_mensal_titular_1, renda_mensal_titular_2, renda_outras_fontes, cadunico_nis, recebe_beneficio_social, beneficios_detalhe, reacao_com_imovel, forma_aquisicao, tempo_ocupacao, paga_iptu, assinou_unica_propriedade, assinou_ocupacao_mansa_pacifica, assinou_veracidade, assinou_lgpd, nome_cadastrador, data_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE id_submissao=id_submissao;";
        $stmt = $pdo->prepare($sql);

        $linhas = 0;
        while (($data = fgetcsv($handle, 4000, ";", '"', "\\")) !== FALSE) {
            if ($linhas==0) {
                $linhas++;
                continue;
            }  // Pula a primeira linha de dados (que é o cabeçalho) para evitar erros de importação
            /*for ($i = 0; $i < count($data); $i++) {
                echo "[$i] " . $data[$i] . "<br/>";
            }*/
            // 3. Validação de segurança: se o ID no índice [106] não for numérico, pula (filtra cabeçalhos/linhas vazias)
            if (!isset($data[106]) || trim($data[106]) === '' || !is_numeric(trim($data[106]))) {
                continue; 
            }

            // Tratamento de conversão de decimais (muda "2,5" para "2.5")
            $renda1 = isset($data[54]) ? (float)str_replace(',', '.', $data[54]) : 0.00;
            $renda2 = isset($data[55]) ? (float)str_replace(',', '.', $data[55]) : 0.00;
            $rendaO = isset($data[56]) ? (float)str_replace(',', '.', $data[56]) : 0.00;

            // Tratamento simplificado dos múltiplos cartões de benefícios baseados nas colunas preenchidas
            $beneficios = [];
            if (($data[59] ?? '') === 'Sim') $beneficios[] = "Bolsa Família";
            if (($data[91] ?? '') === 'Sim') $beneficios[] = "Prato Cheio"; // Mapeado conforme padrão do log
            $beneficios_string = implode(', ', $beneficios);

            $stmt->execute([
                trim($data[106]),     // id_submissao (_id)
                $data[107] ?? null,   // uuid (_uuid)
                $data[3]   ?? null,   // codigo_selo (TP1-D-0014-0007)
                
                // Dados do Responsável 1
                $data[7]   ?? null,   // r1_nome (Kenya Santos de Abreu)
                $data[8]   ?? null,   // r1_rg (268737)
                isset($data[11]) ? str_replace(['.', '-'], '', $data[11]) : null, // r1_cpf limpo
                $data[14]  ?? null,   // r1_naturalidade (Brasília, DF)
                $data[15]  ?? null,   // r1_data_nascimento (1988-06-07)
                $data[16]  ?? null,   // r1_estado_civil (Solteira(o))
                $data[20]  ?? null,   // r1_profissao (Auxiliar Administrativo)
                $data[21]  ?? null,   // r1_escolaridade (Ensino Médio Completo)
                $data[22]  ?? null,   // r1_pcd (Não)
                $data[29]  ?? null,   // r1_telefone (61 992293433)
                
                // Composição e Renda
                is_numeric($data[53]) ? (int)$data[53] : 1, // numero_residentes (2)
                $renda1,
                $renda2,
                $rendaO,
                $data[57]  ?? null,   // cadunico_nis (se houver)
                $data[59]  ?? null,   // recebe_beneficio_social (Não)
                $beneficios_string,
                
                // Situação Legal do Imóvel
                $data[72]  ?? null,   // reacao_com_imovel (Próprio)
                $data[73]  ?? null,   // forma_aquisicao (Ocupação)
                $data[79]  ?? null,   // tempo_ocupacao (5 anos ou mais)
                $data[90]  ?? null,   // paga_iptu (Não)
                
                // Assinaturas de Termos Declaratórios
                $data[91]  ?? null,   // assinou_unica_propriedade
                $data[94]  ?? null,   // assinou_ocupacao_mansa_pacifica
                $data[97]  ?? null,   // assinou_veracidade
                $data[100] ?? null,   // assinou_lgpd
                
                // Identificação do Registrador
                $data[103] ?? null,   // nome_cadastrador (Julia Rafaela De Sousa Freitas)
                $data[104] ?? null    // data_registro (2026-04-25)
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
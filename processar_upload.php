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
    exit;
}

// 3. Processamento dos Arquivos Enviados (Validações de Segurança e Existência)
// Validações de segurança: Verifica o arquivo de selagem, garante que foi enviado e não ocorreu erro no upload
if (!isset($_FILES['arquivo_csv_selagem']) || $_FILES['arquivo_csv_selagem']['error'] !== UPLOAD_ERR_OK) {
    responderJSON(false, "Falha no envio do arquivo. Verifique o tamanho limite do PHP.");
    exit;
} else {
    $caminhoTemporario_selagem = $_FILES['arquivo_csv_selagem']['tmp_name'];
    //echo "<br/>".$caminhoTemporario_selagem."<br/>"; // Separador visual entre as importações
    importarSelagem($caminhoTemporario_selagem, $pdo);
    echo "<br/><hr><br/>"; // Separador visual entre as importações
}

// Validações de segurança: Verifica o arquivo de domicílios, garante que foi enviado e não ocorreu erro no upload
if (!isset($_FILES['arquivo_csv_domicilios']) || $_FILES['arquivo_csv_domicilios']['error'] !== UPLOAD_ERR_OK) {
    responderJSON(false, "Falha no envio do arquivo. Verifique o tamanho limite do PHP.");
    exit;
} else {
    $caminhoTemporario_domicilios = $_FILES['arquivo_csv_domicilios']['tmp_name'];
    //echo "<br/>".$caminhoTemporario_domicilios."<br/>"; // Separador visual entre as importações
    importarDomicilios($caminhoTemporario_domicilios, $pdo);
    echo "<br/><hr><br/>"; // Separador visual entre as importações
}

// Validações de segurança: Verifica o arquivo de sócio jurídico, garante que foi enviado e não ocorreu erro no upload
if (!isset($_FILES['arquivo_csv_socio_juridico']) || $_FILES['arquivo_csv_socio_juridico']['error'] !== UPLOAD_ERR_OK) {
    responderJSON(false, "Falha no envio do arquivo. Verifique o tamanho limite do PHP.");
} else {
    $caminhoTemporario_socio_juridico = $_FILES['arquivo_csv_socio_juridico']['tmp_name'];
    //echo "<br/>".$caminhoTemporario_socio_juridico."<br/>"; // Separador visual entre as importações
    importarSociojuridico($caminhoTemporario_socio_juridico, $pdo);
    echo "<br/><hr><br/>"; // Separador visual entre as importações
}

// Validações de segurança: Verifica o arquivo de caracterização, garante que foi enviado e não ocorreu erro no upload
if (!isset($_FILES['arquivo_csv_caracterizacao']) || $_FILES['arquivo_csv_caracterizacao']['error'] !== UPLOAD_ERR_OK) {
    responderJSON(false, "Falha no envio do arquivo. Verifique o tamanho limite do PHP.");
} else {
    $caminhoTemporario_caracterizacao = $_FILES['arquivo_csv_caracterizacao']['tmp_name'];
    //echo "<br/>".$caminhoTemporario_caracterizacao."<br/>"; // Separador visual entre as importações
    importarCaracterizacao($caminhoTemporario_caracterizacao, $pdo);
    echo "<br/><hr><br/>"; // Separador visual entre as importações
}

// =========================================================================
// FUNÇÕES DE PROCESSAMENTO INDIVIDUAL (Executam isoladamente por arquivo)
// =========================================================================

function importarSelagem($arquivo, $pdo) {
    if (($handle_selagem = fopen($arquivo, "r")) !== FALSE) {
        
        // 1. Limpa o BOM (Byte Order Mark) se existir no início do arquivo
        $bom = fread($handle_selagem, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle_selagem);
        }

        // 2. Pula a linha do cabeçalho tratando o escape para evitar avisos do PHP
        fgetcsv($handle_selagem, 4000, ";", '"', "\\");
        
        // Utilizando parâmetros nomeados (:id_submissao) para máxima legibilidade
        $sql = "INSERT INTO selagem_lotes 
                (id_submissao, uuid, rua_zona_setor, numero_lote, endereco_oficial_completo, 
                 tipo_ocupacao_lote, qtd_domicilios_total, nome_selador, data_formulario, 
                 data_hora_submissao, versao)
                VALUES 
                (:id_submissao, :uuid, :rua_zona_setor, :numero_lote, :endereco_oficial_completo, 
                 :tipo_ocupacao_lote, :qtd_domicilios_total, :nome_selador, :data_formulario, 
                 :data_hora_submissao, :versao)
                ON DUPLICATE KEY UPDATE uuid=VALUES(uuid);";
                
        $stmt = $pdo->prepare($sql);
        $linhas = 0;
        while (($data = fgetcsv($handle_selagem, 4000, ";", '"', "\\")) !== FALSE) {
            // for ($i = 0; $i < count($data); $i++) {
            //     echo "[$i] " . $data[$i] . "<br/>";
            // }
            // 3. Validação de Segurança: Ignora se o ID estiver vazio, nulo ou não for numérico
            if (!isset($data[8]) || trim($data[8]) === '' || !is_numeric(trim($data[8]))) {
                continue; // Pula cabeçalhos residuais ou linhas em branco no final
            }

            // =========================================================================
            // DOCUMENTAÇÃO E DE-PARA DOS CAMPOS (Índices validados conforme o LOG real)
            // =========================================================================
            $id_submissao              = trim($data[8]);  // _id (Chave Primária do Lote)
            $uuid                      = $data[9]  ?? null; // _uuid
            $rua_zona_setor            = $data[0]  ?? null; // Rua (zona/setor) do imóvel
            $numero_lote               = $data[1]  ?? null; // Número do Lote
            $endereco_oficial_completo = $data[2]  ?? null; // Endereço Oficial Completo
            $tipo_ocupacao_lote        = $data[3]  ?? null; // Tipo de Ocupação do Lote
            
            // Tratamento para quantidade total de domicílios (padrão para 1 se estiver vazio)
            $qtd_domicilios_total      = (empty($data[5]) || !is_numeric($data[5])) ? 1 : (int)$data[5];
            
            $nome_selador              = $data[6]  ?? null; // Nome do(a) selador(a) responsável
            $data_formulario           = $data[7]  ?? null; // Data do formulário
            
            // Formatação segura da data/hora de submissão do KoboToolbox
            $data_hora_submissao       = isset($data[10]) ? str_replace('T', ' ', $data[10]) : null;
            
            // Proteção de string: Garante limite máximo de 100 caracteres para evitar quebras
            $versao                    = isset($data[15]) ? substr(trim($data[15]), 0, 100) : null;

            // Executa passando o array nomeado, limpo e à prova de falhas de contagem
            $stmt->execute([
                ':id_submissao'              => $id_submissao,
                ':uuid'                      => $uuid,
                ':rua_zona_setor'            => $rua_zona_setor,
                ':numero_lote'               => $numero_lote,
                ':endereco_oficial_completo' => $endereco_oficial_completo,
                ':tipo_ocupacao_lote'        => $tipo_ocupacao_lote,
                ':qtd_domicilios_total'      => $qtd_domicilios_total,
                ':nome_selador'              => $nome_selador,
                ':data_formulario'           => $data_formulario,
                ':data_hora_submissao'       => $data_hora_submissao,
                ':versao'                    => $versao
            ]);
            $linhas++;
        }
        fclose($handle_selagem);
        //responderJSON(true, "Sucesso! Foram importados/atualizados <strong>{$linhas}</strong> registros de Selagem.");
        echo "Sucesso! Foram importados/atualizados <strong>{$linhas}</strong> registros de Selagem.";
    }
    //responderJSON(false, "Não foi possível abrir o arquivo enviado.");
    echo "Não foi possível abrir o arquivo enviado.";
}

function importarDomicilios($arquivo, $pdo) {
    if (($handle_domicilios = fopen($arquivo, "r")) !== FALSE) {
        
        // 1. Limpa o BOM (Byte Order Mark) se existir no início do arquivo
        $bom = fread($handle_domicilios, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle_domicilios);
        }

        // 2. Ignora as duas linhas de cabeçalho comuns nessa aba do KoboToolbox (com parâmetros explícitos)
        fgetcsv($handle_domicilios, 2000, ";", '"', "\\");
        fgetcsv($handle_domicilios, 2000, ";", '"', "\\");

        // Utilizando parâmetros nomeados (:numero_selo) para eliminar a contagem cega de "?"
        $sql = "INSERT INTO domicilios 
                (numero_selo, id_submissao_pai, index_kobo, nome_entrevistado, nome_principal_morador, 
                 telefone, cpf, casado_uniao_estavel, uso_predominante, tipo_ocupacao_imovel, 
                 numero_pavimentos, localizacao_domicilio, acesso_independente, area_lote_m2, 
                 comprovante_endereco, foto_comprovante_endereco, foto_fachada, foto_selo, foto_ocupacao, latitude, longitude, altitude, precisao)
                VALUES 
                (:numero_selo, :id_submissao_pai, :index_kobo, :nome_entrevistado, :nome_principal_morador, 
                 :telefone, :cpf, :casado_uniao_estavel, :uso_predominante, :tipo_ocupacao_imovel, 
                 :numero_pavimentos, :localizacao_domicilio, :acesso_independente, :area_lote_m2, 
                 :comprovante_endereco, :foto_comprovante_endereco, :foto_fachada, :foto_selo, :foto_ocupacao, :latitude, :longitude, :altitude, :precisao)
                ON DUPLICATE KEY UPDATE numero_selo=numero_selo;";
                
        $stmt = $pdo->prepare($sql);
        $linhas = 0;

        while (($data = fgetcsv($handle_domicilios, 2000, ";", '"', "\\")) !== FALSE) {
            // for ($i = 0; $i < count($data); $i++) {
            //     echo "[$i] " . $data[$i] . "<br/>";
            // }
            // 3. Validação de segurança: se o número do selo ou index estiver vazio, pula a linha
            if (!isset($data[31]) || trim($data[31]) === '' || empty($data[32])) {
                continue; 
            }

            // =========================================================================
            // DOCUMENTAÇÃO E DE-PARA DOS CAMPOS (Índices validados conforme o LOG real)
            // =========================================================================
            $numero_selo         = trim($data[31]); // Chave Primária Lógica (ex: INV-Y-0001-0002)
            $id_submissao_pai    = trim($data[35]); // ID de ligação com a tabela pai (selagem_lotes)
            $index_kobo          = (int)$data[32];  // Índice interno do Kobo
            
            // Dados de Identificação do Morador
            $nome_entrevistado   = $data[0] ?? null;
            $nome_principal_morador = $data[1] ?? null;
            $telefone            = $data[2] ?? null;
            $cpf                 = isset($data[3]) ? str_replace(['.', '-'], '', $data[3]) : null; // CPF limpo
            $casado_uniao_estavel = $data[4] ?? null;
            
            // Características do Domicílio
            $uso_predominante    = $data[5] ?? null; // ex: Exclusivamente Residencial
            $tipo_ocupacao_imovel = $data[6] ?? null; // ex: Edificação consolidada ocupada
            $numero_pavimentos   = is_numeric($data[7]) ? (int)$data[7] : 1;
            $localizacao_domicilio = $data[8] ?? null; // ex: Térreo
            $acesso_independente = $data[9] ?? null;  // Sim / Não
            $area_lote_m2        = is_numeric($data[10]) ? (float)$data[10] : 0.00;
            $comprovante_endereco = $data[11] ?? null; // Sim / Não
            
            // URLs de Mídias e Comprovantes
            $foto_comprovante_endereco = $data[12] ?? null; // URL da foto do comprovante de endereço
            $foto_fachada    = $data[25] ?? null; // URL da foto da fachada do imóvel
            $foto_selo       = $data[27] ?? null; // URL da foto comprovando selo fixado
            $foto_ocupacao   = $data[29] ?? null; // URL da foto da ocupação do imóvel

            // Informações de Geolocalização por GPS
            $latitude            = (trim($data[21]) !== '') ? (float)$data[21] : null;
            $longitude           = (trim($data[22]) !== '') ? (float)$data[22] : null;
            $altitude            = (trim($data[23]) !== '') ? (float)$data[23] : null;
            $precisao            = (trim($data[24]) !== '') ? (float)$data[24] : null;

            // Execução limpa, segura e estruturada
            $stmt->execute([
                ':numero_selo'               => $numero_selo,
                ':id_submissao_pai'          => $id_submissao_pai,
                ':index_kobo'                => $index_kobo,
                ':nome_entrevistado'         => $nome_entrevistado,
                ':nome_principal_morador'    => $nome_principal_morador,
                ':telefone'                  => $telefone,
                ':cpf'                       => $cpf,
                ':casado_uniao_estavel'      => $casado_uniao_estavel,
                ':uso_predominante'          => $uso_predominante,
                ':tipo_ocupacao_imovel'      => $tipo_ocupacao_imovel,
                ':numero_pavimentos'         => $numero_pavimentos,
                ':localizacao_domicilio'     => $localizacao_domicilio,
                ':acesso_independente'       => $acesso_independente,
                ':area_lote_m2'              => $area_lote_m2,
                ':comprovante_endereco'      => $comprovante_endereco,
                ':foto_comprovante_endereco' => $foto_comprovante_endereco,
                ':foto_fachada'              => $foto_fachada,
                ':foto_selo'                 => $foto_selo,
                ':foto_ocupacao'             => $foto_ocupacao,
                ':latitude'                  => $latitude,
                ':longitude'                 => $longitude,
                ':altitude'                  => $altitude,
                ':precisao'                  => $precisao
            ]);
            
            $linhas++;
        }
        fclose($handle_domicilios);
        //responderJSON(true, "Sucesso! Foram importados/atualizados <strong>{$linhas}</strong> Domicílios vinculados.");
        echo "Sucesso! Foram importados/atualizados <strong>{$linhas}</strong> Domicílios vinculados.";
    }
    //responderJSON(false, "Não foi possível abrir o arquivo enviado.");
    echo "Não foi possível abrir o arquivo enviado.";
}

function importarCaracterizacao($arquivo, $pdo) {
    if (($handle_caracterizacao = fopen($arquivo, "r")) !== FALSE) {
        
        // Limpa o BOM (Byte Order Mark) se existir no início do arquivo
        $bom = fread($handle_caracterizacao, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle_caracterizacao);
        }

        // Pula a primeira linha física do cabeçalho
        fgetcsv($handle_caracterizacao, 4000, ";", '"', "\\");
       
        // Utilizando parâmetros nomeados (:id_submissao) para garantir legibilidade absoluta
        $sql = "INSERT INTO caracterizacao_vulnerabilidade 
                (id_submissao, uuid, codigo_selo, foto_selo, quantidade_comodos, comodos_improvisados_dormitorio, 
                 mais_de_3_por_dormitorio, faltam_camas, possui_banheiro, numero_banheiros, 
                 revestimento_ceramico_banheiro, louca_sanitaria_banheiro, problemas_estruturais_observados, 
                 problemas_infiltracao, existe_comodo_com_mofo, comodos_com_mofo, paredes_materiais, 
                 paredes_condicao, cobertura_materiais, cobertura_condicao, piso_materiais, 
                 piso_condicao, energia_acesso, energia_condicao_instalacao, energia_condicao_instalacao_internas, agua_acesso, 
                 materiais_componencatorios_observados, instalacoes_hidrosanitarias_disponives, condicoes_hidrosanitarias,
                 esgotamento_sanitario, coleta_lixo, frequencia_coleta_lixo, existe_drenagem, pavimentacao_rua, pcd_no_domicilio, 
                 quantidade_pcd, pessoas_mobilidade_reduzida, pessoas_cadeirantes, pessoas_doencas_respiratorias, quantidade_afetados_respiratorio, 
                 hove_acidentes_domesticos, ocorrencia_inundacao, frequencia_inundacao, altura_agua_inundacao, 
                 quando_chove, sensacao_seguranca_rua, possui_iluminacao_publica, possui_equipamentos_lazer, possui_equipamentos_saude, 
                 possui_transporte_publico, principal_meio_locomocao,
                 ha_escada, ha_comodos_sem_janela, ha_calcada_na_rua, imovel_terreno_tem_rachaduras, paredes_embarrigadas,
                 ha_postes_arvores_inclinados, houve_deslizamentos, onde_deslizamento, quando_deslizamento,
                 responsavel_coleta, data_registro)
                VALUES 
                (:id_submissao, :uuid, :codigo_selo, :foto_selo, :quantidade_comodos, :comodos_improvisados_dormitorio, 
                 :mais_de_3_por_dormitorio, :faltam_camas, :possui_banheiro, :numero_banheiros, 
                 :revestimento_ceramico_banheiro, :louca_sanitaria_banheiro, :problemas_estruturais_observados,
                 :problemas_infiltracao, :existe_comodo_com_mofo, :comodos_com_mofo, :paredes_materiais, 
                 :paredes_condicao, :cobertura_materiais, :cobertura_condicao, :piso_materiais, 
                 :piso_condicao, :energia_acesso, :energia_condicao_instalacao, :energia_condicao_instalacao_internas, :agua_acesso, 
                 :materiais_componencatorios_observados, :instalacoes_hidrosanitarias_disponives, :condicoes_hidrosanitarias,
                 :esgotamento_sanitario, :coleta_lixo, :frequencia_coleta_lixo, :existe_drenagem, :pavimentacao_rua, :pcd_no_domicilio, 
                 :quantidade_pcd, :pessoas_mobilidade_reduzida, :pessoas_cadeirantes, :pessoas_doencas_respiratorias, :quantidade_afetados_respiratorio, 
                 :hove_acidentes_domesticos, :ocorrencia_inundacao, :frequencia_inundacao, :altura_agua_inundacao, 
                 :quando_chove, :sensacao_seguranca_rua, :possui_iluminacao_publica, :possui_equipamentos_lazer, :possui_equipamentos_saude,
                 :possui_transporte_publico, :principal_meio_locomocao,
                 :ha_escada, :ha_comodos_sem_janela, :ha_calcada_na_rua, :imovel_terreno_tem_rachaduras, :paredes_embarrigadas,
                 :ha_postes_arvores_inclinados, :houve_deslizamentos, :onde_deslizamento, :quando_deslizamento,
                 :responsavel_coleta, :data_registro)
                ON DUPLICATE KEY UPDATE id_submissao=id_submissao;";
                
        $stmt = $pdo->prepare($sql);
        $linhas = 0;

        while (($data = fgetcsv($handle_caracterizacao, 4000, ";", '"', "\\")) !== FALSE) {
            // for ($i = 0; $i < count($data); $i++) {
            //     echo "[$i] " . $data[$i] . "<br/>";
            // }
            // Validação de Segurança: Garante que a linha atual possui o ID numérico do KoboToolbox
            if (!isset($data[132]) || trim($data[132]) === '' || !is_numeric(trim($data[132]))) {
                continue; // Ignora cabeçalhos residuais ou linhas em branco no fim do arquivo
            }

            // =========================================================================
            // DOCUMENTAÇÃO E DE-PARA DOS CAMPOS (Índices validados conforme o LOG real)
            // =========================================================================
            $id_submissao                    = trim($data[132]);
            $uuid                             = $data[133] ?? null;
            $codigo_selo                      = $data[3]   ?? null; // cod_selo
            $foto_selo                        = $data[4]   ?? null; // foto_selo

            // Dados de Habitabilidade Interna
            $quantidade_comodos               = is_numeric($data[6]) ? (int)$data[6] : 0;
            $comodos_improvisados_dormitorio  = $data[7]   ?? null;
            $mais_de_3_por_dormitorio         = $data[8]   ?? null;
            $faltam_camas                     = $data[9]   ?? null;
            $possui_banheiro                  = $data[10]  ?? null;
            $numero_banheiros                 = is_numeric($data[11]) ? (int)$data[11] : 0;
            $revestimento_ceramico_banheiro   = $data[12]  ?? null;
            $louca_sanitaria_banheiro         = $data[13]  ?? null;
            $problemas_estruturais_observados = $data[14]  ?? null; // Campo consolidado de observações e problemas aparentes
            $problemas_infiltracao            = $data[19]  ?? null; // Campo específico para infiltração, se houver
            $existe_comodo_com_mofo           = $data[24]  ?? null; // Campo específico para mofo, se houver
            $comodos_com_mofo                 = $data[25]  ?? null; // Campo específico para mofo, se houver

            // Materiais das Paredes, Cobertura e Piso
            $paredes_materiais                = $data[26]  ?? null;
            $paredes_condicao                 = $data[32]  ?? null; // Condições gerais aparentes
            $cobertura_materiais              = $data[33]  ?? null;
            $cobertura_condicao               = $data[40]  ?? null;
            $piso_materiais                   = $data[41]  ?? null;
            $piso_condicao                    = $data[48]  ?? null;
            
            // Infraestrutura Urbana / Serviços Públicos
            $energia_acesso                   = $data[49]  ?? null;
            $energia_condicao_instalacao      = $data[57]  ?? null;
            $energia_condicao_instalacao_internas = $data[50]  ?? null;
            $agua_acesso                      = $data[58]  ?? null;
            $materiais_componencatorios_observados = $data[65]  ?? null; // Campo específico para materiais componentes observados
            $instalacoes_hidrosanitarias_disponives = $data[71]  ?? null; // Campo específico para instalações hidrossanitárias disponíveis
            $condicoes_hidrosanitarias        = $data[77]  ?? null; // Campo específico para condições hidrossanitárias
            $esgotamento_sanitario            = $data[78]  ?? null; // ex: fossa_negra
            $coleta_lixo                      = $data[79]  ?? null; // ex: sem_coleta...
            $frequencia_coleta_lixo           = $data[80]  ?? null; // ex: coleta_3_vezes_por_semana
            $existe_drenagem                  = $data[81]  ?? null; // ex: sim_nao
            $pavimentacao_rua                 = $data[82]  ?? null; // A_rua_pavimentada
            
            // Dados de Saúde e Vulnerabilidade Familiar
            $pcd_no_domicilio                 = $data[83]  ?? null;
            $quantidade_pcd                   = is_numeric($data[90]) ? (int)$data[90] : 0;
            $pessoas_mobilidade_reduzida      = $data[91]  ?? null;
            $pessoas_cadeirantes              = $data[92]  ?? null;
            $pessoas_doencas_respiratorias    = $data[101] ?? null;
            $quantidade_afetados_respiratorio = is_numeric($data[105]) ? (int)$data[105] : 0;
            
            // Riscos Ambientais e Percepção
            $hove_acidentes_domesticos        = $data[94] ?? null; // H_ouve_acidentes_dom_sticos
            $ocorrencia_inundacao             = $data[116] ?? null; // J_houve_ocorr_ncia_de_inunda_
            $frequencia_inundacao            = $data[117] ?? null; // J_frequencia_inunda_ao
            $altura_agua_inundacao           = $data[118] ?? null; // J_altura_da_gua_durante_inunda_
            $quando_chove                    = $data[119] ?? null; // J_quando_chove_inunda_
            $sensacao_seguranca_rua           = is_numeric($data[125]) ? (int)$data[125] : 3;
            $possui_iluminacao_publica       = $data[124] ?? null; // H_possui_ilumina_ao_p_blica
            $possui_equipamentos_lazer       = $data[126] ?? null; // H_possui_equipamentos_de_lazer
            $possui_equipamentos_saude       = $data[127] ?? null; // H_possui_equipamentos_de_sa_de
            $possui_transporte_publico       = $data[128] ?? null; // H_possui_acesso_a_transporte_p_blico
            $principal_meio_locomocao        = $data[129] ?? null; // H_principal_meio_de_locomoc_o

            $ha_escada                        = $data[106] ?? null; // H_a_escada
            $ha_comodos_sem_janela            = $data[107] ?? null; // H_a_comodos_sem_janela
            $ha_calcada_na_rua                = $data[109] ?? null; // H_a_calcada_na_rua
            $imovel_terreno_tem_rachaduras    = $data[110] ?? null; // O_imovel_ou_terreno_tem_rachaduras
            $paredes_embarrigadas             = $data[111] ?? null; // Paredes_embarrigadas
            $ha_postes_arvores_inclinados     = $data[112] ?? null; // H_a_postes_ou_arvores_inclinados
            $houve_deslizamentos              = $data[113] ?? null; // H_ouve_deslizamentos
            $onde_deslizamento               = $data[114] ?? null; // Onde_ocorreu_deslizamento
            $quando_deslizamento             = $data[115] ?? null; // Quando_ocorreu_deslizamento

            $responsavel_coleta              = $data[130] ?? null; // Respons_vel_pela_coleta_de_lixo
            $data_registro                    = $data[131] ?? null;

            // Executa passando um array associativo limpo e muito mais fácil de ler
            $stmt->execute([
                ':id_submissao'                    => $id_submissao,
                ':uuid'                            => $uuid,
                ':codigo_selo'                     => $codigo_selo,
                ':foto_selo'                       => $foto_selo,
                ':quantidade_comodos'              => $quantidade_comodos,
                ':comodos_improvisados_dormitorio' => $comodos_improvisados_dormitorio,
                ':mais_de_3_por_dormitorio'        => $mais_de_3_por_dormitorio,
                ':faltam_camas'                    => $faltam_camas,
                ':possui_banheiro'                 => $possui_banheiro,
                ':numero_banheiros'                => $numero_banheiros,
                ':revestimento_ceramico_banheiro'  => $revestimento_ceramico_banheiro,
                ':louca_sanitaria_banheiro'        => $louca_sanitaria_banheiro,
                ':problemas_estruturais_observados' => $problemas_estruturais_observados,
                ':problemas_infiltracao'           => $problemas_infiltracao,
                ':existe_comodo_com_mofo'           => $existe_comodo_com_mofo,
                ':comodos_com_mofo'                => $comodos_com_mofo,
                ':paredes_materiais'               => $paredes_materiais,
                ':paredes_condicao'                => $paredes_condicao,
                ':cobertura_materiais'             => $cobertura_materiais,
                ':cobertura_condicao'              => $cobertura_condicao,
                ':piso_materiais'                  => $piso_materiais,
                ':piso_condicao'                   => $piso_condicao,
                ':energia_acesso'                  => $energia_acesso,
                ':energia_condicao_instalacao'     => $energia_condicao_instalacao,
                ':energia_condicao_instalacao_internas' => $energia_condicao_instalacao_internas,
                ':agua_acesso'                     => $agua_acesso,
                ':materiais_componencatorios_observados' => $materiais_componencatorios_observados,
                ':instalacoes_hidrosanitarias_disponives' => $instalacoes_hidrosanitarias_disponives,
                ':condicoes_hidrosanitarias'       => $condicoes_hidrosanitarias,
                ':esgotamento_sanitario'           => $esgotamento_sanitario,
                ':coleta_lixo'                     => $coleta_lixo,
                ':frequencia_coleta_lixo'          => $frequencia_coleta_lixo,
                ':existe_drenagem'                 => $existe_drenagem,
                ':pavimentacao_rua'                => $pavimentacao_rua,
                ':pcd_no_domicilio'                => $pcd_no_domicilio,
                ':quantidade_pcd'                  => $quantidade_pcd,
                ':pessoas_mobilidade_reduzida'     => $pessoas_mobilidade_reduzida,
                ':pessoas_cadeirantes'             => $pessoas_cadeirantes,
                ':pessoas_doencas_respiratorias'   => $pessoas_doencas_respiratorias,
                ':quantidade_afetados_respiratorio'=> $quantidade_afetados_respiratorio,
                ':hove_acidentes_domesticos'       => $hove_acidentes_domesticos,
                ':ocorrencia_inundacao'            => $ocorrencia_inundacao,
                ':frequencia_inundacao'           => $frequencia_inundacao,
                ':altura_agua_inundacao'          => $altura_agua_inundacao,
                ':quando_chove'                   => $quando_chove,
                ':sensacao_seguranca_rua'          => $sensacao_seguranca_rua,
                ':possui_iluminacao_publica'      => $possui_iluminacao_publica,
                ':possui_equipamentos_lazer'      => $possui_equipamentos_lazer,
                ':possui_equipamentos_saude'      => $possui_equipamentos_saude,
                ':possui_transporte_publico'      => $possui_transporte_publico,
                ':principal_meio_locomocao'       => $principal_meio_locomocao,
                ':ha_escada'                     => $ha_escada,
                ':ha_comodos_sem_janela'         => $ha_comodos_sem_janela,
                ':ha_calcada_na_rua'             => $ha_calcada_na_rua,
                ':imovel_terreno_tem_rachaduras' => $imovel_terreno_tem_rachaduras,
                ':paredes_embarrigadas'          => $paredes_embarrigadas,
                ':ha_postes_arvores_inclinados'  => $ha_postes_arvores_inclinados,
                ':houve_deslizamentos'           => $houve_deslizamentos,
                ':onde_deslizamento'            => $onde_deslizamento,
                ':quando_deslizamento'          => $quando_deslizamento,
                ':responsavel_coleta'           => $responsavel_coleta,
                ':data_registro'                   => $data_registro
            ]); 
            
            $linhas++;
        }
        fclose($handle_caracterizacao);
        //responderJSON(true, "Sucesso! Caracterização Complementar de {$linhas} imóveis adicionada.");
        echo "Sucesso! Caracterização Complementar de {$linhas} imóveis adicionada.";
    }
    //responderJSON(false, "Não foi possível abrir o arquivo enviado.");
    echo "Não foi possível abrir o arquivo enviado.";
}

function importarSociojuridico($arquivo, $pdo) {
    if (($handle_joridico = fopen($arquivo, "r")) !== FALSE) {
        
        // 1. Limpa o BOM (Byte Order Mark) se existir no início do arquivo
        $bom = fread($handle_joridico, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle_joridico);
        }

        // 2. Consome a primeira linha física do cabeçalho
        fgetcsv($handle_joridico, 4000, ";", '"', "\\");

        // Utilizando parâmetros nomeados (:id_submissao) para clareza e manutenção imediata
        $sql = "INSERT INTO cadastro_sociojuridico 
                (id_submissao, uuid, codigo_selo, foto_selo, r1_nome, r1_rg, r1_foto_rg, r1_cpf, r1_foto_cpf, r1_naturalidade, r1_data_nascimento, 
                 r1_estado_civil, r1_profissao, r1_escolaridade, r1_pcd, r1_especifiacao_pcd, r1_telefone, numero_residentes, 
                 renda_mensal_titular_1, renda_mensal_titular_2, renda_outras_fontes, cadunico_nis, numero_nis,
                 recebe_beneficio_social, beneficios_detalhe, reacao_com_imovel, forma_aquisicao, 
                 tempo_ocupacao, foto_comprovante_ocupacao_2022, foto_comprovante_ocupacao_2023, foto_comprovante_ocupacao_2024, foto_comprovante_ocupacao_2025, foto_comprovante_ocupacao_2026,
                 paga_iptu, assinou_unica_propriedade, foto_declaracao_unica_propriedade, assinou_ocupacao_mansa_pacifica, foto_declaracao_ocupacao_mansa_pacifica,
                 assinou_veracidade, foto_declaracao_veracidade, assinou_lgpd, foto_declaracao_lgpd, nome_cadastrador, data_registro)
                VALUES 
                (:id_submissao, :uuid, :codigo_selo, :foto_selo, :r1_nome, :r1_rg, :r1_foto_rg, :r1_cpf, :r1_foto_cpf, :r1_naturalidade, :r1_data_nascimento, 
                 :r1_estado_civil, :r1_profissao, :r1_escolaridade, :r1_pcd, :r1_especifiacao_pcd, :r1_telefone, :numero_residentes, 
                 :renda_mensal_titular_1, :renda_mensal_titular_2, :renda_outras_fontes, :cadunico_nis, :numero_nis,
                 :recebe_beneficio_social, :beneficios_detalhe, :reacao_com_imovel, :forma_aquisicao, :tempo_ocupacao, 
                 :foto_comprovante_ocupacao_2022, :foto_comprovante_ocupacao_2023, :foto_comprovante_ocupacao_2024, :foto_comprovante_ocupacao_2025, :foto_comprovante_ocupacao_2026,
                 :paga_iptu, :assinou_unica_propriedade, :foto_declaracao_unica_propriedade, :assinou_ocupacao_mansa_pacifica, :foto_declaracao_ocupacao_mansa_pacifica,
                 :assinou_veracidade, :foto_declaracao_veracidade, :assinou_lgpd, :foto_declaracao_lgpd, :nome_cadastrador, :data_registro)
                ON DUPLICATE KEY UPDATE id_submissao=id_submissao;";
                
        $stmt = $pdo->prepare($sql);
        $linhas = 0;

        while (($data = fgetcsv($handle_joridico, 4000, ";", '"', "\\")) !== FALSE) {
            //for ($i = 0; $i < count($data); $i++) {
            //    echo "[$i] " . $data[$i] . "<br/>";
            //}
            // 3. Validação de segurança: se o ID no índice [106] não for numérico, pula (filtra cabeçalhos/linhas vazias)
            if (!isset($data[106]) || trim($data[106]) === '' || !is_numeric(trim($data[106]))) {
                continue; 
            }

            // =========================================================================
            // DOCUMENTAÇÃO E DE-PARA DOS CAMPOS (Índices validados conforme o LOG real)
            // =========================================================================
            $id_submissao     = trim($data[106]); // _id
            $uuid             = $data[107] ?? null; // _uuid
            $codigo_selo      = $data[3]   ?? null; // Código do Selo (ex: TP1-D-0014-0007)
            $foto_selo        = $data[4]   ?? null; // Foto do Selo

            // Dados do Responsável 1 (Titular)
            $r1_nome          = $data[7]   ?? null; // Kenya Santos de Abreu
            $r1_rg            = $data[8]   ?? null; // 268737
            $r1_foto_rg       = $data[9]   ?? null; // Foto do RG   
            $r1_cpf           = isset($data[11]) ? str_replace(['.', '-'], '', $data[11]) : null; // CPF limpo
            $r1_foto_cpf      = $data[12]  ?? null; // Foto do CPF
            $r1_naturalidade  = $data[14]  ?? null; // Brasília, DF
            $r1_data_nascimento = $data[15] ?? null; // 1988-06-07
            $r1_estado_civil  = $data[16]  ?? null; // Solteira(o)
            $r1_profissao     = $data[20]  ?? null; // Auxiliar Administrativo
            $r1_escolaridade  = $data[21]  ?? null; // Ensino Médio Completo
            $r1_pcd           = $data[22]  ?? null; // Não
            $r1_especifiacao_pcd = $data[23] ?? null; // Especificação do PCD
            $r1_telefone      = $data[29]  ?? null; // 61 992293433
            
            // Composição Familiar e Dinâmica de Renda
            $numero_residentes = is_numeric($data[53]) ? (int)$data[53] : 1;
            $renda_mensal_titular_1 = isset($data[54]) ? (float)str_replace(',', '.', $data[54]) : 0.00;
            $renda_mensal_titular_2 = isset($data[55]) ? (float)str_replace(',', '.', $data[55]) : 0.00;
            $renda_outras_fontes    = isset($data[56]) ? (float)str_replace(',', '.', $data[56]) : 0.00;
            
            // Benefícios Sociais (Mapeamento Kobo e Normalização de String)
            $cadunico_nis            = $data[57] ?? null; // NIS do cidadão
            $numero_nis              = $data[58] ?? null; // Número do NIS (sem formatação)
            $recebe_beneficio_social = $data[57] ?? null; // Sim / Não
            
            $beneficios = [];
            if (($data[59] ?? '') === 'Sim') $beneficios[] = "Bolsa Família";
            if (($data[91] ?? '') === 'Sim') $beneficios[] = "Prato Cheio";
            $beneficios_detalhe = implode(', ', $beneficios);
            
            // Caracterização Jurídico-Patrimonial do Imóvel
            $reacao_com_imovel = $data[72] ?? null; // ex: Próprio
            $forma_aquisicao   = $data[73] ?? null; // ex: Ocupação
            $tempo_ocupacao    = $data[79] ?? null; // ex: 5 anos ou mais
            $foto_comprovante_ocupacao_2022 = $data[88] ?? null; // Foto do comprovante de ocupação (2022)
            $foto_comprovante_ocupacao_2023 = $data[86] ?? null; // Foto do comprovante de ocupação (2023)
            $foto_comprovante_ocupacao_2024 = $data[84] ?? null; // Foto do comprovante de ocupação (2024)
            $foto_comprovante_ocupacao_2025 = $data[82] ?? null; // Foto do comprovante de ocupação (2025)
            $foto_comprovante_ocupacao_2026 = $data[80] ?? null; // Foto do comprovante de ocupação (2026)
            $paga_iptu         = $data[90] ?? null; // ex: Não
            
            // Manifestações e Termos de Consentimento (REURB / LGPD)
            $assinou_unica_propriedade       = $data[91]  ?? null;
            $foto_declaracao_unica_propriedade = $data[92] ?? null;
            $assinou_ocupacao_mansa_pacifica = $data[94]  ?? null;
            $foto_declaracao_ocupacao_mansa_pacifica = $data[95] ?? null;
            $assinou_veracidade              = $data[97]  ?? null;
            $foto_declaracao_veracidade      = $data[98]  ?? null;
            $assinou_lgpd                    = $data[100] ?? null;
            $foto_declaracao_lgpd             = $data[101] ?? null;
            
            // Metadados da Coleta em Campo
            $nome_cadastrador = $data[103] ?? null; // Julia Rafaela De Sousa Freitas
            $data_registro    = $data[104] ?? null; // 2026-04-25

            // Execução limpa e totalmente mapeada sem amarras de posições cegas
            $stmt->execute([
                ':id_submissao'                     => $id_submissao,
                ':uuid'                             => $uuid,
                ':codigo_selo'                      => $codigo_selo,
                ':foto_selo'                        => $foto_selo,
                ':r1_nome'                          => $r1_nome,
                ':r1_rg'                            => $r1_rg,
                ':r1_foto_rg'                       => $r1_foto_rg,
                ':r1_cpf'                           => $r1_cpf,
                ':r1_foto_cpf'                      => $r1_foto_cpf,
                ':r1_naturalidade'                  => $r1_naturalidade,
                ':r1_data_nascimento'               => $r1_data_nascimento,
                ':r1_estado_civil'                  => $r1_estado_civil,
                ':r1_profissao'                     => $r1_profissao,
                ':r1_escolaridade'                  => $r1_escolaridade,
                ':r1_pcd'                           => $r1_pcd,
                ':r1_especifiacao_pcd'             => $r1_especifiacao_pcd,
                ':r1_telefone'                      => $r1_telefone,
                ':numero_residentes'                => $numero_residentes,
                ':renda_mensal_titular_1'           => $renda_mensal_titular_1,
                ':renda_mensal_titular_2'           => $renda_mensal_titular_2,
                ':renda_outras_fontes'              => $renda_outras_fontes,
                ':cadunico_nis'                     => $cadunico_nis,
                ':numero_nis'                       => $numero_nis,
                ':recebe_beneficio_social'          => $recebe_beneficio_social,
                ':beneficios_detalhe'               => $beneficios_detalhe,
                ':reacao_com_imovel'                => $reacao_com_imovel,
                ':forma_aquisicao'                  => $forma_aquisicao,
                ':tempo_ocupacao'                   => $tempo_ocupacao,
                ':foto_comprovante_ocupacao_2022' => $foto_comprovante_ocupacao_2022,
                ':foto_comprovante_ocupacao_2023' => $foto_comprovante_ocupacao_2023,
                ':foto_comprovante_ocupacao_2024' => $foto_comprovante_ocupacao_2024,
                ':foto_comprovante_ocupacao_2025' => $foto_comprovante_ocupacao_2025,
                ':foto_comprovante_ocupacao_2026' => $foto_comprovante_ocupacao_2026,
                ':paga_iptu'                        => $paga_iptu,
                ':assinou_unica_propriedade'        => $assinou_unica_propriedade,
                ':foto_declaracao_unica_propriedade' => $foto_declaracao_unica_propriedade,
                ':assinou_ocupacao_mansa_pacifica'  => $assinou_ocupacao_mansa_pacifica,
                ':foto_declaracao_ocupacao_mansa_pacifica' => $foto_declaracao_ocupacao_mansa_pacifica,
                ':assinou_veracidade'               => $assinou_veracidade,
                ':foto_declaracao_veracidade'       => $foto_declaracao_veracidade,
                ':assinou_lgpd'                     => $assinou_lgpd,
                ':foto_declaracao_lgpd'              => $foto_declaracao_lgpd,
                ':nome_cadastrador'                 => $nome_cadastrador,
                ':data_registro'                    => $data_registro
            ]);
            
            $linhas++;
        }
        fclose($handle_joridico);
        //responderJSON(true, "Sucesso! Cadastro Sociojurídico de {$linhas} famílias importado.");
        echo "Sucesso! Cadastro Sociojurídico de {$linhas} famílias importado.";
    }
    //responderJSON(false, "Não foi possível abrir o arquivo enviado.");
    echo "Não foi possível abrir o arquivo enviado.";
}

// Helper para padronizar o retorno da API para a interface do usuário
function responderJSON($sucesso, $mensagem) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ], JSON_UNESCAPED_UNICODE);
    //exit;
}
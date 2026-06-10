<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2604 - Cadastro Sociojurídico - Teste Metodológico</title>
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container { max-width: 1000px; }
        .form-card { background: #fff; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); padding: 40px; margin-top: 40px; margin-bottom: 40px; border-top: 8px solid #28a745; }
        .section-header { background: #eefaf1; color: #1e7e34; padding: 12px 20px; border-radius: 6px; margin: 30px 0 20px 0; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border-left: 5px solid #28a745; }
        .subsection-header { color: #28a745; border-bottom: 2px solid #d4edda; padding-bottom: 8px; margin: 25px 0 15px 0; font-weight: 600; font-size: 1.1rem; }
        .form-group label { font-weight: 600; color: #495057; }
        .help-text { font-size: 0.85rem; color: #6c757d; font-style: italic; display: block; margin-top: 4px; }
        .required::after { content: " *"; color: #dc3545; }
        .custom-file-label::after { content: "Procurar"; }
        .btn-submit { background-color: #28a745; border-color: #28a745; padding: 12px 40px; font-weight: 600; }
        .btn-submit:hover { background-color: #218838; border-color: #1e7e34; }
        .pcd-box { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-card">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">2604 - CADASTRO SOCIOJURÍDICO</h2>
            <p class="text-muted">TESTE METODOLÓGICO</p>
        </div>

        <form action="salvar_cadastro.php" method="POST" enctype="multipart/form-data">
            
            <!-- DADOS DO IMÓVEL -->
            <div class="section-header">Dados do Imóvel</div>
            
            <div class="form-group">
                <label class="d-block required">O lote passou pelo processo de selagem?</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="selagem_sim" name="processo_selagem" class="custom-control-input" value="Sim" required>
                    <label class="custom-control-label" for="selagem_sim">Sim</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="selagem_nao" name="processo_selagem" class="custom-control-input" value="Não" required>
                    <label class="custom-control-label" for="selagem_nao">Não</label>
                </div>
                <small class="help-text">Verificar se a equipe de selagem já visitou o domicílio e se o morador tem a cópia do Selo.</small>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="codigo_selo">Qual o código do Selo?</label>
                        <input type="text" class="form-control" id="codigo_selo" name="codigo_selo" placeholder="AAA-A-0000-0000 ou AAA-A-000A-0000">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="upload_selo">Selo (Upload)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="upload_selo" name="upload_selo" accept="image/*,application/pdf">
                            <label class="custom-file-label" for="upload_selo">Escolher arquivo</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESPONSÁVEL FAMILIAR -->
            <div class="section-header">Responsável Familiar</div>
            
            <div class="form-group">
                <label class="d-block required">Quantidade de Responsáveis/Respondentes:</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="resp_1" name="qtd_responsaveis" class="custom-control-input" value="1" required checked>
                    <label class="custom-control-label" for="resp_1">1</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="resp_2" name="qtd_responsaveis" class="custom-control-input" value="2" required>
                    <label class="custom-control-label" for="resp_2">2</label>
                </div>
                <small class="help-text">Informar a quantidade de responsáveis pelo lote. Uma ou duas pessoas.</small>
            </div>

            <!-- RESPONSÁVEL 1 -->
            <div id="bloco_responsavel_1">
                <div class="subsection-header">» Responsável Familiar 1</div>
                
                <div class="form-group">
                    <label for="nome_resp1" class="required">Nome do Responsável pelo Cadastro e pelo Imóvel</label>
                    <input type="text" class="form-control" id="nome_resp1" name="nome_resp1" required>
                    <small class="help-text">Inserir nome completo sem abreviações.</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rg_resp1" class="required">Número do Registro Geral (RG)</label>
                            <input type="text" class="form-control" id="rg_resp1" name="rg_resp1" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="foto_rg1" class="required">Foto RG</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto_rg1" name="foto_rg1" accept="image/*" required>
                                <label class="custom-file-label" for="foto_rg1">Escolher foto</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cpf_resp1" class="required">Número do CPF</label>
                            <input type="text" class="form-control" id="cpf_resp1" name="cpf_resp1" placeholder="000.000.000-00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="foto_cpf1" class="required">Foto CPF</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto_cpf1" name="foto_cpf1" accept="image/*" required>
                                <label class="custom-file-label" for="foto_cpf1">Escolher foto</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="naturalidade1" class="required">Naturalidade?</label>
                            <input type="text" class="form-control" id="naturalidade1" name="naturalidade1" placeholder="Cidade - UF" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nascimento1" class="required">Data de Nascimento</label>
                            <input type="date" class="form-control" id="nascimento1" name="nascimento1" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado_civil1" class="required">Estado Civil</label>
                            <select class="form-control" id="estado_civil1" name="estado_civil1" required>
                                <option value="">Selecione...</option>
                                <option>Solteira(o)</option>
                                <option>Casada(o)</option>
                                <option>Divorciada(o)</option>
                                <option>Viúva(o)</option>
                                <option>Separada(o)</option>
                                <option>União Estável</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="foto_casamento1">Foto Certidão Casamento/União Estável</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto_casamento1" name="foto_casamento1" accept="image/*">
                                <label class="custom-file-label" for="foto_casamento1">Escolher foto</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="regime_bens1">Regime de Bens</label>
                            <select class="form-control" id="regime_bens1" name="regime_bens1">
                                <option value="">Selecione...</option>
                                <option>Comunhão Parcial de Bens</option>
                                <option>Comunhão Total de Bens</option>
                                <option>Separação Total de Bens</option>
                                <option>Participação Final nos Aquestos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profissao1" class="required">Profissão</label>
                            <input type="text" class="form-control" id="profissao1" name="profissao1" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="escolaridade1" class="required">Escolaridade</label>
                            <select class="form-control" id="escolaridade1" name="escolaridade1" required>
                                <option value="">Selecione...</option>
                                <option>Não Alfabetizado</option>
                                <option>Ensino Fundamental Incompleto</option>
                                <option>Ensino Fundamental Completo</option>
                                <option>Ensino Médio Incompleto</option>
                                <option>Ensino Médio Completo</option>
                                <option>Ensino Técnico Incompleto</option>
                                <option>Ensino Técnico Completo</option>
                                <option>Ensino Superior Incompleto</option>
                                <option>Ensino Superior Completo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tel1" class="required">Telefone (pref. WhatsApp)</label>
                            <input type="text" class="form-control" id="tel1" name="tel1" placeholder="(61) 99999-9999" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="d-block">PcD (Pessoa com Deficiência)?</label>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="pcd1_sim" name="pcd1" class="custom-control-input" value="Sim">
                        <label class="custom-control-label" for="pcd1_sim">Sim</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="pcd1_nao" name="pcd1" class="custom-control-input" value="Não" checked>
                        <label class="custom-control-label" for="pcd1_nao">Não</label>
                    </div>
                </div>

                <div class="pcd-box mb-3" id="pcd1_especificar" style="display:none;">
                    <label class="font-weight-bold">Especifique (PcD):</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="pcd1_fisica" name="pcd1_tipo[]" value="Física">
                        <label class="custom-control-label" for="pcd1_fisica">Física (paralisia, amputação, nanismo)</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="pcd1_sensorial" name="pcd1_tipo[]" value="Sensorial">
                        <label class="custom-control-label" for="pcd1_sensorial">Sensorial (auditiva, visual)</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="pcd1_intelectual" name="pcd1_tipo[]" value="Intelectual">
                        <label class="custom-control-label" for="pcd1_intelectual">Intelectual (funcionamento inferior à média)</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="pcd1_mental" name="pcd1_tipo[]" value="Mental/Psicossocial">
                        <label class="custom-control-label" for="pcd1_mental">Mental/Psicossocial (TEA, epilepsia, cognitivos)</label>
                    </div>
                </div>
            </div>

            <!-- COMPOSIÇÃO E RENDA FAMILIAR -->
            <div class="section-header">Composição e Renda Familiar</div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="num_residentes" class="required">Número de residentes no imóvel</label>
                        <input type="number" class="form-control" id="num_residentes" name="num_residentes" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="renda1" class="required">Renda bruta mensal (Responsável 01)</label>
                        <input type="text" class="form-control" id="renda1" name="renda1" placeholder="0000,00" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="renda2">Renda bruta mensal (Responsável 02)</label>
                        <input type="text" class="form-control" id="renda2" name="renda2" placeholder="0000,00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="outras_rendas">Rendas de outras fontes</label>
                        <input type="text" class="form-control" id="outras_rendas" name="outras_rendas" placeholder="0000,00">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="d-block">CadÚnico (NIS)?</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="cadunico_sim" name="cadunico" class="custom-control-input" value="Sim">
                            <label class="custom-control-label" for="cadunico_sim">Sim</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="cadunico_nao" name="cadunico" class="custom-control-input" value="Não" checked>
                            <label class="custom-control-label" for="cadunico_nao">Não</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nis">Número de Identificação Social (NIS)</label>
                        <input type="text" class="form-control" id="nis" name="nis">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="d-block">Recebe algum benefício Social?</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="beneficio_sim" name="recebe_beneficio" class="custom-control-input" value="Sim">
                    <label class="custom-control-label" for="beneficio_sim">Sim</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="beneficio_nao" name="recebe_beneficio" class="custom-control-input" value="Não" checked>
                    <label class="custom-control-label" for="beneficio_nao">Não</label>
                </div>
            </div>

            <div class="pcd-box mb-4" id="beneficios_lista" style="display:none;">
                <label class="font-weight-bold">Quais benefícios?</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="b1" name="beneficios[]" value="Bolsa Família"><label class="custom-control-label" for="b1">Bolsa Família</label></div>
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="b2" name="beneficios[]" value="BPC"><label class="custom-control-label" for="b2">BPC</label></div>
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="b3" name="beneficios[]" value="TSEE"><label class="custom-control-label" for="b3">Tarifa Social Energia</label></div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="b4" name="beneficios[]" value="Aluguel Social"><label class="custom-control-label" for="b4">Aluguel Social</label></div>
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="b5" name="beneficios[]" value="Prato Cheio"><label class="custom-control-label" for="b5">Cartão Prato Cheio</label></div>
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="b6" name="beneficios[]" value="Outro"><label class="custom-control-label" for="b6">Outro</label></div>
                    </div>
                </div>
            </div>

            <!-- CONDIÇÕES DE OCUPAÇÃO -->
            <div class="section-header">Condições de Ocupação</div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="relacao_imovel" class="required">Relação com o imóvel</label>
                        <select class="form-control" id="relacao_imovel" name="relacao_imovel" required>
                            <option>Próprio</option>
                            <option>Locado</option>
                            <option>Emprestado</option>
                            <option>Cedido</option>
                            <option>Outro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="forma_aquisicao" class="required">Forma de aquisição</label>
                        <select class="form-control" id="forma_aquisicao" name="forma_aquisicao" required>
                            <option>Compra</option>
                            <option>Doação</option>
                            <option>Herança</option>
                            <option>Ocupação</option>
                            <option>Outra</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tempo_ocupacao" class="required">Tempo de Ocupação do Imóvel</label>
                <select class="form-control" id="tempo_ocupacao" name="tempo_ocupacao" required>
                    <option>5 anos ou mais</option>
                    <option>4 anos</option>
                    <option>3 anos</option>
                    <option>2 anos</option>
                    <option>1 ano</option>
                </select>
            </div>

            <div class="subsection-header">Comprovantes de Residência (Anuais)</div>
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Ano 2026</label><input type="file" class="form-control-file" name="comp_2026"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Ano 2025</label><input type="file" class="form-control-file" name="comp_2025"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Ano 2024</label><input type="file" class="form-control-file" name="comp_2024"></div></div>
            </div>

            <!-- DECLARAÇÕES -->
            <div class="section-header">Declarações</div>
            
            <div class="form-group p-3 border rounded bg-light">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="decl_prop" name="decl_prop" required>
                    <label class="custom-control-label" for="decl_prop">Assinou declaração de única propriedade (REURB-S)?</label>
                </div>
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="decl_mansa" name="decl_mansa" required>
                    <label class="custom-control-label" for="decl_mansa">Assinou declaração de ocupação mansa e pacífica?</label>
                </div>
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="decl_vera" name="decl_vera" required>
                    <label class="custom-control-label" for="decl_vera">Assinou declaração de veracidade das informações?</label>
                </div>
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="decl_lgpd" name="decl_lgpd" required>
                    <label class="custom-control-label" for="decl_lgpd">Assinou declaração de consentimento LGPD?</label>
                </div>
            </div>

            <!-- EQUIPE DE CAMPO -->
            <div class="section-header">Dados do Responsável pelo Formulário</div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="resp_cadastro" class="required">Responsável pelo cadastramento</label>
                        <input type="text" class="form-control" id="resp_cadastro" name="resp_cadastro" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="data_registro" class="required">Data de registro</label>
                        <input type="date" class="form-control" id="data_registro" name="data_registro" required>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-center">
                <button type="submit" class="btn btn-submit btn-lg text-white">Finalizar Cadastro Sociojurídico</button>
            </div>

        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script>
    // Lógica para mostrar/esconder campos PcD
    $('input[name="pcd1"]').change(function() {
        if($(this).val() == 'Sim') $('#pcd1_especificar').fadeIn();
        else $('#pcd1_especificar').fadeOut();
    });

    // Lógica para mostrar/esconder benefícios
    $('input[name="recebe_beneficio"]').change(function() {
        if($(this).val() == 'Sim') $('#beneficios_lista').fadeIn();
        else $('#beneficios_lista').fadeOut();
    });

    // Atualizar label do arquivo
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
</body>
</html>
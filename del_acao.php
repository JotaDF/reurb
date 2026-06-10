<?php

require_once('./actions/ManterEtapa.php');
require_once('./actions/ManterAcao.php');
require_once('./dto/Acao.php');

$db_etapa = new ManterEtapa();

$db_acao = new ManterAcao();
$acao = new Acao();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$acao = $db_acao->getAcaoPorId($id);

$tarefa = $db_etapa->getEtapaPorId($acao->etapa)->tarefa;

$db_acao->excluir($id);

header('Location: gerenciar_etapas.php?tarefa='.$tarefa);


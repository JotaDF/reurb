<?php

require_once('./actions/ManterEtapa.php');
require_once('./dto/Etapa.php');

$db_etapa = new ManterEtapa();
$etapa = new Etapa();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

$tarefa = $db_etapa->getEtapaPorId($id)->tarefa;

$db_etapa->excluir($id);

header('Location: gerenciar_etapas.php?tarefa='.$tarefa);


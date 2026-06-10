<?php


require_once('actions/ManterEtapa.php');

$db_etapa = new ManterEtapa();



$id     = $_REQUEST['id'];
$op     = $_REQUEST['op'];
$ordem  = $_REQUEST['ordem'];
$tarefa = $_REQUEST['tarefa'];

//echo 'ID:' .$_REQUEST['id'] . ' ACAO: '.$_REQUEST['etapa'] . ' ORDEM: '.$_REQUEST['ordem'] . ' ETAPA: '.$_REQUEST['etapa'] ;

//print_r($etapa);
if ($op == "s") {
    $db_etapa->sobeOrdem($id, $tarefa, $ordem);
} else if ($op == "d") {
    $db_etapa->desceOrdem($id, $tarefa, $ordem);
}


header('Location: gerenciar_etapas.php?tarefa='.$tarefa);


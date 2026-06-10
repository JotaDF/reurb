<?php


require_once('actions/ManterAcao.php');

$db_acao = new ManterAcao();



$id     = $_REQUEST['id'];
$op     = $_REQUEST['op'];
$ordem  = $_REQUEST['ordem'];
$etapa  = $_REQUEST['etapa'];
$tarefa = $_REQUEST['tarefa'];

//echo 'ID:' .$_REQUEST['id'] . ' ACAO: '.$_REQUEST['acao'] . ' ORDEM: '.$_REQUEST['ordem'] . ' ETAPA: '.$_REQUEST['etapa'] ;

//print_r($acao);
if ($op == "s") {
    $db_acao->sobeOrdem($id, $etapa, $ordem);
} else if ($op == "d") {
    $db_acao->desceOrdem($id, $etapa, $ordem);
}


header('Location: gerenciar_etapas.php?tarefa='.$tarefa);


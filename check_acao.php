<?php
session_start();
require_once('./dto/Usuario.php'); 
require_once('./actions/ManterTarefa.php');
require_once('./actions/ManterEtapa.php');
require_once('./actions/ManterAcao.php');
require_once('./dto/Acao.php');

$db_tarefa = new ManterTarefa();
$db_etapa = new ManterEtapa();
$db_acao = new ManterAcao();
$acao = new Acao();

$red = isset($_REQUEST['red']) ? $_REQUEST['red'] : 0;

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 0;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

$prevista = isset($_REQUEST['prevista']) ? $_REQUEST['prevista'] : 0;
$acao = $db_acao->getAcaoPorId($id);

$usuario_logado = unserialize($_SESSION['usuario']);
//echo 'RED: '.$red . ' OP:' .$op  . 'ID:'.$id . ' PREVISTA:'.$prevista. ' Usuario:'.$usuario_logado->id;


$tarefa = $db_etapa->getEtapaPorId($acao->etapa)->tarefa;
if($op > 0){
    $res = $db_acao->checkAcao($id, $usuario_logado->id,strtotime($prevista));
}else {
    $res =  $db_acao->removeCheckAcao($id, $usuario_logado->id,strtotime($prevista));
}

if ($red==0) {
    $percentual = round($db_tarefa->getPercentualTarefaPorId($tarefa), 1);
    echo $percentual;
}else{
    $tarefa = $db_etapa->getEtapaPorId($acao->etapa)->tarefa;
    header('Location: gerenciar_etapas.php?tarefa='.$tarefa);
}



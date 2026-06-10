<?php


require_once('./actions/ManterAcao.php');
require_once('./dto/Acao.php');

$db_acao = new ManterAcao();
$a = new Acao();

//echo 'ID:' .$_POST['id'] . ' ACAO: '.$_POST['acao'] . ' ORDEM: '.$_POST['ordem'] . ' ETAPA: '.$_POST['etapa'] ;

$id     = isset($_POST['id']) ? $_POST['id'] : 0;
$acao   = $_POST['acao'];
$ordem  = $_POST['ordem'];
$etapa  = $_POST['etapa'];
$tarefa = $_POST['tarefa'];
$dias   = isset($_POST['dias']) ? $_POST['dias'] : 0; 



$a->id      = $id;
$a->acao    = $acao;
$a->ordem   = $ordem;   
$a->etapa   = $etapa;
$a->dias    = $dias;

//print_r($acao);

$db_acao->salvar($a,$tarefa);

header('Location: gerenciar_etapas.php?tarefa='.$tarefa);


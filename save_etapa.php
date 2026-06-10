<?php

date_default_timezone_set('America/Sao_Paulo'); 

require_once('./actions/ManterEtapa.php');
require_once('./dto/Etapa.php');

$db_etapa = new ManterEtapa();
$etapa = new Etapa();

$id         = isset($_POST['id']) ? $_POST['id'] : 0;
$nome       = $_POST['nome'];
$ordem      = $_POST['ordem'];
$tarefa     = $_POST['tarefa'];
$data_base  = isset($_POST['data_base']) ? $_POST['data_base'] : 0;

$etapa->id          = $id;
$etapa->nome        = $nome;
$etapa->ordem       = $ordem;   
$etapa->tarefa      = $tarefa;
$etapa->data_base   = strtotime($data_base);

$db_etapa->salvar($etapa);

header('Location: gerenciar_etapas.php?tarefa='.$tarefa);


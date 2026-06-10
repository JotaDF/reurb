<?php

require_once('./actions/ManterTarefa.php');
require_once('./dto/Tarefa.php');

$db_tarefa = new ManterTarefa();
$tarefa = new Tarefa();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

if ($id > 0) {
    $db_tarefa->excluir($id);
    header('Location: tarefas.php');
} else {
    echo 'Falta de parâmetros!';
    header('Location: tarefas.php');
}

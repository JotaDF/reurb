<?php

date_default_timezone_set('America/Sao_Paulo');

include_once('actions/ManterTarefa.php');
include_once('actions/ManterUsuario.php');
include_once('actions/ManterEquipe.php');

$manterTarefa = new ManterTarefa();
$manterUsuario = new ManterUsuario();
$manterEquipe = new ManterEquipe();

$lista = $manterTarefa->listar($filtro);

foreach ($lista as $obj) {
    $percentual = round($manterTarefa->getPercentualTarefaPorId($obj->id), 1);
    echo "<tr>";
//            echo "  <td>".$obj->id."</td>";
    echo "  <td>" . $obj->categoria . "</td>";
    echo "  <td>" . $obj->tipo . "</td>";
    echo "  <td>" . $obj->nome . "</td>";
    echo "  <td>" . date('d/m/Y', strtotime($obj->inicio)) . "</td>";
    echo "  <td>" . date('d/m/Y', strtotime($obj->fim)) . "</td>";
//            echo "  <td>".($obj->equipe > 0 ? $manterEquipe->getEquipePorId($obj->equipe)->equipe:'Todos')."</td>";
//            echo "  <td>".($obj->responsavel > 0 ? $manterUsuario->getUsuarioPorId($obj->responsavel)->nome:'')."</td>";
    echo "  <td><div class='mt-2 progress'>"
    . "<div class='progress-bar bg-success' role='progressbar' style='width: " . $percentual . "%;' aria-valuenow='" . $percentual . "' aria-valuemin='0' aria-valuemax='100'>" . $percentual . "%</div></div>"
    . "</td>";
    $editar = false;
    if ($usuario_logado->perfil == 1 || $usuario_logado->id == $obj->criador) {
        $editar = true;
    }
    $clonar = false;
    if ($usuario_logado->perfil <= 2) {
        $clonar = true;
    }

    echo "  <td align='center'>";
    if ($obj->excluir) {
        echo "  <a href='gerenciar_etapas.php?tarefa=" . $obj->id . "' class='btn btn-warning btn-sm' title='Gerenciar etapas'><i class='fa fa-bars'></i></a>";
        if ($editar || $clonar) {
            echo " &nbsp;&nbsp;<button title='Duplicar tarefa' class='btn btn-info btn-sm' type='button' onclick='duplicar(" . $obj->id . ",\"" . $obj->nome . "\",\"" . $obj->descricao . "\",\"" . $obj->categoria . "\",\"" . $obj->inicio . "\",\"" . $obj->fim . "\",\"" . $obj->tipo . "\"," . $obj->criador . "," . $obj->responsavel . "," . $obj->equipe . ")'><i class='fas fa-clone'></i></button>";
        }
        if ($editar) {
            echo "&nbsp;&nbsp;<button title='Alterar tarefa' class='btn btn-primary btn-sm' type='button' onclick='alterar(" . $obj->id . ",\"" . $obj->nome . "\",\"" . $obj->descricao . "\",\"" . $obj->categoria . "\",\"" . $obj->inicio . "\",\"" . $obj->fim . "\",\"" . $obj->tipo . "\"," . $obj->criador . "," . $obj->responsavel . "," . $obj->equipe . ")'><i class='fas fa-edit'></i></button>&nbsp;&nbsp;<button title='Excluir tarefa' class='btn btn-danger btn-sm' type='button' onclick='excluir(" . $obj->id . ",\"" . $obj->nome . "\")'><i class='far fa-trash-alt'></i></button>";;
        }
    } else {
        echo "  <a href='gerenciar_etapas.php?tarefa=" . $obj->id . "' class='btn btn-warning btn-sm' title='Gerenciar etapas'><i class='fa fa-bars'></i></a>";
        if ($editar || $clonar) {
            echo " &nbsp;&nbsp;<button title='Duplicar tarefa' class='btn btn-info btn-sm' type='button' onclick='duplicar(" . $obj->id . ",\"" . $obj->nome . "\",\"" . $obj->descricao . "\",\"" . $obj->categoria . "\",\"" . $obj->inicio . "\",\"" . $obj->fim . "\",\"" . $obj->tipo . "\"," . $obj->criador . "," . $obj->responsavel . "," . $obj->equipe . ")'><i class='fas fa-clone'></i></button>";
        }
        if ($editar) {
            echo " &nbsp;&nbsp;<button title='Alterar tarefa' class='btn btn-primary btn-sm' type='button' onclick='alterar(" . $obj->id . ",\"" . $obj->nome . "\",\"" . $obj->descricao . "\",\"" . $obj->categoria . "\",\"" . $obj->inicio . "\",\"" . $obj->fim . "\",\"" . $obj->tipo . "\"," . $obj->criador . "," . $obj->responsavel . "," . $obj->equipe . ")'><i class='fas fa-edit'></i></button>&nbsp;&nbsp;<button title='Excluir tarefa' class='btn btn-secondary btn-sm' type='button' title='Possuí dependências!'><i class='far fa-trash-alt' alt='Possuí dependências!'></i></button>";
        }
    }
    echo "</td>";
    echo "</tr>";
}


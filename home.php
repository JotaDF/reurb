<?php
date_default_timezone_set('America/Sao_Paulo');

include_once('actions/ManterTarefa.php');
$mTarefa = new ManterTarefa();
$painel = $mTarefa->getPainelTarefa($usuario_logado);
$painel_concluidas = $mTarefa->getPainelTarefaConcluidas($usuario_logado);
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Informações</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Todas as tarefas</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <a href="tarefas.php" class="btn btn-light btn-lg align-top"><strong><?= $painel->total ?></strong></a> 
                                <?= $painel->total > 0 && ($painel->total - $painel_concluidas->total) == 0 ? '<sup><i class="fa fa-check-square text-success" aria-hidden="true"></i></sup>' : '<sup><small><span class="badge badge-danger text-white align-middle">' . ($painel->total - $painel_concluidas->total) . '</span></small></sup>' ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-tasks fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tarefas da equipe</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <a href="tarefas.php?filtro=equipe" class="btn btn-light btn-lg align-top"><strong><?= $painel->total_equipe ?></strong></a> 
                                <?= $painel->total_equipe > 0 && ($painel->total_equipe - $painel_concluidas->total_equipe) == 0 ? '<sup><i class="fa fa-check-square text-success" aria-hidden="true"></i></sup>' : '<sup><small><span class="badge badge-danger text-white align-middle">' . ($painel->total_equipe - $painel_concluidas->total_equipe) . '</span></small></sup>' ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
//                             require_once('./ws/wsmoodle.php');
//                             $moodle = new WSMoodle();
//                             $courses = $moodle->getTotalCourses();
//                             $users = $moodle->getTotalUsers();
        ?>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Minhas Tarefas</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <a href="tarefas.php?filtro=criador" class="btn btn-light btn-lg align-top"><strong><?= $painel->total_criador ?></strong></a>
                                <?= $painel->total_criador > 0 && ($painel->total_criador - $painel_concluidas->total_criador) == 0 ? '<sup><i class="fa fa-check-square text-success" aria-hidden="true"></i></sup>' : '<sup><small><span class="badge badge-danger text-white align-middle">' . ($painel->total_criador - $painel_concluidas->total_criador) . '</span></small></sup>' ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Atribuídas a mim</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h4 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <a href="tarefas.php?filtro=responsavel" class="btn btn-light btn-lg align-top"><strong><?= $painel->total_responsavel ?></strong></a>
                                        <?= $painel->total_responsavel > 0 && ($painel->total_responsavel - $painel_concluidas->total_responsavel) == 0 ? '<sup><i class="fa fa-check-square text-success" aria-hidden="true"></i></sup>' : '<sup><small><span class="badge badge-danger text-white align-middle">' . ($painel->total_responsavel - $painel_concluidas->total_responsavel) . '</span></small></sup>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-address-book fa-4x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Content Row -->

    <div class="row">
        <?php
        $categorias = array("Pessoal","INAS","DIAD","UTIC"); 
        
        $cat = isset($_REQUEST['categoria']) ? $_REQUEST['categoria'] : '';
        //echo ' ANO: '.$ano.'<br/>';
        $txt_cat = "<small>";
        foreach ($categorias as $i => $value) {
            if($categorias[$i] == $cat){
                $txt_cat .= " <b>[".$categorias[$i]."]</b> ";
            }else{
                $txt_cat .= " [<a href='index.php?categoria=" . $categorias[$i]. "'>".$categorias[$i]."</a>] ";
            }
        }
        $txt_cat .= "</small>";
        
        //echo $date->getTimestamp();
        
        //$ano_inicio  = mktime (0, 0, 0, 1  , 1, $ano);
        //$ano_fim  = mktime (0, 0, 0, 12  , 31 , $ano);
//        echo ' INICIO: ' . date('d/m/Y', $date_inicio->getTimestamp());
//        echo ' FIM: ' . date('d/m/Y', $date_fim->getTimestamp());
//        echo '<br/>';
//        exit;
        $where_cat = " ";
        if(isset($cat) && $cat != ""){
            $where_cat = " categoria = '".$cat."'  AND ";
        }
        $tarefas_responsavel = $mTarefa->listar(" WHERE ".$where_cat." t.id_responsavel=" . $usuario_logado->id);
        $tarefas_criador = $mTarefa->listar(" WHERE ".$where_cat." t.id_criador=" . $usuario_logado->id);

        if ($painel->total_responsavel > 0 || $painel->total_criador) {

            $listar = isset($_REQUEST['listar']) ? $_REQUEST['listar'] : 'todas';
            $txt_extra_titulo = "<small>(Todas)</small>";
            switch ($listar) {
                case 'pendentes':
                    $txt_extra_titulo = "<small>(Pendentes)</small>";
                    break;
                case 'concluidas':
                    $txt_extra_titulo = "<small>(Concluídas)</small>";
                    break;
            }
            ?>
            <!-- Project Card Example -->
            <div class="ml-3 mr-3 card shadow mb-4 border-dark" style="width: 100%">
                <div class="row card-header py-2 card-body bg-dark align-middle ml-0" style="width:100%">               
                    <span class="h5 m-0 font-weight text-white"></span>
                    <div class="col-sm ml-0" style="max-width:50px;">
                        <i class="fa fa-tasks fa-lg text-white"></i> 
                    </div>
                    <div class="col mb-0">
                        <span style="align:left;" class="h6 m-0 font-weight text-white">Tarefas Importantes <?= $txt_extra_titulo ?> <?=$txt_cat ?></span>
                    </div>
                    <div class="col text-right" style="min-width:60%">
                        <a class="btn btn-outline-light btn-sm" href="index.php?categoria=<?=$cat ?>">  
                            <i class="fa fa-bars" aria-hidden="true"></i> Todas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </a>

                        <a class="btn btn-outline-light btn-sm" href="index.php?listar=pendentes&categoria=<?=$cat ?>">
                            <i class="fa fa-minus-square" aria-hidden="true"></i> Pendentes
                        </a>

                        <a class="btn btn-outline-light btn-sm" href="index.php?listar=concluidas&categoria=<?=$cat ?>">
                            <i class="fa fa-check-square" aria-hidden="true"></i> Concluídas
                        </a>                                                         
                    </div>
                </div>
                <?php
                if ($painel->total_responsavel > 0) {
                    ?>
                    <div class="row ml-4 my-2 mr-3 border-bottom border-dark">
                    <span class="float-right">
                            <a class="btn btn-sm" data-toggle="collapse" href="#collapseTarefasAtribuidas" role="button" aria-expanded="false" aria-controls="collapseTarefasAtribuidas">
                            <i class="fa fa-tasks fa-lg"></i> <strong> Atribuídas a mim</strong>
                            </a>
                        </span>
                        
                    </div>

                    <div class="collapse show" id="collapseTarefasAtribuidas">
                        <div class="card-body card-deck">
                            <?php
                            foreach ($tarefas_responsavel as $obj) {
                                $percentual = round($mTarefa->getPercentualTarefaPorId($obj->id), 1);
                                $islistar = true;
                                switch ($listar) {
                                    case 'pendentes':
                                        $islistar = ($percentual < 100);
                                        break;
                                    case 'concluidas':
                                        $islistar = ($percentual == 100);
                                        break;
                                }
                                if ($islistar) {
                                    $bg_progress = "bg-success";
                                    if ($percentual <= 50.0) {
                                        $bg_progress = "bg-danger";
                                    } else if ($percentual > 50.0 && $percentual <= 99.0) {
                                        $bg_progress = "bg-warning";
                                    }
                                    $txt_extra = "Início: " . date('d/m/Y', strtotime($obj->inicio)) . " Fim: " . date('d/m/Y', strtotime($obj->fim));
                                    ?>
                                    <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                                        <div class="card-header"><span class="float-right"><a href='gerenciar_etapas.php?tarefa=<?= $obj->id ?>' class='badge bg-dark text-white' title='Gerenciar etapa'><i class='fa fa-cog fa-2x'></i></a></span><strong><?= $obj->nome ?></strong></div>
                                        <div class="card-body">
                                            <p class="card-text"><?= $obj->descricao ?> <br/><br/><?= $txt_extra ?></p>
                                        </div>
                                        <div class="card-footer">
                                            <div class="progress mb-3">
                                                <div class="progress-bar <?= $bg_progress ?>" role="progressbar" style="width: <?= $percentual ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?= $percentual ?>%</div>   
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                if ($painel->total_criador > 0) {
                    ?>

                    <div class="row ml-4 my-0 mr-3 border-bottom border-dark">
                        <span class="float-right">
                            <a class="btn btn-sm" data-toggle="collapse" href="#collapseMinhasTarefas" role="button" aria-expanded="false" aria-controls="collapseMinhasTarefas">
                            <i class="fa fa-tasks fa-lg"></i> <strong> Minhas tarefas </strong>
                            </a>
                        </span>
                        
                    </div>
                    <div class="collapse" id="collapseMinhasTarefas">
                        <div class="card-body card-deck">
                            <?php
                            foreach ($tarefas_criador as $obj) {
                                $percentual = round($mTarefa->getPercentualTarefaPorId($obj->id), 1);
                                $islistar = true;
                                switch ($listar) {
                                    case 'pendentes':
                                        $islistar = ($percentual < 100);
                                        break;
                                    case 'concluidas':
                                        $islistar = ($percentual == 100);
                                        break;
                                }
                                if ($islistar) {
                                    $bg_progress = "bg-success";
                                    if ($percentual <= 50.0) {
                                        $bg_progress = "bg-danger";
                                    } else if ($percentual > 50.0 && $percentual <= 99.0) {
                                        $bg_progress = "bg-warning";
                                    }
                                    $txt_extra = "Início: " . date('d/m/Y', strtotime($obj->inicio)) . " Fim: " . date('d/m/Y', strtotime($obj->fim));
                                    ?>
                                    <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                                        <div class="card-header"><span class="float-right"><a href='gerenciar_etapas.php?tarefa=<?= $obj->id ?>' class='badge bg-dark text-white' title='Gerenciar etapa'><i class='fa fa-cog fa-2x'></i></a></span><strong><?= $obj->nome ?></strong></div>
                                        <div class="card-body">
                                            <p class="card-text"><?= $obj->descricao ?> <br/><br/><?= $txt_extra ?></p>
                                        </div>
                                        <div class="card-footer">
                                            <div class="progress mb-3">
                                                <div class="progress-bar <?= $bg_progress ?>" role="progressbar" style="width: <?= $percentual ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?= $percentual ?>%</div>   
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
            <?php
        }
        ?>
    </div>
</div>
<!-- /.container-fluid -->
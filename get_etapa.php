<?php
include_once('actions/ManterEtapa.php');

$manterEtapa = new ManterEtapa();

$lista = $manterEtapa->listar($id_tarefa);
$total_etapas = count($lista);
?>
<div class="editar mb-4" style="max-width:800px">
    <div class="card border-left-info shadow pb-0 py-0">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="mb-0">
                        <form class="form-inline" id="form_etapa" action="save_etapa.php" method="post">
                            <input type="hidden" id="id_etapa" name="id"/>
                            <input type="hidden" id="tarefa_etapa" name="tarefa" value="<?= $id_tarefa ?>"/>
                            <input type="hidden" id="ordem_etapa" name="ordem" value="<?= ($total_etapas + 1) ?>"/>
                            <div class="row no-gutters align-items-center" style="width:100%">
                                <div class="col mr-2" style="width:75%">
                                    <div class="text-xs font-weight-bold text-uppercase ml-1 mb-2">ETAPA</div>
                                    <div class="mb-0 "><input style="width:100%" type="text" name="nome" class="form-control form-control-sm" id="nome_etapa" placeholder="etapa" required></div>
                                </div>
                                <div class="col mr-2" style="max-width:25%">
                                    <div class="text-xs font-weight-bold text-uppercase ml-1 mb-2">Data Início <small><sup>(Opcional)</sup></small></div>
                                    <input  style="width:160px;" type="date" name="data_base" class="form-control form-control-sm" id="data_base_etapa">
                                </div>
                                <div class="float-right align-bottom">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                                </div>
                            </div>
                        </form>   
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>   

<?php
echo '<div class="accordion border-bottom border-info" style="max-width:800px">';
foreach ($lista as $obj) {
    $id_etapa = $obj->id;
    if ($obj->data_base > 0) {
        $data_base =$obj->data_base;
    }
    
    ?>
    <div class="card border-info">
        <div class="card-header row bg-info" id="etapa<?= $obj->ordem ?>">
            <div class="col mb-0  align-top" style="max-width:10%">
                <span class="align-middle btn btn-link" data-toggle="collapse" data-target="#collapse<?= $obj->ordem ?>" aria-expanded="true" aria-controls="collapse<?= $obj->ordem ?>">
                    <i class="fas fa-random text-white"></i>
                </span>               
            </div>
            <div class="col mb-0 align-top" style="max-width:55%">
                <span class="text-white"><?= $obj->nome ?></span>
            </div>
            <div class="col mb-0 align-top" style="max-width:35%">
                <span class="text-white"><?= ($obj->data_base > 0 ? date('d/m/Y',$obj->data_base) : ' - ') ?></span>
            </div>
            <div class="editar col align-top text-right" style="max-width:15%">
               <?php
                if($obj->ordem >= 1 && $obj->ordem < $total_etapas){
                ?>
                <a class="text-white" href="muda_ordem_etapa.php?op=d&id=<?= $obj->id ?>&ordem=<?= $obj->ordem ?>&tarefa=<?= $id_tarefa ?>"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>&nbsp;
                <?php
                } 
                if($obj->ordem > 1 && $obj->ordem <= $total_etapas){
                ?>
                <a class="text-white" href="muda_ordem_etapa.php?op=s&id=<?= $obj->id ?>&ordem=<?= $obj->ordem ?>&tarefa=<?= $id_tarefa ?>"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <?php
                }
                ?>
                &nbsp;&nbsp;&nbsp;
                <span class="text-white" onclick="alterarEtapa('<?= $obj->id ?>', '<?= $obj->nome ?>', '<?= $obj->ordem ?>', '<?= date('Y-m-d',$obj->data_base) ?>')"><i class="fas fa-edit"></i></span>
                <?php
                if ($obj->excluir) {
                    ?>
                   &nbsp;<span class="text-white" onclick="excluirEtapa('<?= $obj->id ?>', '<?= $obj->nome ?>')"><i class="far fa-trash-alt"></i></span>                
                    <?php
                }
                ?>
            </div>
        </div>

        <div id="collapse<?= $obj->ordem ?>" class="collapse show" aria-labelledby="etapa<?= $obj->ordem ?>">
            <div class="card-body border-info">
                <!-- ETAPAS -->
                <?php include './get_acao.php'; ?>
                <!-- FIM ETAPAS -->
            </div>
        </div>
    </div>
    <?php
}
echo "</div>";

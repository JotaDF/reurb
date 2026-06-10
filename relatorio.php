<?php
require_once('./verifica_login.php');
?> 
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>GERENTE - Gerenciador de tarefas</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico" />
        <!------ Include the above in your HEAD tag ---------->

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

        <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js" integrity="sha384-THVO/sM0mFD9h7dfSndI6TS0PgAGavwKvB5hAxRRvc0o9cPLohB0wb/PTA7LdUHs" crossorigin="anonymous"></script>

        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>


        <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.min.js"></script>

        <style>
            body{
                font-size: small;
            }
            .nao-ver{
                display:none;
            }
        </style>
        <script type="text/javascript" class="init">
            function naover(classe) {
                $("." + classe).css("display", "none");
                //$('#btn_editar').toggleClass('fa-lock fa-unlock');
            }
            $(document).ready(function () {
                $("#btnExport").click(function () {
                    let table = $("#tarefas");
                    TableToExcel.convert(table[0], {// html code may contain multiple tables so here we are refering to 1st table tag
                        name: 'export.xlsx', // fileName you could use any name
                        sheet: {
                            name: 'Sheet 1' // sheetName
                        }
                    });
                });

                $("#btnPDF").click(function () {
                    savePDF();
                });

            });
//            function savePDF() {
//                var element = document.getElementById('tarefas');
//                //element.style.display = 'block'
//                var options = {margin:2, orientation: 'l', filename: 'my-file.pdf', format: 'a4'};
//                html2pdf(element, options);
//
//            }



        </script>
    </head>

    <body id="page-top">

        <?php
        include_once('actions/ManterTarefa.php');
        include_once('actions/ManterEquipe.php');
        include_once('actions/ManterUsuario.php');

        $mUsuario = new ManterUsuario();

        $mEquipe = new ManterEquipe();

        $mTarefa = new ManterTarefa();

        $categoria = isset($_REQUEST['categoria']) ? $_REQUEST['categoria'] : '';
        $tipo = isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '';
        $responsavel = isset($_REQUEST['responsavel']) ? $_REQUEST['responsavel'] : 0;
        $equipe = isset($_REQUEST['equipe']) ? $_REQUEST['equipe'] : 0;
        $inicio_insc = isset($_REQUEST['inicio_insc']) ? $_REQUEST['inicio_insc'] : 0;
        $termino_insc = isset($_REQUEST['termino_insc']) ? $_REQUEST['termino_insc'] : 0;
        $inicio = isset($_REQUEST['inicio']) ? $_REQUEST['inicio'] : 0;
        $termino = isset($_REQUEST['termino']) ? $_REQUEST['termino'] : 0;

        $where = " WHERE ";
        $cont_param = 0;
        if ($categoria != '') {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.categoria= '" . $categoria . "'";
            $cont_param++;
        }
        if ($tipo != '') {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.tipo= '" . $tipo . "'";
            $cont_param++;
        }
        if ($responsavel > 0) {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.id_responsavel= " . $responsavel;
            $cont_param++;
        }
        if ($equipe > 0) {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.id_equipe= " . $equipe;
            $cont_param++;
        }
        if ($inicio_insc != 0) {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.inicio_inscricao >=" . strtotime($inicio_insc);
            $cont_param++;
        }
        if ($termino_insc != 0) {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.fim_inscricao <=" . strtotime($termino_insc);
            $cont_param++;
        }
        if ($inicio != 0) {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.inicio >=" . strtotime($inicio);
            $cont_param++;
        }
        if ($termino != 0) {
            if ($cont_param > 0) {
                $where .= " AND ";
            }
            $where .= " t.termino <=" . strtotime($termino);
            $cont_param++;
        }

        $tarefas = array();
        if ($cont_param > 0) {
            $tarefas = $mTarefa->listarRelatorio($where);
        } else {
            $tarefas = $mTarefa->listar();
        }
        ?>
        <!-- Begin Page Content -->
        <div id="containerTarefa" class="container-fluid align-items-center" style="width:95%">
            <!-- Content Row -->

            <?php
            if (count($tarefas) > 0) {
                ?>

            <div id="containerTarefa" role="main" class="align-items-center" style="width:100%"><h2 class="text-center">Relatório de Tarefas</h2><img src="img/iconexcel.png" width="30" height="30" class="d-print-none" id="btnExport" />
                    <table class="table table-striped" id="tarefas">
                        <tr class="thead-dark">
                            <th class="header c0 text-nowrap text-center" style="width:50px;" scope="col"><i class="fa fa-minus-square text-white c0" onclick="naover('c0');" aria-hidden="true" title="Remover coluna"></i> Categoria</th>
                            <th class="header c1 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c1" onclick="naover('c1');" aria-hidden="true" title="Remover coluna"></i> Tipo</th>
                            <th class="header c2 text-nowrap text-center" style="" scope="col" style="width:50%"><i class="fa fa-minus-square text-white c2" onclick="naover('c2');" aria-hidden="true" title="Remover coluna"></i> Nome</th>
                            <th class="header c3 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c3" onclick="naover('c3');" aria-hidden="true" title="Remover coluna"></i> Início Insc.</th>
                            <th class="header c4 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c4" onclick="naover('c4');" aria-hidden="true" title="Remover coluna"></i> Fim Insc.</th>
                            <th class="header c5 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c5" onclick="naover('c5');" aria-hidden="true" title="Remover coluna"></i> Início</th>
                            <th class="header c6 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c6" onclick="naover('c6');" aria-hidden="true" title="Remover coluna"></i> Fim</th>
                            <th class="header c7 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c7" onclick="naover('c7');" aria-hidden="true" title="Remover coluna"></i> Responsável</th>
                            <th class="header c8 text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c8" onclick="naover('c8');" aria-hidden="true" title="Remover coluna"></i> Equipe</th>
                            <th class="header c9 lastcol text-nowrap text-center" style="" scope="col"><i class="fa fa-minus-square text-white c9" onclick="naover('c9');" aria-hidden="true" title="Remover coluna"></i> Situação</th>
                        </tr>
                        <?php
                        foreach ($tarefas as $obj) {
                            $percentual = round($mTarefa->getPercentualTarefaPorId($obj->id), 1);
                            $usuario = $mUsuario->getUsuarioPorId($obj->responsavel)->nome;
                            $nome = explode(" ", $usuario);
                            $bg_progress = "bg-success";
                            if ($percentual <= 50.0) {
                                $bg_progress = "bg-danger";
                            } else if ($percentual > 50.0 && $percentual <= 99.0) {
                                $bg_progress = "bg-warning";
                            }
                            ?>
                            <tr class="">
                                <td class="cell c0 text-dark text-center" style=""><?= $obj->categoria ?></td>
                                <td class="cell c1 text-dark text-center" style=""><?= $obj->tipo ?></td>
                                <td class="cell c2 text-dark" style=""><?= $obj->nome ?></td>
                                <td class="cell c3 text-dark text-center" style=""><?= ($obj->inicio_inscricao > 0 ? date('d/m/Y', strtotime($obj->inicio_inscricao)) : '-') ?></td>
                                <td class="cell c4 text-dark text-center" style=""><?= ($obj->fim_inscricao > 0 ? date('d/m/Y', strtotime($obj->fim_inscricao)) : '-') ?></td>
                                <td class="cell c5 text-dark text-center" style=""><?= date('d/m/Y', strtotime($obj->inicio)) ?></td>
                                <td class="cell c6 text-dark text-center" style=""><?= date('d/m/Y', strtotime($obj->fim)) ?></td>
                                <td class="cell c7 text-dark text-center" style=""><?= $nome[0] . " " . (strlen($nome[1]) > 2 ? $nome[1] : '') ?></td>
                                <td class="cell c8 text-dark text-center" style=""><?= ($obj->equipe > 0 ? $mEquipe->getEquipePorId($obj->equipe)->equipe : ' Todos ') ?></td>
                                <td class="cell c9 lastcol text-center" style="">
                                    <div class="progress">
                                        <div class="progress-bar <?= $bg_progress ?>" role="progressbar" style="width: <?= $percentual ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?= $percentual ?>%</div>   
                                    </div>
                                </td>
                            </tr>   
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>  
                <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</body>

</html>
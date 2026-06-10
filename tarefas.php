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

        <title>ENDC - Gerente de Tarefas</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico" />
        <!------ Include the above in your HEAD tag ---------->

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
               
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
        <script type="text/javascript" class="init">

            var categorias = [{id: "Pessoal", nome: "Pessoal"}, {id: "INAS", nome: "INAS"}, {id: "DIAD", nome: "DIAD"}, {id: "UTIC", nome: "UTIC"}];
            var tipos = [{id: "Desenvolvimento", nome: "Desenvolvimento"}, {id: "Suporte", nome: "Suporte"}, {id: "SEI", nome: "SEI"}, {id: "Serviços", nome: "Serviços"}, {id: "Outro", nome: "Outro"}];
            var equipes = [];
            var responsaveis = [];
<?php
include_once('actions/ManterEquipe.php');
$mEquipe = new ManterEquipe();

$pesquisa = isset($_REQUEST['filtro']) ? $_REQUEST['filtro'] : '';
$filtro = '';
$titulo = 'Tarefas';
switch ($pesquisa) {
    case 'equipe':
        $filtro = ' WHERE t.id_equipe=' . $usuario_logado->equipe;
        $titulo = 'Tarefas da equipe (' . $mEquipe->getEquipePorId($usuario_logado->equipe)->equipe . ')';
        break;
    case 'responsavel':
        $filtro = ' WHERE t.id_responsavel=' . $usuario_logado->id;
        $titulo = 'Tarefas que realizo';
        break;
    case 'criador':
        $filtro = ' WHERE t.id_criador=' . $usuario_logado->id;
        $titulo = 'Tarefas que criei';
        break;
}

include_once('actions/ManterEquipe.php');
include_once('actions/ManterUsuario.php');

$manterEquipe = new ManterEquipe();
$manterUsuario = new ManterUsuario();

$listaEquipe = $manterEquipe->listar();
$listaUsuario = $manterUsuario->listar();

foreach ($listaEquipe as $obj) {
    ?>item = {id: "<?= $obj->id ?>", nome: "<?= $obj->equipe ?>"};
                equipes.push(item);
    <?php
}

foreach ($listaUsuario as $obj) {
    ?>item2 = {id: "<?= $obj->id ?>", nome: "<?= $obj->nome ?>", equipe: "<?= $obj->equipe ?>"};
            responsaveis.push(item2);
    <?php
}
?>
        function carregaEquipes(id_atual) {
            var html = '<option value="0"> Todos </option>';
            for (var i = 0; i < equipes.length; i++) {
                var option = equipes[i];
                var selected = "";
                if (id_atual > 0) {
                    if (option.id == id_atual) {
                        selected = "selected";
                    } else {
                        selected = "";
                    }
                }
                html += '<option value="' + option.id + '" ' + selected + '>' + option.nome + '</option>';
            }
            $('#equipe').html(html);
        }

        function atualizaUsuarios(equipe) {
            var responsavel = $('#responsavel').val();
            if (responsavel > 0) {
                carregaUsuarios(responsavel, equipe);
            } else {
                carregaUsuarios(0, equipe);
            }
        }

        function carregaUsuarios(id_atual, equipe) {
            var html = '<option value="">Selecione </option>';
            for (var i = 0; i < responsaveis.length; i++) {
                var option = responsaveis[i];
                if (option.equipe == equipe || equipe == 0) {
                    var selected = "";
                    if (id_atual > 0) {
                        if (option.id == id_atual) {
                            selected = "selected";
                        } else {
                            selected = "";
                        }
                    }
                    html += '<option value="' + option.id + '" ' + selected + '>' + option.nome + '</option>';
                }
            }
            $('#responsavel').html(html);
        }

        function carregaCategorias(id_atual) {
            var html = '<option value="">Selecione </option>';
            for (var i = 0; i < categorias.length; i++) {
                var option = categorias[i];
                var selected = "";
                if (id_atual > 0) {
                    if (option.id == id_atual) {
                        selected = "selected";
                    } else {
                        selected = "";
                    }
                }
                html += '<option value="' + option.id + '" ' + selected + '>' + option.nome + '</option>';
            }
            $('#categoria').html(html);
        }
        function carregaTipos(id_atual) {
            var html = '<option value="">Selecione </option>';
            for (var i = 0; i < tipos.length; i++) {
                var option = tipos[i];
                var selected = "";
                if (id_atual != "") {
                    if (option.id == id_atual) {
                        selected = "selected";
                    } else {
                        selected = "";
                    }
                }
                html += '<option value="' + option.id + '" ' + selected + '>' + option.nome + '</option>';
            }
            $('#tipo').html(html);
        }

        $(document).ready(function () {
            $('#tarefas').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5'
                ]
            });

            carregaEquipes(0);
            carregaUsuarios(0, 0);
            carregaCategorias(0);
            carregaTipos(0);
            $(".curso").hide();
        });
        function excluir(id, nome) {
            $('#delete').attr('href', 'del_tarefa.php?id=' + id);
            $('#nome_excluir').text(nome);
            $('#confirm').modal({show: true});
        }
        function alterar(id, nome, descricao, categoria, inicio, fim, tipo, criador, responsavel, equipe) {
            //alert('Tipo: '+tipo);
            $('#id').val(id);
            $('#nome').val(nome);
            $('#descricao').val(descricao);
            $('#inicio').val(inicio);
            $('#termino').val(fim);

            carregaEquipes(equipe);
            carregaUsuarios(responsavel, equipe);
            carregaCategorias(categoria);
            carregaTipos(tipo);
            verificaTipo(tipo);
            $('#form_tarefa').collapse("show");
            $('#btn_cadastrar').hide();
        }

        function duplicar(id, nome, descricao, categoria, inicio, fim, tipo, criador, responsavel, equipe) {
            //alert('Tipo: '+tipo);
            $('#duplicar').val(1);
            $('#id').val(id);
            $('#nome').val(nome);
            $('#descricao').val(descricao);
            $('#inicio').val(inicio);
            $('#termino').val(fim);

            carregaEquipes(equipe);
            carregaUsuarios(responsavel, equipe);
            carregaCategorias(categoria);
            carregaTipos(tipo);
            verificaTipo(tipo);

            $('#form_tarefa').collapse("show");
            $('#btn_cadastrar').hide();
        }

        function cancelar() {
            $('#btn_cadastrar').show();

            carregaEquipes(0);
            carregaUsuarios(0, 0);
            carregaCategorias(0);

            $('#form_cadastro').reset();
        }
        function verificaTipo(tipo) {
            if (tipo == "Curso") {
                $(".curso").show();
            } else {
                $(".curso").hide();
            }
        }

        </script>
        <style>
            body{
                font-size: small;
            }
        </style>
    </head>

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">
            <?php include './menu.php'; ?>
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <?php include './top_bar.php'; ?>

                    <div class="container-fluid">
                        <?php
                        if ($usuario_logado->perfil <= 2) {
                            include './form_tarefa.php';
                        }
                        ?>
                        <!-- Project Card Example -->
                        <div class="card mb-4 border-info" style="max-width:1250px">
                            <div class="row ml-0 card-header py-2 bg-info" style="width:100%">
                                <div class="col-sm ml-0" style="max-width:50px;">
                                    <i class="fa fa-tasks fa-2x text-white"></i> 
                                </div>
                                <div class="col mb-0">
                                    <span style="align:left;" class="h5 m-0 font-weight text-white"><?= $titulo ?></span>
                                </div>
                                <div class="col text-right" style="max-width:20%">
                                    <?php
                                    if ($usuario_logado->perfil <= 2) {
                                        ?>
                                        <button id="btn_cadastrar" class="btn btn-outline-light btn-sm" type="button" data-toggle="collapse" data-target="#form_tarefa" aria-expanded="false" aria-controls="form_tarefa">
                                            <i class="fa fa-plus-circle text-white" aria-hidden="true"></i>
                                        </button>                            
                                        <?php
                                    }
                                    ?>                                    
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tarefas" class="table-sm table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <!--th scope="col">ID</th -->
                                            <th scope="col" style="width:20px;">Categoria</th>
                                            <th scope="col" style="width:20px;">Tipo</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col" style="width:20px;">Início</th>
                                            <th scope="col" style="width:20px;">Fim</th>
                                            <!--th scope="col">Equipe</th>
                                            <th scope="col">Responsável</th-->
                                            <th scope="col" style="width:20px;">Situação</th>
                                            <th scope="col" style="width:30px;">Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include './get_tarefa.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End of Main Content -->
                </div> 
                <?php include './rodape.php'; ?>

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <!-- Modal excluir -->
        <div class="modal fade" id="confirm" role="dialog">
            <div class="modal-dialog modal-sm">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja excluir <strong>"<span id="nome_excluir"></span>"</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" type="button" class="btn btn-danger" id="delete">Excluir</a>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancelar</button>
                    </div>
                </div>

            </div>
        </div>

    </body>

</html>

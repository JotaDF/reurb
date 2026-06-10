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
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script type="text/javascript" class="init">

            var equipes = [];
            var perfis = [];
<?php
include_once('actions/ManterEquipe.php');
include_once('actions/ManterPerfil.php');

$manterEquipe = new ManterEquipe();
$manterPerfil = new ManterPerfil();

$listaE = $manterEquipe->listar();
$listaP = $manterPerfil->listar();

foreach ($listaE as $obj) {
    ?>item = {id: "<?= $obj->id ?>", equipe: "<?= $obj->equipe ?>"};
                equipes.push(item);
    <?php
}
foreach ($listaP as $obj) {
    if ($obj->id >= $usuario_logado->perfil) {
        ?>item = {id: "<?= $obj->id ?>", perfil: "<?= $obj->perfil ?>"};
                perfis.push(item);
        <?php
    }
}
?>
        function carregaEquipes(id_atual) {
            var html = '<option value="">Selecione </option>';
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
                html += '<option value="' + option.id + '" ' + selected + '>' + option.equipe + '</option>';
            }
            $('#equipe').html(html);
        }

        function carregaPerfis(id_atual) {
            var html = '<option value="">Selecione </option>';
            for (var i = 0; i < perfis.length; i++) {
                var option = perfis[i];
                var selected = "";
                if (id_atual > 0) {
                    if (option.id == id_atual) {
                        selected = "selected";
                    } else {
                        selected = "";
                    }
                }
                html += '<option value="' + option.id + '" ' + selected + '>' + option.perfil + '</option>';
            }
            $('#perfil').html(html);
        }

        $(document).ready(function () {
            $('#usuarios').DataTable();
            $('#id').val(0);
            carregaEquipes(0);
            carregaPerfis(0);
        });
        function excluir(id, nome) {
            $('#delete').attr('href', 'del_usuario.php?id=' + id);
            $('#nome_excluir').text(nome);
            $('#confirm').modal({show: true});
        }
        function alterar(id, cpf, nome, senha, email, ativo, id_equipe, id_perfil) {
            $('#id').val(id);
            $('#cpf').val(cpf);
            $('#nome').val(nome);
            $('#senha').val(senha);
            $('#email').val(email);
            $('#ativo').val(ativo);
            if (ativo == 1) {
                $('#ativo').prop('checked', true);
            } else {
                $('#ativo').prop('checked', false);
            }
            carregaEquipes(id_equipe);
            carregaPerfis(id_perfil);
            $('#form_usuario').collapse("show");
            $('#btn_cadastrar').hide();
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
                        <?php include './form_usuario.php'; ?>
                        <!-- Project Card Example -->
                        <div class="card mb-4 border-info" style="max-width:900px">
                            <div class="row ml-0 card-header py-2 bg-info" style="width:100%">
                                <div class="col-sm ml-0" style="max-width:50px;">
                                    <i class="fas fa-user fa-2x text-white"></i> 
                                </div>
                                <div class="col mb-0">
                                    <span style="align:left;" class="h5 m-0 font-weight text-white">Usuários</span>
                                </div>
                                <div class="col text-right" style="max-width:20%">
                                    <button id="btn_cadastrar" class="btn btn-outline-light btn-sm" type="button" data-toggle="collapse" data-target="#form_usuario" aria-expanded="false" aria-controls="form_usuario">
                                        <i class="fa fa-plus-circle text-white" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="usuarios" class="table-sm table-striped table-bordered dt-responsive wrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:5%;">ID</th>
                                            <th scope="col" style="width:20%;">CPF</th>
                                            <th scope="col" style="width:35%;">Nome</th>
                                            <th scope="col">Equipe</th>
                                            <th scope="col">Perfil</th>
                                            <th scope="col">Ativo</th>
                                            <th scope="col" class="align-middle nowrap" style="width:15%;">Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include './get_usuario.php'; ?>
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

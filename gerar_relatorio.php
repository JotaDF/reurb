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

        <title>ENDC - Secretaria Acadêmica</title>

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
        <script type="text/javascript" class="init">

            var categorias = [{id: "2019", nome: "2019"}, {id: "2020", nome: "2020"}];
            var tipos = [{id: "Curso", nome: "Curso"}, {id: "Módulo", nome: "Módulo"}, {id: "Correção", nome: "Correção"}, {id: "Desenvolvimento", nome: "Desenvolvimento"}, {id: "Design", nome: "Design"}];
            var equipes = [];
            var responsaveis = [];
<?php
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
            var html = '<option value="">indiferente</option>';
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

//        function atualizaUsuarios(equipe) {
//            var responsavel = $('#responsavel').val();
//            if (responsavel > 0) {
//                carregaUsuarios(responsavel, equipe);
//            } else {
//                carregaUsuarios(0, equipe);
//            }
//        }

        function carregaUsuarios(id_atual, equipe) {
            var html = '<option value="">indiferente</option>';
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
            var html = '<option value="">indiferente</option>';
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
            var html = '<option value="">indiferente</option>';
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
            carregaEquipes(0);
            carregaUsuarios(0, 0);
            carregaCategorias(0);
            carregaTipos(0);
            verificaTipo(0);
        });

        function cancelar() {
            carregaEquipes(0);
            carregaUsuarios(0, 0);
            carregaCategorias(0);
            verificaTipo(0);
            
            $('#form_relatorio').reset();
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
                        include './form_relatorio.php';
                        ?>
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
    </body>

</html>

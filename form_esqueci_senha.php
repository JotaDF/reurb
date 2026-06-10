
<!DOCTYPE html>
<html lang="pt-br">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Gerente - Esqueci senha</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link rel="shortcut icon" href="favicon.ico" />
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico" />
        <?php
        $mensagem = isset($_REQUEST["msg"]) ? $_REQUEST["msg"] : 3;
        $erro = "";
        if ($mensagem == 0) {
            $erro = "onload='$(\"#alerta_n_encontrado\").collapse(\"show\");'";
        } else if ($mensagem == 1) {
            $erro = "onload='$(\"#alerta\").collapse(\"show\");'";
        } else if ($mensagem == 2) {
            $erro = "onload='$(\"#alerta_erro\").collapse(\"show\");'";
        }
        ?>
    </head>

    <body <?= $erro ?> class="bg-dark">

        <div class="container justify-content-center">

            <!-- Exibe form de login -->
            <div class="card mt-5 mb-3 border-warning" style="max-width: 500px;">
                <div class="card-body bg-primary bg-warning" style="min-height: 5.0rem;">
                    <div class="row">
                        <div class="col c2 ml-2">                     
                            <div class="h5 mb-0  text-dark font-weight-bold">Lembrar senha</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-envelope fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-success collapse fade hide" id="alerta">
                        <span id="notificacao">Senha enviada para seu e-mail!</span><br/>
                        <a href="form_login.php"><span id="notificacao">Ir para págiina de login</span></a>
                        <button type="button" class="close" data-toggle="collapse" data-target="#alerta" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger collapse fade hide" id="alerta_erro">
                        <span id="notificacao">Esse usuário está desativado. Procure o coordenador!</span>
                        <button type="button" class="close" data-toggle="collapse" data-target="#alerta_erro" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger collapse fade hide" id="alerta_n_encontrado">
                        <span id="notificacao">Usuário não encontrado!</span>
                        <button type="button" class="close" data-toggle="collapse" data-target="#alerta_n_encontrado" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_login" action="lembrar_senha.php" method="post">
                        <div class="row">
                            <div class="col c2 ml-2"> 
                                <label for="cpf" class="col-sm-2 col-form-label">CPF:</label>
                                <div class="col">
                                    <input type="text" name="cpf" class="form-control form-control-sm" id="cpf" onkeypress="$(this).mask('000.000.000-00');" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">                           
                            <div class="my-3 col c0 ml-2">   
                                <div class="col c1">
                                    <button type="submit" class="btn btn-warning btn-block text-dark">
                                        Enviar
                                    </button>
                                    <div class="col">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- fim da exibição -->               


        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

    </body>

</html>

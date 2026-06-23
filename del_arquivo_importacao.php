<?php

$arquivo = isset($_REQUEST['file']) ? $_REQUEST['file'] : "";
$pasta = isset($_REQUEST['pasta']) ? $_REQUEST['pasta'] : "";



if ($pasta != "") {
    $caminho = "arquivos/" . $pasta . "/";
	echo $caminho;
    $filePath = $caminho . $arquivo;

    if (file_exists($filePath)) {
        unlink($filePath);
        echo "Arquivo arquivo excluído com sucesso!";
    } else {
        echo "Arquivo não encontrado.";
    }
}

//header('Location: gerenciar_arquivos_importacao.php');

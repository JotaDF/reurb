<?php
session_start();

$editar = isset($_REQUEST['editar']) ? $_REQUEST['editar'] : 0;
if ($editar==1) {
    if ($_SESSION['editar']==1) {
        $_SESSION['editar']=0;
    }  else {
        $_SESSION['editar']=1;
    }
}




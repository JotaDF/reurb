<?php

include_once('Model.php');

class Login extends Model {

    public function __construct() {
        parent::__construct();
    }

    function logar($cpf, $senha) {
        $sql = "SELECT id, nome, cpf, senha, id_perfil, id_equipe, ativo FROM usuario   WHERE cpf='" . $cpf . "' AND ativo=1";
        //echo 'SQL:'.$sql;
        $resultado = $this->db->Execute($sql);
        if ($registro = $resultado->fetchRow()) {
            if (!strcmp($registro["senha"], $senha)) {
                $dados = new Usuario();
                $dados->id          = $registro["id"];
                $dados->nome        = $registro["nome"];
                $dados->cpf         = $registro["cpf"];
                $dados->senha       = $registro["senha"];
                $dados->ativo       = $registro["ativo"];
                $dados->equipe      = $registro["id_equipe"];
                $dados->perfil      = $registro["id_perfil"];
                
                return $dados; 
            } else {
                return false;
            }
        }
        return false;
    }

}

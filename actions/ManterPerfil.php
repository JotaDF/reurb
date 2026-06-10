<?php

require_once('Model.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/samj/dto/Perfil.php');
require_once('dto/Perfil.php');

class ManterPerfil extends Model {

    public function __construct() { //metodo construtor
        parent::__construct();
    }

    function listar() {
        $sql = "select p.id,p.perfil,p.descricao, (select count(*) from usuario as u where u.id_perfil=p.id) as dep FROM perfil as p order by p.id";
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        while ($registro = $resultado->fetchRow()) {
            $dados = new Perfil();
            $dados->excluir = true;
            if ($registro["dep"] > 0) {
                $dados->excluir = false;
            }
            $dados->id          = $registro["id"];
            $dados->perfil = utf8_encode($registro["perfil"]);
            $dados->descricao = utf8_encode($registro["descricao"]);
            $array_dados[] = $dados;
        }
        return $array_dados;
    }
    function getPerfilPorId($id) {
        $sql = "select p.id,p.perfil,p.descricao FROM perfil as p WHERE id=$id";
        //echo $sql;
        $resultado = $this->db->Execute($sql);
        $dados = new Perfil();
        while ($registro = $resultado->fetchRow()) {
            $dados->id          = $registro["id"];
            $dados->perfil = utf8_encode($registro["perfil"]);
            $dados->descricao = utf8_encode($registro["descricao"]);
        }
        return $dados;
    }
    function salvar(Perfil $dados) {
        $dados->perfil = utf8_decode($dados->perfil);
        $dados->descricao = utf8_decode($dados->descricao);
        $sql = "insert into perfil (perfil,descricao) values ('" . $dados->perfil . "','" . $dados->descricao . "')";
        if ($dados->id > 0) {
            $sql = "update perfil set perfil='" . $dados->perfil . "',descricao='" . $dados->descricao . "' where id=$dados->id";
            $resultado = $this->db->Execute($sql);
        } else {
            $resultado = $this->db->Execute($sql);
            $dados->id = $this->db->insert_Id();
        }
        return $resultado;
    }

    function excluir($id) {
        $sql = "delete from perfil where id=" . $id;
        $resultado = $this->db->Execute($sql);
        return $resultado;
    }

}


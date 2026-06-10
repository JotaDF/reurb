<?php

require_once('Model.php');
require_once('dto/Etapa.php');

class ManterEtapa extends Model {

    function ManterEtapa() { //metodo construtor
        parent::model();
    }

    function listar($id_tarefa = 0) {

        $sql = "select e.id,e.nome,e.ordem,e.data_base,e.id_tarefa,(SELECT SUM(aa.qtd_dias) FROM acao as aa WHERE aa.id_etapa=e.id ) as total_dias,(select count(*) from acao as a where a.id_etapa=e.id) as dep FROM etapa as e order by e.ordem";
        if ($id_tarefa > 0) {
            $sql = "select e.id,e.nome,e.ordem,e.data_base,e.id_tarefa,(SELECT SUM(aa.qtd_dias) FROM acao as aa WHERE aa.id_etapa=e.id ) as total_dias,(select count(*) from acao as a where a.id_etapa=e.id) as dep FROM etapa as e WHERE id_tarefa=" . $id_tarefa . " order by e.ordem";
        }

        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        while ($registro = $resultado->fetchRow()) {
            $dados = new Etapa();
            $dados->excluir = true;
            if ($registro["dep"] > 0) {
                $dados->excluir = false;
            }
            $dados->id = $registro["id"];
            $dados->nome = utf8_encode($registro["nome"]);
            $dados->ordem = $registro["ordem"];
            $dados->data_base = $registro["data_base"];
            $dados->tarefa = $registro["id_tarefa"];
            $dados->total_dias = $registro["total_dias"];

            $array_dados[] = $dados;
        }
        return $array_dados;
    }

    function getEtapaPorId($id) {
        $sql = "select e.id,e.nome,e.ordem,e.data_base,e.id_tarefa,(SELECT SUM(aa.qtd_dias) FROM acao as aa, etapa as ee WHERE aa.id_etapa=e.id ) as total_dias FROM etapa as e WHERE id=$id";
        //echo $sql;
        $resultado = $this->db->Execute($sql);
        $dados = new Etapa();
        while ($registro = $resultado->fetchRow()) {
            $dados->id = $registro["id"];
            $dados->nome = utf8_encode($registro["nome"]);
            $dados->ordem = $registro["ordem"];
            $dados->data_base = $registro["data_base"];
            $dados->tarefa = $registro["id_tarefa"];
            $dados->total_dias = $registro["total_dias"];
        }
        return $dados;
    }

    function salvar(Etapa $dados) {
        $dados->nome = utf8_decode($dados->nome);
        $sql = "insert into etapa (nome, ordem, data_base, id_tarefa) values ('" . $dados->nome . "','" . $dados->ordem . "','" . $dados->data_base . "','" . $dados->tarefa . "')";
        //echo $sql . "<BR/>";
        if ($dados->id > 0) {
            $sql = "update etapa set nome='" . $dados->nome . "',ordem='" . $dados->ordem . "',data_base='" . $dados->data_base . "',id_tarefa='" . $dados->tarefa . "' where id=$dados->id";
            $resultado = $this->db->Execute($sql);
        } else {
            $resultado = $this->db->Execute($sql);
            $dados->id = $this->db->insert_Id();
        }
        //echo $sql . "<BR/>";
        return $resultado;
    }

    function excluir($id) {
        $sql = "delete from etapa where id=" . $id;
        $resultado = $this->db->Execute($sql);
        return $resultado;
    }

    function sobeOrdem($id, $id_tarefa, $ordem_atual) {
        $sql_desc = "update etapa set ordem=(ordem+1) where ordem=" . ($ordem_atual - 1) . " AND id_tarefa=" . $id_tarefa;
        $sql_sobe = "update etapa set ordem=" . ($ordem_atual - 1) . " where id=$id";

        $resultado = $this->db->Execute($sql_desc);
        $resultado = $this->db->Execute($sql_sobe);
        //echo $sql . "<BR/>";
        return $resultado;
    }

    function desceOrdem($id, $id_tarefa, $ordem_atual) {
        $sql_sobe = "update etapa set ordem=(ordem-1) where ordem=" . ($ordem_atual + 1) . " AND id_tarefa=" . $id_tarefa;
        $sql_desc = "update etapa set ordem=" . ($ordem_atual + 1) . " where id=$id";

        $resultado = $this->db->Execute($sql_sobe);
        $resultado = $this->db->Execute($sql_desc);
        //echo $sql . "<BR/>";
        return $resultado;
    }

}

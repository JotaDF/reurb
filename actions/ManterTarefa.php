<?php

date_default_timezone_set('America/Sao_Paulo');
require_once('Model.php');

require_once('ManterEtapa.php');

require_once('dto/Tarefa.php');
require_once('dto/Usuario.php');

class ManterTarefa extends Model {

    function ManterTarefa() { //metodo construtor
        parent::model();
    }

    function listar($filtro = "") {
        $sql = "select id,nome,descricao,categoria,inicio,fim,tipo,id_criador,id_responsavel,id_equipe,(SELECT SUM(aa.qtd_dias) FROM acao as aa, etapa as ee, tarefa as tt WHERE aa.id_etapa=ee.id AND ee.id_tarefa=tt.id AND tt.id = t.id) as total_dias,(select count(*) from etapa as e where e.id_tarefa=t.id) as dep FROM tarefa as t " . $filtro . " ORDER BY inicio desc";
        //echo 'SQL: ' . $sql;
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        while ($registro = $resultado->fetchRow()) {
            $dados = new Tarefa();
            $dados->excluir = true;
            if ($registro["dep"] > 0) {
                $dados->excluir = false;
            }
            $dados->id = $registro["id"];
            $dados->nome = utf8_encode($registro["nome"]);
            $dados->descricao = utf8_encode($registro["descricao"]);
            $dados->categoria = utf8_encode($registro["categoria"]);
            $dados->tipo = utf8_encode($registro["tipo"]);
            $dados->inicio = date('Y-m-d', $registro["inicio"]);
            $dados->fim = date('Y-m-d', $registro["fim"]);
            $dados->criador = $registro["id_criador"];
            $dados->responsavel = $registro["id_responsavel"];
            $dados->equipe = $registro["id_equipe"];
            $dados->total_dias = $registro["total_dias"];

            $array_dados[] = $dados;
        }
        return $array_dados;
    }
        function listarRelatorio($filtro = "") {
        $sql = "select id,nome,descricao,categoria,inicio,fim,tipo,id_criador,id_responsavel,id_equipe FROM tarefa as t " . $filtro . " ORDER BY inicio_inscricao, inicio, nome";
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        while ($registro = $resultado->fetchRow()) {
            $dados = new Tarefa();
            $dados->id = $registro["id"];
            $dados->nome = utf8_encode($registro["nome"]);
            $dados->descricao = utf8_encode($registro["descricao"]);
            $dados->categoria = utf8_encode($registro["categoria"]);
            $dados->tipo = utf8_encode($registro["tipo"]);
            $dados->inicio = date('Y-m-d', $registro["inicio"]);
            $dados->fim = date('Y-m-d', $registro["fim"]);
            $dados->criador = $registro["id_criador"];
            $dados->responsavel = $registro["id_responsavel"];
            $dados->equipe = $registro["id_equipe"];

            $array_dados[] = $dados;
        }
        return $array_dados;
    }

    function getTarefaPorId($id) {
        $sql = "select id,nome,descricao,categoria,inicio,fim,tipo,id_criador,id_responsavel,id_equipe, (SELECT SUM(aa.qtd_dias) FROM acao as aa, etapa as ee, tarefa as tt WHERE aa.id_etapa=ee.id AND ee.id_tarefa=tt.id AND tt.id = t.id) as total_dias FROM tarefa as t WHERE id=$id";
        //echo $sql;
        $resultado = $this->db->Execute($sql);
        $dados = new Tarefa();
        while ($registro = $resultado->fetchRow()) {
            $dados->id = $registro["id"];
            $dados->nome = utf8_encode($registro["nome"]);
            $dados->descricao = utf8_encode($registro["descricao"]);
            $dados->categoria = utf8_encode($registro["categoria"]);
            $dados->tipo = utf8_encode($registro["tipo"]);
            $dados->inicio = date('Y-m-d', $registro["inicio"]);
            $dados->fim = date('Y-m-d', $registro["fim"]);
            $dados->criador = $registro["id_criador"];
            $dados->responsavel = $registro["id_responsavel"];
            $dados->equipe = $registro["id_equipe"];
            $dados->total_dias = $registro["total_dias"];
        }
        return $dados;
    }

    function salvar(Tarefa $dados) {
        $dados->nome = utf8_decode($dados->nome);
        $dados->descricao = utf8_decode($dados->descricao);
        $dados->categoria = utf8_decode($dados->categoria);
        $dados->tipo = utf8_decode($dados->tipo);
        $sql = "insert into tarefa (nome,descricao,categoria,inicio,fim,tipo,id_criador,id_responsavel,id_equipe) values ('" . $dados->nome . "','" . $dados->descricao . "','" . $dados->categoria . "','" . $dados->inicio . "','" . $dados->fim . "','" . $dados->tipo . "'," . $dados->criador . "," . $dados->responsavel . "," . $dados->equipe . ")";
        //echo $sql . "<BR/>";
        if ($dados->id > 0) {
            $sql = "update tarefa set nome='" . $dados->nome . "',descricao='" . $dados->descricao . "',categoria='" . $dados->categoria . "',inicio='" . $dados->inicio . "',fim='" . $dados->fim . "',tipo='" . $dados->tipo . "',id_criador=" . $dados->criador . ",id_responsavel=" . $dados->responsavel . ",id_equipe=" . $dados->equipe . " where id=$dados->id";
            $resultado = $this->db->Execute($sql);
        } else {
            $resultado = $this->db->Execute($sql);
            $dados->id = $this->db->insert_Id();
        }
        return $resultado;
    }

    function duplicar(Tarefa $dados) {
        $dados->nome = utf8_decode($dados->nome);
        $dados->descricao = utf8_decode($dados->descricao);
        $dados->categoria = utf8_decode($dados->categoria);
        $dados->tipo = utf8_decode($dados->tipo);
        $sql = "insert into tarefa (nome,descricao,categoria,inicio,fim,tipo,id_criador,id_responsavel,id_equipe) values ('" . $dados->nome . "','" . $dados->descricao . "','" . $dados->categoria . "','" . $dados->inicio . "','" . $dados->fim . "','" . $dados->tipo . "'," . $dados->criador . "," . $dados->responsavel . "," . $dados->equipe . ")";
        //echo $sql . "<BR/>";
        $id_duplicar = $dados->id;
        $resultado = $this->db->Execute($sql);
        $dados->id = $this->db->insert_Id();

        $sql_busca_etapas = "select e.id,e.nome,e.ordem,e.id_tarefa FROM etapa as e WHERE e.id_tarefa=" . $id_duplicar . " order by e.ordem";
        $rs_etapas = $this->db->Execute($sql_busca_etapas);
        while ($reg_etapa = $rs_etapas->fetchRow()) {
            $sql_insert_etapa = "insert into etapa (nome, ordem, id_tarefa) values ('" . $reg_etapa["nome"] . "','" . $reg_etapa["ordem"] . "','" . $dados->id . "')";
            $rs_insert_etapa = $this->db->Execute($sql_insert_etapa);
            $id_etapa = $this->db->insert_Id();

            $sql_busca_acoes = "select a.id,a.acao,a.ordem,a.data_check,a.id_etapa,a.id_usuario,(select count(*) from etapa as e where e.id=a.id_etapa) as dep FROM acao as a WHERE id_etapa=" . $reg_etapa["id"] . " order by a.ordem";
            $rs_acoes = $this->db->Execute($sql_busca_acoes);
            while ($reg_acao = $rs_acoes->fetchRow()) {
                $sql_insert_acao = "insert into acao (acao, ordem, id_etapa) values ('" . $reg_acao["acao"] . "','" . $reg_acao["ordem"] . "','" . $id_etapa . "')";
                $rs_insert_acao = $this->db->Execute($sql_insert_acao);
            }
        }
        return $dados;
    }

    function excluir($id) {
        $manterEtapa = new ManterEtapa();
        $etapas = $manterEtapa->listar($id);
        foreach ($etapas as $obj) {
            $sql_acao = "delete from acao where id_etapa=" . $obj->id;
            $rs_acao = $this->db->Execute($sql_acao);

            $manterEtapa->excluir($obj->id);
        }
        $sql = "delete from tarefa where id=" . $id;
        $resultado = $this->db->Execute($sql);
        return $resultado;
    }

    function getPercentualTarefaPorId($id) {
        $sql = "SELECT
(SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=$id) as total,
(SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=$id AND a.data_check > 0) as concluido
FROM etapa 
GROUP BY total, concluido";
        //echo $sql;
        $resultado = $this->db->Execute($sql);
        if ($registro = $resultado->fetchRow()) {
            $total = $registro["total"];
            $concluido = $registro["concluido"];
            if ($total > 0 && $concluido > 0) {
                $percentual = ($concluido * 100) / $total;
                return $percentual;
            } else {
                return 0;
            }
        }
        return 0;
    }

    function getPainelTarefa(Usuario $user) {
        $sql = "SELECT
(SELECT count(*) FROM tarefa) as total,
(SELECT count(*) FROM tarefa as t WHERE t.id_equipe=" . $user->equipe . ") as total_equipe,
(SELECT count(*) FROM tarefa as t WHERE t.id_criador=" . $user->id . ") as total_criador,
(SELECT count(*) FROM tarefa as t WHERE t.id_responsavel=" . $user->id . ") as total_responsavel
FROM tarefa 
GROUP BY total,total_equipe,total_criador,total_responsavel";
        //echo $sql;
        $dados = new stdClass();
        $resultado = $this->db->Execute($sql);
        if ($registro = $resultado->fetchRow()) {
            $dados->total = $registro["total"];
            $dados->total_equipe = $registro["total_equipe"];
            $dados->total_criador = $registro["total_criador"];
            $dados->total_responsavel = $registro["total_responsavel"];
        }
        return $dados;
    }

    function getPainelTarefaConcluidas(Usuario $user) {
        $sql_concluidas = "SELECT t.id as concluidas,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id) as total,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id AND a.data_check > 0) as concluido
        FROM tarefa as t
        GROUP BY concluidas
        HAVING total > 0 AND total = concluido";

        $rs_concluidas = $this->db->Execute($sql_concluidas);
        $count_concluidas = $rs_concluidas->rowCount() != NULL ? $rs_concluidas->rowCount() : 0;

        $sql_concluidas_equipe = "SELECT t.id as concluidas,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id) as total,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id AND a.data_check > 0) as concluido
        FROM tarefa as t
        WHERE t.id_equipe=" . $user->equipe . "
        GROUP BY concluidas
        HAVING total > 0 AND total = concluido";

        $rs_concluidas_equipe = $this->db->Execute($sql_concluidas_equipe);
        $count_concluidas_equipe = $rs_concluidas_equipe->rowCount() != NULL ? $rs_concluidas_equipe->rowCount() : 0;

        $sql_concluidas_criador = "SELECT t.id as concluidas,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id) as total,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id AND a.data_check > 0) as concluido
        FROM tarefa as t
        WHERE t.id_criador=" . $user->id . "
        GROUP BY concluidas
        HAVING total > 0 AND total = concluido";

        $rs_concluidas_criador = $this->db->Execute($sql_concluidas_criador);
        $count_concluidas_criador = $rs_concluidas_criador->rowCount() != NULL ? $rs_concluidas_criador->rowCount() : 0;

        $sql_concluidas_responsavel = "SELECT t.id as concluidas,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id) as total,
        (SELECT count(*) FROM acao as a, etapa as e WHERE a.id_etapa=e.id AND e.id_tarefa=t.id AND a.data_check > 0) as concluido
        FROM tarefa as t
        WHERE t.id_responsavel=" . $user->id . "
        GROUP BY concluidas
        HAVING total > 0 AND total = concluido";

        $rs_concluidas_responsavel = $this->db->Execute($sql_concluidas_responsavel);
        $count_concluidas_responsavel = $rs_concluidas_responsavel->rowCount() != NULL ? $rs_concluidas_responsavel->rowCount() : 0;

        //echo $sql;
        $dados = new stdClass();

        $dados->total = $count_concluidas;
        $dados->total_equipe = $count_concluidas_equipe;
        $dados->total_criador = $count_concluidas_criador;
        $dados->total_responsavel = $count_concluidas_responsavel;

        return $dados;
    }

}

<?php

require_once('Model.php');

class ManterRelatorio extends Model {

    public function __construct() { //metodo construtor
        parent::__construct();
    }

    /**
     * Retorna todos os registros e colunas da tabela de Selagem de Lotes
     */
    public function listarSelagem($filtro = ''){
        $sql = "SELECT * FROM selagem_lotes $filtro ORDER BY data_formulario DESC";
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        
        while ($registro = $resultado->fetchRow()) {
            $dados = new stdClass();
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
            $array_dados[] = $dados;
        }
        return $array_dados;
    }

    /**
     * Busca uma Selagem de Lote específica pelo ID da Submissão
     */
    public function getSelagemPorId($id_submissao) {
        $sql = "SELECT * FROM selagem_lotes WHERE id_submissao = " . (int)$id_submissao;
        $resultado = $this->db->Execute($sql);
        
        $dados = new stdClass();
        if ($registro = $resultado->fetchRow()) {
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
        }
        return $dados;
    }

    /**
     * Retorna todos os registros e colunas da tabela de Domicílios
     */
    public function listarDomicilios($filtro = '') {
        $sql = "SELECT * FROM domicilios $filtro ORDER BY numero_selo ASC";
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        
        while ($registro = $resultado->fetchRow()) {
            $dados = new stdClass();
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
            $array_dados[] = $dados;
        }
        return $array_dados;
    }

    /**
     * Busca um Domicílio específico pelo Número do Selo (Chave Primária)
     */
    public function getDomicilioPorSelo($numero_selo) {
        $sql = "SELECT * FROM domicilios WHERE numero_selo = '" . $numero_selo . "'";
        $resultado = $this->db->Execute($sql);
        
        $dados = new stdClass();
        if ($registro = $resultado->fetchRow()) {
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
        }
        return $dados;
    }

    /**
     * Retorna todos os registros e colunas da tabela de Cadastro Sociojurídico
     */
    public function listarSociojuridico($filtro = '') {
        $sql = "SELECT * FROM cadastro_sociojuridico  $filtro ORDER BY r1_nome ASC";
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        
        while ($registro = $resultado->fetchRow()) {
            $dados = new stdClass();
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
            $array_dados[] = $dados;
        }
        return $array_dados;
    }

    /**
     * Busca o Cadastro Sociojurídico de uma família pelo ID da Submissão
     */
    public function getSociojuridicoPorId($id_submissao) {
        $sql = "SELECT * FROM cadastro_sociojuridico WHERE id_submissao = " . (int)$id_submissao;
        $resultado = $this->db->Execute($sql);
        
        $dados = new stdClass();
        if ($registro = $resultado->fetchRow()) {
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
        }
        return $dados;
    }

    /**
     * Lista todas as caracterizações trazendo todos os campos da tabela
     * @return array Lista de objetos com o mapeamento completo do banco
     */
    public function listarCaracterizacao($filtro = '') {
        // 🔥 Alterado para buscar todas as colunas de forma irrestrita
        $sql = "SELECT * FROM caracterizacao_vulnerabilidade $filtro ORDER BY data_registro DESC";
                
        $resultado = $this->db->Execute($sql);
        $array_dados = array();
        
        while ($registro = $resultado->fetchRow()) {
            $dados = new stdClass();
            
            // 🔥 Mapeamento dinâmico: qualquer campo novo criado no banco 
            // será indexado automaticamente como propriedade do objeto
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
            
            $array_dados[] = $dados;
        }
        return $array_dados;
    }
    /**
     * Busca um registro completo através do ID de Submissão (Chave Primária)
     */
    public function getCaracterizacaoPorIdSubmissao($id_submissao) {
        $sql = "SELECT * FROM caracterizacao_vulnerabilidade WHERE id_submissao = " . (int)$id_submissao;
        $resultado = $this->db->Execute($sql);
        
        $dados = new stdClass();
        if ($registro = $resultado->fetchRow()) {
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
        }
        return $dados;
    }

    /**
     * Busca um registro através da Chave Estrangeira (Código do Selo)
     */
    public function getCaracterizacaoPorCodigoSelo($codigo_selo) {
        $sql = "SELECT * FROM caracterizacao_vulnerabilidade WHERE codigo_selo = '" . $codigo_selo . "'";
        $resultado = $this->db->Execute($sql);
        
        $dados = new stdClass();
        if ($registro = $resultado->fetchRow()) {
            foreach ($registro as $coluna => $valor) {
                $dados->$coluna = $valor;
            }
        }
        return $dados;
    }

}


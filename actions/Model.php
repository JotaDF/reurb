<?php
//jotadf
//include_once('../adodb5/adodb.inc.php'); //biblioteca necessaria para trabalhar com adodb

//Local
require('adodb/adodb.inc.php'); //biblioteca necessaria para trabalhar com adodb

Class Model{
	protected $db;
	public function __construct() {
		$tipo_banco    = "mysqli";
		/** local */
		//$servidor      = "localhost";
		//$usuario       = "root";
		//$senha         = "";
		//$db            = "gerente";
                /** web */
		$servidor      = "mysql";
		$usuario       = "reurb";
		$senha         = "reurb#2020";
		$db            = "reurb";
		
        $this->db = $banco = NewADOConnection($tipo_banco);
		$this->db->dialect = 3;
		$this->db->debug = false; 
		$this->db->Connect($servidor,$usuario,$senha,$db);
	}
	//metodo formatar o campo data para inserção no bando de dados
	function formataDataDB($data){
		list ($dia, $mes, $ano) = split ('[/.-]', $data);
   		$data_formatada = $ano."-".$mes."-".$dia;
		return $data_formatada;
	}
	//metodo para formatar data para inserção no bando de dados recebe somente numeros
	function formataDataDBtxt($data) {
		$data = preg_replace("/[^0-9]/", "", $data);
		if(strlen($data) == 8) {
			$data = $this->formataDataDB(substr($data, 0, 2).'/'.
			substr($data, 2, 2).'/'.
			substr($data, 4, 4));
			return $data;
		}
		else {
			return $this->formataDataDB($data);
		}
	}

	//metodo formatar o campo data padrão brasil
	function formataDataCampo($data){
		list ($ano, $mes, $dia) = split ('[/.-]', $data);
   		$data_formatada = $dia."/".$mes."/".$ano;
		return $data_formatada;
	}

	//metodo para formatar o CEP
	function formataCep($cep) {
		$cep = preg_replace("/[^0-9]/", "", $cep);
		if(strlen($cep) == 8) {
			$cep = substr($cep, 0, 2).'.'.
			substr($cep, 3, 3).'-'.
			substr($cep, 5, 3);
			return $cep;
		}
		else {
			return $cep;
		}
	}
	//metodo para formatar o Telefone
	function formataTelefone($tel) {
		$tel = preg_replace("/[^0-9]/", "", $tel);
		if(strlen($tel) == 10) {
			$tel = '('.
			substr($tel, 0, 2).')'.
			substr($tel, 2, 4).'-'.
			substr($tel, 6, 4);
			return $tel;
		}
		else {
			return $tel;
		}
	}
	//metodo para formatar o CPF
	function formataCpf($cpf) {
		$cpf = preg_replace("/[^0-9]/", "", $cpf);
		if(strlen($cpf) == 11) {
			$cpf = substr($cpf, 0, 3).'.'.
			substr($cpf, 3, 3).'.'.
			substr($cpf, 6, 3).'-'.
			substr($cpf, 9, 2);
			return $cpf;
		}
		else {
			return $cpf;
		}
	}
	//metodo para formatar o CNPJ
	function formataCnpj($cnpj) {
		$cnpj = preg_replace("/[^0-9]/", "", $cnpj);
		if(strlen($cnpj) == 14) {
			$cnpj = substr($cnpj, 0, 2).'.'.
			substr($cnpj, 2, 3).'.'.
			substr($cnpj, 5, 3).'/'.
			substr($cnpj, 8, 4).'-'.
			substr($cnpj, 12, 2);
			return $cnpj;
		}
		else {
			return cnpj;
		}
	}
	//metodo que retorna o valor por extenso de um numero
	function valorPorExtenso($valor=0) {
		$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
	
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
	
		$z=0;
	
		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];
	
		// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++){
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
		
			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}
	
		return($rt ? $rt : "zero");
	}	
	
}		
?>

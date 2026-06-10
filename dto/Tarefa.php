<?php
  class Tarefa{
	    public $id;
            public $nome;
            public $descricao;
            public $categoria;
            public $inicio;
            public $fim;
            public $tipo;
            public $criador;
            public $responsavel;
            public $equipe;
            public $total_dias = 0;

            //variaveis de manipulação
            public $excluir;
            public $status = true;
            public $msg;
		
  }

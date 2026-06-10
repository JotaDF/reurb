<?php
	include_once('actions/ManterUsuario.php'); 
	include_once('actions/ManterPerfil.php'); 
	include_once('actions/ManterEquipe.php'); 
        
	$manterUsuario = new ManterUsuario();
	$manterPerfil = new ManterPerfil();
        $manterEquipe = new ManterEquipe();
        
	$lista = $manterUsuario->listar();
        
        foreach ($lista as $obj) {
            echo "<tr>";
            echo "  <td>".$obj->id."</td>";
            echo "  <td>".$obj->cpf."</td>";
            echo "  <td>".$obj->nome ."</td>";
            echo "  <td>".$manterEquipe->getEquipePorId($obj->equipe)->equipe."</td>";
            echo "  <td>".$manterPerfil->getPerfilPorId($obj->perfil)->perfil."</td>";
            echo "  <td>".($obj->ativo > 0 ? 'Sim':'Não')."</td>";
            if($usuario_logado->perfil < $obj->perfil || $usuario_logado->id == $obj->id){
            if($obj->excluir){
                echo '  <td align="center" valign="bottom" class="align-middle nowrap"><button class="btn btn-primary btn-sm" type="button" onclick="alterar(\''.$obj->id.'\',\'' .$obj->cpf.'\',\''.addslashes($obj->nome).'\',\''.$obj->senha.'\',\''.$obj->email.'\',\''.$obj->ativo.'\',\''.$obj->equipe.'\',\''.$obj->perfil.'\')"><i class="fas fa-edit"></i></button>&nbsp;&nbsp;<button class="btn btn-danger btn-sm" type="button" onclick="excluir('.$obj->id.',\''.$obj->nome.'\')"><i class="far fa-trash-alt"></i></button></td>';
            } else {
                echo '  <td align="center" valign="bottom" class="align-middle nowrap"><button class="btn btn-primary btn-sm" type="button" onclick="alterar(\''.$obj->id.'\',\'' .$obj->cpf.'\',\''.addslashes($obj->nome).'\',\''.$obj->senha.'\',\''.$obj->email.'\',\''.$obj->ativo.'\',\''.$obj->equipe.'\',\''.$obj->perfil.'\')"><i class="fas fa-edit"></i></button>&nbsp;&nbsp;<button class="btn btn-secondary btn-sm" type="button"><i class="far fa-trash-alt"></i></button></td>';
            }
            }  else {
                echo "  <td align='center'> - </td>";
            }

            echo "</tr>";
        }


<!-- Begin Page Content -->

<!-- Collapsable Form -->
<div class="card mb-4 collapse hide border-info" style="max-width: 800px;" id="form_tarefa">
    <!-- Card Header - Accordion -->
    <div class="card-header py-2 card-body bg-info align-middle" style="min-height: 2.5rem;">               
        <span class="h6 m-0 font-weight text-white">Cadastro de Tarefa</span>
    </div>                
    <!-- Card Content - Collapse -->
    <div class="card-body">
        <form id="form_cadastro" action="save_tarefa.php" method="post">
            <input type="hidden" id="id" name="id"/>
            <input type="hidden" id="id_criador" name="criador" value="<?= $usuario_logado->id ?>"/>
            <input type="hidden" id="duplicar" name="duplicar" value="0"/>
            <div class="form-group row">
                <div class="col input-group">
                    <label for="nome" class="col-sm-4 col-form-label">Nome:</label>
                    <input type="text" name="nome" class="form-control form-control-sm" id="nome" placeholder="nome" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col input-group">
                    <label for="descricao" class="col-sm-4 col-form-label">Descrição:</label>
                    <input type="text" name="descricao" class="form-control form-control-sm" id="descricao" placeholder="descrição" required>
                </div>
            </div>                        <div class="form-group row">
                <div class="col input-group">
                    <label for="categoria" class="col-sm-4 col-form-label">Categoria:</label>
                    <select id="categoria" name="categoria" class="form-control form-control-sm" required>
                        <option value="">Selecione</option>    
                    </select>
                </div>
                <div class="col input-group">
                    <label for="tipo" class="col-sm-4 col-form-label">Tipo:</label>
                    <select id="tipo" name="tipo" class="form-control form-control-sm" onChange="verificaTipo(this.options[this.selectedIndex].value)" required>
                        <option value="">Selecione</option>    
                    </select>
                </div>                            
            </div>                          
            <div class="form-group row">
                <div class="col input-group">
                    <label for="equipe" class="col-sm-4 col-form-label">Equipe:</label>
                    <select id="equipe" name="equipe" class="form-control form-control-sm" onChange="atualizaUsuarios(this.options[this.selectedIndex].value)" required>
                        <option value="0"> Todos </option>    
                    </select>
                </div>
            </div>   
            <div class="form-group row">
                <div class="col input-group">
                    <label for="responsavel" class="col-sm-4 col-form-label">Responsável:</label>
                    <select id="responsavel" name="responsavel" class="form-control form-control-sm" required>
                        <option value="">Selecione</option>    
                    </select>
                </div>
            </div>                         
            <div class="col border">
                <fieldset class="form-group form-inline">
                    <legend class="col c1 col-form-label pt-0">Realização</legend>
                    <div class="input-group">
                        <label for="inicio" class="col c0 col-form-label">Início:</label>
                        <input type="date" name="inicio" class="col c1 form-control form-control-sm" id="inicio" required>
                    </div>
                    <div class="input-group">
                        <label for="termino" class=" col c2 col-form-label">Término:</label>
                        <input type="date" name="termino" class="col c3 form-control form-control-sm" id="termino" required>
                    </div>
                </fieldset>  
            </div>
            <br/>
            <div class="form-group row float-right">
                <button type="reset" onclick="cancelar()" data-toggle="collapse" data-target="#form_tarefa" class="btn btn-danger btn-sm"><i class="fa fa-minus-square"></i> Cancelar</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Salvar</button>
                &nbsp;&nbsp;&nbsp;
            </div>
        </form>                  
    </div>
</div>
<!-- /.container-fluid -->

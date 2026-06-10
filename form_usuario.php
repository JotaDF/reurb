<!-- Begin Page Content -->

<!-- Collapsable Form -->
<div class="card mb-4 collapse hide border-primary" id="form_usuario" style="max-width:900px">
    <!-- Card Header - Accordion -->
    <div class="card-header py-2 card-body bg-gradient-primary align-middle" style="min-height: 2.5rem;">               
        <span class="h6 m-0 font-weight text-white">Cadastro de Usuário</span>
    </div>  
    <!-- Card Content - Collapse -->
    <div class="card-body">
        <form id="form_cadastro" action="save_usuario.php" method="post">
            <input type="hidden" id="id" name="id"/>
            <div class="form-group row">

                <label for="cpf" class="col-sm-2 col-form-label">CPF:</label>
                <div class="col-sm-10 input-group">
                    <input type="text" name="cpf" class="form-control form-control-sm" id="cpf" placeholder="apenas números"  onkeypress="$(this).mask('000.000.000-00');" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="nome" class="col-sm-2 col-form-label">Nome:</label>
                <div class="col-sm-10">
                    <input type="text" name="nome" class="form-control form-control-sm" id="nome" placeholder="nome" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">E-mail:</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control form-control-sm" id="email" placeholder="e-mail" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="senha" class="col-sm-2 col-form-label">Senha:</label>
                <div class="col-sm-10">
                    <input type="password" name="senha" class="form-control form-control-sm" placeholder="senha" id="senha" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="equipe" class="col-sm-2 col-form-label">Equipe:</label>
                <div class="col-sm-10">
                    <select id="equipe" name="equipe" class="form-control form-control-sm" required>
                        <option value="">Selecione</option>    
                    </select>
                </div>

            </div> 
            <div class="form-group row">
                <label for="perfil" class="col-sm-2 col-form-label">Perfil:*</label>
                <div class="col-sm-10">
                    <select id="perfil" name="perfil" class="form-control form-control-sm" required>
                        <option value="">Selecione</option>    
                    </select>
                </div>
            </div> 

            <div class="form-group row">
                <div class="col-sm-2">Ativo</div>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" name="ativo" type="checkbox" id="ativo" value="1" checked>
                        <label class="form-check-label" for="ativo">
                            Sim
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row float-right">
                <button type="reset" onclick="$('#btn_cadastrar').show();" data-toggle="collapse" data-target="#form_usuario" class="btn btn-danger btn-sm"><i class="fa fa-minus-square"></i> Cancelar</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Salvar</button>
                &nbsp;&nbsp;&nbsp;
            </div>
        </form>                  
    </div>
</div>
<!-- /.container-fluid -->


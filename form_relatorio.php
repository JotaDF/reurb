<!-- Begin Page Content -->

<!-- Collapsable Form -->
<div class="card mb-4 border-info" style="max-width: 800px;">
    <!-- Card Header - Accordion -->
    <div class="card-header py-2 card-body bg-info align-middle" style="min-height: 2.5rem;">               
        <span class="h6 m-0 font-weight text-white">Filtro de relatório</span>
    </div>                
    <!-- Card Content - Collapse -->
    <div class="card-body">
        <form id="form_relatorio" action="relatorio.php" method="post">
            <div class="form-group row">
                <div class="col input-group">
                    <label for="categoria" class="col-sm-4 col-form-label">Categoria:</label>
                    <select id="categoria" name="categoria" class="form-control form-control-sm">
                        <option value="">indiferente</option>    
                    </select>
                </div>
                <div class="col input-group">
                    <label for="tipo" class="col-sm-4 col-form-label">Tipo:</label>
                    <select id="tipo" name="tipo" class="form-control form-control-sm" onChange="verificaTipo(this.options[this.selectedIndex].value)">
                        <option value="">indiferente</option>    
                    </select>
                </div>                            
            </div>                          
            <div class="form-group row">
                <div class="col input-group">
                    <label for="equipe" class="col-sm-4 col-form-label">Equipe:</label>
                    <select id="equipe" name="equipe" class="form-control form-control-sm">
                        <option value="">indiferente</option>    
                    </select>
                </div>
            </div>   
            <div class="form-group row">
                <div class="col input-group">
                    <label for="responsavel" class="col-sm-4 col-form-label">Responsável:</label>
                    <select id="responsavel" name="responsavel" class="form-control form-control-sm">
                        <option value="">indiferente</option>    
                    </select>
                </div>
            </div>                         
            <div class="col border">
                <fieldset class="form-group form-inline curso">
                    <legend class="col c1 col-form-label pt-0">Inscrição (Deixe vazio para igonrar datas)</legend>
                    <div class="input-group">
                        <label for="inicio_insc" class="col-form-label col c0">Início depois de:</label>
                        <input type="date" name="inicio_insc" class="col c1 form-control form-control-sm" id="inicio_insc">
                    </div>
                    <div class="input-group">
                        <label for="termino_insc" class="col c2 col-form-label">Término até:</label>
                        <input type="date" name="termino_insc" class="col c3 form-control form-control-sm" id="termino_insc">
                    </div>
                </fieldset>
                <hr class="curso"/>
                <fieldset class="form-group form-inline">
                    <legend class="col c1 col-form-label pt-0">Realização (Deixe vazio para igonrar datas)</legend>
                    <div class="input-group">
                        <label for="inicio" class="col c0 col-form-label">Início depois de:</label>
                        <input type="date" name="inicio" class="col c1 form-control form-control-sm" id="inicio">
                    </div>
                    <div class="input-group">
                        <label for="termino" class=" col c2 col-form-label">Término até:</label>
                        <input type="date" name="termino" class="col c3 form-control form-control-sm" id="termino">
                    </div>
                </fieldset>  
            </div>
            <br/>
            <div class="form-group row float-right">
                <button type="reset" onclick="cancelar()" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i> Limpar </button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-file"></i> Gerar </button>
                &nbsp;&nbsp;&nbsp;
            </div>
        </form>                  
    </div>
</div>
<!-- /.container-fluid -->

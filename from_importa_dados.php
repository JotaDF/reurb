<form action="processar_upload.php" method="POST" enctype="multipart/form-data">
    <label for="tipo_importacao">Selecione o tipo de planilha:</label>
    <select name="tipo_importacao" id="tipo_importacao" required>
        <option value="">-- Selecione uma Opção --</option>
        <option value="selagem">1. Ficha de Selagem de Lote</option>
        <option value="domicilios">2. Domicílios Encontrados</option>
        <option value="caracterizacao">3. Caracterização Complementar</option>
        <option value="sociojuridico">4. Cadastro Sociojurídico</option>
    </select>
    
    <br><br>
    
    <label for="arquivo_csv">Arquivo (.csv):</label>
    <input type="file" name="arquivo_csv" id="arquivo_csv" accept=".csv" required>
    
    <br><br>
    <button type="submit">Realizar Importação</button>
</form>
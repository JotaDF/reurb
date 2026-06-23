<?php

echo "<h2>Teste de Conexão com Bancos de Dados</h2>";
/**
 * MYSQL
 */
echo "<h3>MySQL</h3>";

try {
    $mysql = new PDO(
        "mysql:host=mysql;port=3306;dbname=reurb;charset=utf8mb4",
        "reurb",
        "reurb#2020",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $versao = $mysql->query("SELECT VERSION()")->fetchColumn();

    echo "<p style='color:green'>✓ Conectado ao MySQL</p>";
    echo "<p>Versão: {$versao}</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Erro MySQL: " . $e->getMessage() . "</p>";
}

/**
 * POSTGRESQL
 */
echo "<h3>PostgreSQL</h3>";

try {
    $pgsql = new PDO(
        "pgsql:host=postgres;port=5432;dbname=reurb",
        "reurb",
        "reurb#2020",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $versao = $pgsql->query("SELECT version()")->fetchColumn();

    echo "<p style='color:green'>✓ Conectado ao PostgreSQL</p>";
    echo "<p>Versão: {$versao}</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Erro PostgreSQL: " . $e->getMessage() . "</p>";
}

/**
 * EXTENSÕES PHP
 */
echo "<h3>Extensões PHP</h3>";

$extensoes = [
    'mysqli',
    'pdo_mysql',
    'pgsql',
    'pdo_pgsql',
    'mbstring',
    'intl',
    'gd',
    'zip'
];

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Extensão</th><th>Status</th></tr>";

foreach ($extensoes as $ext) {
    echo "<tr>";
    echo "<td>{$ext}</td>";
    echo "<td>" . (extension_loaded($ext) ? '✓ Carregada' : '✗ Não carregada') . "</td>";
    echo "</tr>";
}

echo "</table>";

phpinfo(INFO_MODULES);
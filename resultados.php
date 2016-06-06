<?php
if (!isset($_POST['data'])) {
    header('Location: index.html');
}

require_once('Config/parametros.php');
require_once('Classe/VerificaGramatica.php');
require_once('Classe/Tabela.php');
require_once('Classe/ReconhecedorEntrada.php');

$gramatica = [];
$variaveisLadoEsquerdo = $_POST['data']['GramaticaVariavel']['Esquerdo'];
$variaveisLadoDireito = $_POST['data']['GramaticaVariavel']['Direito'];

$variaveisTerminaveis = $_POST['data']['Terminais'];
$variaveisNaoTerminaveis = $_POST['data']['NaoTerminais'];

$simboloInicial = $_POST['data']['simbolo_inicio'];

foreach ($variaveisLadoEsquerdo as $indice => $esquerdo) {
    $gramatica[$esquerdo] = $variaveisLadoDireito[$indice];
}

try {
    (new VerificaGramatica($gramatica))->validarRegrasLL1();
    $objTabela = new Tabela($gramatica, $simboloInicial);
    $objTabela->construcaoTabela($variaveisNaoTerminaveis, $variaveisTerminaveis);
    $objReconhecedorEntrada = new ReconhecedorEntrada($objTabela);
    $objReconhecedorEntrada->reconhecer(RECONHECER_ENTRADA);
} catch (Exception $ex) {
    $erro = $ex->getMessage();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Trabalho de Compiladores</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/index.css">
    </head>
    <body>
        <h1>Trabalho de Compiladores</h1>
        <h2>Resultados da Ánalise Tabular</h2>
        <h3>Tabela Gerada</h3>
        <?php
        if (isset($erro)) {
            echo '<span id="erro">' . $erro . '</span><br /><br /><br />';
        }


        if (isset($objTabela)) { // se nao deu erro na verificacao de gramatica LL1, imprimi as tabelas
            echo '<div>';
            echo $objTabela->getTabelaGerada();
            echo '<br /><br />';
            echo $objReconhecedorEntrada->getTabelaReconhecedor();
            echo '</div>';
        }
        ?>     

        <br /><br />
        <button onclick="history.go(-1);">Voltar</button>
    </body>
</html>





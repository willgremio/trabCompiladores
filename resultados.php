<?php
if (!isset($_POST['data'])) {
    header('Location: index.html');
}

require_once('Classe/VerificaGramatica.php');
require_once('Classe/Tabela.php');

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
    $objVerGram = new VerificaGramatica($gramatica);
    $objVerGram->validarRegrasLL1();
    $objTabela = new Tabela($gramatica, $simboloInicial);
    $objTabela->construcaoTabela($variaveisNaoTerminaveis, $variaveisTerminaveis);
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
            echo '<span id="erro">' . $erro . '</span><br />';
        } else {
            echo $objTabela->getTabelaGerada();
        }
        ?>     
        
        <br /><br />
        <button onclick="history.go(-1);">Voltar</button>
    </body>
</html>





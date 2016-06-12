<?php
if (!isset($_POST['data'])) {
    header('Location: index.html');
}

require_once('Classe/VerificaGramatica.php');
require_once('Classe/Tabela.php');
require_once('Classe/ReconhecedorEntrada.php');

$gramatica = [];
$variaveisLadoEsquerdo = $_POST['data']['GramaticaVariavel']['Esquerdo'];
$variaveisLadoDireito = $_POST['data']['GramaticaVariavel']['Direito'];

$variaveisTerminaveis = $_POST['data']['Terminais'];
$variaveisNaoTerminaveis = $_POST['data']['NaoTerminais'];

$simboloInicial = $_POST['data']['simbolo_inicio'];
$sentenca = $_POST['data']['sentenca'];

foreach ($variaveisLadoEsquerdo as $indice => $esquerdo) {
    $gramatica[$esquerdo] = $variaveisLadoDireito[$indice];
}

try {
    session_start();
    (new VerificaGramatica($gramatica))->validarRegrasLL1();
    $objTabela = new Tabela($gramatica, $simboloInicial);
    $objTabela->construcaoTabela($variaveisNaoTerminaveis, $variaveisTerminaveis);
    $_SESSION['objTabela'] = ($objTabela);
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
        <script src="js/jquery.js"></script>
        <script>
            $(function () {
                $('#ButtonTesteSentenca').click(function () {
                    var sentenca = $('#sentenca').val();
                    $.ajax({
                        url: 'ajax/reconhecer_sentenca.php',
                        dataType: 'json',
                        data: 'data[sentenca]=' + sentenca,
                        type: 'POST',
                        success: function (retorno) {
                            $('#RespostaSentenca').html(retorno.msg + '<br />' + retorno.tabelaGerada);
                        },
                        error: function () {
                            alert('Houve algum erro ao tentar fazer o teste da sentença!');
                        }
                    });
                });
            })
        </script>
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
            echo '</div>';
        }
        ?>     

        <br />
        <input id="sentenca" type="text" placeholder="Digite aqui a sentença" />
        <button id="ButtonTesteSentenca" type="button">Testar Sentença!</button>
        <br /><br /><br />
        <div id="RespostaSentenca"></div>

        <br /><br />
        <button onclick="history.go(-1);">Voltar</button>
    </body>
</html>





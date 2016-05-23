<?php
if (!isset($_POST['data'])) {
    header('Location: index.html');
}

$dadosNoTerminais = $_POST['data']['NoTerminais'];
$dadosTerminais = $_POST['data']['Terminais'];
$todasVariaveis = array_merge($dadosNoTerminais, $dadosTerminais);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Trabalho de Compiladores</title>
        <meta charset="UTF-8">
        <script src="js/jquery.js"></script>
        <script src="js/formar_gramatica.js"></script>
        <link rel="stylesheet" type="text/css" href="css/index.css">        
    </head>
    
    <body>        
        <h1>Trabalho de Compiladores</h1>
        <h2>Forme as produções da gramática</h2>
        <p>Clique primeiro em um box e depois em alguma das variáveis abaixo.</p>
        <form action="resultados.php" method="post">
            <?php
            echo 'NT: ';
            foreach ($dadosNoTerminais as $noTerminal) {
                echo '<input class="variaveis" readonly type="button" value="' . $noTerminal . '">';
            }

            echo '<br /><br />';

            echo 'T: ';
            foreach ($dadosTerminais as $Terminal) {
                echo '<input class="variaveis" readonly type="button" value="' . $Terminal . '">';
            }

            echo '<br /><br />';
            echo '<input class="variaveis" readonly type="button" value="X">';
            echo '<input class="variaveis" readonly type="button" value="|">';
            ?>

            <br /><br /><br />
            <input maxlength="1" readonly class="box" name="data[GramaticaVariavel][Esquerdo][]" type="text" />
            => 
            <input readonly class="box" name="data[GramaticaVariavel][Direito][]" type="text" /> 

            <br /><br />
            <input id="AdicionarGramatica" type="button" value="Adicionar mais uma Produção">
            <input class="botaoLimpar" value="Limpar Box Selecionado" type="button" />
            <br /><br /><br /><br /><br />
            <?php
            foreach ($dadosTerminais as $Terminal) {
                echo '<input type="hidden" name="data[Terminais][]" value="' . $Terminal . '" />';
            }
            ?>  

              
            Escolha o Símbolo Inicial:
            <div>
                <?php
                foreach ($dadosNoTerminais as $indice => $noTerminal) {
                    $checked = '';
                    if ($indice == 0) {
                        $checked = 'checked';  // atribui para o 1º NT checked
                    }

                    echo '<input id="lab' . $indice . '" type="radio" name="data[simbolo_inicio]" ' . $checked . ' value="' . $noTerminal . '">';
                    echo '<label for="lab' . $indice . '">' . $noTerminal . '</label>';
                    echo '<input type="hidden" name="data[NaoTerminais][]" value="' . $noTerminal . '" />';
                    echo '<br />';
                }
                ?>
            </div>
            <br /><br />
            <input type="submit">
        </form>
        
        <br /><br />
        <button onclick="history.go(-1);">Voltar</button>
    </body>
</html>





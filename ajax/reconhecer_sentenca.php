<?php

require_once('../Classe/Tabela.php');
require_once('../Classe/ReconhecedorEntrada.php');

session_start();
$sentenca = $_GET['entrada'];
$objTabela = ($_SESSION['objTabela']);
$objReconhecedorEntrada = new ReconhecedorEntrada($objTabela);
$retorno = array('msg' => '');

try {
    $objReconhecedorEntrada->reconhecer($sentenca);
} catch (Exception $ex) {
    $retorno['msg'] = '<span id="erro">' . $ex->getMessage() . '</span>';
}

$retorno['tabelaGerada'] = $objReconhecedorEntrada->getTabelaReconhecedor();
echo json_encode($retorno);
exit();

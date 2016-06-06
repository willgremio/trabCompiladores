<?php

/**
 * Description of ReconhecedorEntrada
 *
 * @author Willian
 */
class ReconhecedorEntrada {

    private $objTabela;
    private $arrayReconhecedor = array();
    private $tabelaReconhecedor = '';

    public function __construct(Tabela $objTabela) {
        $this->setObjTabela($objTabela);
    }

    private function setObjTabela($objTabela) {
        $this->objTabela = $objTabela;
    }

    private function setTabelaReconhecedor($tabelaReconhecedor) {
        $this->tabelaReconhecedor = $tabelaReconhecedor;
    }

    public function getTabelaReconhecedor() {
        return $this->tabelaReconhecedor;
    }

    public function reconhecer($entrada) {
        $simboloInicial = $this->objTabela->getSimboloInicial();
        $primeiroRegistroPilha = '$' . $simboloInicial;
        $entrada .= '$';
        $this->setValoresNoReconhecedor($primeiroRegistroPilha, $entrada, ''); // valores iniciais
        $this->gerarDadosDaTabela($entrada, $simboloInicial);
        $this->criarTabela();
    }

    private function criarTabela() {
        $tabelaGerada = $this->criaTabelaReconhecedor();
        $this->setTabelaReconhecedor($tabelaGerada);
    }

    private function gerarDadosDaTabela($entrada, $simboloNT, $iteracao = 1) {
        $primeiroSimboloDaEntrada = $entrada[0];
        $arrayAgrupaLinhasColunas = $this->objTabela->getArrayAgrupaLinhasColunas();
        if (!isset($arrayAgrupaLinhasColunas[$simboloNT][$primeiroSimboloDaEntrada])) {
            $this->criarTabela(); //se der erro cria a tabela pra mostrar até onde foi
            throw new Exception("Não existe uma produção em que $simboloNT produz $primeiroSimboloDaEntrada");
        }

        $producao = $arrayAgrupaLinhasColunas[$simboloNT][$primeiroSimboloDaEntrada];
        list($ladoEsquerdo, $ladoDireito) = explode('->', $producao);
        $ultimoElemento = end($this->arrayReconhecedor);

        $trocarPor = strrev($ladoDireito); // inverte a producao
        if (!Util::isSimboloNaoTerminal($ladoDireito)) { //se é um terminal
            $trocarPor = $ladoDireito; // pega o terminal para a troca
        }

        if (Util::isSentenciaVazia($ladoDireito)) { // se é a sentença vazia
            $trocarPor = ""; // deixa vazio
        }

        $novoRegistroPilha = str_replace($simboloNT, $trocarPor, $ultimoElemento['pilha']); //troca o simboloNT que tinha na pilha pela producao ao contrario desse simboloNT
        $this->setValoresNoReconhecedor($novoRegistroPilha, $entrada, $producao);

        $ultimoSimbolo = substr($novoRegistroPilha, -1);
        if (!Util::isSimboloNaoTerminal($ultimoSimbolo) && $ultimoSimbolo != '$') { // se é terminal e nao chegou ao fim da pilha
            $entrada = substr($entrada, 1); // retira o primeiro terminal da entrada
            $novoRegistroPilha = substr($novoRegistroPilha, 0, strlen($novoRegistroPilha) - 1); // retira o terminal da pilha
            $this->setValoresNoReconhecedor($novoRegistroPilha, $entrada, ''); //seta os valores no reconhecer com a saida vazia
            $ultimoSimbolo = substr($novoRegistroPilha, -1);
        }

        $ultimoElementoReconhecedor = end($this->arrayReconhecedor);
        if ($ultimoElementoReconhecedor['pilha'] != '$') {
            $iteracao ++;
            $this->gerarDadosDaTabela($entrada, $ultimoSimbolo, $iteracao);
        }
    }

    private function setValoresNoReconhecedor($pilha, $entrada, $saida) {
        $this->arrayReconhecedor[] = [
            'pilha' => $pilha,
            'entrada' => $entrada,
            'saida' => $saida
        ];
    }

    public function criaTabelaReconhecedor() {
        $html = '<table cellpadding="10" cellspacing="1" border="1">';
        $html .= '<tr><th>PILHA</th><th>ENTRADA</th><th>SAÍDA</th></tr>';
        foreach ($this->arrayReconhecedor as $registroReconhecedor) {
            $html .= '<tr align="center">';
            $html .= '<td>' . $registroReconhecedor['pilha'] . '</td>';
            $html .= '<td>' . $registroReconhecedor['entrada'] . '</td>';
            $html .= '<td>' . $registroReconhecedor['saida'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

}

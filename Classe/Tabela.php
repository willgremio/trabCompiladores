<?php

/**
 * Description of Tabela
 *
 * @author Willian
 */
require_once('Util.php');

class Tabela {

    private $gramatica = array();
    private $simboloInicial = '';
    private $arrayAgrupaLinhasColunas = array();
    private $tabelaGerada = '';

    public function __construct($dadosGramatica, $simboloInicial) {
        $this->setGramatica($dadosGramatica);
        $this->setSimboloInicial($simboloInicial);
    }

    private function setGramatica($gramatica) {
        $this->gramatica = $gramatica;
    }

    public function getGramatica() {
        return $this->gramatica;
    }

    private function setSimboloInicial($simboloInicial) {
        $this->simboloInicial = $simboloInicial;
    }

    public function getSimboloInicial() {
        return $this->simboloInicial;
    }
    
    public function setTabelaGerada($tabelaGerada) {
        $this->tabelaGerada = $tabelaGerada;
    }
    
    public function getTabelaGerada() {
        return $this->tabelaGerada;
    }

    //Vai em cada produção da gramatica e faz o first e follow dos NT e por fim gera e seta a tabela     
    public function construcaoTabela($variaveisLadoEsquerdo, $variaveisLadoDireito) {
        foreach ($this->getGramatica() as $ladoEsquerdo => $ladoDireito) {
            $producoesLadoDireito = explode('|', $ladoDireito);
            foreach ($producoesLadoDireito as $producaoLadoDireito) {
                $primeiroSimboloLadoDireito = $producaoLadoDireito[0];
                if (!Util::isSimboloNaoTerminal($primeiroSimboloLadoDireito)) { // se é um terminal
                    $this->arrayAgrupaLinhasColunas[$ladoEsquerdo][$primeiroSimboloLadoDireito] = $ladoEsquerdo . '->' . $producaoLadoDireito;
                } elseif (Util::isSentenciaVazia($primeiroSimboloLadoDireito)) { //se é sentenca vazia
                    //tem q pegar o follow do lado esquerdo
                    $this->buscaAndSetFollowProducao($ladoEsquerdo, $ladoEsquerdo, $producaoLadoDireito);
                } else { // é um NT
                    //tem q pegar o first do nao terminal                    
                    $this->buscaAndSetFirstProducao($primeiroSimboloLadoDireito, $ladoEsquerdo, $producaoLadoDireito);
                }
            }
        }

        $tabelaGerada = $this->criaTabela($variaveisLadoEsquerdo, $variaveisLadoDireito);
        $this->setTabelaGerada($tabelaGerada);
    }

    //funcao recursiva que busca o first do NT, se achar um NT vai ficar chamando ela mesmo até achar os terminais
    private function buscaAndSetFirstProducao($primeiroSimboloLadoDireito, $ladoEsquerdo, $producaoLadoDireito) {
        if (!Util::isSimboloNaoTerminal($primeiroSimboloLadoDireito) || Util::isSentenciaVazia($primeiroSimboloLadoDireito)) {
            $this->arrayAgrupaLinhasColunas[$ladoEsquerdo][$primeiroSimboloLadoDireito] = $ladoEsquerdo . '->' . $producaoLadoDireito;
        } else { //vai ser um nao terminal
            foreach ($this->getGramatica() as $ladoEsquerdoNaoTerminal => $ladoDireito) {
                if ($primeiroSimboloLadoDireito != $ladoEsquerdoNaoTerminal) {
                    continue; //se nao o NT é o que estou procurando, vai pro proximo do array
                }

                $producoesLadoDireitoDeOutroNaoTerminal = explode('|', $ladoDireito);
                foreach ($producoesLadoDireitoDeOutroNaoTerminal as $producaoLadoDireitoDeOutroNaoTerminal) {
                    $primeiroSimboloLadoDireitoDeOutroNaoTerminal = $producaoLadoDireitoDeOutroNaoTerminal[0];
                    // vai chamar a propria funcao até achar um terminal
                    $this->buscaAndSetFirstProducao($primeiroSimboloLadoDireitoDeOutroNaoTerminal, $ladoEsquerdo, $producaoLadoDireito);
                }
            }
        }
    }

    /*funcao recursiva que procura o follow do NT, mesma logica que a do first só esssa é mais elaborada,
     pois aqui chama a funcao do first tbm em certos casos*/
    private function buscaAndSetFollowProducao($primeiroSimboloLadoDireito, $ladoEsquerdo, $producaoLadoDireito) {
        if ($primeiroSimboloLadoDireito == $this->simboloInicial) {
            $this->arrayAgrupaLinhasColunas[$ladoEsquerdo]['$'] = $ladoEsquerdo . '->' . $producaoLadoDireito;
        }

        $dadosGramatica = $this->getGramatica();
        foreach ($dadosGramatica as $ladoEsquerdoNaoTerminal => $ladoDireito) {

            if (preg_match('/' . $primeiroSimboloLadoDireito . '/', $ladoDireito)) {
                if (!Util::temAspasNaVariavel($primeiroSimboloLadoDireito) &&
                        Util::temAspasNaVariavel($primeiroSimboloLadoDireito, $ladoDireito)) {
                    continue;
                }

                if ($ladoEsquerdoNaoTerminal == $primeiroSimboloLadoDireito) {
                    continue;
                }
                
                $posicao = strrpos($ladoDireito, $primeiroSimboloLadoDireito);
                if (isset($ladoDireito[$posicao + 1]) && $ladoDireito[$posicao + 1] == "'") {
                    $posicao ++; //+1 pois tem o ' pra considerar
                }

                if (isset($ladoDireito[$posicao + 1])) {
                    $variavel = $ladoDireito[$posicao + 1];
                    if (!Util::isSimboloNaoTerminal($variavel)) { // se é terminal
                        $this->arrayAgrupaLinhasColunas[$ladoEsquerdo][$variavel] = $ladoEsquerdo . '->' . $producaoLadoDireito;
                    } else { // é um NT
                        if (isset($ladoDireito[$posicao + 2]) && $ladoDireito[$posicao + 2] == "'") {
                            $variavel .= "'"; // ve se tem uma ' na posicao seguinte
                        }

                        $this->buscaAndSetFirstProducao($variavel, $ladoEsquerdo, $producaoLadoDireito);
                        if (preg_match('/X/', $dadosGramatica[$variavel])) {
                            //se esse NT gera sentenca vazia, precisa buscar o follow desse NT tbm
                            $this->buscaAndSetFollowProducao($variavel, $ladoEsquerdo, $producaoLadoDireito);
                        }

                    }
                } else {
                    // se nao tem nenhuma variavel apos ela, pega o follow do lado esquerdo
                    $this->buscaAndSetFollowProducao($ladoEsquerdoNaoTerminal, $ladoEsquerdo, $producaoLadoDireito);
                }
            }
        }
    }

    //gera a tabela
    public function criaTabela($variaveisLadoEsquerdo, $variaveisLadoDireito) {
        $variaveisLadoDireito[] = '$';

        $html = '<table border="1">';
        $html .= '<tr>';
        $html .= '<th></th>';

        foreach ($variaveisLadoDireito as $variavelTerminal) {
            $html .= '<th>' . $variavelTerminal . '</th>';
        }

        $html .= '</tr>';

        foreach ($variaveisLadoEsquerdo as $variavelNaoTerminal) {
            $html .= '<tr>';
            $html .= '<td>' . $variavelNaoTerminal . '</td>';
            foreach ($variaveisLadoDireito as $variavelTerminal) {
                if (isset($this->arrayAgrupaLinhasColunas[$variavelNaoTerminal][$variavelTerminal])) {
                    $html .= '<td>' . $this->arrayAgrupaLinhasColunas[$variavelNaoTerminal][$variavelTerminal] . '</td>';
                } else {
                    $html .= '<td></td>';
                }
            }

            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

}

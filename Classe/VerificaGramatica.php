<?php

/**
 * Description of VerificaGramatica
 *
 * @author Willian
 */
require_once('Util.php');

class VerificaGramatica {

    private $gramatica = array();

    public function __construct($dadosGramatica) {
        $this->setGramatica($dadosGramatica);
    }

    private function setGramatica($gramatica) {
        $this->gramatica = $gramatica;
    }

    public function getGramatica() {
        return $this->gramatica;
    }

    public function validarRegrasLL1() {
        foreach ($this->getGramatica() as $ladoEsquerdo => $ladoDireito) {
            //var_dump($producao);
            $this->verificaRecursividadeEsquerda($ladoEsquerdo, $ladoDireito);
            $this->verificaFatoracao($ladoEsquerdo, $ladoDireito);
            $this->verificaAmbiguidade($ladoEsquerdo, $ladoDireito);
        }
    }

    private function verificaRecursividadeEsquerda($ladoEsquerdo, $ladoDireito) {
        /* quando o primeiro símbolo do lado direito da regra da produção é o mesmo 
          não terminal do lado esquerdo da produção. */
        $producoesLadoDireito = explode('|', $ladoDireito);
        foreach ($producoesLadoDireito as $producaoLadoDireito) {
            $primeiroSimbolo = $producaoLadoDireito[0];
            if ($primeiroSimbolo == $ladoEsquerdo) { //ex: E => Ea
                throw new Exception('1.Grámatica tem recursividade à esquerda!');
            }

            if (Util::isSimboloNaoTerminal($primeiroSimbolo) && !Util::isSentenciaVazia($primeiroSimbolo)) {
                $this->verificaProducaoSeComecamCom($primeiroSimbolo, $ladoEsquerdo);
            }
        }
    }

    /* verifica nas producoes desse primeiro simbolo se a o primeiro simbolo da producao dela é igual ao
      parametro $primeiroSimbolo passado na funcao
      ex:
      S => Aa
      A => Sb|cA|a */
    private function verificaProducaoSeComecamCom($primeiroSimbolo, $ladoEsquerdo) {
        $dadosGramatica = $this->getGramatica();        
        $producoes = explode('|', $dadosGramatica[$primeiroSimbolo]);
        foreach ($producoes as $producao) {
            $primeiroSimbolo = $producao[0];
            if ($primeiroSimbolo == $ladoEsquerdo) {
                throw new Exception('2.Grámatica tem recursividade à esquerda!');
            }
        }
    }

    private function verificaFatoracao($ladoEsquerdo, $ladoDireito) {
        //quando existe um não-determinismo nas regras de produções da gramática.
        //ex:A => aB|aC
        
        $producoes = explode('|', $ladoDireito);
        $primeiroSimboloTerminal = '';
        foreach ($producoes as $producao) {
            //pega o primeiro simbolo pra testar com o proximo da lista
            if(empty($primeiroSimboloTerminal)) {
                $primeiroSimboloTerminal = $producao[0];
                continue; // vai pro próximo da lista
            }
            
            $primeiroSimbolo = $producao[0];
            if($primeiroSimbolo == $primeiroSimboloTerminal) {
                throw new Exception('Deve-se fazer a fatoração da Grámatica!');
            }
        }
        
    }

    private function verificaAmbiguidade($ladoEsquerdo, $ladoDireito) {
        
    }

}

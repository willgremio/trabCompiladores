<?php

/**
 * Description of Util
 *
 * @author Willian
 */

class Util {
    
    public static function isSimboloNaoTerminal($string) {
        return preg_match('/[A-Z]/', $string); // NT só podem ser letras maiusculas
    }
    
    public static function isSentenciaVazia($simbolo) {
        return $simbolo == 'X';
    }
    
    public static function temAspasNaVariavel($primeiroSimboloLadoDireito, $ladoDireito = '') {
        if(empty($ladoDireito)) {
            return preg_match("/'/", $primeiroSimboloLadoDireito);
        }
        
        $posicao = strrpos($ladoDireito, $primeiroSimboloLadoDireito);
        if(isset($ladoDireito[$posicao + 1]) && $ladoDireito[$posicao + 1] == "'") {
            return true;
        }
        
        return false;
    }
}

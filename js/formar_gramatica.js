boxClicado = {};

$(function () {
    $('.variaveis').click(function () {
        var objeto = getObjetoClicado();
        var valorAntigo = $(objeto).val();
        var valor = $(this).val();
         //se só pode uma variavel e ja tem valor nela
        if(objeto.attr('maxlength') == 1 && valorAntigo != "") {
            return;
        }
        
        var valorNovo = valorAntigo + valor; // pega o valor que ja tinha no input e concatena com o valor clicado
        $(objeto).val(valorNovo);
    });

    $('.botaoLimpar').click(function () {
        var objeto = getObjetoClicado();
        $(objeto).val('');
    });

    $('#AdicionarGramatica').click(function () {
        var html = '<input maxlength="1" readonly class="box" name="data[GramaticaVariavel][Esquerdo][]" type="text" />';
        html += ' => ';
        html += '<input readonly class="box" name="data[GramaticaVariavel][Direito][]" type="text" /> ';
        html += '<br /><br />';
        $(html).insertBefore($(this));
    });

});

$(document).on('click', '.box', function () {
    $('.box').removeClass('boxClicado'); // remove background-color do outro box
    setObjetoClicado($(this));
    $(this).addClass('boxClicado');

});

function setObjetoClicado(objeto) {
    boxClicado = objeto;
}

function getObjetoClicado() {
    if (jQuery.isEmptyObject(boxClicado)) {
        alert('Clique em algum box primeiro!');
        return false;
    }

    return boxClicado;
}
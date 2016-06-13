$(function () {
    $('#ButtonTesteSentenca').click(function () {
        var sentenca = $('#sentenca').val();
        $.ajax({
            url: 'ajax/reconhecer_sentenca.php',
            data: {entrada : sentenca},
            success: function (retorno) {
                retorno = jQuery.parseJSON(retorno);
                $('#RespostaSentenca').html(retorno.msg + '<br />' + retorno.tabelaGerada);
            },
            error: function () {
                alert('Houve algum erro ao tentar fazer o teste da sentença!');
            }
        });
    });
});
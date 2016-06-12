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
});
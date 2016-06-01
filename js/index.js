$(function() {
    $("#NoTerminais, #Terminais").bind("keyup change", function(e) {
        var numero = $(this).val();
        if ($.isNumeric(numero)) {
            var html = 'Informe o símbolo de cada um:<br />';
            var tipo = $(this).data('tipo');
            var classe = 'terminais';
            if (tipo == 'NoTerminais') {
                classe = 'no_terminais';
            }

            for (var i = 1; i <= numero; i++) {
                html += i + ': <input type="text" maxlength="1" class="' + classe + '" name="data[' + tipo + '][]" /><br />';
            }

            $('#Inputs' + tipo).html(html);
        }
    })
});


$(document).on('keyup', '.no_terminais', function() {
    var variavel = $(this).val();
    $(this).val(variavel.toUpperCase());
});

$(document).on('keyup', '.terminais', function() {
    var variavel = $(this).val();
    $(this).val(variavel.toLowerCase());
});
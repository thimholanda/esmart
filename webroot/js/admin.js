
function solicita_tipo(elemento_codigo, elemento_nome) {
    $("#elemento_codigo_escolhido").val(elemento_codigo);
    $("#elemento_nome_escolhido").val(elemento_nome);

    dialog = $("#seleciona-tipo").dialog({
        autoOpen: false,
        height: 100,
        width: 100,
        dialogClass: 'dialog-tipo-elemento',
        modal: true,
    });
    dialog.dialog("open");
}

function insere_novo_elemento() {
    $("#list-novos-elementos").append("<tr id='row-" + $("#elemento_codigo_escolhido").val() + "'><input type='hidden' name='elemento_codigos[]' value='" + $('#elemento_codigo_escolhido').val() + "'/>\n\
        <input type='hidden' name='elemento_tipos[]' value='" + $('#tipo_escolhido').val() + "'/>\n\
        <input type='hidden' name='elemento_propriedades[]' value='" + $('#campo_propriedade_escolhida').val() + "'/> <td>" + $("#elemento_codigo_escolhido").val() + "</td><td>" + $("#elemento_nome_escolhido").val() + "</td><td><input type='button' value='Remover' onclick =\"remove_elemento('" + $("#elemento_codigo_escolhido").val() + "')\" /> </td>");
    dialog = $("#seleciona-tipo").dialog({});
    dialog.dialog("close");
}

function remove_elemento(elemento_codigo) {
    $("#row-" + elemento_codigo + "").remove();
}

function novo_elemento_dialog() {
    dialog = $("#novo-elemento").dialog({
        autoOpen: false,
        height: 350,
        width: 900,
        modal: true,
    });
    dialog.dialog("open");
}

function altera_tipo_escolhido(tipo) {
    //Se for campo
    if ($("#select-tipo").val() == 'c') {
        $("#label-tabela").css('display', 'none');
        $("#field-tabela").css('display', 'none');
        $("#label-campo").css('display', 'block');
        $("#field-campo").css('display', 'block');
        $("#row-dominio-propriedade").css('display', 'block');
        $("#row-formato-validador").css('display', 'block');
        //Se for tabela
    } else if ($("#select-tipo").val() == 'b') {
        $("#label-campo").css('display', 'none');
        $("#field-campo").css('display', 'none');
        $("#row-dominio-propriedade").css('display', 'none');
        $("#row-formato-validador").css('display', 'none');
        $("#label-tabela").css('display', 'block');
        $("#field-tabela").css('display', 'block');
        //Se for elementos de tela
    } else {
        $("#label-campo").css('display', 'none');
        $("#field-campo").css('display', 'none');
        $("#label-tabela").css('display', 'none');
        $("#field-tabela").css('display', 'none');
        $("#row-dominio-propriedade").css('display', 'none');
        $("#row-formato-validador").css('display', 'none');
    }

    $('#tipo_escolhido').val(tipo.value);
}


function altera_tipo_html_escolhido(tipo) {
    $('#tipo_html_escolhido').val(tipo.value);
}

function altera_propriedade_escolhido(tipo) {
    $('#campo_propriedade_escolhida').val(tipo.value);
}

function cadastra_novo_elemento() {
    $.ajax({
        type: 'POST',
        url: web_root + '/gertelger/gerinsele',
        data: {
            tipo_elemento: $("#select-tipo").val(), portugues: $("#portugues").val(), ingles: $("#ingles").val(),
            codigo: $("#codigo").val(), campo: $("#campo").val(), nome: $("#nome").val(), tabela: $("#tabela").val(),
            formato: $("#formato").val(), validador: $("#validador").val(),
            displayValue: $("#displayValue").val(), idValue: $("#idValue").val(),
            padrao: $("#padrao").val(), dominio: $("#dominio").val()},
        success: function (html) {
            $('#elementos_criados').val($('#elementos_criados').val() + '<br/>' + html);
            alert("Elemento cadastrado com sucesso");

            $("#list-novos-elementos").append("<tr id='row-" + $("#codigo").val() + "'>\n\\n\
                <input type='hidden' name='elemento_codigos[]' value='" + $("#codigo").val() + "'/>\n\
                <input type='hidden' name='elemento_tipos[]' value='" + $('#tipo_escolhido').val() + "'/>\n\
                <input type='hidden' name='elemento_propriedades[]' value='" + $('#campo_propriedade_escolhida').val() + "'/>\n\
                <td>" + $("#codigo").val() + "</td><td>" + $("#nome").val() + "</td><td><input type='button' value='Remover' onclick =\"remove_elemento('" + $("#codigo").val() + "')\" /> </td>");
            $("#cadastra-novo-elemento").trigger("reset");
            dialog = $("#novo-elemento").dialog({});
            dialog.dialog("close");

        },
        error: function (html) {
            console.log(html.responseText);
            alert("Erro ao inserir");
        }
    });

}

function edita_tela() {
    elementos_codigo = "";
    $('#list-novos-elementos > tbody  > tr').each(function () {
        $this = $(this);

        if (typeof $this.attr('id') === "undefined") {
        } else {
            elementos_codigo += $this.attr('id').split("-")[1] + "|";
        }
    });
    elementos_codigo = elementos_codigo.substring(0, elementos_codigo.length - 1);

    tipo_elementos = "";
    $('#list-novos-elementos > tbody  > tr').each(function () {
        $this = $(this);

        if (typeof $this.attr('id') === "undefined") {
        } else {
            codigo = $this.attr('id').split("-")[1];
            tipo_elementos += $("#propriedade-" + codigo).val() + "|";
        }
    });
    tipo_elementos = tipo_elementos.substring(0, tipo_elementos.length - 1);
    $.ajax({
        type: 'POST',
        url: web_root + '/gertelger/gertelatu/' + $("#tela_codigo").val(),
        data: {tela_nome: $("#tela_nome").val(), elementos: elementos_codigo, tipo_elementos: tipo_elementos},
        success: function (html) {
            alert("Tela editada com sucesso");
            window.location.href = web_root + "gertelger/gertelexi";
        },
        error: function (html) {
            console.log(html.responseText);
            alert("Erro ao inserir");
        }
    });
}
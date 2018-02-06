

function callAjax(url, parameters, successCallback) {
    $.ajax({
        type: 'POST',
        url: web_root_complete + url,
        data: parameters,
        success: successCallback,
        error: function (html) {
            console.log(html.responseText);
            //  alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
            $('.click_disabled').removeClass('click_disabled');
            finaliza_loading();
        },
    });
}

function openDialog(content, width, height, title, dialog_id, close_function) {
    if (typeof dialog_id !== 'undefined') {
        named_dialog = $('#' + dialog_id).dialog({autoOpen: false});
        if (!(named_dialog.dialog('isOpen'))) {
            named_dialog.dialog({
                title: title,
                autoOpen: true,
                height: height,
                width: width,
                modal: true
            });
        }
    } else {
        dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false});
        dialog_level_2 = $('#dialog_level_2').dialog({autoOpen: false});
        dialog_level_3 = $('#dialog_level_3').dialog({autoOpen: false});

        //Verifica se altura foi passada em porcentagem
        if (typeof height == "string" && height.indexOf('%') !== -1) {
            var wHeight = $(window).height();
            var height = wHeight * height.replace("%", "");
        }
        if (!(dialog_level_1.dialog('isOpen'))) {
            $('#content_dialog_level_1').html(content);
            dialog_level_1.dialog({
                title: title,
                autoOpen: true,
                height: height,
                width: width,
                modal: true,
                close: function (event, ui) {
                    if (typeof close_function !== 'undefined')
                        eval(close_function)
                }
            });
        } else if (!dialog_level_2.dialog('isOpen')) {
            $('#content_dialog_level_2').html(content);
            dialog_level_2.dialog({
                title: title,
                autoOpen: true,
                height: height,
                width: width,
                modal: true,
                close: function (event, ui) {
                    if (typeof close_function !== 'undefined')
                        eval(close_function)
                }
            });
        } else if (!dialog_level_3.dialog('isOpen')) {
            $('#content_dialog_level_3').html(content);
            dialog_level_3.dialog({
                title: title,
                autoOpen: true,
                height: height,
                width: width,
                modal: true,
                close: function (event, ui) {
                    if (typeof close_function !== 'undefined')
                        eval(close_function)
                }
            });
        }
    }
    finaliza_loading();
    //Limpa a classe que não permite realizar novos cliques
    $('.click_disabled').removeClass('click_disabled');
}

function closeDialog(dialog_id) {
    if (typeof dialog_id !== 'undefined') {
        if ($('#' + dialog_id).dialog('isOpen'))
            $("#" + dialog_id).dialog('close');
    } else {
        dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false, close: function (event, ui) {
                finaliza_loading();
            }});
        dialog_level_2 = $('#dialog_level_2').dialog({autoOpen: false, close: function (event, ui) {
                finaliza_loading();
            }});
        dialog_level_3 = $('#dialog_level_3').dialog({autoOpen: false, close: function (event, ui) {
                finaliza_loading();
            }});

        if ($('#dialog_level_3').dialog('isOpen'))
            $("#dialog_level_3").dialog('close');
        else if ($('#dialog_level_2').dialog('isOpen'))
            $("#dialog_level_2").dialog('close');
        else if ($('#dialog_level_1').dialog('isOpen'))
            $("#dialog_level_1").dialog('close');
    }

    //Limpa a classe que não permite realizar novos cliques
    $('.click_disabled').removeClass('click_disabled');
}

$.fn.hasAttr = function (name) {
    return this.attr(name) !== undefined;
};

//Libera o click dos botoes ao fechar o dialog
$(document).on("click", ".ui-icon-closethick", function () {
    $('.click_disabled').removeClass('click_disabled');

    //Se for a respaiatu e o primeiro nivel de dialog, limpa a marcação do painel
    if ($('#atual_pagina').val().includes('reservas/respaiatu'))
        removeMarcacao();
});

Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key))
            size++;
    }
    return size;
};


//Exibe as paginas via ajax
function gerpagexi(url, tela_menu_exibir, params) {

    if (!$(this).hasClass("click_disabled")) {
        //Verifica se a tela atual exige confirmação de saida
        if ($('#bloqueia_tela').length > 0 && $('#bloqueia_tela').val() == 1) {
            $('#germencri_mensagem').text('Deseja realmente sair desta tela?');
            dialog = $('#exibe-germencri').dialog({
                title: 'Ação Requerida',
                dialogClass: 'no_close_dialog',
                autoOpen: false,
                height: 200,
                width: 530,
                modal: true,
                buttons: [
                    {
                        text: 'Cancelar',
                        click: function () {
                            $(this).dialog('close');
                        }
                    },
                    {
                        text: 'Ok',
                        click: function () {
                            $('#bloqueia_tela').val(0)
                            gerpagexi(url, tela_menu_exibir, {});
                            $(this).dialog('close');
                        }
                    }
                ]
            });
            dialog.dialog('open');
        } else {
            inicia_loading();
            if (typeof params === 'string') {
                params = $.parseJSON(params.replace(/'/g, '"'));
            }

            if (Object.size(params) > 0) {
                // url_params = $.param();
                url_params = $.param(params);
            } else {
                url_params = '';
            }

            $.ajax({
                type: 'GET',
                url: web_root_complete + url + '?ajax=1&session_id=' + $('#session_id').val() + '&' + url_params,
                success: function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else {
                        finaliza_loading();
                        if (tela_menu_exibir == 0 && window.innerWidth > 768)
                            menu_esconde();
                        else {
                            if ($("#usuario_menu_exibir").val() == 0)
                                menu_esconde();
                            else
                                menu_exibe();
                        }

                        //Limpa possíveis dialogs que possam ter sido abertos em, caso o dialog atual não esteja aberto
                        dialog_atual = $('#' + $('#atual_dialog').val()).dialog({autoOpen: false});
                        if (!(dialog_atual.dialog('isOpen'))) {
                            $('#atual_dialog').val("");
                            $('#atual_dialog_page').val("");
                            $('#atual_dialog_params').val("");
                        }

                        //Verifica se a atualização deve ser feita em dialog ou na tela
                        if ($('#atual_dialog').val() !== "") {
                            $("#content_" + $('#atual_dialog').val()).html(html);
                            //Verifica se existe mais de um nivel de dialog além do dialog que receberá content (dialog nivel 3 ou maior)
                            //Nesse caso devemos fechar mais de um nivel
                            dialog_level_3 = $('#dialog_level_3').dialog({autoOpen: false});
                            if ($('#atual_dialog').val() == 'dialog_level_1' && $('#dialog_level_3').dialog('isOpen')) {
                                closeDialog();
                                closeDialog();
                            } else
                                closeDialog();
                        } else {
                            $("#atual_pagina").val(url);

                            if ($('#atual_pagina').val() == 'estadias/estpaiatu')
                                menu_esconde();
                            $("#inicial_processo_pagina").val(url);

                            $("#content").html(html);
                            controla_exibicao_botoes();
                        }
                    }
                },
                error: function (html) {
                    console.log(html.responseText);
                    //  alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                    $('.click_disabled').removeClass('click_disabled');
                    //  displayErrorDialog(html.responseText);
                    finaliza_loading();
                },
            });
        }
    }

    //Limpa content de dialogs que podem ter ficados, pois os dialogs estão em footer.ctp
    $('#content_dialog_level_1').empty();
    $('#content_dialog_level_2').empty();
    $('#content_dialog_level_3').empty();
    return false;
}

function inicia_loading() {
    loading = setTimeout(function () {
        $("#overlay").css("display", "block");
        $("#loading").css("display", "block");
    }, 500);
}

function finaliza_loading() {
    $("#overlay").css("display", "none");
    $("#loading").css("display", "none");
    clearTimeout(loading);

    setTimeout(function () {
        $("#overlay").css("display", "none");
        $("#loading").css("display", "none");
        clearTimeout(loading);
    }, 500);
}

/* Faz os submits dos forms via ajax*/
$(document).on("click", '.submit-button', function (event) {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var retorno_form_validador = 1;
        var form_id = $(this).attr("aria-form-id");
        var form_action = $("#" + form_id).attr('action');
        if ($("#" + form_id + " #form_validator_function").length > 0)
            retorno_form_validador = eval('(function() {' + $("#form_validator_function").val() + '}())');
        var exec = false;
        $("#" + form_id).submit(function (e) {
            e.preventDefault();
            //Preenche a segunda data em formulários de pesquisa
            if ($("#" + form_id + " #gerdatini").val() !== '') {
                if ($("#" + form_id + " #gerdatfin").val() === '')
                    $("#" + form_id + " #gerdatfin").val($("#" + form_id + " #gerdatini").val());
            }

            if ($("#" + form_id + " #gerdatfin").val() !== '') {
                if ($("#" + form_id + " #gerdatini").val() === '')
                    $("#" + form_id + " #gerdatini").val($("#" + form_id + " #gerdatfin").val());
            }
            if (retorno_form_validador == 1) {
                if (!exec) {
                    inicia_loading();
                    $.ajax({
                        url: form_action,
                        type: 'post',
                        dataType: 'html',
                        data: $("#" + form_id).serialize() + "&ajax=1&session_id=" + $('#session_id').val(),
                        success: function (html) {
                            // console.log(html);
                            if (html == 'sessao_expirada') {
                                window.location.href = web_root_complete + 'geral/gertelpri';
                            } else {
                                dialog_atual = $('#' + $('#atual_dialog').val()).dialog({autoOpen: false});
                                if (!(dialog_atual.dialog('isOpen'))) {
                                    $('#atual_dialog').val("");
                                    $('#atual_dialog_page').val("");
                                    $('#atual_dialog_params').val("");
                                }
                                //Verifica se a atualização deve ser feita em dialog ou na tela
                                if ($('#atual_dialog').val() !== "") {
                                    // console.log($('#atual_dialog_page').val());
                                    //  console.log($("#atual_dialog_params").val());
                                    gerpagexi($('#atual_dialog_page').val(), 1, $("#atual_dialog_params").val());
                                    finaliza_loading();
                                } else {
                                    if ($("#" + form_id + " " + "#url_redirect_after").length > 0) {
                                        gerpagexi($("#" + form_id + " " + "#url_redirect_after").val(), 1, {});
                                        closeDialog();
                                        closeDialog();
                                    } else {
                                        finaliza_loading()
                                        $("#atual_pagina").val(form_action.replace(web_root_complete, ""));
                                        $("#content").html(html);
                                    }
                                }
                            }
                        },
                        error: function (html) {
                            console.log(html.responseText);
                            $('.click_disabled').removeClass('click_disabled');
                            finaliza_loading();
                        }
                    });
                } else {
                    console.log('exec');
                    finaliza_loading();
                }
                exec = true;
            } else {
                console.log('Nao validado');
            }
        });
    }
});
/* Submit form por links de ordenação, paginação ou exportação - Início */
$('.page-link, .order-link, .export-csv-link, .export-pdf-link').click(function (e) {
    e.preventDefault();
    $("#form_force_submit").val('1');
});
$(document).on('click', '.pagination a', function (e) {
    $('#pagina').val($(this).attr('aria-pagina-numero'));
    inicia_loading();
    $.ajax({
        url: $("#" + $(this).attr("aria-form-id")).attr('action'),
        type: 'post',
        dataType: 'html',
        data: $("#" + $(this).attr("aria-form-id")).serialize() + "&ajax=1&session_id=" + $('#session_id').val(),
        success: function (html) {
            if (html == 'sessao_expirada') {
                window.location.href = web_root_complete + 'geral/gertelpri';
            } else {
                finaliza_loading();
                $("#content").html(html);
            }
        },
        error: function (html) {
            finaliza_loading();
            $('.click_disabled').removeClass('click_disabled');

        }
    });
    e.preventDefault();
});
$(document).on('click', 'div .order-link', function (e) {
    var form_id = $(this).attr("aria-form-id");
    inicia_loading();
    //Verifica se a ordenação atual é do sistema, caso seja, desfaz essa ordenação
    if ($('#ordenacao_sistema').val() == 1) {
        $('#ordenacao_coluna').val('');
        $('#ordenacao_tipo').val('');
        $('#ordenacao_sistema').val('0');
    }

//Coluna ainda não existente na ordenação
    if ($('#ordenacao_coluna').val().indexOf($(this).attr('aria-ordenacao-coluna')) < 0) {
        $('#ordenacao_coluna').val($('#ordenacao_coluna').val().concat($(this).attr('aria-ordenacao-coluna')).concat('|'));
        $('#ordenacao_tipo').val($('#ordenacao_tipo').val().concat('asc').concat('|'));
        //Coluna existe na ordenação
    } else {
//Busca o tipo de ordenação que essa coluna tinha
        var colunas = $('#ordenacao_coluna').val().split('|');
        var tipos = $('#ordenacao_tipo').val().split('|');
        var indice = colunas.indexOf($(this).attr('aria-ordenacao-coluna'));
        var removeu_ultimo = false;
        var total_colunas = colunas.length;

        //Se a ordenação desta coluna era DESC
        if (tipos[indice] == 'asc')
            tipos[indice] = 'desc';
        else if (tipos[indice] == 'desc') {
            colunas.splice(indice, 1);
            tipos.splice(indice, 1);
            if (indice == (total_colunas - 1))
                removeu_ultimo = true;
        }

//Remonta o vetor de colunas e tipos de acordo com as configurações geradas acima
        if (colunas.length > 1 && removeu_ultimo) {
            $('#ordenacao_coluna').val(colunas.join('|') + '|');
            $('#ordenacao_tipo').val(tipos.join('|') + '|');
        } else {
            $('#ordenacao_coluna').val(colunas.join('|'));
            $('#ordenacao_tipo').val(tipos.join('|'));
        }
    }

    $.ajax({
        url: $("#" + $(this).attr("aria-form-id")).attr('action'),
        type: 'post',
        dataType: 'html',
        data: $("#" + form_id).serialize() + "&ajax=1&session_id=" + $('#session_id').val(),
        success: function (html) {
            if (html == 'sessao_expirada') {
                window.location.href = web_root_complete + 'geral/gertelpri';
            } else {
                finaliza_loading();
                $("#content").html(html);
            }
        },
        error: function (html) {
            finaliza_loading();
            $('.click_disabled').removeClass('click_disabled');

        }
    });
    e.preventDefault();
});

function displayErrorDialog(html_erro) {
    if (html_erro != "")
        openDialog(html_erro, 1100, 700, 'Exibição de erros');
}


function PrintElem(elem) {
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head><title>' + document.title + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1></h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();
    return true;
}



function eventFire(el, etype) {
    if (el.fireEvent) {
        el.fireEvent('on' + etype);
    } else {
        var evObj = document.createEvent('Events');
        evObj.initEvent(etype, true, false);
        el.dispatchEvent(evObj);
    }
}

function getMesExtenso(mes) {
    arrayMes = new Array(12);
    arrayMes[0] = "Jan";
    arrayMes[1] = "Fev";
    arrayMes[2] = "Mar";
    arrayMes[3] = "Abr";
    arrayMes[4] = "Mai";
    arrayMes[5] = "Jun";
    arrayMes[6] = "Jul";
    arrayMes[7] = "Ago";
    arrayMes[8] = "Set";
    arrayMes[9] = "Out";
    arrayMes[10] = "Nov";
    arrayMes[11] = "Dez";
    return arrayMes[mes];
}

function getDiaExtenso(dia) {
    arrayDia = new Array(7);
    arrayDia[0] = "Dom";
    arrayDia[1] = "Seg";
    arrayDia[2] = "Ter";
    arrayDia[3] = "Qua";
    arrayDia[4] = "Qui";
    arrayDia[5] = "Sex";
    arrayDia[6] = "Sáb";
    return arrayDia[dia];
}


function atualizarDataHora() {
    var dataAtual = new Date();
    var dia = dataAtual.getDate();
    var diaSemana = getDiaExtenso(dataAtual.getDay());
    var mes = getMesExtenso(dataAtual.getMonth());
    var ano = dataAtual.getYear();
    var hora = dataAtual.getHours();
    var minuto = dataAtual.getMinutes();
    var segundo = dataAtual.getSeconds();
    if (minuto < 10)
        minuto = "0" + minuto;
    var horaImprimivel = hora + ":" + minuto;
    mostrarDataHora(horaImprimivel, diaSemana, dia, mes, ano);
    setTimeout("atualizarDataHora()", 1000);
}

$(document).on("change", ".data_incrementa_maior", function () {
    if (gerdatval($(this).val(), false)) {
        var vdate = $(this).val().split("/");
        var d = new Date();
        var dt = [];
        var id_proxima_data = $(this).attr('aria-id-campo-filho');

        //Checa se a data posterior já nao esta preenchida e maior que a inicial
        if ($("#" + id_proxima_data).val() === "")
            var to = $(this).val().split("/");
        else
            var to = $("#" + id_proxima_data).val().split("/");
        var data_posterior = new Date(to[2], to[1] - 1, to[0]);
        var data_anterior = new Date(vdate[2], vdate[1] - 1, vdate[0]);
        if (data_posterior <= data_anterior) {
            d.setDate(parseInt(vdate[0]));
            d.setMonth(parseInt(vdate[1]) - 1);
            d.setFullYear(parseInt(vdate[2]));
            d.setDate(d.getDate() + 1);
            dt[0] = ("0" + (d.getDate())).substr(-2);
            dt[1] = ("0" + (d.getMonth() + 1)).substr(-2);
            dt[2] = d.getFullYear();
            $("#" + id_proxima_data).val(dt[0] + "/" + dt[1] + "/" + dt[2]);
        }
    }
});

$(document).on("change", ".data_incrementa_igual", function () {
    if (gerdatval($(this).val(), false)) {
        var vdate = $(this).val().split("/");
        var d = new Date();
        var dt = [];
        var id_proxima_data = $(this).attr('aria-id-campo-filho');

        //Checa se a data posterior já nao esta preenchida e maior que a inicial
        if ($("#" + id_proxima_data).val() != "") {
            var to = $("#" + id_proxima_data).val().split("/");
            var data_posterior = new Date(to[2], to[1] - 1, to[0]);
            var data_anterior = new Date(vdate[2], vdate[1] - 1, vdate[0]);
            if (data_posterior <= data_anterior) {
                d.setDate(parseInt(vdate[0]));
                d.setMonth(parseInt(vdate[1]) - 1);
                d.setFullYear(parseInt(vdate[2]));
                d.setDate(d.getDate());
                dt[0] = ("0" + (d.getDate())).substr(-2);
                dt[1] = ("0" + (d.getMonth() + 1)).substr(-2);
                dt[2] = d.getFullYear();
                $("#" + id_proxima_data).val(dt[0] + "/" + dt[1] + "/" + dt[2]);
            }
        }
    }
});

function mostrarDataHora(hora, diaSemana, dia, mes, ano) {

    if (dia < 10) {
        retorno = "0" + dia + "/" + mes;
    } else {
        retorno = dia + "/" + mes;
    }
    retorno += " " + hora + "";
    if ($("#exibe_data_hora").length)
        document.getElementById("exibe_data_hora").innerHTML = retorno;
}

$(document).ready(function () {
    var arrayDia = new Array(7);
    arrayDia[0] = "Dom";
    arrayDia[1] = "Seg";
    arrayDia[2] = "Ter";
    arrayDia[3] = "Qua";
    arrayDia[4] = "Qui";
    arrayDia[5] = "Sex";
    arrayDia[6] = "Sáb";
    var arrayMes = new Array(12);
    arrayMes[0] = "Jan";
    arrayMes[1] = "Fev";
    arrayMes[2] = "Mar";
    arrayMes[3] = "Abr";
    arrayMes[4] = "Mai";
    arrayMes[5] = "Jun";
    arrayMes[6] = "Jul";
    arrayMes[7] = "Ago";
    arrayMes[8] = "Set";
    arrayMes[9] = "Out";
    arrayMes[10] = "Nov";
    arrayMes[11] = "Dez";
    var dataAtual = new Date();
    var dia = dataAtual.getDate();
    var diaSemana = arrayDia[dataAtual.getDay()];
    var mes = arrayMes[dataAtual.getMonth()];
    var ano = dataAtual.getYear();
    var hora = dataAtual.getHours();
    var minuto = dataAtual.getMinutes();
    var segundo = dataAtual.getSeconds();
    if (minuto < 10)
        minuto = "0" + minuto;
    var horaImprimivel = hora + ":" + minuto;
    mostrarDataHora(horaImprimivel, diaSemana, dia, mes, ano);
    setTimeout("atualizarDataHora()", 1000);
    /* function getMesExtenso(mes){
     return arrayMes[mes];
     }
     
     function getDiaExtenso(dia){
     return arrayDia[dia];
     }*/



    // $("#exibe_data_hora").text(dias[data.getDay()]+"," +data.getDate() +" de "+meses[data.getMonth()]);



    /* Tooltips - Inicio */
    $(function () {
        $.widget("ui.tooltip", $.ui.tooltip, {
            options: {
                content: function () {
                    return $(this).prop('title');
                }
            }
        });
        (function () {
            var _cache = {};
            $('.partida_valor_pago').each(function () {
                $(this).attr('title', function () {
                    var ele = $(this).attr('id'),
                            title;
                    if (_cache[ele]) {
                        title = _cache[ele];
                    } else {
                        _cache[ele] = $('#' + ele + '_tooltip').remove().html();
                        title = _cache[ele];
                    }
                    return title;
                });
            });
            _cache = undefined;
        }());
        $('.partida_valor_pago').tooltip({
            tooltipClass: "stdTooltip",
            items: '*:not(.ui-dialog-titlebar-close)',
            show: {
                duration: 0,
            },
            hide: {effect: "fade", duration: 100},
            position: {
                my: "center bottom-20",
                at: "center top"}
        });
    });
    $.widget("ui.tooltip", $.ui.tooltip, {
        options: {
            content: function () {
                return $(this).prop('title');
            }
        }
    });
    (function () {
        var _cache = {};
        $('.exibe_hospedes_tooltip').each(function () {
            $(this).attr('title', function () {
                var ele = $(this).attr('id'),
                        title;
                if (_cache[ele]) {
                    title = _cache[ele];
                } else {
                    _cache[ele] = $('#' + ele + '_tooltip').remove().html();
                    title = _cache[ele];
                }
                return title;
            });
        });
        _cache = undefined;
    }());
    $('.exibe_hospedes_tooltip').tooltip({
        tooltipClass: "stdTooltip",
        items: '*:not(.ui-dialog-titlebar-close)',
        show: {
            duration: 0,
        },
        hide: {effect: "fade", duration: 100},
        position: {
            my: "center top",
            at: "right top"}
    });
    $.widget("ui.tooltip", $.ui.tooltip, {
        options: {
            content: function () {
                return $(this).prop('title');
            }
        }
    });
    (function () {
        var _cache = {};
        $('.exibe_pagantees_tooltip').each(function () {
            $(this).attr('title', function () {
                var ele = $(this).attr('id'),
                        title;
                if (_cache[ele]) {
                    title = _cache[ele];
                } else {
                    _cache[ele] = $('#' + ele + '_tooltip').remove().html();
                    title = _cache[ele];
                }
                return title;
            });
        });
        _cache = undefined;
    }());
    $('.exibe_pagantees_tooltip').tooltip({
        tooltipClass: "stdTooltip",
        items: '*:not(.ui-dialog-titlebar-close)',
        show: {
            duration: 0,
        },
        hide: {effect: "fade", duration: 100},
        position: {
            my: "center top",
            at: "right top"}
    });
    $(function () {
        $.widget("ui.tooltip", $.ui.tooltip, {
            options: {
                content: function () {
                    return $(this).prop('title');
                }
            }
        });
        (function () {
            var _cache = {};
            $('.exibe_envios_tooltip').each(function () {
                $(this).attr('title', function () {
                    var ele = $(this).attr('id'),
                            title;
                    if (_cache[ele]) {
                        title = _cache[ele];
                    } else {
                        _cache[ele] = $('#' + ele + '_tooltip').remove().html();
                        title = _cache[ele];
                    }
                    return title;
                });
            });
            _cache = undefined;
        }());
        $('.exibe_envios_tooltip').tooltip({
            tooltipClass: "stdTooltip",
            items: '*:not(.ui-dialog-titlebar-close)',
            show: {
                duration: 0,
            },
            hide: {effect: "fade", duration: 100},
            position: {
                my: "center top",
                at: "right top"}
        });
    });
    /* Tooltips - Final */

    /* Seleção de múltiplos checkboxes - Inicio */
    $("#check_total").change(function () {
        total_partidas = $("#total_partidas").val();
        if (this.checked) {
            for (i = 1; i < total_partidas; i++) {
                if (!($("#check_partida_" + i).prop('disabled')))
                    $("#check_partida_" + i).prop("checked", true);
            }
            $("#check_virtual").prop("checked", true);
        } else {
            for (i = 0; i < total_partidas; i++) {
                $("#check_partida_" + i).prop("checked", false);
            }
            $("#check_virtual").prop("checked", false);
        }
        conpagatu();
    });
    /* Seleção de múltiplos checkboxes - Final */

    /* Altera propriedades de elementos - Inicio */
    $('#conitemod').on('submit', function () {
        $('input, select').prop('disabled', false);
        $('input, select').attr('readonly', 'readonly');
    });
    $('#serquacod, #serdoctip, #serinidat').on('change', function () {
//Verifica se os tres campos estão preenchidos
        if ($("#serquacod").val() != '' && $("#serdoctip").val() != '' && $("#serinidat").val() != '' &&
                ($("#serdoctip").val() == 'ca' || $("#serdoctip").val() == 'cc') || $("#serdoctip").val() == 'cf') {
            callAjax('/ajax/ajaxserrefexi', {empresa_codigo: $("#gerempcod").val(), quarto_codigo: $("#serquacod").val(),
                documento_tipo_codigo: $("#serdoctip").val(), data: $("#serinidat").val()},
                    function (html) {
                        if (html == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            dados_documento_referenciado = JSON.parse(html);
                            //Remove adicionais anteriores, possivelmente criados por outros parametros
                            divs_linha_adicionais = $('div[id^="dados_serdocref_adicional_"]');
                            if (dados_documento_referenciado.length > 0) {
                                $.each(divs_linha_adicionais, function (key, value) {
                                    //Elimina todas as outras maiores que 0
                                    if (key > 0) {
                                        $("#dados_serdocref_adicional_" + key).remove();
                                    }
                                });
                                $("#serdocref").val("");
                                $("#resentdat").val("");
                                $("#resaduqtd").val("");
                                $("#rescriqtd").val("");
                                $("#resaditit-0").val("");
                                $("#resadiqtd-0").val("");
                                $("#resprefat-0").val("");
                                indice = 0;
                                for (i = 0; i < dados_documento_referenciado.length; i++) {
                                    if (dados_documento_referenciado[i].nome != null && dados_documento_referenciado[i].nome.length > 0) {
                                        if (indice == 0) {
                                            $("#serdocref").val(dados_documento_referenciado[i].documento_numero);
                                            $("#resentdat").val(formataDataHora(dados_documento_referenciado[i].inicial_data));
                                            $("#resaduqtd").val(dados_documento_referenciado[i].adulto_qtd);
                                            $("#rescriqtd").val(dados_documento_referenciado[i].crianca_qtd);
                                            $("#resaditit-0").val(dados_documento_referenciado[i].nome);
                                            $("#resadiqtd-0").val(dados_documento_referenciado[i].adicional_qtd);
                                            $("#resprefat-0").val(dados_documento_referenciado[i].variavel_fator_nome);
                                        } else {
                                            var clone = $("#dados_serdocref_adicional_0").clone();
                                            clone.attr('id', 'dados_serdocref_adicional_' + parseInt(indice));
                                            clone.find("#resaditit-0").attr("id", "resaditit-" + indice).val(dados_documento_referenciado[i].nome);
                                            clone.find("#resadiqtd-0").attr("id", "resadiqtd-" + indice).val(dados_documento_referenciado[i].adicional_qtd);
                                            clone.find("#resprefat-0").attr("id", "resprefat-" + indice).val(dados_documento_referenciado[i].variavel_fator_nome);
                                            $("#info_adicionais").append(clone);
                                        }

                                        indice = indice + 1;
                                    }
                                }
                                $("#info_adicionais").css('display', 'block');
                            } else {
                                $("#info_adicionais").css('display', 'none');
                            }
                        }
                    });
        } else {
            $("#info_adicionais").css('display', 'none');
        }
    });
    /* Altera propriedades de elementos - Final */

    /* Autocomplete - Inicio */
    $(document).on('keydown.autocomplete', '#c_nome_autocomplete', function () {
        $(this).autocomplete({
            source: function (request, response) {

                $.ajax({
                    url: web_root + 'clientes/cliconaut?search=' + $("#c_nome_autocomplete").val() + '&tela_exibe_sobrenome=' + $('#clisobnom').length,
                    type: 'GET',
                    success: function (html) {

                        response(html);
                    },
                    error: function (html) {
                        console.log(html.responseText);
                        // alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    },
                    dataType: 'json'
                });
            },
            minLength: 3,
            response: function (event, ui) {
                // console.log(ui);
                $('.ui-autocomplete').zIndex($(".ui-dialog").zIndex() + 2);
                if ($("#c_codigo").val() !== '0') {
                    $("#c_codigo").removeAttr('value');
                    $("#cliprinom").val('');
                    $("#clisobnom").val('');
                    $("#clicadema").val('');
                    $("#clidoctip").val('');
                    $("#clidocnum").val('');
                    $("#clicpfcnp").val('');
                    $("#clicelnum").val('');
                    $("#clitelnum").val('');
                    //Predefine o pagador com as informações do contratante
                    $('#pag_codigo_1').val('');
                    $('#pagante_nome_1').val('');
                    $('#pagante_cpf_cnpj_1').val('');
                    $('[id^="hospede_mesmo_contratante_"]').each(function () {
                        if ($(this).is(':checked')) {
                            var id_atual = $(this).attr('id').replace('hospede_mesmo_contratante_', '');
                            $('#h_codigo_' + id_atual + '_1').val($("#c_codigo").val());
                            $('#h_nome_' + id_atual + '_1').val($("#cliprinom").val());
                            $('#h_sobrenome_' + id_atual + '_1').val($("#clisobnom").val());
                            $('#h_email_' + id_atual + '_1').val($("#clicadema").val());
                        }
                    });
                }

                if (ui.content.length === 0) {
                    $("#empty-message").text("No results found");
                } else {
                    $("#empty-message").empty();
                }
            },
            select: function (event, ui) {
                $("#c_codigo").val(ui.item.c_codigo);

                //Verifica se na tela existe o campo de sobrenome. Se sim, atualiza o input, caso contrário, o campo nome é concatenado com o sobrenome
                if ($("#clisobnom").length == 0) {
                    $("#c_nome_autocomplete").val(ui.item.c_nome + ' ' + ui.item.c_sobrenome);
                    $("#cliprinom").val(ui.item.c_nome + ' ' + ui.item.c_sobrenome);
                } else
                    $("#clisobnom").val(ui.item.c_sobrenome);

                $("#clicadema").val(ui.item.c_email);
                $("#clidoctip").val(ui.item.c_documento_tipo);
                $("#clidocnum").val(ui.item.c_documento_numero);
                if (ui.item.c_cpf_numero != '' || ui.item.c_cnpj_numero != '')
                    $("#clicpfcnp").attr('disabled', 'disabled');
                else
                    $("#clicpfcnp").attr('disabled', false);
                if (ui.item.c_cpf_numero != '')
                    $("#clicpfcnp").val(ui.item.c_cpf_numero);
                else if (ui.item.c_cnpj_numero != '')
                    $("#clicpfcnp").val(ui.item.c_cnpj_numero);
                $("#clicelddi").val(ui.item.c_cel_ddi);
                $("#clicelnum").val(ui.item.c_cel_numero);
                $("#clitelddi").val(ui.item.c_tel_ddi);
                $("#clitelnum").val(ui.item.c_tel_numero);
                /*$("#h_codigo_1_1").val(ui.item.c_codigo);
                 $("#h_nome_1_1").val(ui.item.c_nome);
                 $("#h_email_1_1").attr('disabled', 'disabled');
                 $("#h_sobrenome_1_1").val(ui.item.c_sobrenome);
                 $("#h_email_1_1").val(ui.item.c_email);*/

                $('[id^="hospede_mesmo_contratante_"]').each(function () {
                    if ($(this).is(':checked')) {
                        var id_atual = $(this).attr('id').replace('hospede_mesmo_contratante_', '');
                        $('#h_codigo_' + id_atual + '_1').val(ui.item.c_codigo);
                        $('#h_nome_' + id_atual + '_1').val(ui.item.c_nome);
                        $('#h_sobrenome_' + id_atual + '_1').val(ui.item.c_sobrenome);
                        $('#h_email_' + id_atual + '_1').val(ui.item.c_email);
                    }
                });

                for (i = 2; i <= $("#total_adt_mais_cri_js").val(); i++) {
                    $("#h_codigo_" + i).removeAttr('value');
                    $("#h_nome_" + i).val('');
                    $("#h_sobrenome_" + i).val('');
                    $("#h_email_" + i).val('');
                }

                //Predefine o pagador com as informações do contratante
                $('#pag_codigo_1').val($('#c_codigo').val());
                $('#pagante_nome_1').val(ui.item.c_nome + ' ' + ui.item.c_sobrenome);
                $('#pagante_cpf_cnpj_1').val($('#clicpfcnp').val());
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {

//Add the .ui-state-disabled class and don't wrap in <a> if value is empty
            if (item.value === '') {
                if ($("#clicadmod_redirecionar").length && $("#clicadmod_redirecionar").val() != '')
                    return $('<li class="ui-state-disabled clicadmod" aria-cliente-codigo=' + item.c_codigo + '>' + item.label + '</li>').appendTo(ul);
                else
                    return $('<li class="ui-state-disabled">' + item.label + '</li>').appendTo(ul);
            } else {
                if (item.c_cpf_numero == null || item.c_cpf_numero.length === 0) {
                    if ($("#clicadmod_redirecionar").length && $("#clicadmod_redirecionar").val() != '')
                        return $("<li class='clicadmod' aria-cliente-codigo=" + item.c_codigo + ">")
                                .append("<span>" + item.label + "</span>")
                                .appendTo(ul);
                    else
                        return $("<li>")
                                .append("<span>" + item.label + "</span>")
                                .appendTo(ul);
                } else {
                    if ($("#clicadmod_redirecionar").length && $("#clicadmod_redirecionar").val() != '')
                        return $("<li class='clicadmod' aria-cliente-codigo=" + item.c_codigo + ">")
                                .append("<span>" + item.label + "</span> | " + item.c_cpf_numero)
                                .appendTo(ul);
                    else
                        return $("<li>")
                                .append("<span>" + item.label + "</span> | " + item.c_cpf_numero)
                                .appendTo(ul);
                }
            }
        };
    });
    /* Autocomplete para produtos - Inicio */
    $(document).on('keydown.autocomplete', '.produto_autocomplete', function () {
        var codigo_produto_id = $(this).attr('data-produto-codigo');
        $(this).autocomplete({
            source: function (request, response) {
                if (request.term.trim().length > 0 && request.term.trim() != '') {
                    empresa_codigo = $('#gerempcod').val();
                    $("#has_select").val('0');
                    $('#' + codigo_produto_id).val('');
                    $("#concontip").val('');
                    $("#produto_tipo_codigo").val('');
                    $('#variavel_fator_nome').text('');
                    $('#conta_editavel_preco').val('');
                    if ($("#conpreuni").length > 0) {
                        $("#conpreuni").val(gervalexi(0));
                        $('#conpretot').val(gervalexi(0));
                        $('#servico_taxa_incide').val(gervalexi(0));
                    }
                    $('#conpreuni').prop('readonly', true);
                    $('#conbtndes').css('display', 'block');
                    $('#item_desconto_geral').css('display', 'none');
                    $('#gertipmot_geral_desc').css('display', 'none');
                    $('#gertipmot_geral_acre').css('display', 'none');
                    $('#horario_modificacao_tipo').val(null);
                    $('#horario_modificacao_valor').val(null);

                    //Checa se está na tela de folha de custo
                    if ($('#atual_dialog').val() == 'custos/cusfolexi' || $('#atual_pagina').val() == 'custos/cusfolexi') {
                        //Busca qual a linha modificada
                        var linha_modificada = codigo_produto_id.replace('conprocod_', '');
                        $('#unitario_custo_' + linha_modificada).val('');
                        $('#prounimed_' + linha_modificada).val('');
                        $('#qtd_' + linha_modificada).val('');
                        $('#conprocod_' + linha_modificada).val('');
                        $('#total_custo_' + linha_modificada).val('');
                    }

                    $.ajax({
                        url: web_root + 'geral/progeraut',
                        data: {
                            search: request.term.trim(),
                            empresa_codigo: empresa_codigo,
                            venda_ponto_codigo: $('#convenpon').val(),
                            vendavel: $('#vendavel').val(),
                            quarto_status_codigo: $('#quarto_status_codigo_atual').val()
                        },
                        type: 'GET',
                        success: function (data) {
                            if (data == 'sessao_expirada')
                                window.location.href = web_root_complete + 'geral/gertelpri';
                            else {
                                response(data);
                            }
                        },
                        error: function (html) {
                            console.log(html.responseText);
                            // alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                            $('.click_disabled').removeClass('click_disabled');
                            finaliza_loading();
                        },
                        dataType: 'json'
                    });
                }
            },
            minLength: 1,
            response: function (event, ui) {
                if (ui.content.length === 0) {
                    $("#empty-message").text("No results found");
                } else {
                    $("#empty-message").empty();
                }
            },
            select: function (event, ui) {
                $("#has_select").val('1');
                $('#' + codigo_produto_id).val(ui.item.produto_codigo);
                $("#concontip").val(ui.item.contabil_tipo);
                $('#variavel_fator_nome').text(ui.item.variavel_fator_nome);
                $('#conta_editavel_preco').val(ui.item.conta_editavel_preco);
                $("#produto_tipo_codigo").val(ui.item.produto_tipo_codigo);
                $('#horario_modificacao_tipo').val(ui.item.horario_modificacao_tipo);
                $('#horario_modificacao_valor').val(ui.item.horario_modificacao_valor);
                if ($("#conpreuni").length > 0) {
                    $("#conpreuni").val(gervalexi(ui.item.preco));
                    $('#conpretot').val(gervalexi(ui.item.preco * gervalper($('#conproqtd').val())));
                    $('#servico_taxa_incide').val(ui.item.servico_taxa_incide);
                }

                //Verifica a variável conta_editavel_preco
                if (ui.item.conta_editavel_preco == 1) {
                    //Libera o preço para edição/desconto e exibe outros campos pertinentes
                    $('#conpreuni').prop('readonly', false);
                    $('#conproqtd').val('1');
                    $('#item_desconto_geral').css('display', 'block');
                    if (ui.item.contabil_tipo == 'C') {
                        $('#gertipmot_geral_desc').css('display', 'block');
                        $('#gertipmot_geral_acre').css('display', 'none');
                    } else {
                        $('#gertipmot_geral_desc').css('display', 'none');
                        $('#gertipmot_geral_acre').css('display', 'block');
                    }
                } else {
                    //Bloqueia a edição do preço e esconde outros campos pertinentes
                    $('#conpreuni').prop('readonly', true);
                    $('#item_desconto_geral').css('display', 'none');
                    $('#gertipmot_geral_desc').css('display', 'none');
                    $('#gertipmot_geral_acre').css('display', 'none');
                }

                //Verifica se o desconto é habilitado
                if (ui.item.desconto_habilita == 1)
                    $('#conbtndes').css('display', 'block');
                else
                    $('#conbtndes').css('display', 'none');

                //Checa se está na tela de listagem técnica e é o autocomplete no produto pai
                if (($('#atual_pagina').val() == 'produtos/prolisexi' || $('#atual_pagina').val() == 'produtos/prolismod')) {
                    //Se for auto complete no produto pai
                    if (codigo_produto_id == 'conprocod') {
                        $('#produto_pai_variavel_fator_nome').text(ui.item.variavel_fator_nome);
                        $('#convarnom').val(ui.item.variavel_fator_nome);
                        $('#conpronom').val(ui.item.nome);
                        //Muda a action do form para apenas exibir os produtos filhos do produto selecionado
                        $('#prolismod').attr('action', $('#prolismod').attr('action').replace("produtos/prolismod", "produtos/prolisexi"));
                        $('#prolisexi_exibir').click();
                    } else {
                        //AC no produto filho
                        var linha_modificada = codigo_produto_id.replace('conprocod_', '');
                        $('#prounimed_' + linha_modificada).val(ui.item.preco_fator_codigo);
                    }
                }



                //Checa se está na tela de folha de custo
                if ($('#atual_dialog_page').val() == '/custos/cusresexi/') {
                    //Chama a função cuscomexi para alterar o custo unitario e unidade de medida
                    var preco_fator_codigo = ui.item.preco_fator_codigo;
                    callAjax('/custos/cuscomexi', {produto_codigo: ui.item.produto_codigo}, function (html) {
                        var custo_folha = JSON.parse(html);

                        //Busca qual a linha modificada
                        var linha_modificada = codigo_produto_id.replace('conprocod_', '');
                        $('#unitario_custo_' + linha_modificada).val(gervalexi(custo_folha.unitario_custo));
                        $('#prounimed_' + linha_modificada).val(preco_fator_codigo);

                        $('.unitario_folha_custo_item').trigger('keyup');
                    });
                }
            }
        });
    });

    //Limpa o codigo do produto caso o usuario limpe o campo e saia sem digitar nada
    $(document).on('blur', '.produto_autocomplete', function () {
        var codigo_produto_id = $(this).attr('data-produto-codigo');
        if ($(this).val() == "") {
            $('#' + codigo_produto_id).val('');
            $('#has_select').val('');
        }
    });

    //Limpa o codigo do cliente caso o usuario limpe o campo e saia sem digitar nada
    $(document).on('blur', '#c_nome_autocomplete', function () {
        if ($(this).val() == "") {
            $('#c_codigo').val('');
        }
    });
    /* Autocomplete para cidades - Inicio */
    $(document).on('keydown.autocomplete', '.cidade_autocomplete', function () {
        $(this).autocomplete({
            source: function (request, response) {
                estado_codigo_value = $("#" + this.element.attr('aria-estado_referencia')).val();
                pais_nome_value = $("#" + this.element.attr('aria-pais_referencia')).val();
                $("#has_select").val('0');
                $.ajax({
                    url: web_root + 'geral/gercidaut',
                    data: {
                        search: request.term,
                        estado_codigo: estado_codigo_value,
                        pais_nome: pais_nome_value,
                    },
                    type: 'GET',
                    success: function (data) {
                        if (data == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            response(data);
                        }
                    },
                    error: function (html) {
                        // alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    },
                    dataType: 'json'
                });
            },
            minLength: 3,
            response: function (event, ui) {

                if (ui.content.length === 0) {
                    $("#empty-message").text("No results found");
                } else {
                    $("#empty-message").empty();
                }
            },
            select: function (event, ui) {
                $("#has_select").val('1');
            }
        });
    });
    $(document).on('keydown.autocomplete', '.h_nome', function () {
        $(this).autocomplete();
    });
    $(document).on('focus', '.h_nome', function () {
        var form_id = $(this).closest("form").attr('id')
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: web_root + 'clientes/clirelaut',
                    data: {
                        search: request.term,
                        cliente_codigo_1: $("#" + form_id + " #c_codigo").val()
                    },
                    type: 'GET',
                    success: function (data) {
                        if (data == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            response(data);
                        }
                    },
                    error: function (html) {
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    },
                    dataType: 'json'
                });
            },
            minLength: 3,
            response: function (event, ui) {
                quarto_item = this.id.split("_")[2];
                linha = this.id.split("_")[3];
                $('.ui-autocomplete').zIndex($(".ui-dialog").zIndex() + 1);
                $("#h_codigo_" + quarto_item + "_" + linha).removeAttr('value');
                $("#h_has_changed_" + quarto_item + "_" + linha).val("0");
                $("#h_sobrenome_" + quarto_item + "_" + linha).val("");
                $("#h_email_" + quarto_item + "_" + linha).val("");
                $("#h_email_" + quarto_item + "_" + linha).attr('disabled', false);
                $("#h_cpfnum_" + quarto_item + "_" + linha).val('');
                $("#h_doctip_" + quarto_item + "_" + linha).val('');
                $("#h_docnum_" + quarto_item + "_" + linha).val('');
            },
            select: function (event, ui) {
                quarto_item = this.id.split("_")[2];
                linha = this.id.split("_")[3];
                if ($("#clean_entries_" + quarto_item + "_" + linha).val() == '1') {
                    $("#h_has_changed_" + quarto_item + "_" + linha).val("0");
                    $("#h_codigo_" + quarto_item + "_" + linha).removeAttr('value');
                    $("#h_nome_" + quarto_item + "_" + linha).val('');
                    $("#h_sobrenome_" + quarto_item + "_" + linha).val('');
                    $("#h_email_" + quarto_item + "_" + linha).val('');
                    $("#h_cpfnum_" + quarto_item + "_" + linha).val('');
                    $("#h_doctip_" + quarto_item + "_" + linha).val('');
                    $("#h_docnum_" + quarto_item + "_" + linha).val('');
                } else {
                    $("#h_has_changed_" + quarto_item + "_" + linha).val("0");
                    $("#h_codigo_" + quarto_item + "_" + linha).val(ui.item.c_codigo);
                    $("#h_sobrenome_" + quarto_item + "_" + linha).val(ui.item.c_sobrenome);
                    $("#h_email_" + quarto_item + "_" + linha).val(ui.item.c_email);
                    $("#h_cpfnum_" + quarto_item + "_" + linha).val(ui.item.c_cpf);
                    $("#h_doctip_" + quarto_item + "_" + linha).val(ui.item.c_documento_tipo);
                    $("#h_docnum_" + quarto_item + "_" + linha).val(ui.item.c_documento_numero);
                }

            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
//Add the .ui-state-disabled class and don't wrap in <a> if value is empty
            if (item.value == '') {
                return $('<li class="ui-state-disabled">' + item.label + '</li>').appendTo(ul);
            } else {
                return $("<li>")
                        .append("<span>" + item.label + "</span>")
                        .appendTo(ul);
            }
        };
    });
    $(document).on('keydown.autocomplete', '#c_nome_autocomplete_contas', function () {
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: web_root + 'clientes/cliconaut?search=' + $("#c_nome_autocomplete_contas").val(),
                    type: 'GET',
                    success: function (data) {
                        if (data == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            response(data);
                        }
                    },
                    error: function (html) {
                        //  alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    },
                    dataType: 'json'
                });
            },
            minLength: 3,
            response: function (event, ui) {
                $("#cliente_codigo").removeAttr('value');
                // ui.content is the array that's about to be sent to the response callback.
                if (ui.content.length === 0) {
                    $("#empty-message").text("No results found");
                } else {
                    $("#empty-message").empty();
                }
            },
            select: function (event, ui) {
                codigo_cliente = ui.item.c_codigo;
                $.ajax({
                    type: 'POST',
                    url: web_root + '/clientes/clireslis',
                    data: {empresa_codigo: $("#gerempcod").val(), cliente_codigo: ui.item.c_codigo},
                    success: function (html) {
                        if (html == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            $("#exibe-reservas-cliente table.table_cliclipes").find("tbody tr").remove();
                            $("#exibe-reservas-cliente tbody").append(html);
                            $("#nome-contratante-conta").text(ui.item.c_nome + " " + ui.item.c_sobrenome);
                            dialog = $("#exibe-reservas-cliente").dialog({
                                autoOpen: false,
                                height: 400,
                                width: 450,
                                modal: true,
                                dialogClass: 'dialog-reserva-clientes'
                            });
                            dialog.dialog("open");
                        }
                    },
                    error: function (html) {
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    }
                });
            }
        });
    });
    //Autocomplete para pagantees
    $(document).on('keydown.autocomplete', '.pagante_nome', function () {
        $(this).autocomplete();
    });
    $(document).on('focus', '.pagante_nome', function () {
        id_pagamento = $(this).attr('id');
        linha_pagamento = id_pagamento.split("_")[2];
        $(this).autocomplete({
            source: function (request, response) {
                if ($("#respagfor_" + linha_pagamento).val() != '5') {
                    mais_opcoes = '1';
                } else {
                    mais_opcoes = '0';
                }
                $.ajax({
                    url: web_root + 'clientes/cliconaut',
                    data: {
                        search: request.term, cliente_codigo_1: $("#clicadcod").val(),
                        proprio_cliente: '1', nome_sobrenome: '1', mais_opcoes: mais_opcoes
                    },
                    type: 'GET',
                    success: function (data) {
                        if (data == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            response(data);
                        }
                    },
                    error: function (html) {
                        //  alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    },
                    dataType: 'json'
                });
            },
            minLength: 3,
            response: function (event, ui) {
                $('.ui-autocomplete').zIndex($(".ui-dialog").zIndex() + 1);
                linha = this.id.split("_")[2];
                $("#pag_codigo_" + linha).removeAttr('value');
                $("#pagante_cpf_cnpj_" + linha).val('');
                // ui.content is the array that's about to be sent to the response callback.
                if (ui.content.length === 0) {
                    $("#empty-message").text("No results found");
                } else {
                    $("#empty-message").empty();
                }
            },
            select: function (event, ui) {
                linha = this.id.split("_")[2];
                $("#linha_pgto_atual").val(linha);
                $("#pag_codigo_" + linha).val(ui.item.c_codigo);
                $("#pagante_nome_" + linha).val(ui.item.c_nome + " " + ui.item.c_sobrenome);
                if (ui.item.c_cpf != '')
                    $("#pagante_cpf_cnpj_" + linha).val(ui.item.c_cpf_numero);
                else
                    $("#pagante_cpf_cnpj_" + linha).val(ui.item.c_cnpj_numero);

                var pagamento_forma_codigo = $("#respagfor_" + linha).val().split('|')[0];
                if (pagamento_forma_codigo == '5' && $("#pag_codigo_" + linha).val() != '0' && $("#pag_codigo_" + linha).val() != '') {
                    callAjax('/ajax/ajaxconcreexi', {cliente_codigo: $("#pag_codigo_" + linha).val()},
                            function (html_concreexi) {
                                jsonDC = JSON.parse(html_concreexi);
                                $("#forma_valor_" + linha).val(gervalexi(jsonDC.credito_saldo));
                                /* if ($("#conpagcri_dialog #contabil_tipo").val() == 'D') {
                                 $("#forma_valor_" + linha_pagamento).val(gervalexi(0));
                                 } else {
                                 if (jsonDC.credito_saldo < gervalper($("#convaapag").val()))
                                 $("#forma_valor_" + linha_pagamento).val(gervalexi(jsonDC.credito_saldo));
                                 else
                                 $("#forma_valor_" + linha_pagamento).val(valor_a_pagar);
                                 } */
                            });
                }
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {

            if (item.value == '') {
                return $('<li class="ui-state-disabled">' + item.label + '</li>').appendTo(ul);
            } else {
                return $("<li>")
                        .append("<span>" + item.label + "</span>")
                        .appendTo(ul);
            }
        };
    });

    //Limpa o codigo do pagante caso o usuario limpe o campo e saia sem digitar nada
    $(document).on('blur', '.pagante_nome', function () {
        if ($(this).val() == "") {
            linha = $(this).attr('id').split("_")[2];
            $("#pag_codigo_" + linha).val('');
        }
    });

    //Autocomplete para pagantees de pre autorizacao
    $(document).on('keydown.autocomplete', '.pre_pagante_nome', function () {
        $(this).autocomplete();
    });
    $(document).on('focus', '.pre_pagante_nome', function () {
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: web_root + 'clientes/cliconaut',
                    data: {
                        search: request.term
                    },
                    type: 'GET',
                    success: function (data) {
                        if (data == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            response(data);
                        }
                    },
                    error: function (html) {
                        //  alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                        $('.click_disabled').removeClass('click_disabled');
                        finaliza_loading();
                    },
                    dataType: 'json'
                });
            },
            minLength: 3,
            response: function (event, ui) {
                $('.ui-autocomplete').zIndex($(".ui-dialog").zIndex() + 1);
                $("#pre_pag_codigo").removeAttr('value');
                $("#pre_pagante_cpf_cnpj").val('');

                if (ui.content.length === 0) {
                    $("#empty-message").text("No results found");
                } else {
                    $("#empty-message").empty();
                }
            },
            select: function (event, ui) {
                $("#pre_pag_codigo").val(ui.item.c_codigo);
                $("#pre_pagante_nome").val(ui.item.c_nome + " " + ui.item.c_sobrenome);
                if (ui.item.c_cpf != '')
                    $("#pre_pagante_cpf_cnpj").val(ui.item.c_cpf_numero);
                else
                    $("#pre_pagante_cpf_cnpj").val(ui.item.c_cnpj_numero);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {

            if (item.value == '') {
                return $('<li class="ui-state-disabled">' + item.label + '</li>').appendTo(ul);
            } else {
                return $("<li>")
                        .append("<span>" + item.label + "</span>")
                        .appendTo(ul);
            }
        };
    });

    //Limpa o codigo do pagante caso o usuario limpe o campo e saia sem digitar nada
    $(document).on('blur', '.pre_pagante_nome', function () {
        if ($(this).val() == "") {
            $('#pre_pag_codigo').val('');
        }
    });
    /* Autocomplete - Final */


    $(document).on('click', 'div .barra_funcoes a.export-csv-link ', function (e) {
        $('#export_csv').val(1);
        $('<input>').attr({
            type: 'hidden',
            name: 'ajax', value: '1'
        }).appendTo('#' + $("#aria-form-id-csv").val());
        $('#' + $("#aria-form-id-csv").val()).submit();
        e.preventDefault();
        $('#export_csv').val(0);
    });
    $(document).on('click', 'div .barra_funcoes a.export-pdf-link', function (e) {
        $('#' + $("#aria-form-id-pdf").val()).attr('target', '_blank');
        $('#export_pdf').val(1);
        form_action_original = $('#' + $("#aria-form-id-pdf").val()).attr('action').replace(".pdf", "");
        $('#' + $("#aria-form-id-pdf").val()).attr('action', form_action_original + ".pdf");
        $('#' + $("#aria-form-id-pdf").val()).submit();
        e.preventDefault();
        $('#' + $("#aria-form-id-pdf").val()).attr('action', form_action_original);
        $('#export_pdf').val(0);
    });
    /* Submit form por links de ordenação, paginação ou exportação - Final*/




});
$(document).on("focusout", ".dropdown .gerdocsta", function () {
    $(".dropdown dd ul").hide();
});
$(document).mouseup(function (e)
{
    var container = $(".mutliSelect #serdocsta");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0)
        container.hide();
}
);

/* Masks - Início */
$(document).on("focus", ".moeda", function () {
    if (decimal_separador == ",") {
        $(this).maskMoney({thousands: '.', decimal: ','});
    } else if (decimal_separador == '.') {
        $(this).maskMoney({thousands: ',', decimal: '.'});
    }
});

$(document).on("focus", ".moeda_sem_decimais", function () {
    if (decimal_separador == ",") {
        $(this).maskMoney({thousands: '.', decimal: ',', precision: 0});
    } else if (decimal_separador == '.') {
        $(this).maskMoney({thousands: ',', decimal: '.', precision: 0});
    }
});

$(document).on("keydown", ".cpfcnpj", function (event) {
    mascaraCpfCnpj(this, cpfCnpj);
});
$(document).on("keydown", ".cep", function () {
    $(this).mask("99999-999");
});
$(document).on("keydown", ".credit_card", function () {
    $(this).mask("9999 9999 9999 9999");
});
$(document).on("keydown", ".data_hora", function () {
    $(this).mask("00/00/0000 00:00");
});
$(document).on("keydown", ".validade_cartao", function () {
    $(this).mask("99/99");
});
$(document).on("keyup", ".numeric", function () {
    this.value = this.value.replace(/[^0-9\.]/g, '');
});
$(document).on("keyup", ".numeric-positive", function () {
    if ($(this).val() == '0') {
        $(this).val(1);
    }

    if ($(this).val() > $(this).attr("aria-valor-max")) {
        $(this).val($(this).attr("aria-valor-max"));
    }
    $(this).numeric({decimalPlaces: 0, negative: false, decimal: "."});
});
$(document).on("keypress", ".data", function () {
    mascara(this, data);
});
var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

var SPMaskBehaviorCelular = function (val) {
    return '(00) 00000-0000';
},
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehaviorCelular.apply({}, arguments), options);
            }
        };
$(document).on("keydown", ".telefone", function () {
    $('.telefone').mask(SPMaskBehavior, spOptions);
});

$(document).on("keydown", ".celular", function () {
    $('.celular').mask(SPMaskBehaviorCelular, spOptions);
});

$(document).on("keyup blur", ".alphaonly", function () {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z ]/g, ''));
});
/* Masks - Final */

/*Ações dos botões do topo - Início */
$(document).on("change", ".gerempcod", function () {
    var empresa_selecionada = $(this).val();

    if ($('#atual_pagina').val() != $('#inicial_processo_pagina').val()) {

        $.ajax({
            type: 'POST',
            url: web_root_complete + '/ajax/ajaxgermencri',
            data: {mensagem_codigo: 83, exibicao_tipo: 1},
            async: false,
            success: function (html) {
                html = JSON.parse(html);
                if (html == 'sessao_expirada')
                    window.location.href = web_root_complete + 'geral/gertelpri';
                else {
                    $('#germencri_mensagem').text(html.mensagem);
                    dialog = $('#exibe-germencri').dialog({
                        title: html.titulo_texto,
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: html.botao_1_texto,
                                click: function () {
                                    $(this).dialog('close');
                                    gerpagexi('', 1, {});
                                    gerempsel(empresa_selecionada);
                                }
                            },
                            {
                                text: html.botao_2_texto,
                                click: function () {

                                    $(this).dialog('close');
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                }
            },
            error: function (html) {
                // alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
                $('.click_disabled').removeClass('click_disabled');
                finaliza_loading();
            },
        });
    } else {
        gerempsel(empresa_selecionada);
        gerpagexi('', 1, {});
    }
});


/*Ações dos botões do topo - Início */
$(document).on("change", ".gergrucod", function () {
    var empresa_grupo_selecionado = $(this).val();
    gergrusel(empresa_grupo_selecionado);
    gerpagexi('', 1, {});
});

$(document).on("click", ".atualizar", function () {
    $('.submit-button').not('.sub-submit').click();
});
/*Ações dos botões do topo - Fim */

$(document).on("click", '#filtro .dropdown .sub_link_dropdown', function () {
    $('#' + $(this).attr("aria-link-parent")).css('color', $(this).attr("aria-color")).html('<b>' + $(this).attr("aria-texto") + ' </b><b class="caret"></b>');
    if ($(this).attr("aria-texto") == 'sim')
        $('#' + $(this).attr("aria-link-parent") + ' b:first').css('padding-right', '36px');
    else if ($(this).attr("aria-texto") == 'não')
        $('#' + $(this).attr("aria-link-parent") + ' b:first').css('padding-right', '34px');
});
$(document).on("click", ".redirect_row", function () {
    redirectToController($(this).attr("aria-link-id"), $(this).attr("aria-form-id"), $(this).attr("aria-back-page"), $(this).attr("aria-has-form"));
});
/* Fecha um dialog baseado em seu atributo aria-dialog-id, ou no dialog que estiver aberto de acordo com a hierarquia*/
$(document).on("click", ".close_dialog", function () {

    var aria_dialog_id = $(this).attr('aria-dialog-id');
    if (typeof aria_dialog_id !== typeof undefined && aria_dialog_id !== false) {
        if ($('#' + aria_dialog_id).dialog('isOpen'))
            $('#' + aria_dialog_id).dialog('close');
    } else {
        if ($('#dialog_level_3').dialog('isOpen'))
            $("#dialog_level_3").dialog('close');
        else if ($('#dialog_level_2').dialog('isOpen'))
            $("#dialog_level_2").dialog('close');
        else if ($('#dialog_level_1').dialog('isOpen'))
            $("#dialog_level_1").dialog('close');
    }

    //Habilita os links a serem clicados novamente
    $('.click_disabled').removeClass('click_disabled');
});



function dynamicSort(property) {
    var sortOrder = 1;
    if (property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1);
    }
    return function (a, b) {
        var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
        return result * sortOrder;
    }
}

function dynamicSortMultiple() {
    var props = arguments;
    return function (obj1, obj2) {
        var i = 0, result = 0, numberOfProperties = props.length;
        while (result === 0 && i < numberOfProperties) {
            result = dynamicSort(props[i])(obj1, obj2);
            i++;
        }
        return result;
    }
}

//Atualiza o painel caso o usuario decida por fechar um dialog no painel de reservas
$(document).on("click", ".ui-icon-closethick", function () {
    if ($('#atual_pagina').val() == 'reservas/respaiatu') {
        $("#janela_atual").val(0);
        $('#respaiatu_submit').click();
    }
});



//Quando clica em um link dentro de um dialog que deve fechar os dialogs e redirecionar para uma pagina
$(document).on("click", ".close_dialog_redirect_page", function () {
    $('#atual_dialog').val('');
    closeDialog();
    closeDialog();

});


function controla_exibicao_botoes() {
    //Esconde ou exibe os botões de pdf,csv, atualizar etc
    if ($("#nova_tela_botao").val() == 1) {
        $(".barra_funcoes .nova-aba").css('display', 'inline');
    } else {
        $(".barra_funcoes .nova-aba").css('display', 'none');
    }

    if ($('#voltar_botao').val() == 1) {
        $(".barra_funcoes .voltar").css('display', 'inline');
    } else {
        $(".barra_funcoes .voltar").css('display', 'none');
    }

    if ($('#avancar_botao').val() == 1) {
        $(".barra_funcoes .proximo").css('display', 'inline');
    } else {
        $(".barra_funcoes .proximo").css('display', 'none');
    }

    if ($('#recarregar_botao').val() == 1) {
        $(".barra_funcoes .atualizar").css('display', 'inline');
    } else {
        $(".barra_funcoes .atualizar").css('display', 'none');
    }

    if ($('#pdf_botao').val() == 1) {
        $(".barra_funcoes .gerar-pdf").css('display', 'inline');
    } else {
        $(".barra_funcoes .gerar-pdf").css('display', 'none');
    }

    if ($('#excel_botao').val() == 1) {
        $(".barra_funcoes .gerar-xls").css('display', 'inline');
    } else {
        $(".barra_funcoes .gerar-xls").css('display', 'none');
    }

    if ($('#imprimir_botao').val() == 1) {
        $(".barra_funcoes .imprimir").css('display', 'inline');
    } else {
        $(".barra_funcoes .imprimir").css('display', 'none');
    }
}

//Quando se muda o tipo do nomes de quarto na tela de implementação de empresas
$(document).on("change", ".gerqtinom", function () {
    //Limpa todos os selects de tipos de quartos
    $('.gerquaqti').each(function () {
        $(this).find('option').remove().end().append('<option value=""></option>');
    });

    //Percorre todos os nomes preenchidos
    $('.gerqtinom').each(function () {
        if ($(this).val() != "") {
            var id_atual = $(this).attr("id");
            var linha_atual = id_atual.split('_')[1];
            var quarto_tipo_codigo = $('#gerqticod_' + linha_atual).val();
            var quarto_tipo_nome = $(this).val();
            $('.gerquaqti')
                    .append('<option value=' + quarto_tipo_codigo + '>' + quarto_tipo_nome + '</option>');
        }
    });
});

/*
 * Quando se clica em algum item do menu, limpa o historico
 */
function deleta_historico_sessao() {
    $.ajax({
        type: 'POST',
        url: web_root_complete + 'ajax/ajaxgerdelhis',
        data: {},
        success: function () {
        },
        async: false,
        error: function (html) {
            console.log(html.responseText);
            //  alert("Ocorreu um erro interno no sistema. Por favor, tente mais tarde");
            $('.click_disabled').removeClass('click_disabled');
            finaliza_loading();
        },
    });
}

/*
 * Quando se clica no checkbox de selecionar todos de uma determinada lista
 */

$(document).on("click", ".check_all", function () {
    //Todos selecionados se refere a todos os itens de todas as paginas
    $(".todos_selecionados").val(0);
    $(".check_doc").prop('checked', $(this).prop('checked'));
    $("#total_itens_selecionados").text($(".check_doc:checked").length);
    if ($(".check_doc:checked").length == 1) {
        $('#texto_selecao_varios').css('display', 'none');
        $('#texto_selecao_unico').css('display', 'inline');
    } else {
        $('#texto_selecao_varios').css('display', 'inline');
        $('#texto_selecao_unico').css('display', 'none');
    }
    if ($(this).prop('checked') == true)
        $("#exibe_texto_selecionar_todos").css('display', 'block');
    else {
        $("#exibe_texto_todos_selecionados").css('display', 'none');
        $("#exibe_texto_selecionar_todos").css('display', 'none');
    }
});

/*
 * Quando se clica em um checkbox, atualiza o texto que mostra a contagem de selecionados
 */
$(document).on("click", ".check_doc", function () {
    $(".todos_selecionados").val(0);
    $("#total_itens_selecionados").text($(".check_doc:checked").length);
    if ($(".check_doc:checked").length == 1) {
        $('#texto_selecao_varios').css('display', 'none');
        $('#texto_selecao_unico').css('display', 'inline');
    } else {
        $('#texto_selecao_varios').css('display', 'inline');
        $('#texto_selecao_unico').css('display', 'none');
    }

    $("#exibe_texto_todos_selecionados").css('display', 'none');
    $("#exibe_texto_selecionar_todos").css('display', 'block');
});


/*
 * Quando se clica no link de selecionar todos
 */
$(document).on("click", "#selecionar_todos", function () {
    $(".todos_selecionados").val(1);
    $(".check_doc").prop('checked', true);
    $("#exibe_texto_selecionar_todos").css('display', 'none');
    $("#exibe_texto_todos_selecionados").css('display', 'block');
});

/*
 * Quando se clica no link de limpar seleção, após ter selecionado todos os itens de uma listagem
 */
$(document).on("click", ".limpar_selecao", function () {
    $(".todos_selecionados").val(0);
    $(".check_all").prop('checked', false);
    $(".check_doc").prop('checked', false);
    $("#exibe_texto_selecionar_todos").css('display', 'none');
    $("#exibe_texto_todos_selecionados").css('display', 'none');
});
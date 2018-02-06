//Função que soma dias a uma data
function gerdatsum(data_brasileira, total_dias_somar) {
    var datepicker = data_brasileira;
    var dmy = datepicker.split("/");
    var joindate = new Date(
            parseInt(
                    dmy[2], 10),
            parseInt(dmy[1], 10) - 1,
            parseInt(dmy[0], 10)
            );
    joindate.setDate(joindate.getDate() + parseInt(total_dias_somar));
    nova_data = ("0" + joindate.getDate()).slice(-2) + "/" + ("0" + (joindate.getMonth() + 1)).slice(-2) + "/" + joindate.getFullYear();
    return nova_data;
}

function formatar(mascara, documento, auto) {
    var i = documento.value.length;
    var saida = mascara.substring(0, 1);
    var texto = mascara.substring(i);
    if (texto.substring(0, 1) != saida) {
        documento.value += texto.substring(0, 1);
    }
    if (auto)
        resdatval(documento);
}


//Submissão do formulário de cadastro de empresa
function cadastraEmpresa() {
    //Faz a validação do form
    var errors = [],
            conf = {
                onElementValidate: function (valid, $el, $form, errorMess) {
                    if (!valid) {
                        errors.push({el: $el, error: errorMess});
                    }
                }
            },
    lang = 'pt';
    if (!$("#gerempimp").isValid(conf, false)) {
        $('.error').first().focus();
        return false;
    } else {
        //Submete o formulario
        inicia_loading();
        callAjax('geral/gerempimp', {form: $("#gerempimp").serialize(), back_page: 'geral/gertelpri'}, function (html) {
            try {
                var retorno = JSON.parse(html);

                if (retorno.retorno == 1) {
                    $('#germencri_mensagem').text(retorno.mensagem);

                    dialog = $('#exibe-germencri').dialog({
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');
                                    gerpagexi('geral/gerempimp', 1, {});
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                } else {
                    $('#germencri_mensagem').text(retorno.mensagem);

                    dialog = $('#exibe-germencri').dialog({
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                }
            } catch (e) {
                $('#germencri_mensagem').text(html);

                dialog = $('#exibe-germencri').dialog({
                    dialogClass: 'no_close_dialog',
                    autoOpen: false,
                    height: 600,
                    width: 900,
                    modal: true,
                    buttons: [
                        {
                            text: 'OK',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
                dialog.dialog('open');
            }
            $('#bloqueia_tela').val(0);
            finaliza_loading();
        });
    }
}


function valbr2us(valor) {
    return valor.replace(".", "").replace(",", ".");
}

function valus2br(valor) {
    return valor.replace(",", "").replace(".", ",");
}

/*
 *Diferença em dias entre duas datas
 */
function diasDecorridos(dt1, dt2) {
    var nova1 = dt1.toString().split('/');
    Nova1 = nova1[1] + "/" + nova1[0] + "/" + nova1[2];
    var nova2 = dt2.toString().split('/');
    Nova2 = nova2[1] + "/" + nova2[0] + "/" + nova2[2];
    d1 = new Date(Nova1)
    d2 = new Date(Nova2)

    days_passed = Math.round((d2.getTime() - d1.getTime()) / (1000 * 60 * 60 * 24));
    return days_passed;
}

function gerempsel(empresa_selecionada) {
    $.ajax({
        type: 'POST',
        url: web_root_complete + 'geral/gerempsel',
        data: {empresa_selecionada: empresa_selecionada},
        success: function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                selecionou = false;
                results = html.split("|");
                //Atualiza a quantidade de adultos
                if ($('#adulto_qtd').length) {
                    adultos_sel = $("#adulto_qtd").val();
                    $("#adulto_qtd").empty();
                    var select = document.getElementById("adulto_qtd");
                    for (var i = 1; i <= results[0]; i++) {
                        var o = document.createElement("option");
                        o.value = i;
                        o.text = i;
                        if (i == adultos_sel)
                            o.selected = true;
                        select.appendChild(o);
                    }
                }

                //Atualiza a quantidade de criancas
                if ($('#crianca_qtd').length) {
                    criancas_sel = $("#crianca_qtd").val();
                    $("#crianca_qtd").empty();
                    var select = document.getElementById("crianca_qtd");
                    for (var i = 0; i <= results[1]; i++) {
                        var o = document.createElement("option");
                        o.value = i;
                        o.text = i;
                        if (i == criancas_sel) {
                            selecionou = true;
                            o.selected = true;
                        }
                        select.appendChild(o);
                    }
                }

                if (!selecionou) {
                    $("#lista_c").empty();
                } else {
                    //Atualiza a idade máxima das crianças
                    $("#lista_c").children("select").each(function () {
                        $(this).empty();
                        for (var i = 0; i <= results[2]; i++) {
                            var o = document.createElement("option");
                            o.value = i;
                            o.text = i;
                            $(this).append(o);
                        }
                    });
                    $("#pagante_crianca_idade_js").val(results[2]);
                    $("#nao_pagante_crianca_idade_js").val(results[3]);
                    $("#diarias_max_js").val(results[4]);
                }
            }
        },
        error: function (html) {
            console.log(html.responseText);
        }
    });
}



function gergrusel(empresa_grupo_selecionado) {
    $.ajax({
        type: 'POST',
        url: web_root_complete + 'geral/gergrusel',
        data: {empresa_grupo_selecionado: empresa_grupo_selecionado},
        success: function (html) {
            console.log(html);
        },
        error: function (html) {
            console.log(html.responseText);
        }
    });
}


function gercrimas(o, f) {
    v_obj = o
    v_fun = f
    setTimeout('execmascara()', 1)
}

function mascara(o, f) {
    v_obj = o
    v_fun = f
    setTimeout("execmascara()", 1)
}

function execmascara() {
    v_obj.value = v_fun(v_obj.value)
}

function cpfCnpj(v) {

    //Remove tudo o que não é dígito
    v = v.replace(/\D/g, "")

    if (v.length <= 11) { //CPF

        //Coloca um ponto entre o terceiro e o quarto dígitos
        v = v.replace(/(\d{3})(\d)/, "$1.$2")

        //Coloca um ponto entre o terceiro e o quarto dígitos
        //de novo (para o segundo bloco de números)
        v = v.replace(/(\d{3})(\d)/, "$1.$2")

        //Coloca um hífen entre o terceiro e o quarto dígitos
        v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2")

    } else { //CNPJ

        //Coloca ponto entre o segundo e o terceiro dígitos
        v = v.replace(/^(\d{2})(\d)/, "$1.$2")

        //Coloca ponto entre o quinto e o sexto dígitos
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")

        //Coloca uma barra entre o oitavo e o nono dígitos
        v = v.replace(/\.(\d{3})(\d)/, ".$1/$2")

        //Coloca um hífen depois do bloco de quatro dígitos
        v = v.replace(/(\d{4})(\d)/, "$1-$2")

    }

    return v
}

function mascaraCpfCnpj(o, f) {
    v_obj = o
    v_fun = f
    setTimeout('execmascara()', 1)
}


function data(v) {
    v = v.replace(/\D/g, "");
    //Remove tudo o que não é dígito    
    v = v.replace(/^(\d{2})(\d)/, "$1/$2");
    //Coloca barra entre o 2° e o 3° digito
    v = v.replace(/(\d{2})(\d)/, "$1/$2");
    //Coloca barra  entre o 4° e o 5° dígito   
    return v
}

function gercamfor(campo, Mascara, Digitato) {
    var boleanoMascara;
    exp = /\-|\.|\/|\(|\)| /g
    campoSoNumeros = campo.value.toString().replace(exp, "");
    var posicaoCampo = 0;
    var NovoValorCampo = "";
    var TamanhoMascara = campoSoNumeros.length;
    ;
    if (Digitato != 8) {
// backspace
        for (i = 0; i <= TamanhoMascara; i++) {
            boleanoMascara = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".") || (Mascara.charAt(i) == "/"))
            boleanoMascara = boleanoMascara || ((Mascara.charAt(i) == "(") || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " "))
            if (boleanoMascara) {
                NovoValorCampo += Mascara.charAt(i);
                TamanhoMascara++;
            } else {
                NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
                posicaoCampo++;
            }
        }
        campo.value = NovoValorCampo;
        return true;
    } else {
        return true;
    }
}

function gerintval() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
        return false;
    }
    return true;
}


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function applyDataMask(field) {
    var mask = field.dataset.mask.split('');
    // For now, this just strips everything that's not a number
    function stripMask(maskedData) {
        function isDigit(char) {
            return /\d/.test(char);
        }
        return maskedData.split('').filter(isDigit);
    }

    // Replace `_` characters with characters from `data`
    function applyMask(data) {
        return mask.map(function (char) {
            if (char != '_')
                return char;
            if (data.length == 0)
                return char;
            return data.shift();
        }).join('')
    }

    function reapplyMask(data) {
        return applyMask(stripMask(data));
    }

    function changed() {
        var oldStart = field.selectionStart;
        var oldEnd = field.selectionEnd;
        field.value = reapplyMask(field.value);
        field.selectionStart = oldStart;
        field.selectionEnd = oldEnd;
    }

    field.addEventListener('click', changed)
    field.addEventListener('keyup', changed)
}

function germsccnp(cnpj) {
    if (germscint(cnpj) == false) {
        event.returnValue = false;
    }
    return gercamfor(cnpj, '00.000.000/0000-00', event);
}

// JavaScript Document
// Scripts para validações em campos
// adiciona mascara de cnpj
//adiciona mascara ao CPF
function germsccpf(cpf) {
    if (germscint(cpf) == false) {
        event.returnValue = false;
    }
    return gercamfor(cpf, '000.000.000-00', event);
}

//adiciona mascara de cep
function germsccep(cep) {
    if (germscint(cep) == false) {
        event.returnValue = false;
    }
    return gercamfor(cep, '00.000-000', event);
}

//adiciona mascara de data
function germscdata(data) {
    if (germscint(data) == false) {
        event.returnValue = false;
    }
    return gercamfor(data, '00/00/0000', event);
}


/*
 *Verifica se a data é válida
 * return true: válida
 *       false: inválida
 */
function gerdatval(data, exibir_mensagem) {
    exp = /\d{2}\/\d{2}\/\d{4}/
    if (!exp.test(data)) {
        if (exibir_mensagem) {
            $('#germencri_mensagem').text('Data inválida');

            dialog = $('#exibe-germencri').dialog({
                dialogClass: 'no_close_dialog',
                autoOpen: false,
                height: 150,
                width: 330,
                modal: true,
                buttons: [
                    {
                        text: 'OK',
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });
            dialog.dialog('open');
        }
        return false;
    }
    return true;
}

(function (window) {
    'use strict';
    var noback = {
        //globals 
        version: '0.0.1',
        history_api: typeof history.pushState !== 'undefined',
        init: function () {
            window.location.hash = '#no-back';
            noback.configure();
        },
        hasChanged: function () {
            if (window.location.hash == '#no-back') {
                window.location.hash = '#BLOQUEIO';
                //mostra mensagem que não pode usar o btn volta do browser
                if ($("#msgAviso").css('display') == 'none') {
                    $("#msgAviso").slideToggle("slow");
                    //verifica a existencia do formulario post para transporte de dados para a tela anterior.
                    if (document.forms['send_post'])
                        document.forms['send_post'].submit();
                }
            }
        },
        checkCompat: function () {
            if (window.addEventListener) {
                window.addEventListener("hashchange", noback.hasChanged, false);
            } else if (window.attachEvent) {
                window.attachEvent("onhashchange", noback.hasChanged);
            } else {
                window.onhashchange = noback.hasChanged;
            }
        },
        configure: function () {
            if (window.location.hash == '#no-back') {
                if (this.history_api) {
                    history.pushState(null, '', '#BLOQUEIO');
                } else {
                    window.location.hash = '#BLOQUEIO';
                    //mostra mensagem que não pode usar o btn volta do browser
                    if ($("#msgAviso").css('display') == 'none') {
                        $("#msgAviso").slideToggle("slow");
                    }
                }
            }
            noback.checkCompat();
            noback.hasChanged();
        }
    };
    // AMD support 
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return noback;
        });
    }
// For CommonJS and CommonJS-like 
    else if (typeof module === 'object' && module.exports) {
        module.exports = noback;
    } else {
        window.noback = noback;
    }
    noback.init();
}(window));
// Rescriini.ctp - Fim

// Resquatar.ctp - Inicio
function sc_restarbus(numObjs) {
    var i = 0;
    var elements;
    var id;
    var valor;
    var valor_antigo;
    elements = document.getElementsByClassName('tarifa');
    if (elements) {
        for (x = 0; x < elements.length; x++) {
            if (elements[x]) {
                id = elements[x].id;
                if (id.substr(0, 7) == "tarifa_")
                    document.getElementById(id).style.backgroundColor = 'yellow';
            }
        }
    }

    for (i = 1; i <= numObjs; i++) {
        elements = document.getElementsByName('tarifa_' + i);
        if (elements) {

            for (x = 0; x < elements.length; x++) {
                if (elements[x]) {
                    id = elements[x].getAttribute('id');
                    valor = elements[x].getAttribute('value');
                    style = elements[x].getAttribute('style');
                    valor_antigo = document.getElementById('tarifa_' + id);
                    if (valor) {
                        valor_antigo.value = valor.replace(' ', '');
                        valor_antigo.style.backgroundColor = 'lightgreen';
                    }
                }
            } // for x
        } // if=elements
    }
}


function montaPrazo(obj, quarto_item, sz) {
    var index;
    var dv_prazo;
    for (index = 0; index < sz.length; index++) {
        dv_prazo = document.getElementById('p_' + quarto_item + '_' + sz[index]);
        dv_prazo.style.display = 'none';
    }

    dv_prazo = document.getElementById('p_' + quarto_item + '_' + obj.value);
    dv_prazo.style.display = 'block';
    //Pega o valor da primeira parcela
    valor_parcela_hidden = $("#p_" + quarto_item + "_" + obj.value + "  #valor_parcela1_" + quarto_item).val();
    $("#valor_primeira_parcela_geral_js").val(valor_parcela_hidden);
    $("#forma_valor_" + quarto_item).val(valor_parcela_hidden);
}


function gera_array_url(iLista) {
    var l_valores = '';
    if (iLista.length > 0) {
        for (i = 0; i < iLista.length; i++) {
            if (iLista[i].value.length == 0)
                l_valores = l_valores + ' ';
            l_valores = l_valores + iLista[i].value + '|';
        }
        l_valores = l_valores.substring(0, l_valores.length - 1);
    } else
        l_valores = l_valores + iLista.value;
    return l_valores;
}


function redirectToController(url, id_form, back_page, has_form) {
//Escreve na sessão o formulario serializado
    if (has_form) {
        callAjax('ajax/ajaxgerpagsal', {form: $("#" + id_form).serialize(), back_page: back_page}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi(url, 1, {});
            }
        });
    } else {
        callAjax('ajax/ajaxgerpagsal', {back_page: back_page}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi(url, 1, {});
            }
        });
    }
}

function gerpagsal(id_form, back_page, has_form) {
    if (has_form) {
        callAjax('/ajax/ajaxgerpagsal', {form: $("#" + id_form).serialize(), back_page: back_page}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';

        });
    } else {
        callAjax('/ajax/ajaxgerpagsal', {back_page: back_page}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';

        });
    }
}

function formataData(dataString) {
    var myDate = new Date(dataString);
    return myDate.getDate() + "/" + (myDate.getMonth() + 1) + "/" + myDate.getFullYear();
}

function formataDataHora(dataString) {
    if (dataString !== "") {
        var myDate = new Date(dataString);
        var dia = myDate.getDate();
        if (dia.toString().length == 1)
            dia = "0" + dia;
        var mes = myDate.getMonth() + 1;
        if (mes.toString().length == 1)
            mes = "0" + mes;
        var ano = myDate.getFullYear();

        var hora = myDate.getHours();
        var minuto = myDate.getMinutes();

        if (minuto.toString().length == 1)
            minuto = "0" + minuto;

        return dia + "/" + mes + "/" + ano + ' ' + hora + ':' + minuto;
    } else {
        return "";
    }
}

function fechaDialog(documento_numero) {
    dialog = $("#motivo-cancelamento-" + documento_numero).dialog({
        autoOpen: false,
        height: 400,
        width: 450,
        modal: true,
    });
    dialog.dialog("close");
}

function limpaDialog(id_form) {
    $('#' + id_form).trigger("reset");
    $("#gerdesfat").removeAttr('disabled');
    $("#gerdestip").removeAttr('disabled');
    $("#preco_posterior").val('');
    $("#gerdesfat").val('');
    $("#gerdesval").val('');
    $("#gerdestip").css('display', 'block');
    $("#gerdesval").css('display', 'block');
}

//Função para exibir valores na tela, considerando o separdor
function gervalexi(valor) {
    casas = 2;
    //Se for empresa brasileira
    if (valor == null || valor == "")
        valor = 0;
    if (decimal_separador == ',') {
        separador_decimal = decimal_separador;
        separador_milhar = ".";
        var valor_total = parseFloat(valor).toFixed(2);
        var inteiros = parseInt(valor_total);
        var centavos = parseInt((parseFloat(valor_total % 1).toFixed(2) * 100));
        if (centavos % 10 == 0 && centavos + "".length < 2)
            centavos = centavos + "0";
        else if (centavos < 10)
            centavos = "0" + centavos;
        var milhares = parseInt(inteiros / 1000);
        inteiros = inteiros % 1000;
        var retorno = "";
        if (milhares > 0) {
            retorno = milhares + "" + separador_milhar + "" + retorno;
            if (inteiros == 0)
                inteiros = "000";
            else if (inteiros < 10)
                inteiros = "00" + inteiros;
            else if (inteiros < 100)
                inteiros = "0" + inteiros;
        }
        retorno += inteiros + "" + separador_decimal + "" + centavos;
        return retorno;
        //Se for empresa americana
    } else if (decimal_separador == ".") {
        separador_decimal = decimal_separador;
        separador_milhar = ",";
        var valor_total = parseFloat(valor).toFixed(2);
        var inteiros = parseInt(valor_total);
        var centavos = parseInt((parseFloat(valor_total % 1).toFixed(2) * 100));
        if (centavos % 10 == 0 && centavos + "".length < 2)
            centavos = centavos + "0";
        else if (centavos < 10)
            centavos = "0" + centavos;
        var milhares = parseInt(inteiros / 1000);
        inteiros = inteiros % 1000;
        var retorno = "";
        if (milhares > 0) {
            retorno = milhares + "" + separador_milhar + "" + retorno;
            if (inteiros == 0)
                inteiros = "000";
            else if (inteiros < 10)
                inteiros = "00" + inteiros;
            else if (inteiros < 100)
                inteiros = "0" + inteiros;
        }
        retorno += inteiros + "" + separador_decimal + "" + centavos;
        return retorno;
    }
}

//FUnção que gera valores para persistecondesapl_diariasncia, ou para realizar operações matematicas
//Devem sempre estar em formato americano
function gervalper(valor) {
    if (!(!valor.trim() || 0 === valor.trim().length)) {
        if (decimal_separador == ',') {
            valor = valor.toString().replace(".", "");
            valor = valor.toString().replace(",", ".");
            valor = parseFloat(valor);
        } else {
            valor = valor.toString().replace(",", "");
            valor = parseFloat(valor);
        }
    } else
        valor = 0;

    return valor;
}

/*
 * variavel tipo_mensagem define se é uma mensagem de erro(0) ou warning(1)
 */

function gerdiacon(data1, data2, dias_max, texto_codigo, tipo_mensagem) {
    retorno = 0;
    var texto = "";
    if (texto_codigo == 1) {
        texto = "diárias";
    } else {
        texto = "dias";
    }

    if ((data1 == '' || data2 == '' || (diasDecorridos(data1, data2) > dias_max)) && $("#form_force_submit").val() == '0') {
        if ($("#gerdiacon_executada").val() == '0') {
            if (tipo_mensagem == 0) {
                $.ajax({
                    type: 'POST',
                    url: web_root_complete + '/ajax/ajaxgermencri',
                    data: {mensagem_codigo: 2, exibicao_tipo: 1, texto_1: texto, texto_2: dias_max},
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
                                            $("#gerdiacon_executada").val('0');
                                        }
                                    }
                                ]
                            });
                            dialog.dialog('open');
                        }
                    },
                    error: function (html) {
                        console.log(html.responseText);
                        dialog.dialog('close');
                    },
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: web_root_complete + '/ajax/ajaxgermencri',
                    data: {mensagem_codigo: 72, exibicao_tipo: 1, texto_1: dias_max, texto_2: texto},
                    async: false,
                    success: function (html) {
                        html = JSON.parse(html);
                        if (html == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            $('#germencri_mensagem').text(html.mensagem);
                            $('#title_dialog_validator').val(html.titulo_texto);
                        }

                    },
                    error: function (html) {
                        console.log(html.responseText);
                    },
                });
            }
            $("#gerdiacon_executada").val('1');
        }
    } else {
        console.log("retorno gerdiacon 1");
        retorno = 1;
    }
    return retorno;
}

//Retorna a data atual
function gerdatatu() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }

    today = dd + '/' + mm + '/' + yyyy;
    return today;
}

//Determina o endereço baseado no cep
function gerenddet(cep, id_logradouro, id_bairro, id_cidade, id_estado, id_pais, id_campo_focus) {
    console.log(id_pais);
    console.log($("#" + id_pais).val());
    if ($("#" + id_pais).val() == "Brasil") {
        //Nova variável "cep" somente com dígitos.
        var cep = cep.replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#" + id_logradouro).val("...");
                $("#" + id_bairro).val("...");
                $("#" + id_cidade).val("...");
                $("#" + id_estado).val("...");
                //Consulta o webservice viacep.com.br/
                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#" + id_logradouro).val(dados.logradouro);
                        $("#" + id_bairro).val(dados.bairro);
                        $("#" + id_cidade).val(dados.localidade);
                        $("#" + id_estado).val(dados.uf);
                        if (dados.logradouro == "")
                            $("#" + id_logradouro).focus();
                        else if (dados.bairro == "")
                            $("#" + id_bairro).focus();
                        else if (dados.localidade == "")
                            $("#" + id_cidade).focus();
                        else if (dados.uf == "")
                            $("#" + id_estado).focus();
                        else
                            $("#" + id_campo_focus).focus();
                    }
                });
            } //end if.
        }
    }
}

//Função que navega nas abas, com o botão próximo (+1) ou anterior (-1)
function gernavaba(tipo_navegacao) {
    if ($('#ui-id-' + parseInt(parseInt($('#aba_atual').val()) + tipo_navegacao)).length)
        eventFire(document.getElementById('ui-id-' + parseInt(parseInt($('#aba_atual').val()) + tipo_navegacao)), 'click');
}

function gerdomsta(documento_tipo_codigo, opcao_padrao, tipo_elemento) {
    callAjax('/ajax/ajaxgerdomsta', {serdoctip: documento_tipo_codigo}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $("#serdocsta").empty();
            status_codigo_nome_json = JSON.parse(html);


            //O status pode ser montado em um select ou dropdwon com checkboxes
            if (tipo_elemento == 'select') {
                var select = document.getElementById("serdocsta");
                var o = document.createElement("option");
                select.appendChild(o);
                for (var i = 0; i < status_codigo_nome_json.length; i++) {
                    var o = document.createElement("option");
                    o.value = status_codigo_nome_json[i].valor;
                    o.text = status_codigo_nome_json[i].rotulo;
                    if (opcao_padrao == o.value)
                        o.selected = true;
                    select.appendChild(o);
                }
            } else {
                var select = document.getElementById("serdocsta");
                var o = "";
                for (var i = 0; i < status_codigo_nome_json.length; i++) {
                    o = o.concat("<li><input name='gerdocsta[]' type='checkbox' value='" + status_codigo_nome_json[i].valor + "' /> " + status_codigo_nome_json[i].rotulo + "</li>");
                }
                $('#serdocsta').empty().append(o);
            }
        }
    });
}

function gerdommot(documento_tipo_codigo) {
    if (documento_tipo_codigo == 'bc' || documento_tipo_codigo == 'mb' || documento_tipo_codigo == 'ms') {
        callAjax('/ajax/ajaxgerdommot', {documento_tipo_codigo: documento_tipo_codigo}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                $("#serdocmot").empty();
                motivo_codigo_nome_json = JSON.parse(html);
                var select = document.getElementById("serdocmot");
                var o = document.createElement("option");
                o.value = '';
                o.text = '';
                select.appendChild(o);
                for (var i = 0; i < motivo_codigo_nome_json.length; i++) {
                    var o = document.createElement("option");
                    o.value = motivo_codigo_nome_json[i].valor;
                    o.text = motivo_codigo_nome_json[i].rotulo;
                    select.appendChild(o);
                }
            }
        });
    } else {
        $("#serdocmot").empty();
    }
}

function ajaxgermenpes() {

    callAjax('/ajax/ajaxgermenpes', {},
            function (html) {
                if (html == 'sessao_expirada')
                    window.location.href = web_root_complete + 'geral/gertelpri';
                else {
                    var retorno_germenpes = html;
                    openDialog(retorno_germenpes, 1250, 870, 'Mensagens');

                    $('#example thead th').each(function (i) {
                        $(this).append('<br/><input type="text" data-index="' + i + '" />');
                    });


                    mensagem_sem_resultados = "";
                    callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 57, exibicao_tipo: 1}, function (html) {
                        html = JSON.parse(html);
                        if (html == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            mensagem_sem_resultados = html.mensagem;

                            // DataTable
                            var table = $('#example').DataTable({
                                scrollCollapse: true,
                                paging: false,
                                ordering: false,
                                autoWidth: false,
                                info: false,
                                "oLanguage": {
                                    "sSearch": "Buscar:",
                                    "sZeroRecords": mensagem_sem_resultados}
                            });

                            // Filter event handler
                            $(table.table().container()).on('keyup', 'thead input', function () {
                                table
                                        .column($(this).data('index'))
                                        .search(this.value)
                                        .draw();
                            });
                            $("#gerempcod_msg").on('change', function () {
                                table
                                        .column($(this).data('index'))
                                        .search(this.value)
                                        .draw();
                            });
                            $("#example input").css('width', '99%');


                        }
                    });
                }
            });
}

function ajaxserccomod(empresa_codigo, exibicao_data, contador) {
    callAjax('/ajax/ajaxserccomod', {empresa_codigo: empresa_codigo, exibicao_data: exibicao_data}, function (html) {

        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            if (html) {
                $("#row_" + contador).css("display", "none");
            }
        }
    });
}

function gerpadexi() {
    $("#gerpadexi_itens").empty();
    $("form :input").each(function () {

        var input = $(this);
        //Verifica se está na germencri, onde é permitido alterar o padrao de empresa.
        if ((!($('#atual_pagina').val() == "") && !input.hasClass('gerempcod')) || ($('#atual_pagina').val() == "")) {
            input.removeClass('gerempcod');
            if (input.attr("aria-campo-padrao-valor") == 1) {
                var label = $('label[for="' + input.attr('id') + '"]').first();
                var novo_label = label.clone();
                novo_label.css('display', 'block');
                $("#gerpadexi_itens").append(novo_label);

                if (input.attr("aria-campo-padrao-multiselect") == 1) {
                    novo_input = $("#" + input.attr('id')).clone(false).off('onchange').attr('name', input.attr('id').split("_")[0] + "[]").
                            attr('id', input.attr('id').split("_")[0]).removeClass("datepicker");
                    vals = input.attr("aria-padrao-valor").split("|");
                    novo_input.val(vals);
                } else {
                    novo_input = $("#" + input.attr('id')).clone(false).off('onchange').attr('name', input.attr('id').split("_")[0]).
                            attr('id', input.attr('id').split("_")[0]).removeClass("datepicker");
                    novo_input.val(input.attr("aria-padrao-valor"));
                }
                $("#gerpadexi_itens").append(novo_input);
            }
        }

    });
    //Divide cada input com 50%
    var inputs = $("#gerpadexi_itens").children();
    for (var i = 0; i < inputs.length; i += 2) {
        inputs.slice(i, i + 2).wrapAll("<div class='col-md-6'></div>");
    }

    $('.select-all-no-search').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check',
        selectAllText: 'Marcar todos',
        deselectAllText: 'Desmarcar todos',
        actionsBox: true,
        title: ''
    });

    $('.no-select-all-no-search').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check',
        title: ''
    });

    $('.no-select-all-with-search').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check',
        title: '',
        liveSearch: true
    });

    $('.selectpicker').selectpicker('refresh');
    $('.select-all-no-search').selectpicker('refresh');
    $('.no-select-all-no-search').selectpicker('refresh');
    $('.no-select-all-with-search').selectpicker('refresh');

    openDialog('', 600, 500, '', 'gerpadexi');
}
function gerpadsal(usuario_codigo) {
    if ($("#atual_pagina").val() != "")
        var pagina_atual = $("#atual_pagina").val().split("/");
    else
        var pagina_atual = 'geral/gertelpri'.split("/");

    pagina_atual = pagina_atual[pagina_atual.length - 1];

    inicia_loading();
    callAjax('/ajax/ajaxgerpadsal', {usuario_codigo: usuario_codigo, tela_codigo: pagina_atual, form: $("#gercampad_form").serialize()},
    function (html) {
        //Se estiver mudando a empresa na gertelpri, salva na sessao para que possa ser exibido imediatamente
        if ($('#atual_pagina').val() == "") {
            callAjax('/ajax/ajaxgersescri', {rotulo: 'primeiro_acesso', valor: 1},
            function (html) {
                gerpagexi($('#atual_pagina').val(), 1, {});
            });
        } else {
            gerpagexi($('#atual_pagina').val(), 1, {});
            closeDialog('gerpadexi');
        }
    });
}

$(document).on("change", "#germotpes #germottip", function () {
    var motivo_tipo_codigo = $(this).val();
    if ($(this).val() !== '') {
        var motivo_codigos = JSON.parse($('#vetor_germotcod').val());
        $("#germotcod").find('option').remove();
        var options = $("#germotcod");
        options.append($("<option />").val('').text(''));
        $.each(motivo_codigos[$(this).val()], function (key, motivo) {
            options.append($("<option />").val(key).text(motivo));
        });
    }

    $('#germotcod').selectpicker('refresh');
});

//Funções da tela de cadastro de empresas
$(document).on("click", ".novo_usuario", function () {
    var nova_linha_usuario = $('.linha_usuarios').first().clone().find("input:text").val("").end();
    nova_linha_usuario.insertAfter($('.linha_usuarios').last());
});

$(document).on("click", ".novo_tipo_quarto", function () {
    var nova_linha_tipo_quarto = $('.linha_tipo_quarto').first().clone().find("input:text").val("").end();
    var total_quarto_tipo = $('.linha_tipo_quarto').length;
    nova_linha_tipo_quarto.find('#gerqticod_1').attr('id', 'gerqticod_' + total_quarto_tipo + 1).val(total_quarto_tipo + 1);
    nova_linha_tipo_quarto.find('#gertarpor_1').attr('id', 'gertarpor_' + total_quarto_tipo + 1).val(0);
    nova_linha_tipo_quarto.find('#gerqtinom_1').attr('id', 'gerqtinom_' + total_quarto_tipo + 1).val('');
    nova_linha_tipo_quarto.insertAfter($('.linha_tipo_quarto').last());
});

$(document).on("click", ".novo_quarto", function () {
    var nova_linha_quarto = $('.linha_quarto').first().clone().find("input:text").val("").end();
    nova_linha_quarto.insertAfter($('.linha_quarto').last());
});

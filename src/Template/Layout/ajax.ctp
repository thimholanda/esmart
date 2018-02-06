<?php

use Cake\Network\Session;
use Cake\Routing\Router;

$session = new Session();
?>

<?= $this->fetch('content') ?>
<?php if ($ambiente_producao == 0) { ?>
    <style type="text/css">
        .titulo_pag, .ui-widget-header{
            background: #ccc987!important;
        }
    </style>
    <?php
}

if ($session->check('retorno')) {
    $retorno = $session->read('retorno');
    $session->delete('retorno');
}

if (isset($retorno)) {
    //se for um alert
    $mensagem = $retorno;

    print("<script>
            $('#germencri_mensagem').text('" . $mensagem['mensagem']['mensagem'] . "');

            dialog = $('#exibe-germencri').dialog({
                title: '" . $mensagem['mensagem']['titulo_texto'] . "',
                dialogClass: 'no_close_dialog',
                autoOpen: false,
                height: 200,
                width: 530,
                modal: true,
                 buttons: [
                            {
                                text: '" . $mensagem['mensagem']['botao_1_texto'] . "',
                                click: function () {
                                    $(this).dialog('close');
                                }
                            }
                        ]
            });
            dialog.dialog('open');
            finaliza_loading();
            callAjax('/ajax/ajaxgersesdel', {},
                function (html) {}
            );
        </script>");
}
?>

<script>

    /* Datepicker - Inicio */
    $("#resentdat").datepicker({
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
        dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        dateFormat: "dd/mm/yy",
        numberOfMonths: 1,
        showButtonPanel: false,
        showOn: "both",
        buttonImage: web_root_complete + "img/calendar-1.png",
        buttonImageOnly: true,
        buttonText: "Selecione a data de entrada",
        minDate: 0,
        onSelect: function () {
            var minDate = $(this).datepicker('getDate');
            minDate.setDate(minDate.getDate() + 1); //add two days
            $("#ressaidat").datepicker("option", "minDate", minDate);
        }
    });
    $("#ressaidat").datepicker({
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
        dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        dateFormat: "dd/mm/yy",
        numberOfMonths: 1,
        showButtonPanel: false,
        showOn: "both",
        buttonImage: web_root_complete + "img/calendar-1.png",
        buttonImageOnly: true,
        buttonText: "Selecione a data de saida",
    });
    $(".datepicker").datepicker({
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
        dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        dateFormat: "dd/mm/yy",
        numberOfMonths: 1,
        showButtonPanel: true,
        currentText: "Hoje",
        showOn: "both",
        buttonImage: web_root_complete + "img/calendar-1.png",
        buttonImageOnly: true,
        buttonText: "Selecione a data de saida",
    });

    $(".datepicker_future").datepicker({
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
        dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        dateFormat: "dd/mm/yy",
        numberOfMonths: 1,
        showButtonPanel: false,
        showOn: "both",
        buttonImage: web_root_complete + "img/calendar-1.png",
        buttonImageOnly: false,
        buttonText: "Selecione a data de saida",
        minDate: 0,
    });

    $.datepicker._gotoToday = function (id) {
        $(id).datepicker('setDate', new Date()).datepicker('hide').blur();
    };

    /*
     verifica_cpf_cnpj
     
     Verifica se é CPF ou CNPJ
     
     @see http://www.todoespacoonline.com/w/
     */
    function verifica_cpf_cnpj(valor) {

        // Garante que o valor é uma string
        valor = valor.toString();

        // Remove caracteres inválidos do valor
        valor = valor.replace(/[^0-9]/g, '');

        // Verifica CPF
        if (valor.length === 11) {
            return 'CPF';
        }

        // Verifica CNPJ
        else if (valor.length === 14) {
            return 'CNPJ';
        }

        // Não retorna nada
        else {
            return false;
        }

    } // verifica_cpf_cnpj

    /*
     calc_digitos_posicoes
     
     Multiplica dígitos vezes posições
     
     @param string digitos Os digitos desejados
     @param string posicoes A posição que vai iniciar a regressão
     @param string soma_digitos A soma das multiplicações entre posições e dígitos
     @return string Os dígitos enviados concatenados com o último dígito
     */
    function calc_digitos_posicoes(digitos, posicoes = 10, soma_digitos = 0) {

        // Garante que o valor é uma string
        digitos = digitos.toString();
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for (var i = 0; i < digitos.length; i++) {
            // Preenche a soma com o dígito vezes a posição
            soma_digitos = soma_digitos + (digitos[i] * posicoes);
            // Subtrai 1 da posição
            posicoes--;
            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if (posicoes < 2) {
                // Retorno a posição para 9
                posicoes = 9;
            }
        }

        // Captura o resto da divisão entre soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        soma_digitos = soma_digitos % 11;
        // Verifica se soma_digitos é menor que 2
        if (soma_digitos < 2) {
            // soma_digitos agora será zero
            soma_digitos = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            soma_digitos = 11 - soma_digitos;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        var cpf = digitos + soma_digitos;
        // Retorna
        return cpf;
    } // calc_digitos_posicoes

    /*
     Valida CPF
     
     Valida se for CPF
     
     @param  string cpf O CPF com ou sem pontos e traço
     @return bool True para CPF correto - False para CPF incorreto
     */
    function valida_cpf(valor) {

        // Garante que o valor é uma string
        valor = valor.toString();

        // Remove caracteres inválidos do valor
        valor = valor.replace(/[^0-9]/g, '');


        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        var digitos = valor.substr(0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        var novo_cpf = calc_digitos_posicoes(digitos);

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        var novo_cpf = calc_digitos_posicoes(novo_cpf, 11);

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if (novo_cpf === valor) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }

    } // valida_cpf

    /*
     valida_cnpj
     
     Valida se for um CNPJ
     
     @param string cnpj
     @return bool true para CNPJ correto
     */
    function valida_cnpj(valor) {

        // Garante que o valor é uma string
        valor = valor.toString();

        // Remove caracteres inválidos do valor
        valor = valor.replace(/[^0-9]/g, '');


        // O valor original
        var cnpj_original = valor;

        // Captura os primeiros 12 números do CNPJ
        var primeiros_numeros_cnpj = valor.substr(0, 12);

        // Faz o primeiro cálculo
        var primeiro_calculo = calc_digitos_posicoes(primeiros_numeros_cnpj, 5);

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        var segundo_calculo = calc_digitos_posicoes(primeiro_calculo, 6);

        // Concatena o segundo dígito ao CNPJ
        var cnpj = segundo_calculo;

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if (cnpj === cnpj_original) {
            return true;
        }

        // Retorna falso por padrão
        return false;

    } // valida_cnpj

    /*
     valida_cpf_cnpj
     
     Valida o CPF ou CNPJ
     
     @access public
     @return bool true para válido, false para inválido
     */
    function cpfcnpj(valor) {

        // Verifica se é CPF ou CNPJ
        var valida = verifica_cpf_cnpj(valor);

        // Garante que o valor é uma string
        valor = valor.toString();

        // Remove caracteres inválidos do valor
        valor = valor.replace(/[^0-9]/g, '');


        // Valida CPF
        if (valida === 'CPF') {
            // Retorna true para cpf válido
            return valida_cpf(valor);
        }

        // Valida CNPJ
        else if (valida === 'CNPJ') {
            // Retorna true para CNPJ válido
            return valida_cnpj(valor);
        }

        // Não retorna nada
        else {
            return false;
        }

    } // valida_cpf_cnpj

    $.formUtils.addValidator({
        name: 'cpfcnpj',
        validatorFunction: function (value, $el, config, language, $form) {

            if (cpfcnpj(value) || value == "") {
                return true;
            } else
                return false;
        },
        errorMessage: 'CPF ou CNPJ inválido',
        errorMessageKey: 'badEvenNumber'
    });

    $.formUtils.addValidator({
        name: 'passadodata',
        validatorFunction: function (value, $el, config, language, $form) {
            if (value !== "") {
                var data_hoje = new Date();
                data_hoje.setHours(23, 59, 59, 59);
                var from = value.split("/");
                var data_inserida = new Date(from[2], from[1] - 1, from[0]);
                data_inserida.setHours(0, 0, 0, 0);
                if (data_inserida > data_hoje) {
                    return false;
                } else
                    return true;
            } else
                return false;
        },
        errorMessage: 'A data não pode estar no futuro',
        errorMessageKey: 'badEvenNumber'
    });
    
    $.formUtils.addValidator({
        name: 'futuradata',
        validatorFunction: function (value, $el, config, language, $form) {
            if (value !== "") {
                var data_hoje = new Date();
                data_hoje.setHours(0, 0, 0, 0);
                var from = value.split("/");
                var data_inserida = new Date(from[2], from[1] - 1, from[0]);
                data_inserida.setHours(23, 59, 59, 59);
                if (data_inserida < data_hoje) {
                    return false;
                } else
                    return true;
            } else
                return false;
        },
        errorMessage: 'A data não pode estar no passado',
        errorMessageKey: 'badEvenNumber'
    });


    //Valida se a data posterior é maior que a anterior
    $.formUtils.addValidator({
        name: 'futuradata2',
        validatorFunction: function (value, $el, config, language, $form) {
            if (value !== "") {
                var data_anterior = $('#' + $('#' + $el[0].id).attr('aria-id-campo-dependente')).val();
                if (data_anterior !== "") {
                    data_anterior = data_anterior.split("/");
                    data_anterior = new Date(data_anterior[2], data_anterior[1] - 1, data_anterior[0]);
                    data_anterior.setHours(0, 0, 0, 0);

                    var data_inserida = value.split("/");
                    data_inserida = new Date(data_inserida[2], data_inserida[1] - 1, data_inserida[0]);
                    data_inserida.setHours(0, 0, 0, 0);
                    if (data_inserida <= data_anterior) {
                        return false;
                    } else
                        return true;
                } else
                    return false;
            } else
                return false;
        },
        errorMessage: 'Data deve ser maior que a data anterior',
        errorMessageKey: 'badEvenNumber'
    });



    //Valida se a data posterior é maior OU IGUAL a anterior
    $.formUtils.addValidator({
        name: 'futuradata3',
        validatorFunction: function (value, $el, config, language, $form) {
            if (value !== "") {
                var data_anterior = $('#' + $('#' + $el[0].id).attr('aria-id-campo-dependente')).val();
                if (data_anterior !== "") {
                    data_anterior = data_anterior.split("/");
                    data_anterior = new Date(data_anterior[2], data_anterior[1] - 1, data_anterior[0]);
                    data_anterior.setHours(0, 0, 0, 0);

                    var data_inserida = value.split("/");
                    data_inserida = new Date(data_inserida[2], data_inserida[1] - 1, data_inserida[0]);
                    data_inserida.setHours(0, 0, 0, 0);
                    if (data_inserida < data_anterior) {
                        return false;
                    } else
                        return true;
                } else
                    return false;
            } else
                return false;
        },
        errorMessage: 'Data deve ser maior que a data anterior',
        errorMessageKey: 'badEvenNumber'
    });

    $.formUtils.addValidator({
        name: 'dateTime',
        validatorFunction: function (value, $el, config, language, $form) {

            //Aceita o value apenas com a data sem as horas
            console.log(value.trim().length);
            if (value.trim().length == 10) {
                value = value.trim() + ' 23:59';
            }
            console.log(value);
            var matches = value.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})$/);
            //alt:
            // value.match(/^(\d{2}).(\d{2}).(\d{4}).(\d{2}).(\d{2}).(\d{2})$/);
            // also matches 22/05/2013 11:23:22 and 22a0592013,11@23a22
            if (matches === null) {
                return false;
            } else {
                // now lets check the date sanity
                var year = parseInt(matches[3], 10);
                var month = parseInt(matches[2], 10) - 1; // months are 0-11
                var day = parseInt(matches[1], 10);
                var hour = parseInt(matches[4], 10);
                var minute = parseInt(matches[5], 10);
                var date = new Date(year, month, day, hour, minute);
                if (date.getFullYear() !== year
                        || date.getMonth() != month
                        || date.getDate() !== day
                        || date.getHours() !== hour
                        || date.getMinutes() !== minute
                        ) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        errorMessage: 'Data inválida',
        errorMessageKey: 'badEvenNumber'
    });


    $.formUtils.addValidator({
        name: 'expiracao_cartao_credito',
        validatorFunction: function (value, $el, config, language, $form) {
            if (value != null && value != '') {
                var tmp = value.split('/');
                //get the month and year
                var month = parseInt(tmp[0]);
                var year = parseInt(tmp[1]);
                if (month <= 0 || month > 12) {
                    return false;
                } else {
                    if (year == parseInt(new Date().getFullYear().toString().substr(-2))) {
                        if (month >= parseInt(new Date().getMonth() + 1))
                            return true;
                        else
                            return false;
                    } else if (year >= parseInt(new Date().getFullYear().toString().substr(-2)))
                        return true;
                    else
                        return false;
                }
            }
        },
        errorMessage: 'Expiração inválida',
        errorMessageKey: 'badEvenNumber'
    });

<?php
switch ($session->read('empresa_selecionada')['pais_codigo']) {
    case 'br':
        ?>
            $.validate({lang: 'pt', modules: ['brazil', 'date', 'logic'], scrollToTopOnError: true});
        <?php
        break;
    case 'us':
        ?>
            $.validate({scrollToTopOnError: true, modules: ['date', 'logic']});
        <?php
        break;
    case 'es':
        ?>
            $.validate({lang: 'es', scrollToTopOnError: true, modules: ['date', 'logic']});
        <?php
        break;
}
?>
    $("#tabs").tabs();

    /* Accordion - Inicio */
    $(".accordion").click(function () {
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
    }).children().click(function (e) {
        return false;
    });

    var acc_pgto = document.getElementsByClassName("accordion-pgto");
    if (acc_pgto.length > 0) {
        var i;
        for (i = 0; i < acc_pgto.length; i++) {
            acc_pgto[i].onclick = function () {
                this.classList.toggle("active");
                this.nextElementSibling.classList.toggle("show");
            }
        }

        //acc_pgto[0].classList.toggle("active");
        //acc_pgto[0].nextElementSibling.classList.toggle("show");
    }

    /* Accordion - Final */
    $("#gerdocsta").change(function () {
        if ($("#gerdocsta").val() == '1') {
            $("#lbl_dias_max").css('display', 'inline');
            $("#dias_expiracao_max").css('display', 'inline');
            $("#lbl_horas_max").css('display', 'inline');
            $("#horas_expiracao_max").css('display', 'inline');
        } else {
            $("#lbl_dias_max").css('display', 'none');
            $("#dias_expiracao_max").css('display', 'none');
            $("#lbl_horas_max").css('display', 'none');
            $("#horas_expiracao_max").css('display', 'none');
        }
    });

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

    $('.accordion-conta').children().click(function (e) {
        return false;
    });

    $('a.disabled_link').click(function () {
        return false;
    });
    $('a.disabled_link_painel').click(function () {
        return false;
    });


</script>

<!--Faz inclusao de scripts necessários a respaiatu, apenas na página necessária -->
<?php if ($this->request->params['controller'] == 'Reservas' && $this->request->params['action'] == 'respaiatu') { ?>
    <?= $this->Html->script('reserva-quartos') ?>
    <?= $this->Html->script('reserva-drag-drop-semana') ?>
    <?= $this->Html->script('reserva-semanas') ?>
    <?php
}
?>

<!--Identifica para cada pagina quais ações estão disponiveis -->
<input type="hidden" id="nova_tela_botao" value="<?= $nova_tela_botao ?? 0 ?>" />
<input type="hidden" id="voltar_botao" value="<?= $voltar_botao ?? 0 ?>" />
<input type="hidden" id="avancar_botao" value="<?= $avancar_botao ?? 0 ?>" />
<input type="hidden" id="recarregar_botao" value="<?= $recarregar_botao ?? 0 ?>" />
<input type="hidden" id="log_botao" value="<?= $log_botao ?? 0 ?>" />
<input type="hidden" id="pdf_botao" value="<?= $pdf_botao ?? 0 ?>" />
<input type="hidden" id="excel_botao" value="<?= $excel_botao ?? 0 ?>" />
<input type="hidden" id="imprimir_botao" value="<?= $imprimir_botao ?? 0 ?>" />

<?php
if ($session->check('retorno_footer') && is_string($session->read('retorno_footer'))) {
    echo '<p class="col-xs-12 msg_footer" style=\'z-index:999999999\'>' . $session->read('retorno_footer') . '</p>';
    $session->delete('retorno_footer');
}
?>

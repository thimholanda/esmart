moment.locale('pt-br');

var diaAtualN = moment().date(),
        mesAtualN = moment().month(),
        mesAtual = moment().month(mesAtualN).format('MM'),
        diasPeriodo = [],
        mesAno,
        larguraTd;

var $agendaMes = $('.agenda-mes'),
        $agendaAno = $('.agenda-ano'),
        $agendaTable = $('.agenda-table'),
        $agendaThead = $('.agenda-thead'),
        $agendaTbody = $('.agenda-tbody'),
        $navegadorDatas = $('.navegador-datas'),
        $mesAnoFundo = $('.fundo-mes-ano');

if ($('.agenda-table').length > 0) {

    $('.titulo_pag')
            .css({
                'position': 'fixed',
                'z-index': '9999'
            })
            .width($(window).width() - $('#atl').width() - 60);

}

function montaAgendaSemana(diasArr) {
    var inicio = performance.now();
    $('#content')
            .css({
                'overflow-y': 'scroll',
                'overflow-x': 'hidden'
            })
            .height($(window).height() - 72);

    $agendaThead
            .html('');

    $agendaTbody
            .html('');

    $('.agenda-bloco')
            .width($(window).width() - $('#atl').width() - 60);

    var contaMes,
            contaAno;

    $agendaThead
            .html('');

    $agendaTbody
            .html('');

    $agendaTable
            .addClass('semana');

    larguraTd = ($('.agenda-bloco').width() - 120) / diasArr.length;

    var theadHtml = '<tr>'
            + '<td style="min-width: 80px; max-width: 80px;"></td>'
            + '<td style="min-width: 40px; max-width: 40px;"></td>';

    if ($('#respaiper').val() != 30) {

        var semanasCalc = diasArr.length / 7;

        for (var i = 0; i < semanasCalc; i++) {

            theadHtml += '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">S</td>'
                    + '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">T</td>'
                    + '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">Q</td>'
                    + '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">Q</td>'
                    + '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">S</td>'
                    + '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">S</td>'
                    + '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">D</td>';

        }

    } else {

        for (var i = 0; i < diasArr.length; i++) {

            var mydate = diasArr[i] + 'T00:00:00';
            var weekDayName = moment(mydate).format('dddd');

            theadHtml += '<td class="center" style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px; padding-top: 3px;">' + weekDayName.substr(0, 1);
            +'</td>';

        }

    }

    ordenacao_tipo_quarto = $('#gerordatr').val();

    if (ordenacao_tipo_quarto == 'quarto_tipo_codigo')
        icone_ordenacao_quarto_tipo = 'fa-sort-asc';
    else
        icone_ordenacao_quarto_tipo = 'fa-sort fa-sort-asc';

    theadHtml += '</tr>'
            + '<tr>'
            + '<td class="padding" style="min-width: 80px; max-width: 80px;"><a href="#a" id="respaiatu_sort_quarto_tipo">Tipo <i class="fa ' + icone_ordenacao_quarto_tipo + '" aria-hidden="true"></a></i></td>'
            + '<td class="right padding" style="min-width: 40px; max-width: 40px; font-weight: bold;">Qto</td>';

    for (var i = 0; i < diasArr.length; i++) {
        if (diasArr[i].split('-')[2] == diaAtualN && diasArr[i].split('-')[1] == mesAtual) {

            theadHtml += '<td class="tdSemana center cor-txt1" id="painel_data_'+diasArr[i]+'">' + diasArr[i].split('-')[2] + '</td>';

        } else {

            theadHtml += '<td class="tdSemana center" id="painel_data_'+diasArr[i]+'">' + diasArr[i].split('-')[2] + '</td>';

        }

    }

    theadHtml += +'</tr>';

    $agendaThead
            .append(theadHtml);

    for (var i = 0; i < quartosReservas.length; i++) {

        var QuartoNm;

        for (var ii = 0; ii < quartosTipos.length; ii++) {

            if (quartosTipos[ii]['valor'] == quartosReservas[i]['id_tipo']) {

                QuartoNm = quartosTipos[ii]['rotulo'];

                break;

            }

        }

        htmlQuarto = '<tr>' + '<td class="padding painel_quarto_tipo_codigo" style="min-width: 80px; max-width: 80px; font-size: 11px; padding-right: 0; position: relative; z-index: 1"  id="painel_quarto_tipo_codigo_'+ quartosReservas[i]['id_quarto'] +'">' + QuartoNm + '</td>' +
                '<td class="right padding pseudo painel_quarto_codigo" style="min-width: 40px; max-width: 40px; position: relative; z-index: 1" id="painel_quarto_codigo_'+ quartosReservas[i]['id_quarto'] +'">'
                + quartosReservas[i]['id_quarto'] + '</td>';

        for (var ii = 0; ii <= diasArr.length - 1; ii++) {

            htmlQuarto += '<td ' +
                    'style="min-width: ' + larguraTd + 'px; max-width: ' + larguraTd + 'px;"' +
                    'class="marcavel';

            for (var iii = 0; iii < diasNaoUteis.length; iii++) {

                if (diasNaoUteis[iii] == diasArr[ii]) {

                    htmlQuarto += ' nao-util';

                    break;

                }

            }

            htmlQuarto += '" ' +
                    'data-tipoquarto="' + quartosReservas[i]['id_tipo'] + '" ' +
                    'data-quarto="' + quartosReservas[i]['id_quarto'] + '" ' +
                    'data-format="' + diasArr[ii] + '" ' +
                    'data-ocupado="false" data-inicio="false" ' +
                    '><div class="entrada"><div class="triangulo-topo"></div><div class="triangulo-base"></div></div><div class="saida"><div class="flecha" style="border: 0 solid #8296b4;"></div></div></td>'

        }

        htmlQuarto += '</tr>';

        $agendaTbody
                .append(htmlQuarto);

        setTimeout(function () {

            for (var i = 0; i < $agendaThead.find('tr').eq(0).find('td').length; i++) {

                if ($agendaThead.find('tr').eq(1).find('td').eq(i).hasClass('cor-txt1')) {

                    $agendaThead.find('tr').eq(0).find('td').eq(i).addClass('cor-txt1');

                    break;

                }

            }

            for (var i = 0; i < $agendaTbody.find('tr').eq(0).find('td').length; i++) {

                if ($agendaTbody.find('tr').eq(0).find('td').eq(i).hasClass('nao-util')) {

                    $agendaThead.find('tr').eq(0).find('td').eq(i).addClass('nao-util');

                    $agendaThead.find('tr').eq(1).find('td').eq(i).addClass('nao-util');

                }

            }

        }, 50);

    }

    processando = false;

   montaReservasSemana();

    iniciaDragDropSemana(); // ÃšLTIMA FUNÃ‡ÃƒO A RODAR

    $('td.marcavel')
            .on('mouseover', function () {

                arrastaNovaReserva($(this));

            });

    $('.agenda-bloco')
            .width($('.agenda-tbody').width());

    $agendaMes
            .html('');

    $agendaAno
            .html('');

    var contaMes;

    mesAno = [];

    for (i = 0; i < diasArr.length; i++) {

        var mydate = diasArr[i] + 'T00:00:00',
                mesNome = moment(mydate).format('MMMM'),
                mesNumero = moment(mydate).format('MM'),
                anoNumero = moment(mydate).format('YY'),
                mesAnoC = mesNome.substr(0, 3) + ' / ' + anoNumero;

        mesAno.push({'txt': mesAnoC, 'mes': mesNumero});

        //  }

        //for (var i = 0; i < diasArr.length; i++) {

        if (contaMes != mesAno[i]['txt']) {

            contaMes = mesAno[i]['txt']

            $agendaMes
                    .append('<span class="mes-txt" data-mes="' + mesAno[i]['mes'] + '">' + mesAno[i]['txt'] + '</span>');

        }

    }

    for (var i = 0; i < $('.mes-txt').length; i++) {

        for (var ii = 0; ii < $('td.marcavel').length; ii++) {

            if ($('td.marcavel').eq(ii).data('format').split('-')[1] == $('.mes-txt').eq(i).data('mes')) {

                $('.mes-txt')
                        .eq(i)
                        .css('left', ($('td.marcavel').eq(ii).offset().left - $('#atl').width() - 10) + 'px');

                break;

            }

        }

    }

    $('td.marcavel .saida')
            .width((larguraTd / 2) - 3);

    // setTimeout(function() {

    $agendaThead
            // .width($('#content').width() - ($('.agenda-bloco').offset().left - ($('#atl').width() + 10)))
            .css('left', $('.agenda-bloco').offset().left + 'px');

    $mesAnoFundo
            .width($agendaThead.width() + 1)
            .css('left', $('.agenda-bloco').offset().left + 'px');

    $navegadorDatas
            .css('left', ($('td.marcavel').eq(0).offset().left - $('#atl').width() - 10) + 'px');

    $('#respaiatu')
            .width($('#content').width())
            .css('left', ($('#atl').width() + 10) + 'px');

    $('.agenda-table td.marcavel .entrada')
            .width((larguraTd / 2) - 10);

    // }, 100);

    $(window).scrollTop(scrollTop);

    $('.titulo_pag')
            .css({
                'position': 'fixed',
                'z-index': '9999'
            })
            .width($(window).width() - $('#atl').width() - 61);
    var fim = performance.now();
}

montaAgendaSemana(datasPeriodo); 

$('#respaiper')
        .add('#respaidat')
        .add('#janela_anterior_respaiatu')
        .add('#janela_posterior_respaiatu')
        .on('change', function () {

            montaAgendaSemana(datasPeriodo);

        });

setTimeout(function () {

    $('#respaiatu')
            .width($('#content').width())
            .css('left', ($('#atl').width() + 10) + 'px');

}, 100);

$('.menu_escd')
        .add($('.menu_exib'))
        .on('click', function () {

            montaAgendaSemana(datasPeriodo);

            if ($('.agenda-table').length > 0) {

                $('.titulo_pag')
                        .css({
                            'position': 'fixed',
                            'z-index': '9999'
                        })
                        .width($(window).width() - $('#atl').width() - 60);

            }

        });

$(document)
        .on('click', '.ui-button-text:contains("Cancelar")', function () {

            montaAgendaSemana(datasPeriodo);

        });

$(document)
        .on('click', '.ui-button-text:contains("Ok")', function () {

            montaAgendaSemana(datasPeriodo);

        });

$(window)
        .on('resize', function () {

            scrollTop = $(window).scrollTop();
            montaAgendaSemana(datasPeriodo);

        });

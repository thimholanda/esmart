/* global selection */


/**
 * Função para pegar o tamanho da vertical da tela e alterar no content e menu
 */
$(document).ready(function () {
    var tamanhoV = window.innerHeight;
    //salvando a margin que o menu estava
    var margin_menu_open; //= $("ul#nav li ul.sub-nav").css('margin');
    var margin_menu_close;
    var tamanho_H_menu;// = document.getElementById('menu').clientWidth;

    res_menu = tamanhoV - 120;
    res_content = 50 + res_menu;

    if (window.innerWidth > 768) {

        //atribuindo tamanho ao menu e content
        $("#content").css({"min-height": res_content});
        $("#menu").css({"min-height": res_menu});

        //menu aberto
        $("ul#nav li a.m_seg").mouseenter(function () {
            tamanho_H_menu = document.getElementById('menu').clientWidth;
            margin_menu_open = $("ul#nav li ul.sub-nav").css('margin');

            $("ul#nav li ul.sub-nav").css({"margin-left": tamanho_H_menu});
        });

        //menu fechado
        $("ul#nav li a.m_pri").mouseenter(function () {
            tamanho_H_menu = document.getElementById('menu').clientWidth;
            margin_menu_close = $("ul#nav li ul.sub-nav").css('margin');

            $("ul#nav li ul.sub-nav").css({"margin-left": tamanho_H_menu});
        });
    }
});


/**
 * Função para esconder o menu principal na horizontal a esquerda
 * 
 * @returns {undefined}
 */
function menu_esconde() {
    /*Comandos para a loading*/
    $("#loading").addClass("loading_inner");

    /*Comandos para a msg_footer*/
    $("#content p.msg_footer").addClass("tam_msg");
    $("#dialog_level_1 p.msg_footer").addClass("tam_msg");

    /*Comandos para a div logo*/
    $("div.logo label2").css({"display": "none"});
    $("div.logo label1").css({"display": "block"});

    /*Comandos para a div atalho*/
    $("a.atalho_inner").css({"display": "none"});
    $("a.menu_escd").css({"display": "none"});
    $("a.menu_exib").css({"display": "block"});

    /*Comandos para a div atalho*/
    $("ul#nav li a.m_pri").css({"display": "block"});
    $("ul#nav li a.m_seg").css({"display": "none"});

    if (window.innerWidth <= 1024) {
        /*Comandos para a div logo*/
        $("div.header-padrao div#pri").addClass("col-xs-1");
        $("div.header-padrao div#pri").addClass("col-lg-1");
        $("div.header-padrao div#pri").removeClass("col-xs-2");
        $("div.header-padrao div#pri").removeClass("col-lg-2");
        $("div.header-padrao div#seg").addClass("col-xs-3-5");
        $("div.header-padrao div#seg").removeClass("col-xs-3");
        $("div.header-padrao div#ter").addClass("col-xs-3-5");/*.col-xs-5").addClass("col-xs-6");*/
        $("div.header-padrao div#ter").removeClass("col-xs-3");/*.col-xs-5").removeClass("col-xs-5");*/
        /*Comandos para a div atalho*/
        $("div#atl").addClass("col-xs-1");
        $("div#atl").removeClass("col-xs-2");
        //$("div#atl div#atalhos").css({"width":"40px"});
        /*Comandos para a div content*/
        $("div#atl2").addClass("col-xs-11");
        $("div#atl2").removeClass("col-xs-10");
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"7.3%"});
        $("#atalho_out").css({"width": "7.5%"});
        $("#atalhos").css({"width": "7.3%"});
        $("#content").css({"width": "91%"});
    } else {
        /*Comandos para a div logo*/
        $("div.header-padrao div#pri").addClass("col-xs-0-5");
        $("div.header-padrao div#pri").addClass("col-lg-0-5");
        $("div.header-padrao div#pri").removeClass("col-xs-2");
        $("div.header-padrao div#pri").removeClass("col-lg-2");
        $("div.header-padrao div#seg").addClass("col-xs-3-5");
        $("div.header-padrao div#seg").removeClass("col-xs-3");
        $("div.header-padrao div#ter").addClass("col-xs-4");/*.col-xs-5").addClass("col-xs-6-5");*/
        $("div.header-padrao div#ter").removeClass("col-xs-3");/*.col-xs-5").removeClass("col-xs-5");*/
        /*Comandos para a div atalho*/
        $("div#atl").addClass("col-xs-0-5");
        $("div#atl").removeClass("col-xs-2");
        //$("div#atl div#atalhos").css({"width":"40px"});
        /*Comandos para a div content*/
        $("div#atl2").addClass("col-xs-11-5");
        $("div#atl2").removeClass("col-xs-10");
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"3.75%"});
        $("#atalho_out").css({"width": "3.95%"});
        $("#atalhos").css({"width": "3.75%"});
        $("#content").css({"width": "95.18%"});
    }

    if (window.innerWidth <= 375) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "96.95%"});
    }
    if (window.innerWidth > 375 && window.innerWidth <= 480) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "97.7%"});
    }
    if (window.innerWidth > 480 && window.innerWidth <= 640) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "98.1%"});
    }
    if (window.innerWidth > 640 && window.innerWidth <= 768) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "98.4%"});
    }
    if (window.innerWidth > 768 && window.innerWidth <= 992) {
        /*Comandos para a div menu e div content*/
        //$("#meu").css({"width":"7.3%"});
        $("#atalho_out").css({"width": "7.5%"});
        $("#atalhos").css({"width": "7.3%"});
        $("#content").css({"width": "90.6%"});
    }
    if (window.innerWidth > 1024 && window.innerWidth <= 1280) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"3.5%"});
        $("#atalho_out").css({"width": "3.7%"});
        $("#atalhos").css({"width": "3.5%"});
        $("#content").css({"width": "95.05%"});
    }
    if (window.innerWidth > 1280 && window.innerWidth <= 1360) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"3.55%"});
        $("#atalho_out").css({"width": "3.75%"});
        $("#atalhos").css({"width": "3.55%"});
        $("#content").css({"width": "95.1%"});
    }
    if (window.innerWidth > 1366 && window.innerWidth <= 1440) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"3.6%"});
        $("#atalho_out").css({"width": "3.8%"});
        $("#atalhos").css({"width": "3.6%"});
        $("#content").css({"width": "95.1%"});
    }
    if (window.innerWidth > 1440 && window.innerWidth <= 1600) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"3.7%"});
        $("#atalho_out").css({"width": "3.9%"});
        $("#atalhos").css({"width": "3.7%"});
        $("#content").css({"width": "95.1%"});
    }
    
}

/**
 * Função para exibir o menu principal na horizontal a esquerda
 * 
 * @returns {undefined}
 */
function menu_exibe() {
    /*Comandos para a loading*/
    $("#loading").removeClass("loading_inner");

    /*Comandos para a msg_footer*/
    $("#content p.msg_footer").removeClass("tam_msg");
    $("#dialog_level_1 p.msg_footer").removeClass("tam_msg");

    /*Comandos para a div logo*/
    $("div.logo label2").css({"display": "block"});
    $("div.logo label1").css({"display": "none"});

    /*Comandos para a div atalho*/
    $("a.atalho_inner").css({"display": "block"});
    $("a.menu_escd").css({"display": "block"});
    $("a.menu_exib").css({"display": "none"});

    /*Comandos para a div atalho*/
    $("ul#nav li a.m_pri").css({"display": "none"});
    $("ul#nav li a.m_seg").css({"display": "block"});

    if (window.innerWidth <= 1024) {
        /*Comandos para a div logo*/
        $("div.header-padrao div#pri").addClass("col-xs-2");
        $("div.header-padrao div#pri").addClass("col-lg-2");
        $("div.header-padrao div#pri").removeClass("col-xs-1");
        $("div.header-padrao div#pri").removeClass("col-lg-1");
        $("div.header-padrao div#seg").addClass("col-xs-3");
        $("div.header-padrao div#seg").removeClass("col-xs-3-5");
        $("div.header-padrao div#ter").addClass("col-xs-3");/*.col-xs-6").addClass("col-xs-5");*/
        $("div.header-padrao div#ter").removeClass("col-xs-3-5");/*.col-xs-6").removeClass("col-xs-6");*/
        /*Comandos para a div atalho*/
        $("div#atl").addClass("col-xs-2");
        $("div#atl").removeClass("col-xs-1");
        /*Comandos para a div content*/
        $("div#atl2").addClass("col-xs-10");
        $("div#atl2").removeClass("col-xs-11");
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"15.6%"});
        $("#atalho_out").css({"width": "15.8%"});
        $("#atalhos").css({"width": "15.6%"});
        $("#content").css({"width": "82.65%"});
    } else {
        /*Comandos para a div logo*/
        $("div.header-padrao div#pri").addClass("col-xs-2");
        $("div.header-padrao div#pri").addClass("col-lg-2");
        $("div.header-padrao div#pri").removeClass("col-xs-0-5");
        $("div.header-padrao div#pri").removeClass("col-lg-0-5");
        $("div.header-padrao div#seg").addClass("col-xs-3");
        $("div.header-padrao div#seg").removeClass("col-xs-3-5");
        $("div.header-padrao div#ter").addClass("col-xs-3");/*.col-xs-6-5").addClass("col-xs-5");*/
        $("div.header-padrao div#ter").removeClass("col-xs-4");/*.col-xs-6-5").removeClass("col-xs-6-5");*/
        /*Comandos para a div atalho*/
        $("div#atl").addClass("col-xs-2");
        $("div#atl").removeClass("col-xs-0-5");
        /*Comandos para a div content*/
        $("div#atl2").addClass("col-xs-10");
        $("div#atl2").removeClass("col-xs-11-5");
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"16.1%"});
        $("#atalho_out").css({"width": "16.3%"});
        $("#atalhos").css({"width": "16.1%"});
        $("#content").css({"width": "82.86%"});
    }

    if (window.innerWidth <= 375) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "96.95%"});
    }
    if (window.innerWidth > 375 && window.innerWidth <= 480) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "97.7%"});
    }
    if (window.innerWidth > 480 && window.innerWidth <= 640) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "98.1%"});
    }
    if (window.innerWidth > 640 && window.innerWidth <= 768) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"150px"});
        $("#content").css({"width": "98.4%"});
    }
    if (window.innerWidth > 768 && window.innerWidth <= 992) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"15.6%"});
        $("#content").css({"width": "82.6%"});
    }
    if (window.innerWidth > 1024 && window.innerWidth <= 1280) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"15.8%"});
        $("#atalho_out").css({"width": "16%"});
        $("#atalhos").css({"width": "15.8%"});
        $("#content").css({"width": "82.7%"});
    }
    if (window.innerWidth > 1280 && window.innerWidth <= 1360) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"15.9%"});
        $("#atalho_out").css({"width": "16.1%"});
        $("#atalhos").css({"width": "15.9%"});
        $("#content").css({"width": "82.75%"});
    }
    if (window.innerWidth > 1360 && window.innerWidth <= 1440) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"15.9%"});
        $("#atalho_out").css({"width": "16.1%"});
        $("#atalhos").css({"width": "15.9%"});
        $("#content").css({"width": "82.8%"});
    }
    if (window.innerWidth > 1440 && window.innerWidth <= 1600) {
        /*Comandos para a div menu e div content*/
        //$("#menu").css({"width":"16%"});
        $("#atalho_out").css({"width": "16.2%"});
        $("#atalhos").css({"width": "16%"});
        $("#content").css({"width": "82.75%"});
    }
}


/**
 * Função para deixar aberto os filhos do pai selecionado
 * 
 * @returns {undefined}
 */
$(document).ready(function () {
    $("ul#nav li.li_cliente a").click(function () {
        $("ul#nav li.li_cliente").addClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao").removeClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_reserva a").click(function () {
        $("ul#nav li.li_reserva").addClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_reserva ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao").removeClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_conta a").click(function () {
        $("ul#nav li.li_conta").addClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao").removeClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_pagamento a").click(function () {
        $("ul#nav li.li_pagamento").addClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao").removeClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_estadia a").click(function () {
        $("ul#nav li.li_estadia").addClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao").removeClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_servico a").click(function () {
        $("ul#nav li.li_servico").addClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao").removeClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_comunicacao a").click(function () {
        $("ul#nav li.li_comunicacao").addClass("selected");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_comunicacao ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comunicacao ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto").removeClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
    });
    $("ul#nav li.li_produto a").click(function () {
        $("ul#nav li.li_produto").addClass("selected");
        $("ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-0-5 ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
        $(".col-xs-1 ul#nav li.li_produto ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_produto ul.sub-nav li a").parent().children().first().addClass("select_inner");
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").removeClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner2");

        $("ul#nav li.li_reserva").removeClass("selected");
        $("ul#nav li.li_reserva ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_conta").removeClass("selected");
        $("ul#nav li.li_conta ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_pagamento").removeClass("selected");
        $("ul#nav li.li_pagamento ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_estadia").removeClass("selected");
        $("ul#nav li.li_estadia ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_servico").removeClass("selected");
        $("ul#nav li.li_servico ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_cliente").removeClass("selected");
        $("ul#nav li.li_cliente ul.sub-nav li a").removeClass("select_inner");
        $("ul#nav li.li_comucicacao").removeClass("selected");
        $("ul#nav li.li_comucicacao ul.sub-nav li a").removeClass("select_inner");
    });
});

/**
 * Função para exibir qual filho esta selecionado
 * 
 * @returns {undefined}
 */
$(document).ready(function () {
    $("ul#nav li ul.sub-nav li a").click(function () {
        $("ul#nav li ul.sub-nav li a").removeClass("select_inner");
        $(this).addClass("select_inner");
    });
});

/**
 * Função para exibir filhos do tercerio nivel
 * 
 * @returns {undefined}
 */
$(document).ready(function () {
    $("ul#nav li ul.sub-nav li.select-dropdown a").click(function () {
        $("ul#nav li.selected ul.sub-nav li.select-dropdown ul.sub-dropdow").addClass("visible");
        $("ul#nav li ul.sub-nav li.select-dropdown a").removeClass("select_inner");
        $("ul#nav li ul.sub-nav li.select-dropdown ul.sub-dropdow li a").removeClass("select_inner");
        $("ul#nav li ul.sub-nav li.select-dropdown ul.sub-dropdow li a").parent().children().first().addClass("select_inner");
        $(this).addClass("select_inner2");
    });
});

/**
 * Função para exibir qual filho do terceiro nivel esta selecionado
 * 
 * @returns {undefined}
 */
$(document).ready(function () {
    $("ul#nav li ul.sub-nav li.select-dropdown ul.sub-dropdow li a").click(function () {
        $("ul#nav li ul.sub-nav li.select-dropdown ul.sub-dropdow li a").removeClass("select_inner");
        $(this).addClass("select_inner");
        $("ul#nav li ul.sub-nav li.select-dropdown > a").addClass("select_inner2");
    });
});

/**
 * Função para exibir legenda do item que esta selecionado
 * 
 * @returns {undefined}
 */
$(document).ready(function () {
    $("a.m_pri").tooltip({show: {effect: "blind", duration: 100, delay: 1000}});
});

/**
 * Função para esconder o filtro na horizontal a esquerda
 * 
 * @returns {undefined}
 */
function filtro_esconde() {
    //Comandos para o filtros
    $("div#fil_pri").css({"display": "none"});
    $("div#roms_seg").addClass("col-xs-12");
    $("div#roms_seg").removeClass("col-lg-10");
    $("div#roms_seg").removeClass("col-md-9");
    $("#rooms").css({"display": "block", "padding-left": " 20px", "margin-left": " 5px"});

    //Comandos para os botões
    $(".esc_fil").css({"display": "none"});
    $(".esc_fil a.filtro_escd").css({"display": "none"});
    $(".exb_fil").css({"display": "block"});
    $(".exb_fil a.filtro_exib").css({"display": "block"});
}

/**
 * Função para exibir o filtro na horizontal a esquerda
 * 
 * @returns {undefined}
 */
function filtro_exibe() {
    //Comandos para o filtros
    $("div#fil_pri").css({"display": "block"});
    $("div#roms_seg").addClass("col-lg-10");
    $("div#roms_seg").addClass("col-md-9");
    $("div#roms_seg").removeClass("col-xs-12");
    $("#rooms").css({"display": "block", "border-left": "none", "padding-left": " 0px", "margin-left": " 0px"});

    //Comandos para os botões
    $(".esc_fil").css({"display": "block"});
    $(".esc_fil a.filtro_escd").css({"display": "block"});
    $(".exb_fil").css({"display": "none"});
    $(".exb_fil a.filtro_exib").css({"display": "none"});
}


/**
 * Função para exibir tratar a barra de Scroll
 * 
 * @returns {undefined}
 */
/*
 function changeSize() {
 var width = parseInt($("#Width").val());
 var height = parseInt($("#Height").val());
 $("#content").width(width).height(height);
 // update scrollbars
 $('#content').perfectScrollbar('update');
 // or even with vanilla JS!
 Ps.update(document.getElementById('content'));
 }
 $(function() {
 $('#content').perfectScrollbar();
 // with vanilla JS!
 Ps.initialize(document.getElementById('content'));
 });
 */

/**
 * Funções para exibir/esconder dados do usuario e o menu
 * 
 * @returns {undefined}
 */
function exibir_DadosUser() {
    $('.col-xs-4 .class_info_login').fadeToggle();

    if (window.innerWidth <= 768) {
        $('#menu').css({"display": "none"});
    }
}
function exibir_MenuPrinc() {

    if (window.innerWidth <= 375) {
        $('.col-xs-4 .class_info_login').css({"display": "none"});
        $('#menu').fadeToggle();
        $('#atalhos a.menu_escd').css({"display": "none"});
        $('#atalhos a.menu_exib').css({"display": "none"});
    }
    if (window.innerWidth > 375 && window.innerWidth <= 768) {
        $('#menu').fadeToggle();
        $('.col-xs-4 .class_info_login').css({"display": "none"});
        $('#atalhos a.menu_escd').css({"display": "none"});
        $('#atalhos a.menu_exib').css({"display": "none"});
    }
}


/**
 * Função para esconder menu quando cleinte em telas menores 
 * que 768px
 * 
 * @returns {undefined}
 */
$(document).ready(function () {

    if (window.innerWidth <= 768) {
        $("ul#nav li a").click(function () {
            $("#menu").css({"display": "none"});
            $("#atalhos").css({"display": "none"});
        });
        $("ul#nav li ul li a").click(function () {
            $("#menu").css({"display": "none"});
            $("#atalhos").css({"display": "none"});
        });
        $("#atalhos a.atalho_inner").click(function () {
            $("#menu").css({"display": "none"});
            $("#atalhos").css({"display": "none"});
        });
    }
});

/**
 * Função sanfona para itens
 * 
 * @return {undefined}
 */
function exibi_info_quartos(x) {
    /* $(x+" .exibi_info a").css({"display":"none"});
     $(x+" .escd_info a").css({"display":"block"});*/
    $(x + " .exibi_info").removeClass('exibi_info').addClass('escd_info');
    $(x + " .escd_info").attr("onclick","escd_info_quartos('"+x+"')");
    $(x + " .table_resquaaco").slideDown(1000);
    $(x + " .total_adc").slideDown(1000);
    $(x + " .D_contratante").slideDown(1000);
    $(x + " .list_rescli").slideDown(1000);
    $(x + " #pagamentos").slideDown(1000);
    $(x + " .pre_auto").slideDown(1000);
    $(x + " .panel").slideDown(1000);
}

function escd_info_quartos(x) {
     $(x + " .escd_info").removeClass('escd_info').addClass('exibi_info');
     $(x + " .exibi_info").attr("onclick","exibi_info_quartos('"+x+"')");
    $(x + " .table_resquaaco").slideUp(1000);
    $(x + " .total_adc").slideUp(1000);
    $(x + " .D_contratante").slideUp(1000);
    $(x + " .list_rescli").slideUp(1000);
    $(x + " #pagamentos").slideUp(1000);
    $(x + " .pre_auto").slideUp(1000);
    $(x + " .panel").slideUp(1000);
}

function exibi_info_alocacao_multipla(x) {
    /* $(x+" .exibi_info a").css({"display":"none"});
     $(x+" .escd_info a").css({"display":"block"});*/
    $(x + " .exibi_info").removeClass('exibi_info').addClass('escd_info');
    $(x + " .table_resquaaco").slideDown(1000);
    $(x + " .total_adc").slideDown(1000);
    $(x + " .D_contratante").slideDown(1000);
    $(x + " .list_rescli").slideDown(1000);
    $(x + " #pagamentos").slideDown(1000);
    $(x + " .pre_auto").slideDown(1000);
    $(x + " .panel").slideDown(1000);
}


/**
 * Função para selecionar datas 
 * 
 * @returns {undefined}
 */
function selecao_item(x) {
    var beginX, beginY; // a posição do vértice fixo
    var active; // se a seleção está ativa (visível)
    var limit = document.getElementById(x).getBoundingClientRect();

    window.onmousedown = function (e) {
        // se o clique foi fora do limite, não continuar
        if (e.clientX > limit.right || e.clientX < limit.left ||
                e.clientY > limit.bottom || e.clientY < limit.top) {
            return;
        }

        beginX = e.clientX + document.body.scrollLeft;
        beginY = e.clientY + document.body.scrollTop;
        active = true;
        window.onmousemove(e); // forçar a atualização de posição (função abaixo)
    };

    window.onmousemove = function (e) {
        if (active) {
            var sx = document.body.scrollLeft;
            var sy = document.body.scrollTop;

            // a posição do mouse referende ao body
            var mx = e.clientX + sx;
            var my = e.clientY + sy;

            // cx,cy = a posição do segundo vértice
            // é a posição do mouse limitada pelo limite
            var cx = Math.max(Math.min(mx, limit.right), limit.left);
            var cy = Math.max(Math.min(my, limit.bottom), limit.top);

            // x,y,w,h = o retângulo entre os vértices
            var x = Math.min(beginX, cx);
            var y = Math.min(beginY, cy);
            var w = Math.abs(beginX - cx);
            var h = Math.abs(beginY - cy);

            // procurar elementos selecionados
            var list = document.getElementsByClassName("selectable");
            for (var i = 0; i < list.length; ++i) {
                var rect = list[i].getBoundingClientRect();
                if (rect.bottom + sy > y && rect.top + sy < y + h &&
                        rect.right + sx > x && rect.left + sx < x + w) {
                    list[i].classList.add("mark");
                } else {
                    list[i].classList.remove("mark");
                }
            }
        }
    };

    window.onmouseup = function (e) {
        active = false; // desligar
        var result = '';

        // aqui você pode fazer algo diferente manter marcado
        var list = document.getElementsByClassName("mark");
        for (var i = 0; i < list.length; ++i) {
            result = result + '( ' + list[i].id + ' ); ';
        }
        // desmarcar tudo.
        var list = document.getElementsByClassName("selectable");
        for (var i = 0; i < list.length; ++i) {
            list[i].classList.remove("mark");
        }
        //retorna as informações dos icones selecionados
        //alert(result);
        return(result);
    };
}
;


/**
 * Função para selecionar os dias da reserva
 * 
 * @returns {undefined}
 */
function selececiona_rev(quarto, inicial_data, final_data) {
    var array_ent = inicial_data.split("/");
    var array_sai = final_data.split("/");
    var aux;

    //Quando valor da data de entrada for maior que data de saida
    if (array_ent[0] > array_sai[0]) {
        for (var i = array_sai[0]; i > 0; i--) {
            //Fevereiro
            if (array_sai[1] == 03 && i == 1) {
                $('#' + quarto + '_' + array_sai[2] + '_' + array_sai[1] + '_' + i).addClass('mark');
                aux = 28;
            }//Abril - Junho - Setembro - Novembro
            else if ((array_sai[1] == 04 || array_sai[1] == 06 ||
                    array_sai[1] == 09 || array_sai[1] == 11) && (i == 01)) {
                $('#' + quarto + '_' + array_sai[2] + '_' + array_sai[1] + '_' + i).addClass('mark');
                aux = 31;
            }//Janeiro - Março - Maio - Julho - Agosto - Outobro - Dezembro
            else if ((array_sai[1] == 01 || array_sai[1] == 05 ||
                    array_sai[1] == 07 || array_sai[1] == 08 || array_sai[1] == 10 ||
                    array_sai[1] == 12) && (i == 01)) {
                $('#' + quarto + '_' + array_sai[2] + '_' + array_sai[1] + '_' + i).addClass('mark');
                aux = 30;
            } else {
                $('#' + quarto + '_' + array_sai[2] + '_' + array_sai[1] + '_' + i).addClass('mark');
            }
        }

        //Quando valor da data de entrada for menor que data de saida
        for (var j = array_ent[0]; j <= aux; j++) {
            $('#' + quarto + '_' + array_ent[2] + '_' + array_ent[1] + '_' + j).addClass('mark');
        }
    }

    //Quando valor da data de entrada for menor que data de saida
    if (array_ent[0] < array_sai[0]) {
        for (var j = array_ent[0]; j <= array_sai[0]; j++) {
            $('#' + quarto + '_' + array_ent[2] + '_' + array_ent[1] + '_' + j).addClass('mark');
        }
    }
}
;


/**
 * Funções para movimentação de elemento
 *  
 */
function dragStart(ev) {
    ev.dataTransfer.effectAllowed = 'move';
    ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));
    //variavel com o atributo numero do documento
    var num_doc = ev.target.getAttribute("num_doc");

//   var result;
//   var list = ev.target.getAttribute("data_ent");
//   for (var i = 0; i < list.length; ++i) {
//     result = result+'( '+list[i]+' ); ';
//   }
//   console.log(result);

    //Posiciona o mouse no inicio do elemento
    ev.dataTransfer.setDragImage(ev.target, 10, 20);
    return true;
}
function dragEnter(ev) {
    event.preventDefault();
    return true;
}
function dragOver(ev) {
    event.preventDefault();
}
function dragDrop(ev) {

    var data = ev.dataTransfer.getData("Text");
    //variavel conteo o Id do destino
    var destino = ev.target.id;
    //variavel conteo o Id da origem
    var origem = ev.target.appendChild(document.getElementById(data)).id;
    //Exibindo os resultado da movimentação
    alert(origem + '  ->  ' + destino);
    //console.log(ev.target.appendChild(document.getElementById(data)).id);
    ev.stopPropagation();
    return false;
}

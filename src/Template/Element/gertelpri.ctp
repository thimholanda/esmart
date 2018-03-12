<?php

use Cake\Network\Session;
use Cake\Routing\Router;

$session = new Session();
?><div class="row">
    <!--Temporizador para atualização do painel e do cockpit -->
    <script type="text/javascript">
        setInterval(function () {
            if ($("#atual_pagina").val() == '/estadias/estpaiatu') {
                $('#sessao_contador_nao_reinicia').val('1');
                $('#estpaiatu-submit.submit-button').click();
            }

            callAjax('/ajax/ajaxestpaiatu', {saida_tipo: 'c', sessao_contador_nao_reinicia: 1}, function (html) {
                if (html == 'sessao_expirada')
                    window.location.href = web_root_complete + 'geral/gertelpri';
                else {
                    ocupados = JSON.parse(html).ocupado;
                    var quartos_ocupados = [];
                    ocupado_totais = 0;
                    $.each(ocupados, function (quarto_codigo, documentos) {
                        ocupado_totais = ocupado_totais + documentos.length;
                        quartos_ocupados.push(quarto_codigo);
                    });

                    quartos_ocupados.sort();

                    check_ins = JSON.parse(html).check_in;
                    var quartos_check_ins = [];
                    check_in_totais = 0;
                    $.each(check_ins, function (quarto_codigo, documentos) {
                        check_in_totais = check_in_totais + documentos.length;
                        quartos_check_ins.push(quarto_codigo);
                    });

                    quartos_check_ins.sort();

                    check_outs = JSON.parse(html).check_out;
                    var quartos_check_outs = [];
                    check_out_totais = 0;
                    $.each(check_outs, function (quarto_codigo, documentos) {
                        check_out_totais = check_out_totais + documentos.length;
                        quartos_check_outs.push(quarto_codigo);
                    });

                    quartos_check_outs.sort();

                    servicos = JSON.parse(html).servico;
                    var quartos_servicos = [];
                    servico_totais = 0;
                    $.each(servicos, function (quarto_codigo, documentos) {
                        servico_totais = servico_totais + documentos.length;
                        quartos_servicos.push(quarto_codigo);
                    });

                    quartos_servicos.sort();

                    $(".cockpit_info").find("tbody tr").remove();
                    $('#cockpit_ocupacao_total span').text(ocupado_totais);

                    for (q = 0; q < quartos_ocupados.length; q++) {
                        for (i = 0; i < ocupados[quartos_ocupados[q]].length; i++)
                            $('#cockpit_ocupacao_total .cockpit_info > tbody:last-child').append('<tr class="resdocmod" aria-documento-numero=' + ocupados[quartos_ocupados[q]][i].documento_numero + ' aria-quarto-item=' + ocupados[quartos_ocupados[q]][i].quarto_item + '><td>' + quartos_ocupados[q] + '</td><td>' + ocupados[quartos_ocupados[q]][i].nome + ' ' + ocupados[quartos_ocupados[q]][i].sobrenome + '</td></tr>');
                    }

                    $('#cockpit_check_out_total span').text(check_out_totais);
                    for (q = 0; q < quartos_check_outs.length; q++) {
                        for (i = 0; i < check_outs[quartos_check_outs[q]].length; i++)
                            $('#cockpit_check_out_total .cockpit_info > tbody:last-child').append('<tr class="checkout" aria-documento-numero=' + check_outs[quartos_check_outs[q]][i].documento_numero + '  aria-quarto-item=' + check_outs[quartos_check_outs[q]][i].quarto_item + '><td>' + quartos_check_outs[q] + '</td><td>' + check_outs[quartos_check_outs[q]][i].nome + ' ' + check_outs[quartos_check_outs[q]][i].sobrenome + '</td></tr>');
                    }

                    $('#cockpit_servico_total span').text(servico_totais);
                    for (q = 0; q < quartos_servicos.length; q++) {
                        for (i = 0; i < servicos[quartos_servicos[q]].length; i++)
                            $('#cockpit_servico_total .cockpit_info > tbody:last-child').append('<tr class="serdocmod" aria-documento-numero=' + servicos[quartos_servicos[q]][i].documento_numero + ' aria-documento-tipo-codigo= ' + servicos[quartos_servicos[q]][i].documento_tipo_codigo + '><td>' + quartos_servicos[q] + '</td><td>' + servicos[quartos_servicos[q]][i].documento_tipo_nome_curto + '</td></tr>');
                    }

                    $('#cockpit_check_in_total span').text(check_in_totais);
                    for (q = 0; q < quartos_check_ins.length; q++) {
                        for (i = 0; i < check_ins[quartos_check_ins[q]].length; i++)
                            $('#cockpit_check_in_total .cockpit_info > tbody:last-child').append('<tr class="checkin" aria-documento-numero=' + check_ins[quartos_check_ins[q]][i].documento_numero + ' aria-quarto-codigo=' + check_ins[quartos_check_ins[q]][i].quarto_codigo + ' aria-quarto-item=' + check_ins[quartos_check_ins[q]][i].quarto_item + ' aria-quarto-tipo-comprado=' + check_ins[quartos_check_ins[q]][i].quarto_tipo_comprado + '><td>' + quartos_check_ins[q] + '</td><td>' + check_ins[quartos_check_ins[q]][i].nome + ' ' + check_ins[quartos_check_ins[q]][i].sobrenome + '</td></tr>');
                    }
                }
            });
        }, <?= $session->read('estpaiatu_atualizacao') * 1000 * 60 ?>);</script>
    <!-- Cabeçalho e menu -->
    <div class="header-padrao">
        <div id="menu_ini" class="col-sm-2">
            <div class="menu_ini_inner" class="col-sm-2">
                <div class="menu_prin_min"><a onclick="exibir_MenuPrinc();" title="Menu"></a></div>
            </div>
        </div>
        <!-- Logo -->
        <div id="pri" class="col-xs-2 col-lg-2 col-sm-8">
            <div class="logo">
                <center>
                    <?php echo '<label1>' . $this->Html->image('logo-mini.png', array('width' => '36px', 'height' => '32px')) . '</label1>'; ?>
                    <?php echo '<label2>' . $this->Html->image('logo-esmart.png', array('width' => '94px', 'height' => '32px')) . '</label2>'; ?>
                </center>
            </div>
        </div>
        <!-- Empresa -->
        <div id="seg" class="col-xs-3">
            <div class="barra_funcoes">

                <a class="barra_inner imprimir" href="#" alt="Imprimir" title="Imprimir" style="<?php
                if (isset($imprimir_botao) && $imprimir_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>"></a>
                <a class="barra_inner gerar-xls export-csv-link" href="#" alt="Gerar XLS" style="<?php
                if (isset($excel_botao) && $excel_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>" title="Gerar XLS"></a>
                <a class="barra_inner gerar-pdf export-pdf-link" href="#" alt="Gerar PDF" style="<?php
                if (isset($pdf_botao) && $pdf_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>" title="Gerar PDF"></a>

                <a class="barra_inner nova-aba" href="<?= Router::url('/', true) ?>geral/gertelpri"  style="<?php
                if (isset($nova_tela_botao) && $nova_tela_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>" target="_blank" alt="Nova aba" title="Nova aba"></a>
                <a class="barra_inner voltar" href="#"  style="<?php
                if (isset($voltar_botao) && $voltar_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>"
                   onclick="
                           if ($('#atual_pagina').val() == 'reservas/resquatar' || $('#atual_pagina').val() == 'reservas/resadisel' ||
                                   $('#atual_pagina').val() == 'reservas/rescliide') {
                               $('#reserva_voltar').click();
                           } else
                           if ($('#pagina_referencia').length > 0) {
                               gerpagexi($('#pagina_referencia').val(), 1, {})
                           } else {
                               if ($('#atual_pagina').val() != '')
                                   window.location.href = web_root_complete + 'geral/gertelpri';
                           }" alt="Voltar" title="Voltar"></a>
                <a class="barra_inner proximo" href="#" alt="Próximo" title="Próximo"  style="<?php
                if (isset($avancar_botao) && $avancar_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>"></a>
                <a class="barra_inner atualizar" href="#" alt="Atualizar" title="Atualizar"  style="<?php
                if (isset($recarregar_botao) && $recarregar_botao == 1)
                    echo 'display:inline';
                else
                    echo 'display:none'
                    ?>"></a>
                   <?php if ($session->read('acesso_perfil_codigo') == 'sistema') { ?>
                    <select style="width: 38%; display:inline; margin-top: -10px;" class="form-control gergrucod" name="gergrucod" id="gergrucod"> 
                        <?php
                        foreach ($gergrucod_list as $item) {
                            $selected = "";
                            if ($item['valor'] == $empresa_grupo_codigo)
                                $selected = " selected='selected' ";
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                        <?php } ?> 
                    </select>
                <?php } ?>

            </div>

        </div>


        <!-- Mini Dashboard -->
        <div id="qua" class="col-xs-4">
            <div class="dashboard">
                <table id="cockpit">
                    <tr>
                        <th><?= $rot_gerocutit ?></th>
                        <th class="bordaEsq"><?= $rot_gerchotit ?></th>
                        <th class="bordaEsq"><?= $rot_gersertit ?></th>
                        <th class="bordaEsq"><?= $rot_gerchitit ?></th>

                    </tr>
                    <tr>
                        <?php
                        $ocupado_qtd = 0;
                        foreach ($cockpit_dados['ocupado'] as $quarto_codigo)
                            $ocupado_qtd += sizeof($quarto_codigo);
                        ?>
                        <td class="bordaEsq dialogo"><a id="cockpit_ocupacao_total"><span><?= $ocupado_qtd ?></span>
                                <?php if (sizeof($cockpit_dados['ocupado']) > 0) { ?>
                                    <table class="dialogo_inner cockpit_info">

                                        <tbody>
                                            <?php
                                            foreach ($cockpit_dados['ocupado'] as $quarto_codigos) {
                                                foreach ($quarto_codigos as $ocupacao_dado) {
                                                    ?>
                                                    <tr class="resdocmod" aria-documento-numero="<?= $ocupacao_dado['documento_numero'] ?>" aria-quarto-item="<?= $ocupacao_dado['quarto_item'] ?>">

                                                        <td><?= $ocupacao_dado['quarto_codigo'] ?> <?= $ocupacao_dado['nome'] . ' ' . $ocupacao_dado['sobrenome'] ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </a>
                        </td>
                        <?php
                        $check_out_qtd = 0;
                        foreach ($cockpit_dados['check_out'] as $quarto_codigo)
                            $check_out_qtd += sizeof($quarto_codigo);
                        ?>
                        <td class="bordaEsq dialogo"><a id="cockpit_check_out_total"><span><?= $check_out_qtd ?></span>
                                <?php if (sizeof($cockpit_dados['check_out']) > 0) { ?>
                                    <table class="dialogo_inner cockpit_info">
                                        <thead>

                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($cockpit_dados['check_out'] as $quarto_codigos) {
                                                foreach ($quarto_codigos as $check_out_dados) {
                                                    ?>
                                                    <tr class="checkout" aria-documento-numero="<?= $check_out_dados['documento_numero'] ?>"  aria-quarto-item="<?= $check_out_dados['quarto_item'] ?>">

                                                        <td><?= $check_out_dados['quarto_codigo'] ?> <?= $check_out_dados['nome'] . ' ' . $check_out_dados['sobrenome'] ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </a>
                        </td>

                        <?php
                        $servico_qtd = 0;
                        if (array_key_exists('servico', $cockpit_dados))
                            foreach ($cockpit_dados['servico'] as $quarto_codigo)
                                $servico_qtd += sizeof($quarto_codigo);
                        ?>
                        <td class="bordaEsq dialogo"><a id="cockpit_servico_total"><span><?= $servico_qtd ?></span>
                                <?php if (array_key_exists('servico', $cockpit_dados) && sizeof($cockpit_dados['servico']) > 0) { ?>
                                    <table class="dialogo_inner cockpit_info ">

                                        <tbody>
                                            <?php
                                            foreach ($cockpit_dados['servico'] as $quarto_codigos) {
                                                foreach ($quarto_codigos as $servico_dados) {
                                                    ?>
                                                    <tr class="serdocmod" aria-documento-numero="<?= $servico_dados['documento_numero'] ?>"  aria-documento-tipo-codigo="<?= $servico_dados['documento_tipo_codigo'] ?>">

                                                        <td><?= $servico_dados['quarto_codigo'] ?> <?= $servico_dados['documento_tipo_nome_curto'] ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </a>
                        </td>

                        <?php
                        $check_in_qtd = 0;
                        foreach ($cockpit_dados['check_in'] as $quarto_codigo)
                            $check_in_qtd += sizeof($quarto_codigo);
                        ?>

                        <td class="bordaEsq dialogo"><a id="cockpit_check_in_total"><span><?= $check_in_qtd ?></span>
                                <?php if (sizeof($cockpit_dados['check_in']) > 0) { ?>
                                    <table class="dialogo_inner cockpit_info">
                                        <thead>
                                            <tr>
                                                <th><?= $rot_resquacod ?></th>
                                                <th><?= $rot_resdochos ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($cockpit_dados['check_in'] as $quarto_codigos) {
                                                foreach ($quarto_codigos as $check_in_dados) {
                                                    ?>
                                                    <tr class="checkin" aria-documento-numero="<?= $check_in_dados['documento_numero'] ?>" aria-quarto-item="<?= $check_in_dados['quarto_item'] ?>"
                                                        aria-quarto-codigo="<?= $check_in_dados['quarto_codigo'] ?>">
                                                        <td><?= $check_in_dados['quarto_codigo'] ?></td>
                                                        <td><?= $check_in_dados['nome'] . ' ' . $check_in_dados['sobrenome'] ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </a>
                        </td>

                    </tr>
                </table>
            </div>
        </div>

        <div id="ter" class="col-xs-3 col-sm-2">
            <!-- dados do login -->
            <div class="dadosL">
                <div class="menu_user_min"><a onclick="exibir_DadosUser();"></a></div>
                <!-- Empresa -->  
                <div class="col-xs-6 col-lg-6 col-md-7"> 
                    <form>
                        <label style="display:none" for="gerempcod"><?= $rot_gerempcod ?>  <?= $rot_gerpadbot ?></label>
                        <select class="form-control gerempcod" name="gerempcod" id="gerempcod"  aria-campo-padrao-valor ="<?= $campo_padrao_valor_gerempcod ?>"  aria-padrao-valor="<?= $padrao_valor_gerempcod ?? '' ?>"> 
                            <?php
                            foreach ($gerempcod_list as $item) {
                                $selected = "";

                                if ($empresa_codigo == $item["valor"]) {
                                    $selected = 'selected = \"selected\"';
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </form>
                </div>
                <div class="col-xs-2">
                    <div id='dv_tmp_gerpadcab'>
                        <span id="exibe_data_hora"></span>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class='class_info_login' id='div_info_login' align="right">
                        <?php $usuario_nome = explode(" ", $usuario_dados['usuario_nome']); ?>
                        <div class="info_user">
                            <div class="info_user_ns"><?php echo $usuario_nome[0]{0} . $usuario_nome[sizeof($usuario_nome) - 1]{0} ?></div>
                            <div class="info_user_inner"> 

                                <p><b><?= $usuario_dados['label_empresa_grupo'] ?>:</b><br> <?= $usuario_dados['empresa_grupo_nome'] ?></p>
                                <p><b><?= $usuario_dados['label_usuario'] ?>:</b> <?= $usuario_dados['usuario_codigo'] ?> - <?= $usuario_dados['usuario_nome'] ?> </p>
                                <p><a class="info_idioma" alt="<?= $usuario_dados['label_idioma'] ?>" title="<?= $usuario_dados['label_idioma'] ?>"><b>Idioma: </b><?= $usuario_dados['usuario_idioma'] ?></a></p>
                                <p class="lg_info"><a href="<?= $usuario_dados['link_logout'] ?>" > <?= $usuario_dados['label_logout'] ?></a></p>
                            </div>
                        </div>
                        <div class="info_msg">
                            <a class="mensagens" style='padding-bottom: 5px; padding-top: 1px;' type='button' onclick='ajaxgermenpes();' alt="Mensagens" title="Mensagens">
                                <?php //echo $this->Html->image('carta-1.png', array('width' => '20px', 'height' => '14px'));        ?>
                            </a>
                        </div>
                        <div class="info_valorp">
                            <a class="valor_p" alt="Valor Padrão" title="Valor Padrão" onClick="javascript: gerpadexi();"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div id="atl" class="col-xs-2">
        <!-- Barra de Atalhos -->
        <div id="atalho_out">
            <div id="atalhos">
                <a class="atalho_inner atalho_home" title="Página Inicial" alt="Página Inicial" href="<?= Router::url('/', true) . 'geral/gertelpri' ?>"></a>
                <a  class="atalho_inner atalho_cliente" title="Clientes" alt="Clientes" href="<?= Router::url('/', true) ?>?page=clientes/clicadpes" onclick="return gerpagexi('/clientes/clicadpes', 1, {})"></a>
                <a  class="atalho_inner atalho_reserva" href="<?= Router::url('/', true) ?>?page=reservas/respaiatu"  title="Reservas" alt="Reservas"  onclick="return gerpagexi('/reservas/respaiatu', 1, {})"></a>
                <a class="atalho_inner atalho_estadia" title="Situação atual" alt="Situação atual"  href="<?= Router::url('/', true) ?>?page=estadias/estpaiatu"  onclick="return gerpagexi('/estadias/estpaiatu', 0, {})"></a>
                <a class="menu_escd" title="Esconder menu" onclick="menu_esconde();
                        $('#usuario_menu_exibir').val(0);"></a>
                <a class="menu_exib" title="Exibir menu" onclick="menu_exibe();
                        $('#usuario_menu_exibir').val(1);"></a>
            </div>
        </div>

        <input type="hidden" id="usuario_menu_exibir" value="1">
        <input type="hidden" id="atual_pagina" value="">
        <input type="hidden" id="inicial_processo_pagina" value="">
        <input type="hidden" id="atual_dialog" value="">
        <input type="hidden" id="atual_dialog_page" value="">
        <input type="hidden" id="atual_dialog_params" value="">

        <!-- Menu de Navegação -->
        <div id="menu">
            <ul id="nav">
                <li class='<?= $ace_cliente ?> li_cliente'>
                    <a href="<?= Router::url('/', true) ?>?page=clientes/clicadcri"  onclick="deleta_historico_sessao(); return gerpagexi('/clientes/clicadcri', 1, {})" class="img_cli m_pri"></a>
                    <a href="<?= Router::url('/', true) ?>?page=clientes/clicadcri"  onclick="deleta_historico_sessao(); return gerpagexi('/clientes/clicadcri', 1, {})" class="img_cli1 m_seg"><?= $rot_cliclitit ?></a>
                    <ul class="sub-nav">
                        <li class='<?= $ace_clicadcri ?>'><a href="<?= Router::url('/', true) ?>?page=clientes/clicadcri" onclick="deleta_historico_sessao(); return gerpagexi('/clientes/clicadcri', 1, {})"><?= $rot_gertitcri ?></a></li>
                        <li class='<?= $ace_clicadpes ?>'><a href="<?= Router::url('/', true) ?>?page=clientes/clicadpes" onclick="deleta_historico_sessao(); return gerpagexi('/clientes/clicadpes', 1, {})"><?= $rot_gertitexi . "/" . $rot_gertitmod ?></a></li>
                        <li class='<?= $ace_concreexi ?>'><a href="<?= Router::url('/', true) ?>?page=documentocontas/concreexi"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/concreexi', 1, {})"><?= $rot_gertitexi ?> <?= $rot_gercretit ?></a></li>
                    </ul>
                </li>
                <li class='<?= $ace_reserva ?> li_reserva'>
                    <a href="<?= Router::url('/', true) ?>?page=reservas/rescriini"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/rescriini', 1, {})" class="img_res m_pri" title="Reserva"></a>
                    <a href="<?= Router::url('/', true) ?>?page=reservas/rescriini"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/rescriini', 1, {})" class="img_res1 m_seg"><?= $rot_restittit ?></a>
                    <ul class="sub-nav">
                        <li class='<?= $ace_rescriini ?>'><a href="<?= Router::url('/', true) ?>?page=reservas/rescriini"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/rescriini', 1, {})"><?= $rot_gertitcri ?></a></li>
                        <li class='<?= $ace_resdocpes ?>'><a href="<?= Router::url('/', true) ?>?page=reservas/resdocpes"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/resdocpes', 1, {})"><?= $rot_gertitexi . "/" . $rot_gertitmod ?></a></li>
                        <li class='<?= $ace_respaiatu ?>'><a  href="<?= Router::url('/', true) ?>?page=reservas/respaiatu" onclick="deleta_historico_sessao(); return gerpagexi('/reservas/respaiatu', 1, {})"><?= $rot_gertitexi . "/" . $rot_respaiatu ?></a></li>
                        <li class="select-dropdown">
                            <a  href="<?= Router::url('/', true) ?>?page=reservas/resblocri" onclick="deleta_historico_sessao(); return gerpagexi('/reservas/resblocri', 1, {})"><?= $rot_serblocom ?></a>
                            <ul class="sub-dropdow">
                                <li class='<?= $ace_serdoccri ?>'><a href="<?= Router::url('/', true) ?>?page=reservas/resblocri"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/resblocri', 1, {})"><?= $rot_gertitcri ?></a></li>
                                <li class='<?= $ace_serdocpes ?>'><a href="<?= Router::url('/', true) ?>?page=reservas/resblopes"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/resblopes', 1, {})"><?= $rot_gertitexi . "/" . $rot_gertitmod ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class='<?= $ace_conta ?> li_conta'>
                    <a href="<?= Router::url('/', true) ?>?page=documentocontas/conresexi"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conresexi', 1, {})" class="img_pag m_pri" title="Contas"></a>
                    <a href="<?= Router::url('/', true) ?>?page=documentocontas/conresexi"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conresexi', 1, {})" class="img_pag1 m_seg"><?= $rot_gercontit ?></a>
                    <ul class="sub-nav">
                        <li class='<?= $ace_conresexi ?>'><a href="<?= Router::url('/', true) ?>?page=documentocontas/conresexi"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conresexi', 1, {})"><?= $rot_gertitexi . "/" . $rot_gertitmod ?></a></li>
                        <li class='<?= $ace_conpagpes ?>'><a href="<?= Router::url('/', true) ?>?page=documentocontas/conpagpes"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conpagpes', 1, {})"><?= $rot_gertitexi ?> <?= $rot_gerdocpag ?></a></li>
                        <li class="select-dropdown">
                            <a  href="<?= Router::url('/', true) ?>?page=documentocontas/conpfppes" onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conpfppes', 1, {})"><?= $rot_gerrlstit ?></a>
                            <ul class="sub-dropdow">
                                <li class='<?= $ace_conpfppes ?>'><a href="<?= Router::url('/', true) ?>?page=documentocontas/conpfppes"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conpfppes', 1, {})"><?= $rot_gerreltit ?> de conciliação</a></li>
                                <li class='<?= $ace_conitepes ?>'><a href="<?= Router::url('/', true) ?>?page=documentocontas/conitepes"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conitepes', 1, {})"><?= $rot_conrecpro ?></a></li>
                                <li class='<?= $ace_conihopes ?>'><a href="<?= Router::url('/', true) ?>?page=documentocontas/conihopes"  onclick="deleta_historico_sessao(); return gerpagexi('/documentocontas/conihopes', 1, {})"><?= $rot_conrecpda ?></a></li>
                                <li class='<?= $ace_conihopes ?>'><a href="<?= Router::url('/', true) ?>?page=geral/germotpes"  onclick="deleta_historico_sessao(); return gerpagexi('/geral/germotpes', 1, {})"><?= $rot_germottit ?>s</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class='<?= $ace_estadia ?> li_estadia'>
                    <a href="<?= Router::url('/', true) ?>?page=estadias/estfnrpes"  onclick="deleta_historico_sessao(); return gerpagexi('/estadias/estfnrpes', 1, {})" class="img_est m_pri" title="Estadia"></a>
                    <a href="<?= Router::url('/', true) ?>?page=estadias/estfnrpes"  onclick="deleta_historico_sessao(); return gerpagexi('/estadias/estfnrpes', 1, {})" class="img_est1 m_seg"><?= $rot_esttittit ?></a>
                    <ul class="sub-nav">
                        <li class='<?= $ace_estfnrpes ?>'><a href="<?= Router::url('/', true) ?>?page=estadias/estfnrpes"  onclick="deleta_historico_sessao(); return gerpagexi('/estadias/estfnrpes', 1, {})"><?= $rot_gerpesfnr ?></a></li>
                        <li class='<?= $ace_estpaiatu ?>'><a href="<?= Router::url('/', true) ?>?page=estadias/estpaiatu"  onclick="deleta_historico_sessao(); return gerpagexi('/estadias/estpaiatu', 0, {})"><?= $rot_estpaiocu ?></a></li>
                        <li><a href="<?= Router::url('/', true) ?>?page=reservas/resveipes"  onclick="deleta_historico_sessao(); return gerpagexi('/reservas/resveipes', 1, {})">Veículos</a></li>
                    </ul>
                </li>
                <li class='<?= $ace_servico ?> li_servico'>
                    <a href="<?= Router::url('/', true) ?>?page=servicos/serdoccri"  onclick="deleta_historico_sessao(); return gerpagexi('/servicos/serdoccri', 1, {})" class="img_ser m_pri" title="Serviços"></a>
                    <a href="<?= Router::url('/', true) ?>?page=servicos/serdoccri"  onclick="deleta_historico_sessao(); return gerpagexi('/servicos/serdoccri', 1, {})" class="img_ser1 m_seg"><?= $rot_sersertit ?></a>
                    <ul class="sub-nav">
                        <li class='<?= $ace_serdoccri ?>'><a href="<?= Router::url('/', true) ?>?page=servicos/serdoccri"  onclick="deleta_historico_sessao(); return gerpagexi('/servicos/serdoccri', 1, {})"><?= $rot_gertitcri ?></a></li>
                        <li class='<?= $ace_serdocpes ?>'><a href="<?= Router::url('/', true) ?>?page=servicos/serdocpes"  onclick="deleta_historico_sessao(); return gerpagexi('/servicos/serdocpes', 1, {})"><?= $rot_gertitexi . "/" . $rot_gertitmod ?></a></li>
                        <li><a href="<?= Router::url('/', true) ?>?page=servicos/sercamrel"  onclick="deleta_historico_sessao(); return gerpagexi('/servicos/sercamrel', 1, {})"><?= $rot_sercamtit ?></a></li>
                    </ul>
                </li>
                <li class='<?= $ace_comunicacao ?> li_comunicacao'>
                    <a href="<?= Router::url('/', true) ?>?page=geral/gercompes"  onclick="deleta_historico_sessao(); return gerpagexi('/geral/gercompes', 1, {})" class="img_com m_pri" title="Comunicação"></a>
                    <a href="<?= Router::url('/', true) ?>?page=geral/gercompes"  onclick="deleta_historico_sessao(); return gerpagexi('/geral/gercompes', 1, {})" class="img_com1 m_seg"><?= $rot_gercomtit ?></a>
                    <ul class="sub-nav">
                        <li class='<?= $ace_gercompes ?>'><a href="<?= Router::url('/', true) ?>?page=geral/gercompes"  onclick="deleta_historico_sessao(); return gerpagexi('/geral/gercompes', 1, {})"><?= $rot_gertitexi ?></a></li>
                    </ul>
                </li>
               <!-- <li class='li_produto'>
                    <a href="<?= Router::url('/', true) ?>?page=produtos/procadexi"  onclick="deleta_historico_sessao(); return gerpagexi('/produtos/procadexi', 1, {})" class="img_pro m_pri" title="Produtos"></a>
                    <a href="<?= Router::url('/', true) ?>?page=produtos/procadexi"  onclick="deleta_historico_sessao(); return gerpagexi('/produtos/procadexi', 1, {})" class="img_pro1 m_seg"><?= "Produtos" ?></a>
                    <ul class="sub-nav">
                        <li class=''>
                            <a href="<?= Router::url('/', true) ?>?page=produtos/prolisexi"  onclick="deleta_historico_sessao(); return gerpagexi('produtos/prolisexi', 1, {})" title="Produtos">
                                <?= $rot_prolistec ?>
                            </a>
                        </li>
                        <li class=''>
                            <a href="<?= Router::url('/', true) ?>?page=produtos/procadexi"  onclick="deleta_historico_sessao(); return gerpagexi('/produtos/procadexi', 1, {})" title="Produtos">
                                <?= "Listar" ?>
                            </a>
                        </li>
                        <li class='<?= $ace_comunicacao ?>'>
                            <a onclick="deleta_historico_sessao(); gerpagexi('/produtos/procadpdf', 1, {})" title="Produtos">
                                <?= "PDF" ?>
                            </a>
                        </li>
                    </ul>
                </li>-->
                <li class='<?= $ace_comunicacao ?> li_cadastro'>
                    <a class="img_com m_pri" title="Cadastros"></a>
                    <a class="img_com1 m_seg"><?= "Cadastros" ?></a>
                    <ul class="sub-nav">
                        <li class=''>
                            <a href="<?= Router::url('/', true) ?>?page=produtos/prolisexi"  onclick="deleta_historico_sessao(); return gerpagexi('produtos/prolisexi', 1, {})" title="Produto">
                                <?= $rot_prolistec ?>
                            </a>
                        </li>
                        <li class=''>
                            <a href="<?= Router::url('/', true) ?>?page=produtos/procadexi"  onclick="deleta_historico_sessao(); return gerpagexi('/produtos/procadexi', 1, {})" title="Produto">
                                <?= "Produto" ?>
                            </a>
                        </li>
                        <li>
                            <a onclick="deleta_historico_sessao(); gerpagexi('/reservas/restarmod', 1, {})" title="Tarifa">
                                <?= "Tarifa" ?>
                            </a>
                        </li>
                        <li>
                            <a onclick="deleta_historico_sessao(); gerpagexi('/geral/gerempimp', 1, {})" title="Empresa">
                                <?= "Empresa" ?>
                            </a>
                        </li>
                        <li class='<?= $ace_comunicacao ?>'>
                            <a onclick="deleta_historico_sessao(); gerpagexi('/quartos/gerquaini', 1, {})" title="Quarto">
                                <?= "Quarto" ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <input type="hidden" id="session_id" value="<?= $session_id ?>">

    <?php if (isset($new_gerpagexi)) { ?>
        <script type="text/javascript">
            gerpagexi('<?= $new_gerpagexi ?>', 1, "<?= $parametros_url ?>");
        </script>
    <?php } ?>

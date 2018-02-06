<?php

use Cake\Routing\Router;
use Cake\Network\Session;
use App\Model\Entity\Estadia;
use App\Utility\Util;

$estadia = new Estadia();
$path = Router::url('/', true);
$session = new Session();
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<?php if (isset($reservas_sem_alocacao) && sizeof($reservas_sem_alocacao) > 0) { ?>
    <input type="hidden" id="title_dialog_validator" value="" >
    <script type='text/javascript'>
        callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 111}, function (html) {
            html = JSON.parse(html);
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                $('#germencri_mensagem').text(html.mensagem);
                dialog = $('#exibe-germencri').dialog({
                    title: $('#title_dialog_validator').val(),
                    dialogClass: 'no_close_dialog',
                    autoOpen: false,
                    height: 200,
                    width: 530,
                    modal: true,
                    buttons: [
                        {
                            text: html.botao_1_texto,
                            click: function () {
                                dialog.dialog('close');
                            },
                            {
                            text: html.botao_2_texto,
                            click: function () {
                                dialog.dialog('close');
                                gerpagexi('reservas/resdocpes', 0, {});
                            }
                        }]
                });
                dialog.dialog('open');
            }
        });
    </script>
<?php }
?>
<div class="content_inner">
    <div class="col-lg-2 col-md-3" style='margin-top: 14px;'>
        <div class="esc_fil"><a class="filtro_escd" title="Esconder filtro" onclick="filtro_esconde();"></a></div>
        <div class="exb_fil"><a class="filtro_exib" title="Exibir filtro" onclick="filtro_exibe();"></a></div>
    </div>
    <div class="clear"></div>
    <div id="fil_pri" class="col-lg-2 col-md-3">
        <div id="filtro">
            <form action="<?= Router::url('/', true) ?>estadias/estpaiatu" name="estpaiatu" id="estpaiatu" method="post">

               <!-- <div class="form-group row">
                    <label class="control-label col-md-2 col-sm-3" for="respaidat"><?= $rot_gerdattit ?>:</label>
                    <div class="col-md-4 col-sm-3"  style="margin-right:25px"> 
                        <input maxlength="10" class="form-control datepicker data" type="text" name="respaidat" id="respaidat" value="<?= $respaidat ?? '' ?>" placeholder="00/00/0000"  onchange="gerdatval(this)">
                    </div>
                    <div class='col-md-4 col-sm-3'>
                        <input class="form-control btn-primary submit-button" aria-form-id="estpaiatu" type="submit" value="<?= $rot_gerexebot ?>" >
                    </div>
                </div> -->
                <input type="hidden" name="saida_tipo" id="saida_tipo" value="p" />
                <input type="hidden" name="form_atual" id="form_atual" value="estpaiatu" />
                <input type="hidden" name="sessao_contador_nao_reinicia" id="sessao_contador_nao_reinicia" />
                <input type="hidden" name="formato_relatorio" id="formato_relatorio" value="0" />
                <input type="hidden" id="export_pdf" value="1" name="export_pdf" />
                <ul id="tipo_quarto">
                    <li>
                        <input type="checkbox" <?php if ($tipo_quarto_select_all == 1) echo 'checked' ?> id="tipo_quarto_select_all" name="tipo_quarto_select_all" class="típo_quarto_filtro" onchange="if ($(this).prop('checked')) {
                                    $('.típo_quarto_filtro').prop('checked', true);
                                } else {
                                    $('.típo_quarto_filtro').prop('checked', false);
                                }
                                $('#estpaiatu-submit').click()">
                        <label for='tipo_quarto_select_all'><span><strong><?= $rot_resquatip ?></strong></span></label>
                    </li>
                    <?php foreach ($vetor_quarto_tipo as $quarto_tipo) { ?>
                        <li>
                            <input type="checkbox" <?php if ($filtro[$quarto_tipo['valor']] == 1) echo 'checked' ?> name="filtro_quarto_tipo_codigo[]" class="típo_quarto_filtro" onchange="$('#estpaiatu-submit').click()" value="<?= $quarto_tipo['valor'] ?>"><span class='nome_filtro'><?= $quarto_tipo['rotulo'] ?></span><span class='filtro1_inner stilo_filtro1'><?= $estpaiatu_totais[$quarto_tipo['valor']] ?></span>
                        </li>
                    <?php } ?>
                </ul>
                <ul  id="situacao">
                    <li>
                        <input type="checkbox" <?php if ($situacao_select_all == 1) echo 'checked' ?>  id="situacao_select_all" name="situacao_select_all" class="situacao_filtro" onchange="if ($(this).prop('checked')) {
                                    $('.situacao_filtro').prop('checked', true);
                                } else {
                                    $('.situacao_filtro').prop('checked', false);
                                }
                                $('#estpaiatu-submit').click()"><label for='situacao_select_all'><span><strong><?= $rot_gersittit ?></strong></span></label>
                    </li>
                    <li><input type="checkbox" <?php if ($filtro['vazio'] == 1) echo 'checked' ?> name="filtro_vazio" class="situacao_filtro" onchange="$('#estpaiatu-submit').click()"><span class='nome_filtro'><?= $rot_gervaztit ?></span><span class='filtro1_inner stilo_filtro9'><?= $estpaiatu_totais['vazio'] ?></span></li>
                    <li><input type="checkbox" <?php if ($filtro['ocupado'] == 1) echo 'checked' ?> name="filtro_ocupado" class="situacao_filtro" onchange="$('#estpaiatu-submit').click()"><span class='nome_filtro'><?= $rot_gerocptit ?></span><span class='filtro1_inner stilo_filtro6'><?= $estpaiatu_totais['ocupado'] ?></span></li>
                    <li><input type="checkbox" <?php if ($filtro['bloqueado'] == 1) echo 'checked' ?> name="filtro_bloqueado" class="situacao_filtro" onchange="$('#estpaiatu-submit').click()"><span class='nome_filtro'><?= $rot_gerblqtit ?></span><span class='filtro1_inner stilo_filtro7'><?= $estpaiatu_totais['bloqueado'] ?></span></li>
                </ul>
                <ul id="atividades">
                    <li><label for='atividades_select_all'><span><strong><?= $rot_geratitit ?></strong></span></label></li>
                    <li>
                        <input type="hidden" id="filtro_check_out" name="filtro_check_out" value="<?= $filtro['check_out'] ?>">

                        <div class="drop_po">
                            <?php if ($filtro['check_out'] == 1) { ?>
                                <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:25px"><?= $rot_gersimtit ?> </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['check_out'] == 0) { ?>
                                <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b><?= $rot_gertnftit ?> </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['check_out'] == -1) { ?>
                                <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:25px"><?= $rot_gernaotit ?> </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_check_out').val('1');
                                        $('#estpaiatu-submit').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b><?= $rot_gersimtit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_out').val('0');
                                        $('#estpaiatu-submit').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b><?= $rot_gertnftit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_out').val('-1');
                                        $('#estpaiatu-submit').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b><?= $rot_gernaotit ?></b></a></li>
                            </ul>
                        </div>
                        <div class="drop_pa"><span class='nome_filtro'><?= $rot_gerchotit ?></span></div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro2'><?= $estpaiatu_totais['check_out'] ?></span>
                        </div>
                    </li>
                    <li>
                        <input type="hidden" id="filtro_check_in" name="filtro_check_in" value="<?= $filtro['check_in'] ?>">

                        <div  class="drop_po">
                            <?php if ($filtro['check_in'] == 1) { ?>
                                <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:25px"><?= $rot_gersimtit ?> </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['check_in'] == 0) { ?>
                                <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b><?= $rot_gertnftit ?> </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['check_in'] == -1) { ?>
                                <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:25px"><?= $rot_gernaotit ?> </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>

                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_check_in').val('1');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b><?= $rot_gersimtit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_in').val('0');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b><?= $rot_gertnftit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_in').val('-1');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b><?= $rot_gernaotit ?></b></a></li>
                            </ul>
                        </div>
                        <div class="drop_pa"><span class='nome_filtro'><?= $rot_gerchitit ?></span></div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro11'><?= $estpaiatu_totais['check_in'] ?></span>
                        </div>
                    </li>
                    <li>
                        <input type="hidden" id="filtro_servico" name="filtro_servico" value="<?= $filtro['servico'] ?>">

                        <div class="drop_po">
                            <?php if ($filtro['servico'] == 1) { ?>
                                <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:25px"><?= $rot_gersimtit ?> </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['servico'] == 0) { ?>
                                <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b><?= $rot_gertnftit ?> </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['servico'] == -1) { ?>
                                <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:25px"><?= $rot_gernaotit ?> </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>                            
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_servico').val('1');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b><?= $rot_gersimtit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_servico').val('0');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b><?= $rot_gertnftit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_servico').val('-1');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b><?= $rot_gernaotit ?></b></a></li>
                            </ul>
                        </div>
                        <div class="drop_pa"><span class='nome_filtro'><?= $rot_sersertit ?></div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro5'><?= $estpaiatu_totais['servico'] ?></span>
                        </div>
                    </li>
                    <li>
                        <input type="hidden" id="filtro_bloqueio" name="filtro_bloqueio" value="<?= $filtro['bloqueio'] ?>">

                        <div class="drop_po">
                            <?php if ($filtro['bloqueio'] == 1) { ?>
                                <a href="#" id="bloqueio_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:28px"><?= $rot_gersimtit ?> </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['bloqueio'] == 0) { ?>
                                <a href="#" id="bloqueio_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b><?= $rot_gertnftit ?> </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['bloqueio'] == -1) { ?>
                                <a href="#" id="bloqueio_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:27px"><?= $rot_gernaotit ?> </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>                              
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_bloqueio').val('1');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b><?= $rot_gersimtit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_bloqueio').val('0');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b><?= $rot_gertnftit ?></b></a></li>
                                <li><a href="#" onclick="$('#filtro_bloqueio').val('-1');
                                        $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b><?= $rot_gernaotit ?></b></a></li>
                            </ul>
                        </div>
                        <div class="drop_pa"><span class='nome_filtro'><?= $rot_gerblotit ?></div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro10'><?= $estpaiatu_totais['bloqueio'] ?></span>
                        </div>
                    </li>   
                </ul>
                <input class="submit-button hide"  aria-form-id="estpaiatu" type="submit" id="estpaiatu-submit" />
                <ul class="relatorio_estpaiatu">
                    <li> 
                        <input class="submit-button  sub-submit  btn btn-primary" onclick="
                                $('#estpaiatu').attr('action', '<?= Router::url('/', true) ?>estadias/estpaiatu/painel.pdf');
                                $('#estpaiatu').attr('target', '_blank');
                               " type="submit" value="PDF" />
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <div id="roms_seg" class="col-lg-10 col-md-9">
        <div id="rooms" >
            <?php
            foreach ($painel_ocupacao as $quarto_codigo => $quarto) {
                if ($quarto['painel_exibir'] == 1) {
                    echo '<div class="icon" >';
                    /* Verifica o servico de camareira */

                    /* Verifica a reserva atual */
                    $key = array_search('a', array_column($quarto, 'painel_posicao'));
                    //Verifica se esta ocupado
                    if ($key !== false) {
                        /* Verifica se a ocupação é por motivo de reserva */
                        if ($quarto[$key]['documento_tipo_codigo'] == 'rs') {
                            $reserva_info = $quarto[$key];
                            echo '<div><a href="#" aria-documento-numero="' . $reserva_info["documento_numero"] . '" aria-quarto-item="' . $reserva_info["quarto_item"] . '" class="porta porta_ocupado resdocmod"><h5>' . $quarto_codigo . '</h5><label></label>';
                            echo '<div class="clear caixa_rod">';
                            echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div>';
                            echo '<div class="porta_open"><h6>' . $rot_restittit . '</h6><p>' . $reserva_info['nome'] . ' ' . $reserva_info['sobrenome'] . '</p>'
                            . '<p>' . Util::convertDataDMY($reserva_info['inicial_data']) . ' ' . explode(' ', $reserva_info['inicial_data'])[1] . ' / ' . Util::convertDataDMY($reserva_info['final_data']) . ' ' . explode(' ', $reserva_info['final_data'])[1] . '</p></div></a></div>';
                        }
                        /* Verifica se a ocupação é por motivo de manutenção */
                        if ($quarto[$key]['documento_tipo_codigo'] == 'mb') {
                            $manutencao_com_bloqueio_info = $quarto[$key];
                            echo '<div><a href="#" class="porta porta_bloqueio serdocmod" aria-documento-numero="' . $manutencao_com_bloqueio_info["documento_numero"] . '" aria-documento-tipo-codigo="mb"><h5>' . $quarto_codigo . '</h5><label></label>';
                            echo '<div class="clear caixa_rod">';
                            echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div>';
                            echo '<div class="porta_open"><h6>' . $rot_sermabtit . '</h6>'
                            . '<p>' . Util::convertDataDMY($manutencao_com_bloqueio_info['inicial_data']) . ' ' . explode(' ', $manutencao_com_bloqueio_info['inicial_data'])[1] . ' / ' . Util::convertDataDMY($manutencao_com_bloqueio_info['final_data']) . ' ' . explode(' ', $manutencao_com_bloqueio_info['final_data'])[1] . '</p></div></a></div>';
                        }
                        /* Verifica se a ocupação é por motivo de bloqueio */
                        if ($quarto[$key]['documento_tipo_codigo'] == 'bc') {
                            $bloqueio_comercial_info = $quarto[$key];
                            echo '<div><a href="#" class="porta porta_bloqueio serdocmod"  aria-documento-numero="' . $bloqueio_comercial_info["documento_numero"] . '" aria-documento-tipo-codigo="bc"><h5>' . $quarto_codigo . '</h5><label></label>';
                            echo '<div class="clear caixa_rod">';
                            echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div>';
                            echo '<div class="porta_open"><h6>' . $rot_serblocom . '</h6>'
                            . '<p>' . Util::convertDataDMY($bloqueio_comercial_info['inicial_data']) . ' ' . explode(' ', $bloqueio_comercial_info['inicial_data'])[1] . ' / ' . Util::convertDataDMY($bloqueio_comercial_info['final_data']) . ' ' . explode(' ', $bloqueio_comercial_info['final_data'])[1] . '</p></div></a></div>';
                        }
                    } else {
                        echo '<div><a href="#" class="porta solicita_reserva_datas" data-quarto-tipo-codigo="' . $quarto['quarto_tipo_codigo'] . '"'
                                . ' data-quarto-codigo="' . $quarto_codigo . '"><h5>' . $quarto_codigo . '</h5><label></label>';
                        echo '<div class="clear caixa_rod">';
                        echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div></a></div>';
                    }

                    echo '<div class="caixa">';
                    //Verifica se tem algum checkout
                    $key = array_search('o', array_column($quarto, 'painel_posicao'));
                    if ($key !== false) {
                        $checkout_info = $quarto[$key];
                        echo '<a href="#" class="dialogo checkout" aria-documento-numero="' . $checkout_info['documento_numero'] . '"  aria-quarto-item="' . $checkout_info['quarto_item'] . '">' . $this->Html->image('co_confirmado.png', array('width' => '28px', 'height' => '28px'));
                        echo '<div class="dialogo_inner"><h6>' . $rot_gerchotit . '</h6><p>' . $checkout_info['nome'] . ' ' . $checkout_info['sobrenome'] . '</p>'
                        . '<p>' . Util::convertDataDMY($checkout_info['inicial_data']) . ' ' . explode(' ', $checkout_info['inicial_data'])[1] . ' / ' . Util::convertDataDMY($checkout_info['final_data']) . ' ' . explode(' ', $checkout_info['final_data'])[1] . '</p></div></a>';
                        $checkout_existe = 1;
                    } else {
                        
                    }

                    echo '</div><div class="caixa">';

                    $key = array_search('c', array_column($quarto, 'painel_posicao'));

                    //Verifica se tem alguma camareira nesse quarto
                    if ($key !== false) {
                        //Verifica a quantidade de posicoes 'c'
                        $itens_posicao_c = 0;
                        foreach ($quarto as $quarto_codigo_documento => $info_quarto_codigo) {
                            if (is_int($quarto_codigo_documento) && $info_quarto_codigo['painel_posicao'] == 'c') {
                                $itens_posicao_c++;
                            }
                        }

                        $camareira_info = $quarto[$key];

                        //Verifica o tipo de camareira(arrumação, faxina ou conferência, para imprimir o ícone correto
                        switch ([$camareira_info['documento_tipo_codigo'], $camareira_info['documento_status_nome']]) {
                            case ['ca', 'planejada']:
                                //Busca a imagem referente a camareira arrumação planejada
                                //Verifica se existe mais de um documento ocupando a posicao c
                                if ($itens_posicao_c == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $camareira_info["documento_numero"] . '" aria-documento-tipo-codigo="ca">' . $this->Html->image('ca_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_c > 1)
                                    echo '<a href="#" class="serdocpes" data-documento-tipo="cc,ca,cf" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('ca_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                            case ['ca', 'em execução']:
                                if ($itens_posicao_c == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $camareira_info["documento_numero"] . '" aria-documento-tipo-codigo="ca">' . $this->Html->image('ca_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_c > 1)
                                    echo '<a href="#"  class="serdocpes"  data-documento-tipo="cc,ca,cf" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('ca_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                            case ['cc', 'planejada']:
                                if ($itens_posicao_c == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $camareira_info["documento_numero"] . '" aria-documento-tipo-codigo="cc">' . $this->Html->image('cc_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_c > 1)
                                    echo '<a href="#"  class="serdocpes"  data-documento-tipo="cc,ca,cf" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('cc_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                            case ['cc', 'em execução']:
                                if ($itens_posicao_c == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $camareira_info["documento_numero"] . '" aria-documento-tipo-codigo="cc">' . $this->Html->image('cc_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_c > 1)
                                    echo '<a href="#"  class="serdocpes"  data-documento-tipo="cc,ca,cf" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('cc_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                            case ['cf', 'planejada']:
                                if ($itens_posicao_c == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $camareira_info["documento_numero"] . '" aria-documento-tipo-codigo="cf">' . $this->Html->image('cf_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_c > 1)
                                    echo '<a href="#"  class="serdocpes"  data-documento-tipo="cc,ca,cf" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('cf_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                            case ['cf', 'em execução']:
                                if ($itens_posicao_c == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $camareira_info["documento_numero"] . '" aria-documento-tipo-codigo="cf">' . $this->Html->image('cf_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_c > 1)
                                    echo '<a href="#"  class="serdocpes"  data-documento-tipo="cc,ca,cf" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('cf_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                        }
                        //Se não tem serviço de camareira
                    } else {
                        //Ação quando não se tem o serviço de camareira
                    }

                    echo '</div><div class="caixa">';

                    /* Verifica a manutenção simples */
                    $verifica_manutencao_simples = array_search('m', array_column($quarto, 'painel_posicao'));

                    //Verifica se tem alguma manutenção
                    if ($verifica_manutencao_simples !== false) {
                        //Verifica a quantidade de posicoes 'm'
                        $itens_posicao_m = 0;
                        foreach ($quarto as $quarto_codigo_documento => $info_quarto_codigo) {
                            if (is_int($quarto_codigo_documento) && $info_quarto_codigo['painel_posicao'] == 'm') {
                                $itens_posicao_m++;
                            }
                        }

                        $manutencao_info = $quarto[$verifica_manutencao_simples];
                        switch ([$manutencao_info['documento_tipo_codigo'], $manutencao_info['documento_status_nome']]) {
                            case ['ms', 'planejada']:
                                if ($itens_posicao_m == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $manutencao_info["documento_numero"] . '" aria-documento-tipo-codigo="ms">' . $this->Html->image('ms_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_m > 1)
                                    echo '<a href="#" class="serdocpes"  data-documento-tipo="ms" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('ms_planejada.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                            case ['ms', 'em execução']:
                                if ($itens_posicao_m == 1)
                                    echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $manutencao_info["documento_numero"] . '" aria-documento-tipo-codigo="ms">' . $this->Html->image('ms_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                elseif ($itens_posicao_m > 1)
                                    echo '<a href="#" class="serdocpes"  data-documento-tipo="ms" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('ms_execucao.png', array('width' => '28px', 'height' => '28px')) . '</a>';
                                break;
                        }
                    } else {
                        //Ação quando não se tem manutencao
                    }

                    echo '</div><div class="caixa">';

                    /* Verifica o bloqueio comercial */
                    $pos_i = array_search('i', array_column($quarto, 'painel_posicao'));
                    if ($pos_i !== false) {
                        //Verifica a quantidade de posicoes 'i'
                        $itens_posicao_i = 0;
                        $posicoes_habilitadas = array();
                        foreach ($quarto as $quarto_codigo_documento => $info_quarto_codigo) {
                            if (is_int($quarto_codigo_documento) && $info_quarto_codigo['painel_posicao'] == 'i') {
                                $itens_posicao_i++;
                                if ($estadia->estchihab($info_quarto_codigo['inicial_data'], $info_quarto_codigo['final_data'], $info_quarto_codigo['documento_status_codigo']))
                                    array_push($posicoes_habilitadas, $quarto_codigo_documento);
                            }
                        }
                        // Verifica o checkin
                      //  $verifica_checkin = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                        // Verifica o bloqueio comercial
                       // $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                        // Verifica o manutencao com bloqueio
                       // $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));

                        if ($quarto[$pos_i]['documento_tipo_codigo'] == 'rs') {
                            $checkin_info = $quarto[$pos_i];
                            $disabled_link = '';

                            if ($itens_posicao_i == 1 || (sizeof($posicoes_habilitadas) == 1)) {
                                if (sizeof($posicoes_habilitadas) > 0) {
                                    //Se for checkin preliminar
                                    if ($checkin_info['documento_status_nome'] == 'preliminar') {
                                        echo '<a href="#" class="dialogo checkin ' . $disabled_link . ' "  aria-documento-numero="' . $quarto[$posicoes_habilitadas[0]]['documento_numero'] . '" aria-quarto-item="' . $quarto[$posicoes_habilitadas[0]]['quarto_item'] . '"  aria-quarto-codigo="' . $quarto[$posicoes_habilitadas[0]]['quarto_item'] . '"  aria-quarto-tipo-comprado="' . $quarto[$posicoes_habilitadas[0]]['quarto_tipo_codigo'] . '" >' . $this->Html->image('ci_preliminar.png', array('width' => '28px', 'height' => '28px'));
                                        echo '<div class="dialogo_inner"><h6>' . $rot_gerchitit . '</h6><p>' . $quarto[$posicoes_habilitadas[0]]['nome'] . ' ' . $quarto[$posicoes_habilitadas[0]]['sobrenome'] . '</p>'
                                        . '<p>' . $quarto[$posicoes_habilitadas[0]]['inicial_data'] . ' / ' . $quarto[$posicoes_habilitadas[0]]['final_data'] . '</p></div></a>';
                                        //Se for checkin confirmado
                                    } else {
                                        echo '<a href="#" class="dialogo checkin ' . $disabled_link . '" aria-documento-numero="' . $quarto[$posicoes_habilitadas[0]]['documento_numero'] . '" aria-quarto-item="' . $quarto[$posicoes_habilitadas[0]]['quarto_item'] . '"  aria-quarto-codigo="' . $quarto[$posicoes_habilitadas[0]]['quarto_item'] . '"  aria-quarto-tipo-comprado="' . $quarto[$posicoes_habilitadas[0]]['quarto_tipo_codigo'] . '" >' . $this->Html->image('ci_confirmada.png', array('width' => '28px', 'height' => '28px'));
                                        echo '<div class="dialogo_inner"><h6>' . $rot_gerchitit . '</h6><p>' . $quarto[$posicoes_habilitadas[0]]['nome'] . ' ' . $quarto[$posicoes_habilitadas[0]]['sobrenome'] . '</p>'
                                        . '<p>' . $quarto[$posicoes_habilitadas[0]]['inicial_data'] . ' / ' . $quarto[$posicoes_habilitadas[0]]['final_data'] . '</p></div></a>';
                                    }
                                } else {
                                    $disabled_link = ' disabled_link_painel ';

                                    //Se for checkin preliminar
                                    if ($checkin_info['documento_status_nome'] == 'preliminar') {
                                        echo '<a href="#" class="dialogo checkin ' . $disabled_link . ' "  aria-documento-numero="' . $checkin_info['documento_numero'] . '" aria-quarto-item="' . $checkin_info['quarto_item'] . '"  aria-quarto-codigo="' . $checkin_info['quarto_item'] . '" aria-quarto-tipo-comprado="' . $checkin_info['quarto_tipo_codigo'] . '">' . $this->Html->image('ci_preliminar.png', array('width' => '28px', 'height' => '28px'));
                                        echo '<div class="dialogo_inner"><h6>' . $rot_gerchitit . '</h6><p>' . $checkin_info['nome'] . ' ' . $checkin_info['sobrenome'] . '</p>'
                                        . '<p>' . $checkin_info['inicial_data'] . ' / ' . $checkin_info['final_data'] . '</p></div></a>';
                                        //Se for checkin confirmado
                                    } else {
                                        echo '<a href="#" class="dialogo checkin ' . $disabled_link . '" aria-documento-numero="' . $checkin_info['documento_numero'] . '" aria-quarto-item="' . $checkin_info['quarto_item'] . '"  aria-quarto-codigo="' . $checkin_info['quarto_item'] . '" aria-quarto-tipo-comprado="' . $checkin_info['quarto_tipo_codigo'] . '">' . $this->Html->image('ci_confirmada.png', array('width' => '28px', 'height' => '28px'));
                                        echo '<div class="dialogo_inner"><h6>' . $rot_gerchitit . '</h6><p>' . $checkin_info['nome'] . ' ' . $checkin_info['sobrenome'] . '</p>'
                                        . '<p>' . $checkin_info['inicial_data'] . ' / ' . $checkin_info['final_data'] . '</p></div></a>';
                                    }
                                }
                            } else {
                                //Se for checkin preliminar
                                if ($checkin_info['documento_status_nome'] == 'preliminar') {
                                    echo '<a href="#" class="resdocpes" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('ci_preliminar.png', array('width' => '28px', 'height' => '28px'));
                                    echo '</a>';
                                    //Se for checkin confirmado
                                } else {
                                    echo '<a href="#" class="resdocpes" data-quarto-codigo="' . $quarto_codigo . '" data-data-tipo="entrada" data-status = "1,2" '
                                    . ' data-inicio-data="' . $data_inicio_com_cerca_ano_anterior . '" data-fim-data="' . $respaidat . '">' . $this->Html->image('ci_confirmada.png', array('width' => '28px', 'height' => '28px'));
                                    echo '</a>';
                                }
                            }
                        } else  if ($quarto[$pos_i]['documento_tipo_codigo'] == 'bc') {
                            $bloqueio_comercial_info = $quarto[$pos_i];
                            echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $bloqueio_comercial_info["documento_numero"] . '" aria-documento-tipo-codigo="bc">' . $this->Html->image('bc_planejado.png', array('width' => '28px', 'height' => '28px'));
                            echo '<div class="dialogo_inner"><h6>' . $rot_serblocom . '</h6><p></p><p>' . $bloqueio_comercial_info['inicial_data'] . ' / ' . $bloqueio_comercial_info['final_data'] . '</p></div></a>';
                        } if ($quarto[$pos_i]['documento_tipo_codigo'] == 'mb') {
                            $manutencao_info = $quarto[$pos_i];
                            //Verifica o tipo de manutencao(simples, bloqueio)
                            echo '<a href="#" class="dialogo serdocmod"  aria-documento-numero="' . $manutencao_info["documento_numero"] . '" aria-documento-tipo-codigo="mb">' . $this->Html->image('mb_planejada.png', array('width' => '28px', 'height' => '28px'));
                            echo '<div class="dialogo_inner"><h6>' . $rot_sermabtit . '</h6>'
                            . '<p>' . $manutencao_info['inicial_data'] . ' / ' . $manutencao_info['final_data'] . '</p></div></a>';
                        }
                    } else {
                        
                    }

                    echo '</div></div>';
                }
            }
            ?>
        </div>
    </div>
</div>


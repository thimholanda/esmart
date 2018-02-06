<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
use App\Utility\Util;

$geral = new Geral();
$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<!--<div class="content_inner">-->

<div id="resquatar_quartos" class="col-md-8-5 col-sm-12">
    <form name="resquatar" id="resquatar" method="POST" action="<?= $path ?>reservas/resadisel">
        <input type="hidden" id="form_validator_function" name="form_validator_function" value="
               todos_quartos_sem_tarifas=true;
               //se ao menos um quarto possuir tarifa, deve validar
               for(i=1; i<= $('#resquaqtd').val(); i++){
               if($('#quarto_item_sem_tarifas_'+i).val() == 0)
               todos_quartos_sem_tarifas=false;
               }
               console.log(todos_quartos_sem_tarifas);
               for(i=1; i<= $('#resquaqtd').val(); i++){
               if(todos_quartos_sem_tarifas || ($('#quarto_item_removido_'+i).val() == 0 && $('#tarifa_tipo_codigo_'+i).val() == '' && $('#quarto_item_sem_tarifas_'+i).val() == 0)){
               $.ajax({
               type: 'POST',
               url: web_root_complete + '/ajax/ajaxgermencri',
               data:  {mensagem_codigo: 81, exibicao_tipo: 1},
               async: false,
               success: function (html) {
               html = JSON.parse(html);
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
               $('.click_disabled').removeClass('click_disabled');
               }
               }
               ]
               });
               dialog.dialog('open');
               },
               error: function (html) {
               console.log(html.responseText);
               } ,
               });
               return false;
               } 
               }
               return true;">
        <input type="hidden" id="bloqueia_tela" value="1">
        <input type="hidden" id="tarifa_manual_entrada" value="<?= $tarifa_manual_entrada ?>" name="tarifa_manual_entrada" />
        <?php
        $indice_quarto = 1;
        $quarto_item_removido_array = array();
        $quarto_item_sem_tarifas_array = array();
        $unidades_disponiveis_por_quarto_tipo = array();
        for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
            if (isset($resadisel_volta) || isset($rescliide_volta))
                $quarto_item_removido_array[$quarto_item] = $this->request->data['quarto_item_removido_' . $quarto_item];
            else
                $quarto_item_removido_array[$quarto_item] = '0';

            if (isset($resadisel_volta) || isset($rescliide_volta))
                $quarto_item_sem_tarifas_array[$quarto_item] = $this->request->data['quarto_item_sem_tarifas_' . $quarto_item];
            else if (!isset($final_quarto_tipo[$quarto_item]))
                $quarto_item_sem_tarifas_array[$quarto_item] = '1';
            else
                $quarto_item_sem_tarifas_array[$quarto_item] = '0';
            ?>

            <input type="hidden" id="inicial_data_<?= $quarto_item ?>" value="<?= Util::convertDataSQL($this->request->data['resentdat']) ?>">
            <input type="hidden" id="final_data_<?= $quarto_item ?>"  value="<?= Util::convertDataSQL($this->request->data['ressaidat']) ?>">
            <input type="hidden" name="quarto_item_removido_<?= $quarto_item ?>" id="quarto_item_removido_<?= $quarto_item ?>" 
                   value="<?= $quarto_item_removido_array[$quarto_item] ?>">
            <input type="hidden" name="quarto_item_sem_tarifas_<?= $quarto_item ?>" id="quarto_item_sem_tarifas_<?= $quarto_item ?>" 
                   value="<?= $quarto_item_sem_tarifas_array[$quarto_item] ?>">
                   <?php
               }

               for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
                   //Exibicao normal e incrementa o indice
                   if ($quarto_item_removido_array[$quarto_item] != 1 && $quarto_item_sem_tarifas_array[$quarto_item] != 1) {
                       ?>
                <div id="quarto_item_<?= $quarto_item ?>" class="dados_item">
                    <div class="col-md-12 col-sm-12 info_quarto escd_info" onclick="escd_info_quartos('#quarto_item_<?= $quarto_item ?>');">
                        <div class="col-md-3 col-sm-12">
                            <a></a>
                            <strong><?= $rot_resquacod ?></strong> <strong><span id="label_quarto_item_<?= $quarto_item ?>"><?= $indice_quarto ?></span></strong>
                        </div>
                    </div>

                    <div class="table_resquaaco col-md-12 col-sm-12" >
                        <div class="col-md-3 col-sm-3 quat_titulo"><?= $rot_resquatip ?></div>
                        <div class="col-md-9 col-sm-9 quat_titulo"><?= $rot_restiptav . " (" . $geral->germoeatr() . ")" ?></div>

                        <?php
                        $pinta_fundo = 1;
                        foreach ($final_quarto_tipo[$quarto_item] as $quarto_tipo_tarifas) {
                            if ($pinta_fundo % 2 == 0) {//par
                                echo "<div class='col-md-12 col-sm-12 quat_tar cor_gray quarto_tipo_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "'>";
                            } else {//impar
                                echo "<div class='col-md-12 col-sm-12 quat_tar cor_white quarto_tipo_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "'>";
                            }
                            ?>
                            <div class="col-md-3 col-sm-3">
                                <p class="quat_tar_inner_title"><?= $quarto_tipo_tarifas['quarto_tipo_nome'] ?></p>
                                <p class="quat_tar_inner_info">Adultos: <?= $quarto_tipo_tarifas['adulto_quantidade'] ?> 
                                    Crianças: <?= $quarto_tipo_tarifas['crianca_quantidade'] ?><br/>
                                    Unid. disponíveis: <?= $quarto_tipo_tarifas['quantidade_disponivel'] ?></p>
                            </div>
                            <div class="col-md-9 col-sm-9">
                                <?php
                                $indice_tarifa = 1;
                                foreach ($quarto_tipo_tarifas['tarifas'] as $tarifa_tipo_codigo => $tarifa) {
                                    ?>
                                    <div class = "col-md-12 col-sm-12 quat_tar_inner_det ">
                                        <div class = "col-md-7 col-sm-6 ">
                                            <p class="tarifa_titulo"><?= $indice_tarifa
                                    ?> - <?= $tarifa['tarifa_tipo_nome'] ?></p>
                                            <p class="quat_tar_inner_info"><?= $tarifa['condicao'] ?></p>
                                        </div>

                                        <?php
                                        //Verifica se esta voltando da resquatar e precisa remarcar as tarifas e descontos (se existentes)
                                        $tarifa_selecionada_anteriormente = 0;
                                        if (isset($resadisel_volta) || isset($rescliide_volta)) {
                                            if ($this->request->data['tarifa_quarto_item_' . $quarto_item] == $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo) {
                                                $valor_tarifa = $this->request->data['tarifa_valor_' . $quarto_item];
                                                $tarifa_selecionada_anteriormente = 1;
                                                //verifica se tem diferença entre os valores (desconto, cortesia ou acrescimo)
                                                if ($valor_tarifa != $tarifa['total_tarifa'])
                                                    $button_background = 'background: url(../img/lupa-2.png) no-repeat center center'; //'background-color: rgb(155, 190, 247)';
                                            }else {
                                                $valor_tarifa = $tarifa['total_tarifa'];
                                                $button_background = '';
                                            }
                                            $total_tarifa = $this->request->data['total_original_' . $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo];
                                        } else {
                                            $valor_tarifa = $tarifa['total_tarifa'];
                                            $total_tarifa = $tarifa['total_tarifa'];
                                            $button_background = '';
                                        }
                                        ?>

                                        <div class="col-md-2 col-sm-3" style="padding-top:5px;">
                                            <input readonly="readonly" class="tarifa moeda" id="tarifa_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>"
                                                   value="<?= $geral->gersepatr($valor_tarifa) ?>" size="10" type="text">
                                            <input type="hidden" id="total_original_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>"
                                                   name="total_original_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>"
                                                   value="<?= $geral->gersepatr($total_tarifa) ?>" size="10" type="text">
                                            <a style='padding-bottom: 3px;'  title="Valores das diárias"  id="restardia_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>" class="restardia col-md-12 col-sm-12" 
                                               aria-quarto-item="<?= $quarto_item ?>" aria-tarifa-tipo-codigo="<?= $tarifa_tipo_codigo ?>" aria-quarto-tipo-codigo="<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>"
                                               aria-acesso-sequencia-tipo="<?= $quarto_tipo_tarifas['acesso_sequencia_codigo'] ?>"></a>
                                        </div>
                                        <div class="col-md-3 col-sm-3" style="padding-top: 11px;">   
                                            <button class="<?php
                                            if ($tarifa_selecionada_anteriormente) {
                                                echo 'reservado';
                                            } else {
                                                echo 'nao_reservado btn-info form-control';
                                            }
                                            ?> reservar_<?= $quarto_item ?>" id="btn_reservar_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>" type="button"
                                                    onclick="if ($(this).hasClass('nao_reservado')) {
                                                                                $('.reservar_<?= $quarto_item ?>').each(function (element) {
                                                                                    if ($(this).hasClass('reservado')) {
                                                                                        $(this).removeClass('reservado');
                                                                                        $(this).addClass('nao_reservado');
                                                                                        $(this).addClass('btn-info');
                                                                                        $(this).addClass('form-control');
                                                                                        $('#texto_selecionar_' + $(this).attr('id').replace('btn_reservar_', '')).css('display', 'block');
                                                                                        $('#icon_check_' + $(this).attr('id').replace('btn_reservar_', '')).css('display', 'none');
                                                                                        $('#checkbox_' + $(this).attr('id').replace('btn_reservar_', '')).prop('checked', false);

                                                                                        //Verifica se com essa os quartos desse tipo estavam nao visiveis por causa do esgotamento de unidades
                                                                                        quarto_tipo_codigo = $(this).attr('id').split('_')[3];

                                                                                        if ($('#unidades_disponiveis_quarto_tipo_' + quarto_tipo_codigo).val() == 0) {
                                                                                            $('.quarto_tipo_' + quarto_tipo_codigo).each(function () {
                                                                                                $(this).css('display', 'block');
                                                                                            });
                                                                                        }
                                                                                        $('#quarto_item_<?= $quarto_item ?> .quarto_tipo_' + quarto_tipo_codigo).removeClass('not_remove_unid_disponivel');
                                                                                        $('#unidades_disponiveis_quarto_tipo_' + quarto_tipo_codigo).val(parseInt($('#unidades_disponiveis_quarto_tipo_' + quarto_tipo_codigo).val()) + 1);
                                                                                    }
                                                                                });
                                                                                //Atualiza informações de exibição na tabela da resquatar
                                                                                $(this).removeClass('nao_reservado');
                                                                                $(this).removeClass('btn-info');
                                                                                $(this).removeClass('form-control');
                                                                                $(this).addClass('reservado');
                                                                                $('#texto_selecionar_' + $(this).attr('id').replace('btn_reservar_', '')).css('display', 'none');
                                                                                $('#icon_check_' + $(this).attr('id').replace('btn_reservar_', '')).css('display', 'block');
                                                                                $('#checkbox_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>').prop('checked', true);

                                                                                //Atualiza informações de exibição no carrinho (os valores são atualizados na rescaratu)
                                                                                $('#quarto_tipo_nome_quarto_item_<?= $quarto_item ?>').text('<?= $quarto_tipo_tarifas['quarto_tipo_nome'] ?>');
                                                                                $('#tarifa_nome_quarto_item_<?= $quarto_item ?>').text('<?= $tarifa['tarifa_tipo_nome'] ?>: ');
                                                                                $('#tarifa_moeda_quarto_item_<?= $quarto_item ?>').text('<?= $geral->germoeatr() ?>');

                                                                                //Atualiza informações de campos hidden referentes ao quarto (os valores  sao atualizadas na rescaratu)
                                                                                $('#quarto_tipo_nome_<?= $quarto_item ?>').val('<?= $quarto_tipo_tarifas['quarto_tipo_nome'] ?>');
                                                                                $('#quarto_tipo_codigo_<?= $quarto_item ?>').val('<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>');
                                                                                $('#tarifa_nome_<?= $quarto_item ?>').val('<?= $tarifa['tarifa_tipo_nome'] ?>');
                                                                                $('#tarifa_tipo_codigo_<?= $quarto_item ?>').val('<?= $tarifa_tipo_codigo ?>');

                                                                                //Verifica se com essa seleção as unidades disponíveis desse tipo esgotaram
                                                                                $('#unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').val(parseInt($('#unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').val()) - 1);
                                                                                $('#quarto_item_<?= $quarto_item ?> .quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').addClass('not_remove_unid_disponivel');
                                                                                if ($('#unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').val() == 0) {
                                                                                    $('.quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').not('.not_remove_unid_disponivel').each(function () {
                                                                                        $(this).css('display', 'none');
                                                                                    });
                                                                                }
                                                                            } else if ($(this).hasClass('reservado')) {
                                                                                $(this).removeClass('reservado');
                                                                                $(this).addClass('nao_reservado');
                                                                                $(this).addClass('btn-info');
                                                                                $(this).addClass('form-control');
                                                                                $('#texto_selecionar_' + $(this).attr('id').replace('btn_reservar_', '')).css('display', 'block');
                                                                                $('#icon_check_' + $(this).attr('id').replace('btn_reservar_', '')).css('display', 'none');
                                                                                $('#checkbox_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>').prop('checked', false);

                                                                                //Atualiza todos os valores no carrinho e hidden
                                                                                $('#total_preco_txt').text(gervalexi(gervalper($('#total_preco_txt').text()) - gervalper($('#tarifa_' + $(this).attr('id').replace('btn_reservar_', '')).val())));
                                                                                $('#total_preco').val(gervalper($('#total_preco_txt').text()));
                                                                                $('#quarto_tipo_nome_quarto_item_<?= $quarto_item ?>').text('');
                                                                                $('#tarifa_moeda_quarto_item_<?= $quarto_item ?>').text('');
                                                                                $('#tarifa_nome_quarto_item_<?= $quarto_item ?>').text('');
                                                                                $('#tarifa_valor_quarto_item_<?= $quarto_item ?>').text('');
                                                                                $('#quarto_tipo_nome_<?= $quarto_item ?>').val('');
                                                                                $('#quarto_tipo_codigo_<?= $quarto_item ?>').val('');
                                                                                $('#tarifa_nome_<?= $quarto_item ?>').val('');
                                                                                $('#tarifa_valor_<?= $quarto_item ?>').val('');
                                                                                $('#tarifa_tipo_codigo_<?= $quarto_item ?>').val('');

                                                                                //Verifica se com essa os quartos desse tipo estavam nao visiveis por causa do esgotamento de unidades

                                                                                if ($('#unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').val() == 0) {
                                                                                    $('.quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').each(function () {
                                                                                        $(this).css('display', 'block');
                                                                                    });
                                                                                }
                                                                                $('#quarto_item_<?= $quarto_item ?> .quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').removeClass('not_remove_unid_disponivel');
                                                                                $('#unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').val(parseInt($('#unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>').val()) + 1);
                                                                            }

                                                                            rescaratu();
                                                    ">
                                                <span style="<?php
                                                if ($tarifa_selecionada_anteriormente) {
                                                    echo 'display:none';
                                                }
                                                ?>" id="texto_selecionar_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>"><?= $rot_gerselbtn ?></span>
                                                <span class="item_selected" id="icon_check_<?= $quarto_item ?>_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>_<?= $tarifa_tipo_codigo ?>" style="<?php
                                                if ($tarifa_selecionada_anteriormente) {
                                                    echo 'display:block';
                                                } else {
                                                    echo 'display:none';
                                                }
                                                ?>"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                    $indice_tarifa++;
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        $pinta_fundo++;

                        if (!in_array($quarto_tipo_tarifas['quarto_tipo_codigo'], $unidades_disponiveis_por_quarto_tipo)) {
                            ?>
                            <!--Variável que controla se as unidades disponiveis esgotaram durante o processo de reserva -->
                            <input type="hidden" id="unidades_disponiveis_quarto_tipo_<?= $quarto_tipo_tarifas['quarto_tipo_codigo'] ?>" value="<?= $quarto_tipo_tarifas['quantidade_disponivel'] ?>" />
                            <?php
                            array_push($unidades_disponiveis_por_quarto_tipo, $quarto_tipo_tarifas['quarto_tipo_codigo']);
                        }
                    }
                    ?>
                </div>
                <?php
                $indice_quarto++;
            } else {
                //Quarto foi removido, nao exibe nem incrementa o indice
                if ($quarto_item_removido_array[$quarto_item] == 1) {
                    
                }
                //Quarto sem tarifas, exibe mensagem e incrementa o indice
                if (!isset($resadisel_volta) && !isset($rescliide_volta) && $quarto_item_sem_tarifas_array[$quarto_item] == 1) {
                    ?>
                    <div id="quarto_item_<?= $quarto_item ?>">
                        <div class="col-md-12" style="margin-bottom:15px">
                            <div class="col-md-3">
                                <strong><?= $rot_resquacod ?></strong> <strong><span id="label_quarto_item_<?= $quarto_item ?>"><?= $indice_quarto ?></span></strong>
                            </div>


                        </div>
                        <strong style="color:red"><?= $geral->germencri($this->request->session()->read('empresa_selecionada')['empresa_codigo'], 82)['mensagem']; ?></strong>
                    </div>   
                    <?php
                    $indice_quarto++;
                }
            }
        }
        ?>
        <div class="row col-md-12 col-sm-12 quat_botoes">
            <div class="col-md-6 col-sm-4"></div>
            <div class="cancel-right col-md-3 col-sm-4">
                <input class="form-control btn-default submit-button" id="reserva_voltar" type="submit" name="resmodbtn" aria-form-id="resquatar"  
                       value="<?= $rot_gervolbot ?>" onclick="$('#form_validator_function').remove();
                               $('#resquatar').attr('action', '<?= $path ?>reservas/rescriini')" >
            </div>
            <div class="pull-left col-md-3 col-sm-4">
                <input aria-form-id='resquatar' type="submit" class="form-control btn-primary submit-button"  value="<?= $rot_geravabot ?>">          
            </div>
        </div>

        <!--Armazena os dados da página anterior -->
        <input type="hidden" name="empresa_nome"  value="<?= $empresa_nome_fantasia ?>">
        <input type="hidden" name="resentdat"  value="<?= $this->request->data['resentdat'] ?>">
        <input type="hidden" name="ressaidat"  value="<?= $this->request->data['ressaidat'] ?>">
        <input type="hidden" name="inicial_data_completa"  value="<?= $inicial_data_completa ?>">
        <input type="hidden" name="final_data_completa"  value="<?= $final_data_completa ?>">
        <input type="hidden" name="inicial_data_completa_original"  value="<?= $inicial_data_completa ?>">
        <input type="hidden" name="final_data_completa_original"  value="<?= $final_data_completa ?>">
        <input type="hidden" name="pagina_referencia"  value="<?= $pagina_referencia ?>">
        <input type="hidden" name="resquaqtd" id="resquaqtd"  value="<?= $this->request->data['resquaqtd'] ?>">
        <?php for ($i = 1; $i <= sizeof($resaduqtd); $i++) { ?>
            <input type="hidden" name="resaduqtd_<?= $i ?>" id="resaduqtd_<?= $i ?>" value="<?= $resaduqtd[$i - 1] ?>">
        <?php } ?>

        <?php for ($i = 1; $i <= sizeof($rescriqtd); $i++) { ?>
            <input type="hidden" name="rescriqtd_<?= $i ?>" id="rescriqtd_<?= $i ?>" value="<?= $rescriqtd[$i - 1] ?>">
            <?php
        }
        foreach ($quarto_ocupacao as $key => $quarto_item) {
            if (isset($quarto_item['crianca_idade'])) {
                for ($i = 0; $i < sizeof($quarto_item['crianca_idade']); $i++) {
                    ?>
                    <input type="hidden" name="crianca_idade_<?= $key ?>_<?= $i ?>" value="<?= $quarto_item['crianca_idade'][$i] ?>">
                    <?php
                }
            }
        }
        ?>
        <?php
        for ($quarto_item = 1; $quarto_item <= $this->request->data['resquaqtd']; $quarto_item++) {
            ?>
            <input type="hidden" name="tarifa_nome_<?= $quarto_item ?>" id="tarifa_nome_<?= $quarto_item ?>" value="<?php
            if (isset($this->request->data['tarifa_nome_' . $quarto_item])) {
                echo $this->request->data['tarifa_nome_' . $quarto_item];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="tarifa_valor_<?= $quarto_item ?>"  id="tarifa_valor_<?= $quarto_item ?>" value="<?php
            if (isset($this->request->data['tarifa_valor_' . $quarto_item])) {
                echo $this->request->data['tarifa_valor_' . $quarto_item];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="tarifa_tipo_codigo_<?= $quarto_item ?>"  id="tarifa_tipo_codigo_<?= $quarto_item ?>" value="<?php
            if (isset($this->request->data['tarifa_tipo_codigo_' . $quarto_item])) {
                echo $this->request->data['tarifa_tipo_codigo_' . $quarto_item];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="quarto_tipo_nome_<?= $quarto_item ?>" id="quarto_tipo_nome_<?= $quarto_item ?>" value="<?php
            if (isset($this->request->data['quarto_tipo_nome_' . $quarto_item])) {
                echo $this->request->data['quarto_tipo_nome_' . $quarto_item];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="quarto_tipo_codigo_<?= $quarto_item ?>" id="quarto_tipo_codigo_<?= $quarto_item ?>" value="<?php
            if (isset($this->request->data['quarto_tipo_codigo_' . $quarto_item])) {
                echo $this->request->data['quarto_tipo_codigo_' . $quarto_item];
            } else {
                echo '';
            }
            ?>">
               <?php } ?>
        <input type="hidden" id="inicial_padrao_horario" name="inicial_padrao_horario" value="<?= $inicial_padrao_horario ?>" />
        <input type="hidden" id="final_padrao_horario" name="final_padrao_horario" value="<?= $final_padrao_horario ?>" />
        <input type="hidden" name="dias_estadia" id="dias_estadia" value="<?= $dias_estadia ?>">
        <input type="hidden" name="total_preco" id="total_preco" value="<?= $total_preco ?>">
        <input type="hidden" name="resquatar_volta" value="1">

        <?php
//Armazena os dados referentes às diárias
        if (!isset($resadisel_volta) && !isset($rescliide_volta)) {
            foreach ($final_quarto_tipo as $quarto_item => $quarto_info) {
                foreach ($quarto_info as $quarto_tipo_tarifas) {
                    foreach ($quarto_tipo_tarifas['tarifas'] as $tarifa_tipo_codigo => $tarifa) {
                        $data_indice = 1;
                        foreach ($tarifa as $key => $tarifa_info) {
                            if ($key != 'tarifa_tipo_nome' && $key != 'condicao' && $key != 'total_tarifa' && $key != 'tarifa_tipo_codigo') {
                                print "<input type='hidden' id='info_diarias_" . $quarto_item . "_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo . "_$data_indice' "
                                        . "name='info_diarias_" . $quarto_item . "_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo . "_$data_indice' value='" . $key . "|" . $tarifa[$key] . "'/>";
                                print "<input type='hidden' id='tarifa_valor_original_" . $quarto_item . "_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo . "_$data_indice' name='tarifa_valor_original_" . $quarto_item . "_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo . "_$data_indice' value='" . $geral->gersepatr($tarifa[$key]) . "' /> ";
                                $data_indice++;
                            }
                        }
                        //Armazena a variavel para controle de desconto total
                        print "<input type='hidden' id='total_desconto_tmp_" . $quarto_item . "_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo . "' name='total_desconto_tmp_" . $quarto_item . "_" . $quarto_tipo_tarifas['quarto_tipo_codigo'] . "_" . $tarifa_tipo_codigo . "'  value='d|0.00|p|0.00|||' />";
                    }
                }
            }
        } else {
            //Salva as info_diarias conforme foram para as páginas resadisel e rescliide
            foreach ($this->request->data as $key => $value) {
                if (strpos($key, 'info_diarias_') !== false || strpos($key, 'tarifa_valor_original_') !== false || strpos($key, 'total_desconto_tmp_') !== false) {
                    ?>
                    <input type="hidden" id="<?= $key ?>" name="<?= $key ?>" value="<?= $value ?>">
                    <?php
                }
            }
        }
        ?>
    </form>
</div>
</div>

<!--
<div id="restardia" style="display: none" title="Valore(s) da(s) Diária(s)" >
    <form class="form-horizontal" id="form_restardia">
        <input type="hidden" id="codigo_referencia_atual" value="0" />
        <div id="restardia-table" class="table col-md-12 col-sm-12">
            <div class="col-md-6 col-sm-6 rest_table_inner"><?= $rot_resdiaqtd ?></div>
            <div class="col-md-3 col-sm-3 rest_table_inner">&nbsp;</div>
            <div class="col-md-3 col-sm-3 rest_table_inner">&nbsp;</div>


<?php
foreach ($final_quarto_tipo as $quarto_item => $quarto_info) {
    foreach ($quarto_info as $quarto_tipo_tarifas) {
        $total_tarifas = 0;
        foreach ($quarto_tipo_tarifas['tarifas'] as $tarifa_tipo_codigo => $tarifa) {
            ?>
                                                                                                                        <div id='<?php echo $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo ?>' class="valor_esc col-md-12 col-sm-12">
            <?php
            $diaria_indice = 1;
            foreach ($tarifa as $key => $tarifa_info) {
                if ($key != 'tarifa_tipo_nome' && $key != 'condicao' && $key != 'total_tarifa' && $key != 'tarifa_tipo_codigo') {
                    //se estiver voltando da resadisel, precisa manter os valores de desconto
                    if (isset($resadisel_volta) || isset($rescliide_volta)) {

                        $tarifa_desconto_tmp = $this->request->data['info_diarias_' . $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice];
                        $tarifa_valor = explode("|", $tarifa_desconto_tmp)[1];

                        if (sizeof(explode("|", $tarifa_desconto_tmp)) <= 2)
                            $tarifa_desconto_tmp .= '|d|0.00|p|0.00|||';
                        $tarifa_desconto_tmp = str_replace($key . "|" . $tarifa_valor . "|", "", $tarifa_desconto_tmp);
                        //se houve desconto, cortesia ou acrescimo
                        if (sizeof(explode('|', $tarifa_desconto_tmp)) > 2) {
                            $button_background = 'background: url(../img/lapis-2.png) no-repeat center center'; //'background-color: rgb(155, 190, 247)';
                            //verifica se foi dado desconto cortesia ou acrescimo
                            $desc_cort = explode("|", $tarifa_desconto_tmp) [0];
                            $desc_valor = explode("|", $tarifa_desconto_tmp) [3];
                            if ($desc_cort == 'd') {
                                $tarifa_txt = $tarifa_valor - $desc_valor;
                            } else if ($desc_cort == 'a') {
                                $tarifa_txt = $tarifa_valor + $desc_valor;
                            } else if ($desc_cort == 'c') {
                                $tarifa_txt = 0;
                            } else
                                $tarifa_txt = $tarifa_valor;
                        } else {
                            $tarifa_txt = $tarifa_valor;
                        }
                    } else {
                        $tarifa_desconto_tmp = 'd|0.00|p|0.00|||';
                        $tarifa_txt = $tarifa[$key];
                        $button_background = '';
                    }
                    ?>
                                                                                                                                                                                                    <div class="col-md-6 col-sm-6" style="text-align:right">Diária <?php echo $diaria_indice . " " ?>: <?= date('d/m/Y', strtotime($key)) ?></div>
                    <?php if ($tarifa_manual_entrada == null) { ?>
                                                                                                                                                                                                                                        <div class="col-md-3 col-sm-3">                                    
                                                                                                                                                                                                                                            <span id="tarifa_txt_<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>" 
                                                                                                                                                                                                                                                  style="float:right"> <?= $geral->gersepatr($tarifa_txt) ?></span>
                                                                                                                                                                                                                                            <span style="float:right"><?= $geral->germoeatr() . " " ?>  </span>
                                                                                                                                                                                                                                        </div> 
                    <?php } else { ?>
                                                                                                                                                                                                                                        <div class="col-md-1 col-sm-1">     
                                                                                                                                                                                                                                            <span><?= $geral->germoeatr() . " " ?>  </span>
                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                        <div class="col-md-3 col-sm-3">                                    

                                                                                                                                                                                                                                            <input style="margin-top: -6px;" type="text" class="form-control moeda tarifa_manual_entrada" 
                                                                                                                                                                                                                                                   id="tarifa_txt_<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>" 
                                                                                                                                                                                                                                                   style="float:right" value="<?= $geral->gersepatr($tarifa_txt) ?>"
                                                                                                                                                                                                                                                   aria-tarifa-referencia-id='<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>' /> 
                                                                                                                                                                                                                                        </div> 

                    <?php } ?>
                                                                                                                                                                                                    <div class="col-md-1 col-sm-1">
                                                                                                                                                                                                        <input type="hidden" id="tarifa_diaria_<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>" value="<?= $geral->gersepatr($tarifa_txt) ?>" />
                                                                                                                                                                                                        <input type="hidden" id="tarifa_desconto_tmp_<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>" value="<?= $tarifa_desconto_tmp ?>" />
                                                                                                                                                                                                        <button  tabindex="-1" type="button" title="Modificar valores" style="<?= $button_background ?>" id="tarifa_btn_<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>" class="btn-editar condesapl_diarias" aria-tarifa-referencia-id='<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo . '_' . $diaria_indice ?>'>
                                                                                                                                                                                                        </button>
                                                                                                                                                                                                    </div>
                    <?php
                    $diaria_indice++;
                    $total_tarifas += $tarifa_txt;
                }
            }
            ?>
                                                                                                                            <div class="col-md-6 col-sm-6" style="text-align:right">
                                                                                                                                <strong>Total </strong>
                                                                                                                            </div>
                                                                                                                            <div class="col-md-1 col-sm-1">     
                                                                                                                                <span><?= $geral->germoeatr() . " " ?>  </span>
                                                                                                                            </div>
                                                                                                                            <div class="col-md-3 col-sm-3">
                                                                                                                                <span id="total_tarifas_restardia_<?= $quarto_item . '_' . $quarto_tipo_tarifas['quarto_tipo_codigo'] . '_' . $tarifa_tipo_codigo ?>"><?= $geral->gersepatr($total_tarifas) ?></span>
                                                                                                                            </div>

                                                                                                                        </div>

        <?php }
        ?>


        <?php
    }
}
?>
        </div>


        <div class="row col-md-12 col-sm-12 quat_botoes2">
            <div class="col-md-6 col-sm-4"></div>
            <div class="cancel-right col-md-3 col-sm-4 ui-dialog-btn-close">
                <input class="form-control btn-default" style="float:left; margin-right:10px" type="button" value="<?= $rot_gerdesbot ?>" onclick="$('#restardia').dialog('close')">
            </div>
            <div class="pull-left col-md-3 col-sm-4">
                <input class="form-control btn-primary condessal_diarias" type="button" name="resmodbtn"  value="<?= $rot_gersalbot ?>">
            </div>
        </div>
    </form>
</div>
-->

<?php
echo $this->element('reserva/rescarinf_elem', ['pagina' => 'resquatar', 'quarto_item_removido_array' => $quarto_item_removido_array,
    'quarto_item_sem_tarifas_array' => $quarto_item_sem_tarifas_array]);
?>
<!--</div>-->

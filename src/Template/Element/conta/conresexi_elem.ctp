<?php

use App\Model\Entity\Geral;
use App\Utility\Util;
use App\Model\Entity\Estadia;
use App\Model\Entity\DocumentoConta;
use App\Model\Entity\Reserva;

$reserva = new Reserva();
$estadia = new Estadia();
$documento_conta = new DocumentoConta();

$geral = new Geral();
if (isset($pesquisa_contas) && count($pesquisa_contas) > 0) {
    ?>
    <input type="hidden" id="quarto_itens" value="<?= implode("|", array_keys($quarto_itens)) ?>"
           <div style="margin-top:100px">
        <div class="cabecalho_conta" style="padding-bottom: 8px;">
            <div class="row">
                <!-- <div class="col-md-3">
                     <label><b><?= $rot_condadtit ?> <?= $documento_numero_selecionado ?></b></label>
                 </div>
                 <div class="col-md-6">
                     <label><b><?= $rot_cliclicon ?>: </b></label><a href="#a" class="clicadmod" aria-cliente-codigo = '<?= $cabecalho_conta[0]["cliente_codigo"] ?>'> <?= $cabecalho_conta[0]['nome'] . ' ' . $cabecalho_conta[0]['sobrenome'] ?></a>
                 </div>-->
                <div class="col-md-12">
                    <label class="pull-right" for="ocultar_estornados">Ocultar itens estornados</label>
                    <input type="checkbox" style="margin-right:3px" class="pull-right" name="ocultar_estornados" id="ocultar_estornados" <?php if (isset($ocultar_estornados) && $ocultar_estornados == '1') echo 'checked' ?> /> 
                </div>
            </div>
        </div>
        <?php
        $total_geral = 0;
        $total_pago_geral = 0;
        $total_despesas_geral = 0;
        $total_a_pagar_agora_geral = 0;

        $pagamento_geral_habilitado = 1;
        $checkout_geral_habilitado = 1;
        $exibir_estornados = 1;
        if (isset($ocultar_estornados) && $ocultar_estornados == '1')
            $exibir_estornados = 0;

        foreach ($contas_quarto_item as $quarto_item => $conta_quarto) {
            //Itens virtuais são utilizados na tela de cancelamento
            if ($tela == 'resdoccan') {
                $itens_virtuais = array();
                //Verifica se precisa inserir o item de multa, de acordo com o prazo de cancelamento 
                //Se estiver com o prazo ultrapassado
                $indice_quarto_item_cancelamento = array_search($quarto_item_selecionado, array_column($cabecalho_conta, 'quarto_item'));

                if (Util::comparaDatas($geral->geragodet(2, $empresa_codigo), $cabecalho_conta[$indice_quarto_item_cancelamento]['cancelamento_limite']) == 1)
                    array_push($itens_virtuais, $multa_cancelamento_item[$quarto_item]);
            }

            $dados_resdocpes_quarto_item = array_search($quarto_item, array_column($cabecalho_conta, 'quarto_item'));
            $total[$quarto_item] = 0;
            $total_pago[$quarto_item] = 0;
            $total_despesas[$quarto_item] = 0;

            $total_a_pagar_agora[$quarto_item] = $reserva->resapgval($empresa_codigo, $documento_numero_selecionado, $quarto_item, $geral->geragodet(2), null)['a_pagar_total'];

            foreach ($conta_quarto as $conta) {
                if ($conta['pre_autorizacao'] == 0)
                    $total[$quarto_item] = round($total[$quarto_item] + ($conta['total_valor']), 2);
                if ($conta['produto_tipo_codigo'] == 'PAG')
                    $total_pago[$quarto_item] = round($total_pago[$quarto_item] + ($conta['total_valor']), 2);

                if ($conta['contabil_tipo'] == 'D')
                    $total_despesas[$quarto_item] = round($total_despesas[$quarto_item] + ($conta['total_valor']), 2);

                if ($tela == 'resdoccan' && $conta['evento'] != null && $conta['evento'] == '0') {
                    $item_virtual = $conta;
                    $item_virtual['referenciado_item'] = $conta['conta_item'];
                    $item_virtual['produto_qtd'] = (-1) * $conta['produto_qtd'];
                    array_push($itens_virtuais, $item_virtual);
                }
            }
            if ($tela == 'resdoccan') {
                foreach ($itens_virtuais as $conta) {
                    $total_valor = $conta['total_valor'];
                    if (!isset($conta['multa_cancelamento']))
                        $total_valor *= -1;
                    $total[$quarto_item] = round($total[$quarto_item] + floatval($total_valor), 2);
                }
            }

            //Verifica a habilitação dos botões de ação
            $adicionar_item_habilitado = 1;
            $pagamento_habilitado = 1;
            $checkout_habilitado = 1;
            if (!$documento_conta->conadihab($conta_quarto[0]['quarto_status_codigo'])) {
                $adicionar_item_habilitado = 0;
            }
            if (!$documento_conta->conpaghab($conta_quarto[0]['quarto_status_codigo'])) {
                $pagamento_habilitado = 0;
                $pagamento_geral_habilitado = 0;
            }
            //Botão de checkout nao deve estar habilitado de acordo com a estchohab ou quando a tela for a tela de conta
            if (!$estadia->estchohab($conta_quarto[0]['quarto_status_codigo']) || $tela == 'conresexi') {
                $checkout_habilitado = 0;
                $checkout_geral_habilitado = 0;
            }
            ?>
            <div class="es-account-container">
                <input type="hidden" name="total_pago_<?= $quarto_item ?>" id="total_pago_<?= $quarto_item ?>" value="<?= (-1) * $total_pago[$quarto_item] ?>" />
                <input <?php if ($geracever_conitecri == '0') echo 'disabled' ?> type="button" style="display:none" id="conitecri_button_<?= $quarto_item ?>" class="conitecri" aria-quarto-item='<?= $quarto_item ?>'
                                                                                 aria-modo-exibicao="<?= $modo_exibicao ?>" aria-inicial-data="<?= $cabecalho_conta[$dados_resdocpes_quarto_item]['inicial_data'] ?>" 
                                                                                 aria-final-data="<?= $cabecalho_conta[$dados_resdocpes_quarto_item]['final_data'] ?>" aria-redirect-page='<?= $redirect_page ?>'
                                                                                 aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-status='<?= $conta_quarto[0]['quarto_status_codigo'] ?>'  />
                <input type="button" style="display:none" id="conpagcri_button_<?= $quarto_item ?>" class="conpagcri" aria-quarto-item='<?= $quarto_item ?>'  aria-modo-exibicao="<?= $modo_exibicao ?>" 
                       aria-pagante-codigo='<?= $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cliente_codigo'] ?>'  aria-quarto-status='<?= $conta_quarto[0]['quarto_status_codigo'] ?>' 
                       aria-pagante-nome='<?= $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['nome'] . ' ' . $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['sobrenome'] ?>'
                       aria-pagante-cpf-cnpj='<?= isset($cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cpf']) ? $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cpf'] : $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cnpj'] ?>' aria-form-id='<?= $form_id ?>' 
                       aria-redirect-page='<?= $redirect_page ?>' aria-back-page='<?= $back_page ?>' aria-documento-numero='<?= $documento_numero_selecionado ?>'
                       aria-evento='<?= $evento ?>' aria-pagamento-total="0" />

                <button type="button" class="accordion-conta exibi_info  es-accordion-conta <?php if (isset($opened_acordions) && (strpos($opened_acordions, $quarto_item . "|") !== false)) echo 'active' ?>" aria-accordion-item="<?= $quarto_item ?>">

                    <a></a>
                    <?php #echo "{$quarto_item})"; ?>

                    <b class="es-room-title"><b><?= $rot_resquacod ?> <?php if ($cabecalho_conta[$dados_resdocpes_quarto_item]['quarto_codigo'] != null && $cabecalho_conta[$dados_resdocpes_quarto_item]['quarto_codigo'] != "") echo $cabecalho_conta[$dados_resdocpes_quarto_item]['quarto_codigo']; ?></b> - <?= $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['nome'] . ' ' . $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['sobrenome'] ?></b>

                </button>

                <div class="panel-accordion es-panel-accordion col-md-12
                     <?php if (isset($opened_acordions) && (strpos($opened_acordions, $quarto_item . "|") !== false)) echo 'show' ?>" >
                         <?php
                         //Checa se a data atual é maior que a data limite de cancelamento
                         $color = '#000';
                         if (Util::comparaDatas($cabecalho_conta[$dados_resdocpes_quarto_item]['cancelamento_limite'], $geral->geragodet()) == -1)
                             $color = 'red';
                         ?>
                    <div class="row es-inner-row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <label><b>Data: </b></label>
                            <?php
                            $datas = explode("|", $cabecalho_conta[$dados_resdocpes_quarto_item]['datas']);
                            ?>
                            <?= Util::convertDataDMY($cabecalho_conta[$dados_resdocpes_quarto_item]['inicial_data'], 'd/m/Y H:i') ?> -
                            <?= Util::convertDataDMY($cabecalho_conta[$dados_resdocpes_quarto_item]['final_data'], 'd/m/Y H:i') ?>
                            (<?= sizeof($datas) ?> <?php
                            if (sizeof($datas) > 1)
                                echo 'diárias';
                            else
                                echo 'diária';
                            ?>)
                        </div>

                        <?php if ($tela == 'resdoccan') { ?>
                            <div class="col-md-3" style="color:<?= $color ?>"><label><b><?= $rot_reslimcan ?>: </b> </label> <?= Util::convertDataDMY($cabecalho_conta[$dados_resdocpes_quarto_item]['cancelamento_limite'], 'd/m/Y H:i') ?></div>
                        <?php } ?>
                    </div>

                    <table class="table_cliclipes es-table_cliclipes">
                        <thead>
                            <tr>
                                <th><?= $rot_geritetit ?></th>  
                                <th><?= $rot_gerdattit ?></th>
                                <th><?= $rot_conprocod ?></th>
                                <th><?= $rot_conproqtd ?></th>
                                <th><?= $rot_conpretot ?> (<?= $geral->germoeatr() ?>)</th>
                            </tr>
                        </thead>
                        <?php
                        $max_conta_item = 1;
                        foreach ($conta_quarto as $conta) {
                            //Checa se esse item é referenciado por algum
                            $referenciado = array_search($conta['conta_item'], array_column($conta_quarto, 'referenciado_item'));
                            $referenciado_item_virtual = false;
                            if ($tela == 'resdoccan')
                                $referenciado_item_virtual = array_search($conta['conta_item'], array_column($itens_virtuais, 'referenciado_item'));

                            $estornado_ou_referenciado = 0;
                            if ($conta['referenciado_item'] != null || $referenciado !== false || $referenciado_item_virtual !== false)
                                $estornado_ou_referenciado = 1;
                            if ($conta['pre_autorizacao'] == 0) {
                                ?>
                                <tr class="conitemod <?php if ($estornado_ou_referenciado) echo 'item_estornado' ?>"
                                <?php if ($estornado_ou_referenciado && !$exibir_estornados) echo "style='display:none'"; ?>
                                    aria-modo-exibicao="<?= $modo_exibicao ?>" aria-quarto-item='<?= $quarto_item ?>' aria-redirect-page='<?= $redirect_page ?>'
                                    aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-item-numero='<?= $conta['conta_item'] ?>'>                      
                                    <td><?= $conta['conta_item'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($conta['data'])) ?></td>
                                    <td><?= $conta['produto_codigo'] ?></td>
                                    <td><?= $geral->gersepatr($conta['produto_qtd']) ?></td>
                                    <td><?= $geral->gersepatr($conta['total_valor']) ?></td>
                                </tr>
                                <?php
                                if ($conta['conta_item'] > $max_conta_item)
                                    $max_conta_item = $conta['conta_item'];
                            }
                        }
                        ?>

                        <!-- Exibe os itens virtuais em caso de tela de cancelamento -->
                        <?php
                        $conta_item_virtual = $max_conta_item + 1;
                        if ($tela == 'resdoccan') {
                            foreach ($itens_virtuais as $conta) {
                                $total_valor = $conta['total_valor'];
                                if (!isset($conta['multa_cancelamento']))
                                    $total_valor *= -1;
                                ?>
                                <tr  class="<?php if (!isset($conta['multa_cancelamento'])) echo 'item_estornado' ?>" 
                                     <?php if (!$exibir_estornados) echo "style='display:none'"; ?>>                      
                                    <td  <?php if (isset($conta['multa_cancelamento'])) echo 'style="color:red; font-weight:bold"' ?>><?= $conta_item_virtual ?></td>
                                    <td  <?php if (isset($conta['multa_cancelamento'])) echo 'style="color:red; font-weight:bold"' ?>><?= date('d/m/Y', strtotime($conta['data'])) ?></td>
                                    <td  <?php if (isset($conta['multa_cancelamento'])) echo 'style="color:red; font-weight:bold"' ?>><?= $conta['produto_codigo'] ?></td>
                                    <td  <?php if (isset($conta['multa_cancelamento'])) echo 'style="color:red; font-weight:bold"' ?>><?= $geral->gersepatr($conta['produto_qtd']) ?></td>
                                    <td  <?php if (isset($conta['multa_cancelamento'])) echo 'style="color:red; font-weight:bold"' ?>><?= $geral->gersepatr($total_valor) ?></td>
                                </tr>
                                <?php
                                // $total[$quarto_item] = round($total[$quarto_item] + floatval($total_valor), 2);
                                $conta_item_virtual++;
                            }
                        }
                        ?>

                        <tr class="es-table-total">
                            <td colspan="4"><?php echo "<b style='font-size: 12px;'>" . $rot_gertottit . "</b>"; ?></td>
                            <td><?php echo "<b style='font-size: 12px;'>" . $geral->gersepatr($total[$quarto_item]) . "</b>"; ?>
                            </td>
                        </tr> 
                    </table>

                    <div class="row es-inner-row" style="margin: 0; margin-top: 10px; padding:10px; ">

                        <div class="col-md-12">
                            <span class="es-featured-row row">

                                <!-- Dados da reserva -->
                                <div class="es-inline-elements"><lable><b>Despesas:</b></lable> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_despesas[$quarto_item]) ?></div>
                                <div class="es-inline-elements"><lable><b><?= $rot_contotpag ?>:</b></lable> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_pago[$quarto_item]) ?></div>
                                <div class="es-inline-elements"><lable><b><?= $rot_convalpag ?>:</b></lable> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total[$quarto_item]) ?></div>
                                <div class="es-inline-elements"><lable><b>A pagar agora:</b></lable> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_a_pagar_agora[$quarto_item]) ?></div>

                                <button class="form-control btn-default es-btn-right" type="button"
                                onclick="$('div[id^=\'conta_pdf_quarto_item_\']').css('display', 'none');
                                               $('#conta_pdf_quarto_item_<?= $quarto_item ?>').css('display', 'block');
                                               PrintElem('conresexi_pdf')"><i class="fa fa-print"></i> <?= $rot_gerimptit ?>
                                </button>

                                <?php if ($pagamento_habilitado) { ?>
                                    <button class="form-control btn-primary es-btn-right" type="button" onclick="$('#conpagcri_button_<?= $quarto_item ?>').click()" ><i class="fa fa-credit-card"></i> <?= $rot_conpagbot ?></button>
                                <?php } ?>

                                <?php if ($adicionar_item_habilitado) { ?>
                                    <button <?php if ($geracever_conitecri == '0') echo 'disabled' ?> class="form-control btn-primary es-btn-right" type="button" onclick="$('#conitecri_button_<?= $quarto_item ?>').click()"><i class="fa fa-plus-circle"></i> <?= $rot_concribot ?></button>
                                <?php } ?>

                            </span>



                        </div>

                        <div class="col-md-12 text-center">
                            <?php if ($checkout_habilitado) { ?>
                                <button class="form-control btn-primary" style="display: inline-block; width: auto; margin: 10px 0;" type="button" onclick="$('#checkout_todos_quartos').val(0);
                                                $('#quarto_item_checkout').val(<?= $quarto_item ?>);
                                                estchocri()"><i class="fa fa-sign-out"></i> <?= $rot_gerchotit ?></button>
                            <?php } ?>
                        </div>



                        </div>

                    <!--Exibição de pagamentos pre autorizados, so exibe se o total for maior que 0 -->
                    <?php
                    $total_pre_autorizado = 0;
                    foreach ($conta_quarto as $conta) {
                        if ($conta['pre_autorizacao'] == 1) {
                            $total_pre_autorizado = $total_pre_autorizado + ($conta['total_valor']);
                        }
                    }

                    if ($total_pre_autorizado > 0) {
                        ?>
                        <h5><b><?= $rot_conpreaut ?></b></h5>
                        <table class="table_cliclipes" style="margin-top:15px">
                            <thead>
                                <tr>
                                    <th><?= $rot_geritetit ?></th> 
                                    <th><?= $rot_gerdattit ?></th>
                                    <th><?= $rot_conprocod ?></th>
                                    <th><?= $rot_conproqtd ?></th>
                                    <th><?= $rot_conpretot ?> (<?= $geral->germoeatr() ?>)</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($conta_quarto as $conta) {
                                if ($conta['pre_autorizacao'] == 1) {
                                    ?>
                                    <tr class="conitemod" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item ?>'  aria-item-numero='<?= $conta['conta_item'] ?>'
                                        aria-modo-exibicao="<?= $modo_exibicao ?>"  aria-redirect-page='<?= $redirect_page ?>'>                         
                                        <td><?= $conta['conta_item'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($conta['data'])) ?></td>
                                        <td><?= $conta['produto_codigo'] ?></td>
                                        <td><?= $geral->gersepatr($conta['produto_qtd']) ?></td>
                                        <td><?= $geral->gersepatr($conta['total_valor']) ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                            <tr>
                                <td colspan="4"><?php echo "<b style='font-size: 12px;'>" . $rot_gertottit . "</b>"; ?></td>
                                <td><?php echo "<b style='font-size: 12px;'>" . $geral->gersepatr($total_pre_autorizado) . "</b>"; ?>
                                </td>
                            </tr>

                        </table>
                    <?php } ?>
                </div>
            </div>
            <?php
            $total_geral += $total[$quarto_item];
            $total_pago_geral += $total_pago[$quarto_item];
            $total_despesas_geral += $total_despesas[$quarto_item];
            $total_a_pagar_agora_geral += $total_a_pagar_agora[$quarto_item];
        }
        ?>
        <div class="es-container-total-conta">
            <input type="button" style="display:none" id="conpagcri_button_all" class="conpagcri" aria-quarto-item='1' aria-form-id='<?= $form_id ?>'  aria-modo-exibicao="<?= $modo_exibicao ?>" 
                   aria-redirect-page='<?= $redirect_page ?>' aria-back-page='<?= $back_page ?>' aria-documento-numero='<?= $documento_numero_selecionado ?>'
                   aria-evento='<?= $evento ?>' aria-pagamento-total="1"
                   aria-pagante-codigo='<?= $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cliente_codigo'] ?>'
                   aria-pagante-nome='<?= $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['nome'] . ' ' . $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['sobrenome'] ?>'
                   aria-pagante-cpf-cnpj='<?= isset($cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cpf']) ? $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cpf'] : $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['cnpj'] ?>'  />

            <button type="button" class="accordion-conta es-accordion-conta">
            <div class="es-room-title"><b><?= $rot_gertottit ?></b></div>
            </button>
            <div class="es-container-total">

                <button class="form-control btn-default es-btn-right" type="button"
                       onclick="$('div[id^=\'conta_pdf_quarto_item_\']').css('display', 'block');
                                   PrintElem('conresexi_pdf')"><i class="fa fa-print"></i> <?= $rot_gerimptit ?></button>
                <?php if ($pagamento_geral_habilitado) { ?>
                        <button class="form-control btn-primary es-btn-right" type="button" onclick="$('#conpagcri_button_all').click()"><i class="fa fa-credit-card"></i> <?= $rot_conpagbot ?></button>
                <?php } ?>
                <div class="col-md-2" style="width:160px">
                    <label><b>Despesas</b></label><br> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_despesas_geral) ?>
                </div>
                <div class="col-md-2">
                    <label><b><?= $rot_contotpag ?></b></label><br> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_pago_geral) ?>
                </div>
                <div class="col-md-2">
                    <label><b><?= $rot_convalpag ?></b></label><br> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_geral) ?>
                </div>
                <div class="col-md-2">
                    <label><b>A pagar agora</b></label><br> <?= $geral->germoeatr() ?> <?= $geral->gersepatr($total_a_pagar_agora_geral) ?>
                </div>
                <div class="col-md-12 text-center">

                    <?php if ($checkout_geral_habilitado) { ?>

                        <hr style="display: block; width: 100%; border-top: 1px solid #ccc;">

                        <button style="width: auto; display: inline-block; margin-bottom: 10px;" class="form-control btn-primary" type="button" onclick="$('#checkout_todos_quartos').val(1);
                                        estchocri()"
                               ><i class="fa fa-sign-out"></i> <?= $rot_gerchotit ?></button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
        //Elemento para geração de pdf
        echo  $this->element('conta/conresexi_pdf', ['cabecalho_conta' => $cabecalho_conta, 'contas_quarto_item' => $contas_quarto_item, 'itens_virtuais' => $itens_virtuais??null, 'tela' => $tela]);
    } else if (isset($pesquisa_contas)) {
        ?>
        <br/><br/>Nenhum item encontrado
        <?php
    }
    ?>
    <?php if ($tela == 'resdoccan') { ?>
        <div class="row col-md-12 col-sm-12 quat_botoes2">
            <div class="cancel-right col-md-4 col-sm-4">
                <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>" onclick="docs_a_cancelar = undefined;
                            $('.check_doc').prop('checked', false)">
            </div>
            <div class="pull-right col-md-4 col-sm-4">
                <?php
                if ($reserva->rescslhab($total[$quarto_item_selecionado])) {
                    ?>
                    <input  class="form-control btn-secundary" type="button" value = "<?= $rot_gercanbot ?> quarto <?= $quarto_item_selecionado ?>"
                            onclick="resdoccan_2(<?= $documento_numero_selecionado ?>, <?= $quarto_item_selecionado ?>, '<?= $cancelamento_limite ?>', '<?= $cancelamento_valor ?>')" >
                        <?php } ?>
            </div>
        </div>
        <?php
    }
    ?>
<?php

use Cake\Routing\Router;
use App\Utility\Util;
$datas = explode("|", $reserva_dados[$indice_quarto_item_atual]['datas']);
?>
<form id="estchocri" action="<?= Router::url('/', true) ?>/estadias/estchocri" method="post">
    <input type="hidden" id="quarto_item_checkout" name="quarto_item_checkout" value="">
    <input type="hidden" id="checkout_todos_quartos" name="checkout_todos_quartos" value="1">
    <input type="hidden" name="opened_acordions" id="opened_acordions" value="" />
    <input type="hidden" name="empresa_codigo" id="empresa_codigo" value="<?= $empresa_codigo ?>" />
    <input type="hidden" name="documento_numero" id="documento_numero" value="<?= $documento_numero_selecionado ?>" />
    <input type="hidden" name="total_quartos" id="total_quartos" value="<?= $total_quartos ?>" />
    <input type="hidden" name="url_redirect_after"  id="url_redirect_after"  value="reservas/resdocpes"> <?php
        //Adiciona o elemento de cabecalho
        echo $this->element('reserva/cabecalho_reserva', ['datas' => $datas, 'reserva_dados' => $reserva_dados[$indice_quarto_item_atual], 'exibe_datas' => 1]);
        ?>
    <!-- Dados do cliente e da reserva -->
    <!--<strong class="col-md-12 col-sm-12 title_topic"><?= $rot_cliclicon ?></strong>
    <!-- Dados do cliente e da reserva 
    <div class="col-md-12 col-sm-12 list_rescli_inner">
            <div><?= $cabecalho_conta[0]['nome'] . ' ' . $cabecalho_conta[0]['sobrenome'] ?></span></div>
            <div><?= $cabecalho_conta[0]['residencia_logradouro'] . ' ' . $cabecalho_conta[0]['residencia_numero'] . ' ' . $cabecalho_conta[0]['residencia_complemento'] ?></div>
            <div><?= $cabecalho_conta[0]['residencia_cidade'] . ' ' . $cabecalho_conta[0]['residencia_estado'] . ' ' . $cabecalho_conta[0]['residencia_pais'] . ' ' . $cabecalho_conta[0]['residencia_cep'] ?></div>
        </div>-->
    <?php foreach ($reserva_dados as $reserva) { ?>

        <strong class="col-md-12 col-sm-12 title_topic es-title-topic"><?= $rot_resquacod ?> <?= $reserva['quarto_item'] ?></strong>
        <div class="col-md-12 col-sm-12 list_rescli_inner es-list-inner">
            <div class="col-md-6 col-sm-12">
                <div><?= $rot_resdocnum ?>: <?= $reserva['documento_numero'] ?> - <?= $reserva['quarto_item'] ?></span></div>
                <div><?= Util::convertDataDMY($reserva['inicial_data']) ?> - <?= Util::convertDataDMY($reserva['final_data']) ?></div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div><?= $rot_resquacod ?>: <?= $reserva['quarto_codigo'] ?> - <?= $quarto_tipos[$reserva['quarto_tipo_codigo']] ?></span></div>
                <div><?= $reserva['hospedes'][0]['nome'] . ' ' . $reserva['hospedes'][0]['sobrenome'] ?>[PAX] <?= $reserva['adulto_quantidade'] ?>/<?= $reserva['crianca_quantidade'] ?></div>
            </div>
        </div>
        <!-- Verifica se é checkout tardio -->
        <?php if ($reserva['excedido_tempo'] > 0) { ?>
            <div class="col-md-12 col-sm-12 list_rescli_inner es-list-inner">
                <div class="col-md-4 col-sm-12">
                    <?= $rot_restmpexc ?> <span style="color:red"><?= $reserva['excedido_tempo'] ?> hrs</span>
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_germottit ?></label>
                    <div class="col-md-11 col-sm-12">
                    <select class="form-control" name="motivo_codigo_<?= $reserva['quarto_item'] ?>">
                            <?php foreach ($checkout_tardia_motivos as $item) { ?>
                            <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option>
                            <?php } ?>
                    </select>
                </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_gertxttit ?></label>
                    <div class="col-md-11 col-sm-12">
                    <input type="text" class="form-control" name="motivo_texto_<?= $reserva['quarto_item'] ?>" />
                </div>
            </div>
            </div>

        <?php }
        ?>
    <?php }
    ?>
    &nbsp;
    <br>
    <?php
    echo $this->element('conta/conresexi_elem', ['pesquisa_contas' => $pesquisa_contas, 'geracever_conitecri' => '', 'redirect_page' => '/reservas/resdocpes', 
        'opcao_add_conta' => false, 'back_page' => '', 'has_form' => '1', 'form_id' => 'estchocri', 'has_link' => 0, 'evento' => 2, 'modo_exibicao' => 'dialog', 'tela' => 'estchocri']);
    ?>
    <div class="row col-md-12 col-sm-12 quat_botoes2 text-center">
        <button class="form-control btn-default close_dialog" style="width: auto; display: inline-block;" type="button"><i class="fa fa-times-circle"></i><?= $rot_gerdesbot ?></button>
    </div>
</form>


<?php

use Cake\Routing\Router;

$path = Router::url('/', true);

$datas = explode("|", $reserva_dados[$indice_quarto_item_atual]['datas']);

?>
<form id='estchicri' action="<?= $path ?>/estadias/estchicri" method="post">
    <input type="hidden" name="empresa_codigo_checkin" id="empresa_codigo_checkin" value="<?= $reserva_dados[$indice_quarto_item_atual]['empresa_codigo'] ?>" />
    <input type="hidden" name="documento_numero_checkin" id="documento_numero_checkin" value="<?= $reserva_dados[$indice_quarto_item_atual]['documento_numero'] ?>" />
    <input type="hidden" name="total_hospedes_checkin" id="total_hospedes_checkin" value="<?= $total_hospedes ?>" />
    <input type="hidden" name="quarto_numero_checkin" id="quarto_numero_checkin" value="" />
    <input type="hidden" name="quarto_alocado" id="quarto_alocado" value="<?= $reserva_dados[$indice_quarto_item_atual]['quarto_codigo'] ?? 0 ?>" />
    <input type="hidden" name="datas_checkin" id="datas_checkin" value="<?= $reserva_dados[$indice_quarto_item_atual]['datas'] ?>" />
    <input type="hidden" name="c_codigo_checkin" id="c_codigo_checkin" value="<?= $reserva_dados[$indice_quarto_item_atual]['cliente_codigo'] ?>" />
    <input type="hidden" name="c_codigo" id="c_codigo" value="<?= $reserva_dados[$indice_quarto_item_atual]['cliente_codigo'] ?>" />
    <input type="hidden" name="inicial_data_checkin" id="inicial_data_checkin" value="<?= $reserva_dados[$indice_quarto_item_atual]['inicial_data'] ?>" />
    <input type="hidden" name="final_data_checkin" id="final_data_checkin" value="<?= $reserva_dados[$indice_quarto_item_atual]['final_data'] ?>" />
    <input type="hidden" name="quarto_item_checkin" id="quarto_item_checkin" value="<?= $quarto_item ?>" />
    <input type="hidden" name="opened_acordions" id="opened_acordions" value="" />

    <div id="checkin_revisao_e_alocacao" style="<?php if($tela_exibicao == 'checkin_revisao_e_alocacao') echo 'display:block'; else echo 'display:none' ?>">
        <?php
        //Adiciona o elemento de cabecalho
        echo $this->element('reserva/cabecalho_reserva', ['datas' => $datas, 'reserva_dados' => $reserva_dados[$indice_quarto_item_atual], 'exibe_datas' => 1]);
        //Elemento para revisão de hóspedes
        echo $this->element('reserva/reslishos_elem', ['total_hospedes' => $total_hospedes, 'exibe_campos_adicionais' => true, 'quarto_item' => $quarto_item, $hospede_mesmo_contratante => 0]);
        //Elemento para alocacao multipla
        echo $this->element('estadia/alocacao_multipla', ['datas' => $datas, 'quartos_alocados' => $quartos_alocados, 'reserva_dados' => $reserva_dados[$indice_quarto_item_atual]]);
        ?>
        <div class="row col-md-12 col-sm-12 quat_botoes2 text-center" style="margin-top: 20px; margin-bottom: 20px;">
            <button class="form-control btn-default close_dialog" style="width: auto; min-width: 170px; display: inline-block; vertical-align: middle; margin: 0; float: left;" type="button"><i class="fa fa-times-circle"></i> <?= $rot_gerdesbot ?></button>
            <button class="form-control btn-primary conferir_conta_checkin" style="width: auto; min-width: 170px; display: inline-block; vertical-align: middle; margin: 0; float: right" type="button" ><i class="fa fa-check-circle"></i> <?= $rot_estcnfcon ?></button>
            <button class="form-control btn-primary conferir_conta_checkin" style="width: auto; min-width: 170px; display: inline-block; vertical-align: middle; margin: 0; margin-right: 10px; float: right" type="button" onclick="estfnrpri();" ><i class="fa fa-print"></i> <?= $rot_estimpfnr ?></button>
        </div>
    </div>
    <div id="checkin_revisao_contas" style="<?php if($tela_exibicao == 'checkin_revisao_contas') echo 'display:block'; else echo 'display:none' ?>">
        <!--Inclui o elemento de exibicao das contas da reserva selecionada -->
        <?php
        echo $this->element('conta/conresexi_elem', ['pesquisa_contas' => $pesquisa_contas, 'geracever_conitecri' => '', 'redirect_page' => '/reservas/resdocpes',
            'opcao_add_conta' => false, 'back_page' => '', 'has_form' => '1', 'form_id' => 'estchicri', 'has_link' => 0, 'evento' => 2, 'modo_exibicao' => 'dialog', 'tela' => 'estchicri']);
        ?>

        <div class="row col-md-12 col-sm-12 quat_botoes2">
            <div class="cancel-right col-md-4 col-sm-4" style="padding-left: 0;">
                <button class="form-control btn-default close_dialog es-default-button" style="margin-left: 0;" type="button"><i class="fa fa-times-circle" style="margin-left: 0;"></i>  <?= $rot_gerdesbot ?></button>
            </div>
            <div class="pull-left col-md-8 col-sm-8">
                <button style="float:right; margin-right: 0;" class="form-control btn-primary es-default-button" type="button" onclick="estchrcri()" ><i class="fa fa-arrow-circle-right"></i>  <?= $rot_gerchitit ?> quarto <?= $quarto_item ?></button>
                <button style="float:right; margin-right: 10px;" class="form-control btn-default es-default-button" type="button"
                        onclick="$('#checkin_revisao_contas').css('display', 'none');
                               $('#checkin_revisao_e_alocacao').css('display', 'block');
                               $('#atual_dialog_params').val($('#atual_dialog_params').val().substring(0, $('#atual_dialog_params').val().length - 1) + ',\'tela_exibicao\':\'checkin_revisao_e_alocacao\'}')" ><i class="fa fa-arrow-circle-left"></i>  <?= $rot_gervolbot ?></button>
            </div>
        </div>
    </div>
</form>

<?php
echo $this->element('reserva/fichas_embratur_elem', []);
?>

<?php
date_default_timezone_set($mm);

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<div class="content_inner">

    <div class="formulario">
        <form method="POST" name="resdocpes" id="resdocpes" action="<?= $path ?>/reservas/resdocpes" class="form-horizontal form-atualizar">
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" name="form_atual" value="resdocpes" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="gerdiacon_executada" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="export_pdf" value="0" name="export_pdf" />
            <input type="hidden" id="aria-form-id-csv" value="resdocpes" >
            <input type="hidden" id="aria-form-id-pdf" value="resdocpes" >
            <input type="hidden" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
            <input type="hidden" id="title_dialog_validator" value="" >
            <input type="hidden" id="form_validator_function" name="form_validator_function" value="
                   if ($('#resdocnum').val() == '' && $('#resesttit').val() ==  '' && ($('#c_codigo').val() == '' || $('#c_codigo').val() == '0')) {
                        if (gerdiacon($('#resentdai').val(), $('#resentdaf').val(),<?= $reserva_pesquisa_max ?>, 2, 1) == 1){
                            return true;
                        }
                        else if (gerdiacon($('#ressaidai').val(), $('#ressaidaf').val(),<?= $reserva_pesquisa_max ?>, 2, 1) == 1){
                            return true;
                        }
                        else {
                            dialog = $('#exibe-germencri').dialog({
                            title: $('#title_dialog_validator').val(),
                            dialogClass: 'no_close_dialog',
                            autoOpen: false,
                            height: 200,
                            width: 530,
                            modal: true,
                            buttons: {
                                '<?= $rot_gerconrev ?>': function () {
                                    dialog.dialog('close');
                                    $('#form_force_submit').val('1');
                                    $('#resdocpes .submit-button').click();
                                    $('.click_disabled').removeClass('click_disabled');
                                },
                                '<?= $rot_gerrevdat ?>': function () {
                                    dialog.dialog('close');
                                    $('#gerdiacon_executada').val('0');
                                    $('.click_disabled').removeClass('click_disabled');
                                }
                            }
                        });
                        dialog.dialog('open');
                        return false;
                        }
                   }else{
                        return true;
                   }">
                   <?php
                   echo $this->element('reserva/resdocpes_form_elem', ['pagina' => 'resdocpes']);
                   ?>
                   <?php
                   if (isset($pesquisa_reservas) && sizeof($pesquisa_reservas) > 0) {
                       echo $this->element('reserva/resexiele_elem', ['pesquisa_reservas' => $pesquisa_reservas, 'id_form' => 'resdocpes',
                           'back_page' => "reservas/resdocpes", 'has_form' => '1', 'multiple_select' => true, 'limite_confirmacao' => true, 'limited_actions' => false]);
                   }
                   ?>
        </form>
    </div>

    <div id="dialogs" >
        <div id="motivo-cancelamento" style="display:none"> 
            <form>
                <table style="margin-top:10px">
                    <span style="font-size:15px;"><?= $rot_rescncdoc ?> <strong id="documento_quarto_item_cancelar"></strong></span>
                    <input type="hidden" id="documento-numero-canc" value="" />
                    <input type="hidden" id="quarto-item-canc" value="" />
                    <input type="hidden" id="empresa-codigo-canc" value="" />

                    <tr id="row-motivo-codigo">
                        <td><label for="cancelamento-motivo-codigo" id="cancelamento-motivo-codigo-lbl" ><?= $rot_germottit ?>:</label></td>
                        <td><select id="cancelamento-motivo-codigo" name="cancelamento_motivo_codigo">
                                <?php
                                foreach ($cancelamento_motivos as $item) {
                                    ?>
                                    <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                                <?php } ?> 
                            </select></td></tr>
                    <tr id="row-motivo-texto">
                        <td><label for="cancelamento-motivo-texto" id="cancelamento-motivo-texto-lbl" ><?= $rot_gerobstit ?>:</label></td>
                        <td><textarea cols="31" id="cancelamento-motivo-texto" name="cancelamento_motivo_texto" maxlength="50"></textarea></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>


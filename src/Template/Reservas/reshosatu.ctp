<?php

use Cake\Routing\Router;
use App\Model\Entity\Reserva;

$path = Router::url('/', true);
$reserva = new Reserva();
$datas = explode("|", $reserva_dados[$indice_quarto_item_atual]['datas']);
?>
<form id='reshosatu' action="<?= $path ?>/reservas/reshosatu" method="post">
    <input type="hidden" name="empresa_codigo" value="<?= $empresa_codigo ?>" />
    <input type="hidden" name="documento_numero" value="<?= $documento_numero ?>" />
    <input type="hidden" name="quarto_item" value="<?= $quarto_item ?>" />
    <input type="hidden" name="total_hospedes" value="<?= $total_hospedes ?>" />
    <input type="hidden" name="contratante_codigo" value="<?= $contratante_codigo ?>" />
    <input type="hidden"  name="url_redirect_after" id="url_redirect_after" value="<?= $url_redirect_after ?>" />
    <div id="checkin_revisao_e_alocacao">
        <?php
        //Adiciona o elemento de cabecalho
        echo $this->element('reserva/cabecalho_reserva', ['datas' => $datas, 'reserva_dados' => $reserva_dados[$indice_quarto_item_atual], 'exibe_datas' => 0]);
        //Elemento para revisão de hóspedes
        echo $this->element('reserva/reslishos_elem', ['total_hospedes' => $total_hospedes, 'exibe_campos_adicionais' => true,
            'quarto_item' => $quarto_item, $hospede_mesmo_contratante => 0]);
        ?>
        <div class="row col-md-12 col-sm-12 quat_botoes2">
            <div class="cancel-right col-md-2 col-sm-4">
                <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>">
            </div>
            <div class=" cancel-right  col-md-2 col-sm-4">
                <input style="float:left" id="listar_hospedes" class="form-control btn-default" type="button" value="<?= $rot_gerimptit ?>">
            </div>
            <?php if ($reserva->reshoshab($reserva_dados[$indice_quarto_item_atual]['quarto_status_codigo'])) { ?>
                <div class="pull-right col-md-2 col-sm-4" style="margin: 20px 0px 10px;">
                    <input style="float:right" class="form-control btn-primary submit-button" aria-form-id="reshosatu" id="reshosatu_submit" type="submit" value = "<?= $rot_gersalbot ?>">
                </div>
            <?php } ?>
        </div>
    </div>
</form>
<form action="<?= Router::url('/', true) ?>reservas/reslishos.pdf" id="reslishos" target="_blank" method="post">
    <input type="hidden" name="empresa_codigo" value="<?= $empresa_codigo ?>" />
    <input type="hidden" name="documento_numero" value="<?= $documento_numero ?>" />
</form>
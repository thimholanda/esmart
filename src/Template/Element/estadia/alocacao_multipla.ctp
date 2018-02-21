<?php

use Cake\Routing\Router;
use App\Utility\Util;
use App\Model\Entity\Geral;
use App\Model\Entity\Estadia;
use Cake\Network\Session;

$geral = new Geral();
$estadia = new Estadia();
$session = new Session();
$path = Router::url('/', true);
?>
<input type="hidden" id="datas_aloc"  name="datas_aloc" value="<?= $reserva_dados['datas'] ?>" />
<input type="hidden" id="empresa_codigo_aloc" name="empresa_codigo_aloc" value="<?= $reserva_dados['empresa_codigo'] ?>" />
<input type="hidden" id="documento_numero_aloc" name="documento_numero_aloc" value="<?= $reserva_dados['documento_numero'] ?>" />
<input type="hidden" id="total_hospedes_aloc"  name="total_hospedes_aloc" value="<?= $total_hospedes ?>" />
<input type="hidden" id="quarto_item_aloc"  name="quarto_item_aloc" value="<?= $quarto_item ?>" />
<input type="hidden" id="quarto_tipo_comprado"  name="quarto_tipo_comprado" value="<?= $quarto_tipo_comprado ?>" />
<!-- Dados do tipo de quarto -->
<?php
//Regra para  abrir ou não a alocação multipla
$disable_campos_gerais = false;
if ($quarto_unico_alocado == null)
    $disable_campos_gerais = true;
?>
<button type="button" class="accordion-conta es-accordion-conta" style="margin-top: 15px; pointer-events: none;"><div class="es-room-title"><strong> Alocação em um único quarto
        </strong></div></button>
<div class="es-container-generico" style="padding: 0; margin-bottom: 0;">
<div class="form-group col-md-12 col-sm-12 es-title-topic" style="margin-top:10px; padding: 10px; margin-bottom: 0px; margin-top: 0;">
    <div class="row es-inner-row es-inner-row-gray" style="margin-bottom: 0; padding: 15px;">
        <div class="col-md-4" style="padding: 0;">
            <label class="control-label label-no-top col-md-12 col-sm-12" for="gerquatip_0" <?= $pro_gerquatip ?>><b><?= $rot_gerquatip ?></b></label>
            <div class="col-md-11 col-sm-12" style="padding: 0;">
                <select class="form-control alocacao_multipla_quarto_tipo"  <?php if ($disable_campos_gerais) echo 'disabled' ?>  name="gerquatip" required="required" id="gerquatip_0" <?= $pro_gerquatip ?> data-linha-atual = '0'>
                    <option value=""></option>
                    <?php
                   // if (!$disable_campos_gerais) {
                        foreach ($quarto_tipo_lista as $item) {
                            $selected = "";
                            if ($quarto_tipo_unico_alocado == $item['valor'])
                                $selected = "selected";
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?> >
                                <?= $item["rotulo"] ?>
                            </option>
                        <?php } ?>
                    <?php // } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4 col-sm-4" style="padding: 0;">
            <label class="control-label label-no-top col-md-12 col-sm-12" for="quarto_codigo_alocacao_0"><b><?= $rot_resquacod ?></b></label>
            <div class="col-md-11 col-sm-11" style="padding: 0;">
                <select class="no-select-all-with-search alocacao_multipla_quarto_codigo" <?php if ($disable_campos_gerais) echo 'disabled' ?> name="quarto_codigo_alocacao" id="quarto_codigo_alocacao_0" data-linha-atual = '0'>
                    <option value=""></option>
                    <?php
                    if (!$disable_campos_gerais) {
                        foreach ($gerquadis_geral_retorno['quarto_codigo'] as $item) {
                            $selected = "";
                            if (array_keys($quartos_alocados)[0] == $item['quarto_codigo'])
                                $selected = 'selected';
                            ?>
                            <option value="<?= $item['quarto_codigo'] ?>"  <?= $selected ?>><?= $item['quarto_codigo'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>
</div>
<div  class="form-group col-md-12 col-sm-12" id="motivos_quartos_diferentes" style="display:none">
    <div  class="form-group col-md-6 col-sm-12">
        <label class="control-label label-no-top col-md-12 col-sm-12" for="germqtmot" <?= $pro_germqtmot ?>><?= $rot_germqtmot ?></label>
        <div class="form-group col-md-11 col-sm-12">
            <select class="form-control" <?= $pro_germqtmot ?> name="gertipmot" id="gertipmot"> 
                <?php foreach ($gertipmot_list as $item) { ?>
                    <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                <?php } ?> 
            </select>   
        </div>
    </div>
    <div class="form-group col-md-6 col-sm-12">
        <label class='control-label label-no-top col-md-12 col-sm-12' for="gerobstit"><?= $rot_gerobstit ?> </label> 
        <div  class="form-group col-md-11 col-sm-12">
            <textarea maxlength="50" style="height: 50px !important;" class='form-control col-md-11' type="text" name="gerobstit" id="gerobstit" placeholder="<?= $for_gerobstit ?>"  <?= $pro_gerobstit ?> <?= $val_gerobstit ?>></textarea>
        </div>
    </div>
</div>

<?php
//Regra para  abrir ou não a alocação multipla
$abre_alocacao_multipla = false;
if ($quarto_unico_alocado == null)
    $abre_alocacao_multipla = true;
?>
<div id="alocacao_multipla_datas">
    <div id="exibir_quarto_inner" class="dados_item2">
        <div class="col-md-12 col-sm-12 info_quarto es-accordion-conta" style="margin-bottom:0px; margin-top:10px">
            <div class="col-md-10 col-sm-10 exibi_info"
                 <?php if (!$abre_alocacao_multipla) echo 'onclick="exibi_info_alocacao_multipla(\'#alocacao_multipla_datas\');"'; ?>>
                <a ></a>
                <strong>Alocação Múltipla
                </strong>
            </div>
        </div>
    </div>
    <div class="panel col-md-12 col-sm-12 es-panel-accordion" style="background: #eaeaea !important; padding: 10px !important; margin-bottom: 0;"   <?php
    if (!$abre_alocacao_multipla)
        echo 'style="display:none"';
    else
        echo 'style="display:block"'
        ?>>

        <div class="row es-inner-row es-inner-row-gray" style="margin-bottom: 0; padding: 10px 5px;">

             <?php
             foreach ($datas as $linha => $data) {
                 ?>
            <div class="col-md-4 col-sm-4" style="margin-bottom:10px">
                <?php if (($linha + 1) == 1) { ?>
                    <label class="control-label label-no-top col-md-12 col-sm-12" for="gerdatalo_<?= $linha + 1 ?>" <?= $pro_gerdatalo ?>><b><?= $rot_gerdatalo ?></b></label>
                <?php } ?>
                <div class="col-md-11 col-sm-12">
                    <input type="text" class="form-control" style="background: white; color: #555; font-size: 14px;" name="gerdatalo[]" readonly value="<?= Util::convertDataDMY($data) ?>" required="required" id="gerdatalo_<?= $linha + 1 ?>" <?= $pro_gerdatalo ?> />
                </div>
            </div>
            <?php
            //Verifica se o quarto já foi previamente alocado
            $data_linha_atual = Util::convertDataDMY($data);
            $quarto_codigo_linha_atual = "";
            foreach ($quartos_alocados as $key => $datas_por_quarto) {
                if (array_search($data_linha_atual, $datas_por_quarto) !== false)
                    $quarto_codigo_linha_atual = $key;
            }
            ?>
            <input type="hidden" id="quarto_codigo_alocado_<?= $linha + 1 ?>" value="<?= $quarto_codigo_linha_atual ?>" />
            <div class="col-md-4 col-sm-4" style="margin-bottom:10px">
                <?php if (($linha + 1) == 1) { ?>
                    <label class="control-label label-no-top col-md-12 col-sm-12" for="gerquatip_<?= $linha + 1 ?>" <?= $pro_gerquatip ?>><b><?= $rot_gerquatip ?></b></label>
                <?php } ?>
                <div class="col-md-11 col-sm-12">
                    <select class="form-control alocacao_multipla_quarto_tipo" name="gerquatip[]" <?php if (!$estadia->estquahab($reserva_dados['empresa_codigo'], $reserva_dados['quarto_status_codigo'], $data, $session->read('final_padrao_horario'))) echo 'readonly' ?> required="required" id="gerquatip_<?= $linha + 1 ?>" <?= $pro_gerquatip ?>  data-linha-atual = '<?= $linha + 1 ?>'>
                        <option value=""></option>
                        <?php
                        foreach ($quarto_tipo_lista as $item) {
                            $selected = "";
                            //Se teve algum quarto ja alocado nessa data, verifica seu tipo
                            if ($quarto_codigo_linha_atual != '') {
                                if ($quartos_tipos_alocados[$quarto_codigo_linha_atual] == $item['valor'])
                                    $selected = "selected";
                            } elseif ($reserva_dados['quarto_tipo_codigo'] == $item['valor'])
                                $selected = "selected";
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?> >
                                <?= $item["rotulo"] ?>
                            </option>
                        <?php } ?>
                    </select> 
                </div>
            </div>

            <div class="col-md-4 col-sm-4" style="margin-bottom:10px">
                <?php if (($linha + 1) == 1) { ?>
                    <label class="control-label label-no-top col-md-12 col-sm-12" for="quarto_codigo_alocacao_<?= $linha + 1 ?>"><b><?= $rot_resquacod ?></b></label>
                <?php } ?>
                <div class="col-md-11 col-sm-11">
                    <select class="no-select-all-with-search alocacao_multipla_quarto_codigo" <?php if (!$estadia->estquahab($reserva_dados['empresa_codigo'], $reserva_dados['quarto_status_codigo'], $data, $session->read('final_padrao_horario'))) echo 'disabled' ?> name="quarto_codigo_alocacao[]" id="quarto_codigo_alocacao_<?= $linha + 1 ?>"  data-linha-atual = '<?= $linha + 1 ?>'>
                        <option value=""></option>
                        <?php
                        foreach ($gerquadis_retorno[Util::convertDataDMY($data)]['quarto_codigo'] as $item) {
                            $selected = "";
                            if ($quarto_codigo_linha_atual != '' && $quarto_codigo_linha_atual == $item['quarto_codigo']) {
                                $selected = 'selected';
                            }
                            ?>
                            <option value="<?= $item['quarto_codigo'] ?>" <?= $selected ?>><?= $item['quarto_codigo'] ?></option>
                        <?php } ?> 
                    </select>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>


<?php
use Cake\Routing\Router;
?>
<form class="form-horizontal" method="post" id="coniteexc" action="<?= Router::url('/', true) ?>documentocontas/coniteexc">
    <input type="hidden" name="conta_item" value="<?= $conta_item ?>" />
    <input type="hidden" name="documento_numero" value="<?= $documento_numero ?>" />
    <input type="hidden" name="quarto_item" value="<?= $quarto_item ?>" />
    <input type="hidden" name="servico_taxa_incide" value="<?= $servico_taxa_incide ?>" />
    <input type="hidden" name="url_redirect_after"  id="url_redirect_after" value="<?= $pagina_referencia ?>">

    <div class="form-group col-md-12">
        <label class='control-label col-md-3 col-sm-3' for="germotexc" <?= $pro_germotexc ?>><?= $rot_germotexc ?>: </label> 
        <div class='col-md-8 col-sm-3'>
            <select class="form-control" id="germotexc" name="motivo_exclusao">
                <?php
                foreach ($gertipmot_list_exc as $item) {
                    ?>
                    <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                <?php } ?> 
            </select>                 
        </div>
    </div>

    <div class="form-group col-md-12">
        <label class='control-label col-md-3 col-sm-3' for="gerobstit" <?= $pro_gerobstit ?>><?= $rot_gerobstit ?>: </label> 
        <div class='col-md-8 col-sm-3'> 
            <textarea maxlength="50" style="height: 80px !important;" class='form-control' type="text" name="observacao_exclusao" id="gerobstit_exc" value="<?= $gerobstit ?? '' ?>" placeholder="<?= $for_gerobstit ?>"  <?= $pro_gerobstit ?> <?= $val_gerobstit ?> ></textarea>
        </div>
    </div>
    <div class="row col-md-12">
        <input type="button" class="close_dialog"  value="<?= $rot_gerdesbot ?>" >
        <input style="float:left" class="btn-primary submit-button" type="submit" aria-form-id="coniteexc" value="<?= $rot_gersalbot ?>" >
    </div>
</form>
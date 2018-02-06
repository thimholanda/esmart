<?php

use Cake\Routing\Router;
use App\Model\Entity\Geral;

$geral = new Geral();
?>
<h1 class="titulo_pag">
    Criação e modificação de tarifas
</h1>

<div class="content_inner">
    <form method="POST" name="restarmod" id="restarmod" action="<?= Router::url('/', true) ?>reservas/restarmod" class="form-horizontal" 
          onkeypress="return event.keyCode != 13;">
        <input type="hidden" id="bloqueia_tela" value="1" />
        <div class="form-group">
            <div class="col-md-3 col-sm-6">
                <label class="control-label col-md-12 col-sm-12" for="gerdatini" ><?= $rot_gerdatini ?></label>
                <div class='col-md-11 col-sm-11'> 
                    <input class='form-control datepicker_future data data_place data_incrementa_maior' aria-id-campo-filho='gerdatfin' 
                           maxlength="10" type="text" name="gerdatini" id="gerdatini" placeholder="__/__/____"  />
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="control-label col-md-12 col-sm-12"><?= $rot_gerdatfin ?></label>
                <div class='col-md-12 col-sm-12'> 
                    <input class='form-control datepicker_future data data_place' aria-id-campo-dependente="gerdatini" maxlength="10" type="text" 
                           name="gerdatfin" id="gerdatfin" placeholder="__/__/____" />
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <label class="control-label col-md-12 col-sm-12">Dia da semana</label>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">D</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]" value="Sunday">
                    <label class="form-check-label">S</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]"  value="Monday">
                    <label class="form-check-label">T</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]"  value="Tuesday">
                    <label class="form-check-label">Q</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]"  value="Wednesday">
                    <label class="form-check-label">Q</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]"  value="Thursday">
                    <label class="form-check-label">S</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]"  value="Friday">
                    <label class="form-check-label">S</label>

                    <input class="form-check-input" checked="checked" type="checkbox" name="dds[]"  value="Saturday">
                </div>
            </div>
        </div>
        <div>
            <div class="col-md-3">
                <label class="control-label col-md-12 col-sm-12"><?= $rot_resquatip ?></label>
            </div>
            <div class="col-md-3">
                <label class="control-label col-md-12 col-sm-12">Tipo de tarifa</label>
            </div>
            <div class="col-md-3">
                <label class="control-label col-md-12 col-sm-12">Qtd. adultos</label>
            </div>
            <div class="col-md-3">
                <label class="control-label col-md-12 col-sm-12" for="tarifa">Tarifa (<?= $geral->germoeatr() ?>)</label>
            </div>
        </div>
        <div class='form-group  linha_tarifa'>
            <div class="col-md-3">
                <div class="col-md-11 col-sm-12">
                    <select  name="resquatip[]"  id="resquatip_1"  class="form-control">
                        <?php foreach ($resquatip_list as $item) { ?>
                            <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                        <?php } ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-11 col-sm-12">
                    <select  name="restartip[]"  id="restartip_1"  class="form-control">
                        <?php foreach ($restiptar_list as $item) { ?>
                            <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                        <?php } ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-11 col-sm-12">
                    <select name="resaduqtd[]" id="resaduqtd_1" class="form-control">
                        <?php for ($a = 0; $a <= $resaduqtd; $a++) { ?>
                            <option value="<?= $a ?>"><?= $a ?> </option> 
                        <?php } ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-11 col-sm-12">
                    <input type="text" onfocus="this.select();" class="form-control moeda tarifa_manual_entrada" 
                           name="tarifa[]" /> 
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class='pull-right col-md-2 col-sm-4'>
                    <input class="form-control btn-default mais_tarifas" type="button" value="Mais tarifas">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class='pull-right col-md-2 col-sm-4'>
                    <input class="form-control btn-primary" type="button" value="Salvar" onclick="cadastraTarifas();">
                </div>

                <div class='pull-left col-md-2 col-sm-4'>
                    <input class="form-control btn-default " type="button" value="Cancelar" onclick="gerpagexi('', 1, {})">
                </div>
            </div>
        </div>
    </form>
</div>
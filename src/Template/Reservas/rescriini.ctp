<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
use Cake\Network\Session;
date_default_timezone_set($fuso_horario);

if (isset($erro_max_diarias) && ($erro_max_diarias == '1')) {
    $geral = new Geral();
    print "<script>" . $geral->germencri($this->request->session()->read("empresa_selecionada")['empresa_codigo'],2)['mensagem'] . "</script";
}

$session = new Session();
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<div class="content_inner">
    <div class="formulario">
    <form method="POST" name="f1" id="f1" action="<?= Router::url('/', true) ?>reservas/resquatar" onSubmit="return;" class="form-horizontal">
        <input type="hidden" id="form_force_submit" value="0" />
        <input type="hidden" id="gerdiacon_executada" value="0" />
         <input type="hidden" name="pagina_referencia" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
        <input type="hidden" id="form_validator_function" value="if ( 
               gerdiacon($('#resentdat').val(),$('#ressaidat').val(), $('#diarias_max_js').val(),0, 0) == 1)
               return true;
               else return false;">


        <div class="form-group">
            <div class="col-md-3 col-sm-12">    
                <label class="control-label col-md-12 col-sm-12" for="resentdat" <?= $pro_resentdat ?>><?= $rot_resentdat ?></label>
                    <div class="col-md-11 col-sm-11">
                    <input size="10" maxlength="10" class="form-control datepicker_future data data_incrementa_maior data_place"  aria-id-campo-filho='ressaidat'  type="text" name="resentdat" id="resentdat" value="<?= $m_inicial_data ?>" placeholder="<?= $for_resentdat ?>"  <?= $pro_resentdat ?> <?= $val_resentdat ?> onchange="gerdpaval(this, true)" aria-campo-padrao-valor ="<?= $campo_padrao_valor_resentdat ?>"  aria-padrao-valor="<?= $padrao_valor_resentdat ?? '' ?>"  autocomplete="off"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">    
                <label class="control-label col-md-12 col-sm-12" for="ressaidat" <?= $pro_ressaidat ?>><?= $rot_ressaidat ?></label>
                    <div class="col-md-11 col-sm-11">
                    <input size="10" maxlength="10" class="form-control data datepicker_future data_place"  aria-id-campo-dependente="resentdat"   type="text" name="ressaidat" id="ressaidat" value="<?= $m_final_data ?>" placeholder="<?= $for_ressaidat ?>" <?= $pro_ressaidat ?> <?= $val_ressaidat ?> aria-campo-padrao-valor ="<?= $campo_padrao_valor_ressaidat ?>"  
                           aria-padrao-valor="<?= $padrao_valor_ressaidat ?? '' ?>"  autocomplete="off" />
                </div>
            </div>
                <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="resquaqtd" ><?= $rot_resquaqtd ?></label>
                <div class="col-md-12 col-sm-12">
                    <select class="form-control" name="resquaqtd" id="resquaqtd" <?= $pro_resquaqtd ?> aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquaqtd ?>"  aria-padrao-valor="<?= $padrao_valor_resquaqtd ?? '' ?>"> 
                        <?php for ($i = 1; $i <= 3; $i++) { ?>
                            <option value="<?= $i ?>" <?php if ($i == $resquaqtd ?? 1) echo 'selected' ?>><?= $i ?></option>
                        <?php } ?>
                    </select> 
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="res_info_title">Informações do(s) quarto(s)</div>
            <div id="quarto_itens"  class="col-md-12 col-sm-12">
                <?php
                //Se não estiver voltando da resquatar
                if (!isset($resquatar_volta)) {
                    for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
                        ?>
                        <div id="quarto_item_<?= $quarto_item ?>" class="n_quartos col-md-12 col-sm-12">
                            <?php if ($resquaqtd > 1) { ?>
                                    <label class="control-label col-md-2 col-sm-12" id="quarto_item_label_<?= $quarto_item ?>"><strong><?= $rot_resquacod ?> <span><?= $quarto_item ?></span> :</strong></label>
                            <?php } else { ?>
                                    <label class="control-label col-md-2 col-sm-12" id="quarto_item_label_1" style="display:none"><strong><?= $rot_resquacod ?> <span>1</span> :</strong></label>
                            <?php } ?>

                                <label class="control-label col-md-1 col-sm-3" for="resaduqtd_<?= $quarto_item ?>" <?= $pro_resaduqtd ?>><?= $rot_resaduqtd ?></label>
                                <div class="col-md-1 col-sm-3">
                                <div class="col-md-12 row">
                                    <select class="form-control rescrimax" name="resaduqtd[]" id="resaduqtd_<?= $quarto_item ?>"  <?= $pro_resaduqtd ?> aria-quarto-item="<?= $quarto_item ?>" aria-campo-padrao-valor ="<?php if ($quarto_item == 1) echo $campo_padrao_valor_resaduqtd ?>"  aria-padrao-valor="<?php if ($quarto_item == 1) echo $padrao_valor_resaduqtd ?? '' ?>"> 
                                        <?php for ($i = 1; $i <= $var_max_adulto; $i++) { ?>
                                            <option value="<?= $i ?>" <?php
                                            if ($m_a == $i) {
                                                print " selected";
                                            }
                                            ?>>
                                                        <?= $i ?>
                                            </option>
                                        <?php } ?>
                                    </select> 
                                </div>
                            </div>
                                <label class="control-label col-md-1 col-sm-3" for="rescriqtd" <?= $pro_rescriqtd ?>><?= $rot_rescriqtd ?></label>
                                <div class="col-md-1 col-sm-3">
                                <div class="col-md-12 row">
                                    <select class="form-control rescriida" name="rescriqtd[]" id="rescriqtd" <?= $pro_rescriqtd ?> aria-quarto-item="<?= $quarto_item ?>" aria-campo-padrao-valor ="<?php if ($quarto_item == 1) echo $campo_padrao_valor_rescriqtd; else echo 0 ?>"  aria-padrao-valor="<?php if ($quarto_item == 1) echo $padrao_valor_rescriqtd ?? '' ?>"> 
                                        <?php for ($i = 0; $i <= $var_max_crianca; $i++) { ?>
                                            <option value="<?= $i ?>" <?php
                                            if ($m_c == $i) {
                                                print " selected";
                                            }
                                            ?>> <?= $i ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <span class="lista_c col-sm-12">&nbsp;</span>
                        </div>
                        <?php
                    }
                    //Se estiver voltando da resquatar
                } else {
                    for ($quarto_item = 1; $quarto_item <= $resquaqtd; $quarto_item++) {
                        ?>
                        <div id="quarto_item_<?= $quarto_item ?>" class="n_quartos col-md-12 col-sm-12">
                            <?php if ($resquaqtd > 1) { ?>
                                    <label class="control-label col-md-2 col-sm-12" id="quarto_item_label_<?= $quarto_item ?>"><strong><?= $rot_resquacod ?> <span><?= $quarto_item ?></span> :</strong></label>
                            <?php } else { ?>
                                    <label class="control-label col-md-2 col-sm-12" id="quarto_item_label_1" style="display:none"><strong><?= $rot_resquacod ?> <span>1</span> :</strong></label>
                            <?php } ?>

                                <label class="control-label col-md-1 col-sm-3" for="resaduqtd" <?= $pro_resaduqtd ?>><?= $rot_resaduqtd ?></label>
                                <div class="col-md-1 col-sm-3">
                                <div class="col-md-12 row">
                                    <select class="form-control rescrimax" name="resaduqtd[]" id="resaduqtd_<?= $quarto_item ?>"  <?= $pro_resaduqtd ?> aria-quarto-item="<?= $quarto_item ?>" aria-campo-padrao-valor ="<?= $campo_padrao_valor_resaduqtd ?>"  aria-padrao-valor="<?= $padrao_valor_resaduqtd ?? '' ?>"> 
                                        <?php for ($i = 1; $i <= $var_max_adulto; $i++) { ?>
                                            <option value="<?= $i ?>" <?php
                                            if ($m_a[$quarto_item] == $i) {
                                                echo " selected";
                                            }
                                            ?>><?= $i ?>
                                            </option>
                                        <?php } ?>
                                    </select> 
                                </div>
                            </div>
                                <label class="control-label col-md-1 col-sm-3" for="rescriqtd" <?= $pro_rescriqtd ?>><?= $rot_rescriqtd ?></label>
                                <div class="col-md-1 col-sm-3">
                                <div class="col-md-12 row">
                                    <select class="form-control rescriida" name="rescriqtd[]" id="rescriqtd_<?= $quarto_item ?>"  aria-quarto-item="<?= $quarto_item ?>"  <?= $pro_rescriqtd ?> aria-campo-padrao-valor ="<?= $campo_padrao_valor_rescriqtd ?>"  aria-padrao-valor="<?= $padrao_valor_rescriqtd ?? '' ?>"> 
                                        <?php for ($i = 0; $i <= $var_max_crianca[$quarto_item]; $i++) { ?>
                                            <option value="<?= $i ?>" <?php
                                            if ($m_c[$quarto_item] == $i) {
                                                echo " selected";
                                            }
                                            ?>><?= $i ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <span class="lista_c col-sm-12">
                                <?php if (sizeof($idade_criancas[$quarto_item]) > 0) { ?>
                                        <label class="control-label col-md-1 col-sm-1">Idade</label>
                                    <?php foreach ($idade_criancas[$quarto_item]['idades'] as $idade) { ?>
                                        <div class="col-md-1 col-sm-3">
                                            <select class="form-control" name="crianca_idade[]">
                                                <?php for ($i = 0; $i <= $this->request->session()->read("empresa_selecionada")["pagante_crianca_idade"]; $i++) { ?>
                                                    <option value="<?= $i ?>" <?php if ($i == $idade) echo 'selected'; ?>>
                                                        <?= $i ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </span>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-10 col-sm-8"></div>
                <div class="pull-left col-md-2 col-sm-4">
                    <input class="form-control btn-primary submit-button"  aria-form-id="f1" type="submit" value="<?= $rot_resresbot ?>" >
                </div>
            </div>
        </div>
        <!-- Dados para serem acessados via JS - Inicio -->
        <input type="hidden" id="rot_rescriida_js" value="<?= $rot_rescriida ?>" />
        <input type="hidden" id="nao_pagante_crianca_idade_js" value="<?= $this->request->session()->read("empresa_selecionada")["nao_pagante_crianca_idade"] ?>" />
        <input type="hidden" id="diarias_max_js" value="<?= $this->request->session()->read("empresa_selecionada")["diarias_max"] ?>" />
        <input type="hidden" id="pagante_crianca_idade_js" value="<?= $this->request->session()->read("empresa_selecionada")["pagante_crianca_idade"] ?>" />
        <input type="hidden" id="inicial_padrao_horario" name="inicial_padrao_horario" value="<?= $inicial_padrao_horario ?>" />
        <input type="hidden" id="final_padrao_horario" name="final_padrao_horario" value="<?= $final_padrao_horario ?>" />

        <!-- Dados para serem acessados via JS - Fim -->
    </form>
    </div>
    <?php
    //Se nÃ£o estiver voltando da resquatar
    if (!isset($resquatar_volta)) {
        ?>
        <div id="quarto_item_0" style="display: none">
            <div class="col-md-1 col-sm-2">
                <div class="col-md-12 row">
                    <select class="form-control rescriida" name="rescriqtd[]" id="rescriqtd" <?= $pro_rescriqtd ?>> 
                        <?php for ($i = 0; $i <= $var_max_crianca; $i++) { ?>
                            <option value="<?= $i ?>" <?php
                            if ($m_c == $i) {
                                print " selected";
                            }
                            ?>><?= $i ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <span class="lista_c col-sm-12">&nbsp;</span>
        </div>
    <?php } ?>
</div>

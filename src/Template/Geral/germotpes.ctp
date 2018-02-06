<?php

use Cake\Routing\Router;
use App\Utility\Util;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="germotpes" id="germotpes" action="<?= $path ?>geral/germotpes" class="form-horizontal" >
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="germotpes" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="aria-form-id-csv" value="germotpes" >
            <input type="hidden" id="export_pdf" value="1" name="export_pdf" />
            <input type="hidden" id="pagina_referencia" value="<?= $pagina_referencia ?? '' ?>" />
            <input type="hidden" id="vetor_germotcod" value='<?= json_encode($vetor_germotcod) ?>' />

            <div class="form-group">
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdattip"><?= $rot_gerdattit ?></label>
                    <div class='col-md-11 col-sm-11'>
                        <select name="gerdattip" id="gerdattip"  class="form-control" aria-campo-padrao-valor ="<?= $campo_padrao_valor_gerdattip ?>"  aria-padrao-valor="<?= $padrao_valor_gerdattip ?? '' ?>" >
                            <option value=""></option>
                            <option value="criacao" <?php if (($gerdattip ?? '') == 'criacao') echo 'selected'; ?>><?= $rot_gercritit ?></option>
                            <option value="entrada" <?php if (($gerdattip ?? '') == 'entrada') echo 'selected'; ?>><?= $rot_gerentdat ?></option>
                            <option value="estadia" <?php if (($gerdattip ?? '') == 'estadia') echo 'selected'; ?>><?= $rot_esttittit ?></option>  
                            <option value="saida" <?php if (($gerdattip ?? '') == 'saida') echo 'selected'; ?>><?= $rot_gersaidat ?></option>                           
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdatini" <?= $pro_gerdatini ?>><?= $rot_gerdatini ?></label>
                    <div class='col-md-11 col-sm-11'> 
                        <input maxlength="10" class='form-control datepicker data data_place data_incrementa_igual' aria-id-campo-filho='gerdatfin'  type="text" name="gerdatini" id="gerdatini"
                               value="<?= $gerdatini ?? '' ?>" placeholder="<?= $for_gerdatini ?>" />
                    </div>
                    <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12"><?= $rot_gerdatfin ?></label>
                    <div class='col-md-11 col-sm-11'> 
                        <input maxlength="10" class='form-control datepicker data data_place' aria-id-campo-dependente="gerdatini"  type="text" name="gerdatfin" id="gerdatfin"
                               value="<?= $gerdatfin ?? '' ?>" placeholder="<?= $for_gerdatfin ?>"  data-validation="futuradata3" data-validation-optional="true" />
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="germottip" ><?= $rot_germottip ?></label>
                    <div class="col-md-12 col-sm-12">
                        <select id="germottip" name="germottip[]" class="no-select-all-no-search">
                            <option></option>
                            <?php
                            foreach ($vetor_germottip as $item) {
                                $selected = "";
                                if (isset($germottip)) {
                                    foreach ($germottip as $tipo_motivo_preenchido) {
                                        if ($item['valor'] == $tipo_motivo_preenchido)
                                            $selected = "selected='selected'";
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="germotcod" ><?= $rot_germottit ?></label>
                    <div class="col-md-12 col-sm-12">
                        <select id="germotcod" name="germotcod[]" class="no-select-all-no-search">
                            <option></option>
                            <?php
                            if (isset($germottip)) {
                                foreach ($germottip as $tipo_motivo_preenchido) {
                                    foreach ($vetor_germotcod[$tipo_motivo_preenchido] as $motivo_codigo => $motivo_nome) {
                                        $selected = "";
                                        if (isset($germotcod)) {
                                            foreach ($germotcod as $motivo_preenchido) {
                                                if ($motivo_codigo == $motivo_preenchido)
                                                    $selected = "selected='selected'";
                                            }
                                        }
                                        ?>
                                        <option value="<?= $motivo_codigo ?>" <?= $selected ?>><?= $motivo_nome ?> </option> 
                                        <?php
                                    }
                                }
                            }
                            ?> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="gerdoctip" ><?= $rot_gerdoctip ?></label>
                    <div class="col-md-12 col-sm-12">
                        <select id="gerdoctip" name="gerdoctip[]" class="no-select-all-no-search">
                            <option></option>
                            <?php
                            foreach ($gerdoctip_list as $item) {
                                $selected = "";
                                if (isset($gerdoctip)) {
                                    foreach ($gerdoctip as $documento_tipo_preenchido) {
                                        if ($item['valor'] == $documento_tipo_preenchido)
                                            $selected = "selected='selected'";
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= ucfirst($item["rotulo"]) ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_gerdoctit ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input class="form-control" type="text" id="gerdocnum" value="<?= $gerdocnum ?? '' ?>" name="gerdocnum" />
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
                    <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_gerclitit ?> </label>
                    <div class="col-md-7 col-sm-7">    
                        <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
                    </div>  
                    <div class="col-md-1 col-sm-1">
                        <button class="clicadpes btn-pequisar" type="button"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id=''>
                        </button>
                    </div>  
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="gerusucod" <?= $pro_gerusucod ?>><?= $rot_gerusucod ?> </label>
                    <select id="gerusucod" name="gerusucod" class="no-select-all-no-search">
                        <option></option>
                        <?php
                        foreach ($gerusucod_list as $usuario) {
                            $selected = "";
                            if (isset($gerusucod) && $gerusucod == $usuario['valor'])
                                $selected = "selected='selected'";
                            ?>
                            <option value="<?= $usuario['valor'] ?>" <?= $selected ?>><?= $usuario['rotulo'] ?> </option> 
                        <?php } ?> 
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-10 col-sm-8"></div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-primary submit-button" aria-form-id="germotpes" type="submit" name="germotpes" value="<?= $rot_gerexebot ?>">
                    </div>
                </div>
            </div>
        </form>
        <?php
        if (isset($pesquisa_motivos) && sizeof($pesquisa_motivos) > 0) {
            if (count($pesquisa_motivos) > 0) {
                ?>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <div class="col-md-12 col-sm-12">
                            <table class="table_cliclipes">                       
                                <thead>
                                    <tr class="tabres_cabecalho2">
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'criacao_data', 'aria_form_id' => 'germotpes', 'label' => $rot_gercritit]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'germotpes', 'label' => $rot_gerdoctit]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'conta_item', 'aria_form_id' => 'germotpes', 'label' => $rot_gerconite]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'referencia', 'aria_form_id' => 'germotpes', 'label' => $rot_gerreftit]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'motivo_tipo_codigo', 'aria_form_id' => 'germotpes', 'label' => $rot_germottip]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'motivo_codigo', 'aria_form_id' => 'germotpes', 'label' => $rot_germottit]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'motivo_texto', 'aria_form_id' => 'germotpes', 'label' => $rot_gerobstit]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'criacao_usuario', 'aria_form_id' => 'germotpes', 'label' => $rot_gerusucod]); ?>
                                </thead>
                                <?php
                                $indice = 0;
                                foreach ($pesquisa_motivos as $value) {
                                    $motivo_tipo_nome = '';
                                    $busca_motivo_tipo = array_search($value['motivo_tipo_codigo'], array_column($vetor_germottip, 'valor'));

                                    if ($busca_motivo_tipo !== false)
                                        $motivo_tipo_nome = $vetor_germottip[$busca_motivo_tipo]['rotulo'];
                                    ?>
                                    <?php if ($value['referencia'] == 'item_de_conta') { ?>
                                        <tr class="conitemod" aria-documento-numero='<?= $value['documento_numero'] ?>' aria-quarto-item='<?= $value['quarto_item'] ?>'  aria-item-numero='<?= $value['conta_item'] ?>'
                                            aria-modo-exibicao="tela"  aria-redirect-page='geral/germotpes'>
                                            <?php } else { ?>
                                        <tr class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>
                                        <?php } ?>
                                        <td><?= Util::convertDataDMY($value['criacao_data']) ?></td>                     
                                        <td><?= $value['documento_numero'] . '-' . $value['quarto_item'] ?></td>                     
                                        <td><?= $value['conta_item'] ?></td>                     
                                        <td><?= ucfirst(str_replace("_", " ", $value['referencia'])) ?></td>
                                        <td><?= ucfirst($motivo_tipo_nome); ?></td>
                                        <td><?= ucfirst($vetor_germotcod[$value['motivo_tipo_codigo']][$value['motivo_codigo']] ?? ''); ?></td>       
                                        <td><?= $value['motivo_texto'] ?></td>                     
                                        <td><?= ucfirst($vetor_gerusucod[$value['criacao_usuario']] ?? ''); ?></td>
                                    </tr>
                                    <?php
                                    $indice++;
                                }
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class='col-md-8 col-sm-4 font_12' style="float: right; padding-top: 10px;"> 
    <div class="col-md-10 col-sm-12" style="float: right; " >
        <?php
        echo $paginacao;
        ?>
    </div>
</div>
<?php

use Cake\Routing\Router;
use App\Utility\Util;
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>
<div class="content_inner">
    <div class="formulario">
        <form method="POST" name="conitepes" id="conitepes" action="<?= Router::url('/', true) ?>documentocontas/conitepes" class="form-horizontal">
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="conitepes" />
            <input type="hidden" id="export_pdf" value="0" name="export_pdf" />
            <input type="hidden" id="aria-form-id-pdf" value="conitepes" >
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="gerdiacon_executada" value="0" />
            <input type="hidden" id="aria-form-id-pdf" value="conitepes" >
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="aria-form-id-csv" value="conitepes" >
            <div class='form-group'>
                <div class='col-md-2 col-sm-6'> 
                    <label class="control-label col-md-12 col-sm-12" for="resesttit" <?= $pro_resesttit ?>><?= $rot_condatlai ?> </label>
                    <div class='col-md-11 col-sm-11'>
                        <input maxlength="10" class='form-control data_place datepicker data data_incrementa_igual'  aria-id-campo-filho='condatlan_final'  type="text" name="condatlan_inicio"  id="condatlan_inicio" 
                               aria-campo-padrao-valor ="<?= $campo_padrao_valor_condatlai ?>"  aria-padrao-valor="<?= $padrao_valor_condatlai ?? '' ?>"
                               value="<?= $condatlan_inicio ?? $padrao_valor_condatlai ?? '' ?>" placeholder="<?= $for_condatlai ?>"
                               <?= $pro_condatlai ?> <?= $val_condatlai ?> />
                    </div>
                    <div class="col-md-1 col-sm-1"><span style="padding: 0 4px;"> _ </span></div>
                </div>

                <div class='col-md-2 col-sm-6'>
                    <label class="control-label col-md-12 col-sm-12">&nbsp;</label>
                    <div class="col-md-11 col-sm-11"> 
                        <input maxlength="10" class='form-control data_place datepicker data' aria-id-campo-dependente="condatlan_inicio"  type="text" name="condatlan_final"  id="condatlan_final" 
                               value="<?= $condatlan_final ?? $padrao_valor_condatlaf ?? '' ?>"  data-validation="futuradata3" data-validation-optional="true" 
                               aria-campo-padrao-valor ="<?= $campo_padrao_valor_condatlaf ?>"  aria-padrao-valor="<?= $padrao_valor_condatlaf ?? '' ?>"
                               placeholder="<?= $for_condatlaf ?>"
                               <?= $pro_condatlaf ?> <?= $val_condatlaf ?> />
                    </div>
                </div>
                <div class='col-md-2 col-sm-6'>
                    <label class="control-label col-md-12 col-sm-12" for="progrutip" ><?= $rot_progrutip . ":" ?></label>
                    <div class="col-md-11 col-sm-11">
                        <select class="form-control" name="progrutip" id="progrutip" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_progrutip ?>"  aria-padrao-valor="<?= $padrao_valor_progrutip ?? '' ?>">
                            <option value=""></option>
                            <?php
                            foreach ($procadtip_list as $item) {
                                if ($item['valor'] != 'PAG') {
                                    $selected = "";

                                    if (isset($progrutip)) {
                                        if ($progrutip == $item['valor'])
                                            $selected = 'selected = \"selected\"';
                                    }else if (isset($padrao_valor_progrutip)) {
                                        if ($padrao_valor_progrutip == $item['valor']) {
                                            $selected = 'selected = \"selected\"';
                                        }
                                    }
                                    ?>
                                    <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                                    <?php
                                }
                            }
                            ?> 
                        </select>
                    </div>
                </div>

                <div class='col-md-2 col-sm-6'>
                    <label class='control-label col-md-12 col-sm-12' for="conprocod" <?= $pro_conprocod ?>><?= $rot_conprocod ?> </label> 
                    <div class='col-md-11 col-sm-11'> 
                        <input type="hidden" id="conprocod" name="conprocod" value="<?= $conprocod ?? '' ?>"  />
                        <input type="hidden" id="has_select" value="0"   />
                        <input id="conpronom" name="conpronom" autocomplete="off" type="text"  data-produto-codigo="conprocod" class='produto_autocomplete form-control' <?= $pro_conprocod ?>   value="<?= $conpronom ?? '' ?>"/> 
                    </div>

                </div>

                <div class='col-md-2 col-sm-6'>
                    <label class="control-label col-md-12 col-sm-12" for="convenpon" ><?= $rot_convenpon . ":" ?></label>
                    <div class="col-md-11 col-sm-11">
                        <select class="form-control" name="convenpon" id="convenpon" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_convenpon ?>"  aria-padrao-valor="<?= $padrao_valor_convenpon ?? '' ?>">
                            <option value=""></option>
                            <?php
                            foreach ($provenpon_list as $item) {
                                $selected = "";

                                if (isset($convenpon)) {
                                    if ($convenpon == $item['valor'])
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_convenpon)) {
                                    if ($padrao_valor_convenpon == $item['valor']) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="gerdattip"><?= $rot_gerdattit ?></label>
                    <div class='col-md-11 col-sm-11'>
                        <select name="gerdattip" id="gerdattip"  class="form-control" aria-campo-padrao-valor ="<?= $campo_padrao_valor_gerdattip ?>"  aria-padrao-valor="<?= $padrao_valor_gerdattip ?? '' ?>" >
                            <option value=""></option>
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
                    <label class="control-label col-md-12 col-sm-12" for="resquatip" ><?= $rot_resquatip ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select id="resquatip" name="resquatip[]" class="select-all-no-search" multiple aria-campo-padrao-multiselect="1" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resquatip ?>"  aria-padrao-valor="<?= $padrao_valor_resquatip ?? '' ?>">
                                    <?php
                                    foreach ($resquatip_list as $item) {
                                        $selected = "";
                                        if (isset($resquatip)) {
                                            foreach ($resquatip as $quarto_tipo_preenchido) {
                                                if ($item['valor'] == $quarto_tipo_preenchido)
                                                    $selected = "selected='selected'";
                                            }
                                        }
                                        ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="control-label col-md-12 col-sm-12" for="respagagc" ><?= $rot_respagagc . ":" ?></label>
                    <div class="col-md-11 col-sm-11">
                        <select class="form-control" name="respagagc" id="respagagc" 
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_respagagc ?>"  aria-padrao-valor="<?= $padrao_valor_respagagc ?? '' ?>">
                            <option value=""></option>
                            <?php
                            foreach ($respagagc_list as $item) {
                                $selected = "";

                                if (isset($respagagc)) {
                                    if ($respagagc == $item['valor'])
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_respagagc)) {
                                    if ($padrao_valor_respagagc == $item['valor']) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
                    <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_gerclitit ?>: </label>
                    <div class="col-md-11 col-sm-11">    
                        <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
                    </div>  
                    <div class="col-md-1">
                        <button class=" clicadpes" type="button"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id=''>
                            <span class='ui-icon ui-icon-search'></span>
                        </button>
                    </div>
                </div>               

            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <div class="col-md-10 col-sm-8"></div>
                    <div class="pull-left col-md-2 col-sm-4">
                        <input class="form-control btn-primary submit-button" aria-form-id="conitepes"  type="submit" value="<?= $rot_gerexebot ?>">&nbsp;
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php if (isset($pesquisa_contas) && sizeof($pesquisa_contas) > 0) { ?>
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-12 col-sm-12" style="margin-bottom:12px">
                    <table class="table_cliclipes">
                        <thead>
                            <tr  class="tabres_cabecalho">
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'data', 'aria_form_id' => 'conitepes', 'label' => $rot_condatlai]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'produto_tipo_codigo', 'aria_form_id' => 'conitepes', 'label' => $rot_progrutip]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'produto_nome', 'aria_form_id' => 'conitepes', 'label' => $rot_conprocod]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'produto_qtd', 'aria_form_id' => 'conitepes', 'label' => $rot_conproqtd]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'unitario_preco', 'aria_form_id' => 'conitepes', 'label' => $rot_conpreuni]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_valor', 'aria_form_id' => 'conitepes', 'label' => $rot_gertottit]); ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($pesquisa_contas as $value) {
                                ?>
                                <tr>                    
                                    <td><?= Util::convertDataDMY($value['data']) ?></td>
                                    <td><?= Util::encontra_rotulo_gercamdom($procadtip_list, $value['produto_tipo_codigo']); ?></td>
                                    <td><?= $value['produto_nome'] ?></td>
                                    <td><?= intval($value['produto_qtd']) ?></td>
                                    <td><?= $geral->germoeatr() . ' ' . $geral->gersepatr($value['unitario_preco']) ?></td>
                                    <td><?= $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_valor']) ?></td>

                                </tr>
                                <?php
                            }
                            ?>

                        </tbody>
                        <tfoot>
                            <tr  style="background: #dedddd;">
                                <td colspan="3"><strong><?= $rot_gertottit ?></strong></td>
                                <td><strong><?= intval($somas['soma_produto_qtd']) ?></strong></td>
                                <td><strong>&nbsp;</strong></td>
                                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_total_valor']) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 mt1">

            <div class="col-md-4 col-sm-12" style="float: right; " >
                <?php
                echo $paginacao;
                ?>


            </div>
        </div>
        <?php
    }
    ?>
</div>



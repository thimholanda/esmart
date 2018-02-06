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
        <form method="POST" name="conihopes" id="conihopes" action="<?= Router::url('/', true) ?>documentocontas/conihopes" class="form-horizontal">
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="conihopes" />
            <input type="hidden" id="export_pdf" value="0" name="export_pdf" />
            <input type="hidden" id="aria-form-id-pdf" value="conihopes" >
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="gerdiacon_executada" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <input type="hidden" id="aria-form-id-csv" value="conihopes" >

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
                        <input class="form-control btn-primary submit-button" aria-form-id="conihopes"  type="submit" value="<?= $rot_gerexebot ?>">&nbsp;
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (isset($pesquisa_contas) && sizeof($pesquisa_contas) > 0) { ?>
    <div class="form-group">
        <div class="col-md-12 col-sm-12">
            <div class="col-md-12 col-sm-12" style="margin-bottom:12px">
                <table class="table_cliclipes">
                    <thead>
                        <tr  class="tabres_cabecalho">
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_codigo', 'aria_form_id' => 'conihopes', 'label' => $rot_resquacod]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'hospede', 'aria_form_id' => 'conihopes', 'label' => $rot_resdochos]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'inicial_data', 'aria_form_id' => 'conihopes', 'label' => $rot_gerchitit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'final_data', 'aria_form_id' => 'conihopes', 'label' => $rot_gerchotit]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'produto_nome', 'aria_form_id' => 'conihopes', 'label' => $rot_conprocod]); ?>
                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'produto_qtd', 'aria_form_id' => 'conihopes', 'label' => $rot_conproqtd]); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($pesquisa_contas as $value) {
                            ?>
                            <tr>             
                                <td><?= $value['quarto_codigo'] ?></td>
                                <td><?= $value['hospede'] ?></td>
                                <td><?= Util::convertDataDMY($value['inicial_data']) ?></td>
                                <td><?= Util::convertDataDMY($value['final_data']) ?></td>                    
                                <td><?= $value['produto_nome'] ?></td>
                                <td><?= intval($value['produto_qtd']) ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr  style="background: #dedddd;">
                            <td colspan="5"><strong><?= $rot_gertottit ?></strong></td>
                            <td><strong><?= intval($somas['soma_produto_qtd']) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php echo $paginacao; ?>
    <?php
}
?>


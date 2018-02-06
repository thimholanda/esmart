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
        <div style="margin-bottom: 60px">
            <form method="POST" name="gercompes" id="gercompes" action="<?= $path ?>geral/gercompes" class="form-horizontal">
                <input type="hidden" id="pesquisar_comunicacoes" name="pesquisar_comunicacoes" value="no">
                <input type="hidden" id="pagina" value="1" name="pagina" />
                <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
                <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
                <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
                <input type="hidden" id="export_csv" value="0" name="export_csv" />
                <input type="hidden" name="ajax_form" value="1" />
                <input type="hidden" id="form_atual" value="gercompes" />
                <input type="hidden" id="form_force_submit" value="0" />
                <input type="hidden" id="gerdiacon_executada" value="0" />
                <input type="hidden" id="aria-form-id-csv" value="gercompes" >
                <input type="hidden" id="title_dialog_validator" value="" >
                <input type="hidden" id="form_validator_function" name="form_validator_function" value="
                       if ($('#gercomdes').val() == '' && $('#comdocnum').val() == '' ) {
                       if (gerdiacon($('#comdapini').val(), $('#comdapfin').val(),<?= $comunicacao_pesquisa_max ?>, 2, 1) == 1)
                       return true;
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
                       $('.click_disabled').removeClass('click_disabled');
                       $('#form_force_submit').val('1');
                       $('#gercompes .submit-button').click();
                       },
                       '<?= $rot_gerrevdat ?>': function () {
                       dialog.dialog('close');
                       $('.click_disabled').removeClass('click_disabled');
                       $('#gerdiacon_executada').val('0');
                       }
                       }
                       });
                       dialog.dialog('open');
                       return false;
                       }
                       }else
                       return true;
                       ">

                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-3"><?= $rot_gerdoctit ?>:</label><br/>
                </div>
                <div class="form-group">

                    <label class="control-label col-md-1 col-sm-3" for="comdoctip" ><?= $rot_comdoctip . ":" ?></label>
                    <div class="col-md-2 col-sm-3">
                        <select class="form-control" name="comdoctip" id="comdoctip"
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_comdoctip ?>"  aria-padrao-valor="<?= $padrao_valor_comdoctip ?? '' ?>"> 
                            <option value=""></option>
                            <?php
                            foreach ($gerdoctip_list as $item) {
                                $selected = "";

                                if (isset($comdoctip)) {
                                    if ($comdoctip == $item['valor'])
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_comdoctip)) {
                                    if ($padrao_valor_comdoctip == $item['valor']) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                                <?php
                            }
                            ?> 
                        </select>
                    </div>
                    <label class="control-label col-md-1 col-sm-3" for="comdocnum"><?= $rot_gernumtit ?>:</label>
                    <div class="col-md-2 col-sm-3"> 
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="comdocnum" id="comdocnum" value="<?= $comdocnum ?? '' ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-3"><?= $rot_gercomtit ?>:</label><br/>
                </div>        
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-3" for="gertiptit" ><?= $rot_gertiptit . ":" ?></label>
                    <div class="col-md-2 col-sm-3">
                        <select class="form-control" name="gercomtip" id="gertiptit"
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_gertiptit ?>"  aria-padrao-valor="<?= $padrao_valor_gertiptit ?? '' ?>"> 
                            <option value=""></option>
                            <?php
                            foreach ($gercomtip_list as $item) {
                                if ($item["valor"] != "rs") {

                                    $selected = "";

                                    if (isset($gercomtip)) {
                                        if ($gercomtip == $item['valor'])
                                            $selected = 'selected = \"selected\"';
                                    }else if (isset($padrao_valor_gertiptit)) {
                                        if ($padrao_valor_gertiptit == $item['valor']) {
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
                    <label class="control-label col-md-1 col-sm-3" for="gercomdes"><?= $rot_gercomdes ?>:</label>
                    <div class="col-md-3 col-sm-3"> 
                        <input class="form-control" type="text" name="gercomdes" id="gercomdes" value="<?= $gercomdes ?? '' ?>">
                    </div>

                    <label class="control-label col-md-1 col-sm-3"  for="resdocsta"><?= $rot_resdocsta . ":" ?></label>
                    <div class="col-md-2 col-sm-3">

                        <select class="form-control" name="gercomsta" id="resdocsta"
                                aria-campo-padrao-valor ="<?= $campo_padrao_valor_resdocsta ?>"  aria-padrao-valor="<?= $padrao_valor_resdocsta ?? '' ?>"> 
                            <option value=""></option>
                            <?php
                            foreach ($gercomsta_list as $item) {


                                $selected = "";

                                if (isset($gercomsta)) {
                                    if ($gercomsta == $item['valor'])
                                        $selected = 'selected = \"selected\"';
                                }else if (isset($padrao_valor_resdocsta)) {
                                    if ($padrao_valor_resdocsta == $item['valor']) {
                                        $selected = 'selected = \"selected\"';
                                    }
                                }
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                                <?php
                            }
                            ?> 
                        </select>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3" for="comdapini"><?= $rot_comdaptit ?>:</label>
                    <div class="col-md-2 col-sm-3" style="padding-left:0; padding-right: 0;"> 
                        <input maxlength="10" class="form-control datepicker data" type="text" name="comdapini" id="comdapini" 
                               aria-campo-padrao-valor ="<?= $campo_padrao_valor_comdapini ?>"  aria-padrao-valor="<?= $padrao_valor_comdapini ?? '' ?>"
                               value="<?= $comdapini ?? $padrao_valor_comdapini ?? '' ?>" placeholder="00/00/0000"  
                               >
                    </div>
                    <div class="col-md-1 col-sm-3" style="padding:0; margin-left: -70px; margin-top: 4px;margin-right: -40px;"> 
                        <span>-</span>
                    </div>
                    <div class="col-md-2 col-sm-3" style="padding-left:0; margin-left: -58px;"> 
                        <input maxlength="10" class="form-control datepicker data" type="text" name="comdapfin" id="comdapfin" value="<?= $comdapfin ?? $padrao_valor_comdapfin ?? '' ?>"
                               aria-campo-padrao-valor ="<?= $campo_padrao_valor_comdapfin ?>"  aria-padrao-valor="<?= $padrao_valor_comdapfin ?? '' ?>"
                               placeholder="00/00/0000" >
                    </div>            
                </div>
                <div class="col-md-7">
                    <div class='col-md-2 col-sm-3'>
                        <input class="form-control btn-primary submit-button" aria-form-id="gercompes" type="submit" name="compesbtn" value="<?= $rot_gerexebot ?>"
                               >
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (isset($pesquisa_comunicacoes) && sizeof($pesquisa_comunicacoes) > 0) {
        if (count($pesquisa_comunicacoes) > 0) {
            ?>
            <form id="comconmod" name="comconmod" action="<?= $path ?>/ajax/ajaxgercomcen" method="post">
                <input type="hidden" name="action_form" value="1" />
                <input type="hidden" id="url_redirect_after" value="geral/gercompes" />
                <input type="hidden" name="gerempcod" value="<?= $gerempcod ?>" />
                <div>
                    <table class="table_cliclipes">
                        <thead>
                            <!--<tr><td colspan='7'><?= $rot_comdatpla ?></td></tr>-->
                            <tr  class="tabres_cabecalho">
                                <th>&nbsp;</th>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'envio_planejada_data', 'aria_form_id' => 'gercompes', 'label' => $rot_comdaptit]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_tipo_nome', 'aria_form_id' => 'gercompes', 'label' => $rot_gerdoctip]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'acesso_objeto', 'aria_form_id' => 'gercompes', 'label' => $rot_gerdoctit]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'comunicacao_tipo_nome', 'aria_form_id' => 'gercompes', 'label' => $rot_comtipcom]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'destinatario_contato', 'aria_form_id' => 'gercompes', 'label' => $rot_comcondes]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'envio_real_data', 'aria_form_id' => 'gercompes', 'label' => $rot_comdatrea]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'tentativa_quantidade', 'aria_form_id' => 'gercompes', 'label' => $rot_comtenenv]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'comunicacao_status_codigo', 'aria_form_id' => 'gercompes', 'label' => $rot_resdocsta]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'erro_mensagem', 'aria_form_id' => 'gercompes', 'label' => $rot_commsgerr]); ?>
                            </tr>
                        </thead>
                        <?php
                        $indice = 0;
                        foreach ($pesquisa_comunicacoes as $value) {
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="check_doc" name="comunicacao_numeros_selecionados[]" value="<?= $value['comunicacao_numero'] ?>" />
                                </td>
                                <td><?= Util::convertDataDMY($value['envio_planejada_data']) ?></td>                     
                                <td><?= $value['documento_tipo_nome'] ?></td>                     
                                <td><?= $value['acesso_objeto'] ?></td>                     
                                <td><?= $value['comunicacao_tipo_nome'] ?></td>
                                <td><?= $value['destinatario_contato'] ?></td>
                                <td><?= Util::convertDataDMY($value['envio_real_data']) ?></td>
                                <td><?= $value['tentativa_quantidade'] ?></td>
                                <td><?php
                                    if ($value['comunicacao_status_codigo'] == 0)
                                        echo $rot_comnevtit;
                                    elseif ($value['comunicacao_status_codigo'] == 1)
                                        echo $rot_comenvtit;
                                    elseif ($value['comunicacao_status_codigo'] == 2)
                                        echo $rot_comenstit;
                                    elseif ($value['comunicacao_status_codigo'] == 3)
                                        echo $rot_comeevtit;
                                    ?></td>
                                <td><?= $value['erro_mensagem'] ?></td>

                            </tr>
                            <?php
                            $indice++;
                        }
                        ?>

                    </table>
                </div>
                <div class="row" style="margin-top:15px">
                    <div class='col-md-2 col-sm-3'>
                        <input class="form-control btn-primary submit-button" aria-form-id="comconmod" type="submit" name="serpesbtn" value="<?= $rot_gerenvbot ?>" 
                               >
                    </div>
                </div>
            </form>
            <?php
            echo $paginacao;
        }
    }
    ?>
</div>

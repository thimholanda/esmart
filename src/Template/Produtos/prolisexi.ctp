<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<div class="content_inner">
    <div class="formulario">
        <form id='prolismod' action="<?= Router::url('/', true) ?>produtos/prolismod" method="POST">
            <input type="hidden" id="form_atual" value="prolismod" />
            <div class='form-group row'>
                <label class='control-label col-md-1 col-sm-3' for="conprocod" <?= $pro_conprocod ?>><?= $rot_conprocod ?> </label> 
                <div class='col-md-3 col-sm-3'> 
                    <input type="hidden" id="conprocod" name="conprocod" value="<?= $conprocod ?? '' ?>"  required='required'  />
                    <input type="hidden" id="convarnom" name="convarnom" value="<?= $convarnom ?? '' ?>" />
                    <input type="hidden" id="vendavel" value="1"   />
                    <input id="conpronom" name="conpronom" value="<?= $conpronom ?? '' ?>" autocomplete="off" type="text" class='produto_autocomplete form-control input_autocomplete' required='required'
                           <?= $pro_conprocod ?> data-produto-codigo="conprocod" /> 

                </div>
                <div class='col-md-2 col-sm-3'>
                    <?= $rot_conproqtd ?>: <b>1</b>
                </div>

                <div class='col-md-3 col-sm-3'>
                    <?= $rot_prounimed ?>: <span id="produto_pai_variavel_fator_nome"><b><?= $convarnom ?? '' ?></b></span>
                </div>

            </div>
            <div  class='form-group' id='linha_lista_tecnica_itens' style="margin-top:40px">
                <?php
                $indice_lista_tecnica_item = 1;

                foreach ($lista_tecnica_itens as $lista_tecnica_item) {
                    ?>

                    <!--Mantem os excluido para preservar o controle do adicionar mais-->
                    <?php if ($lista_tecnica_item['excluido'] != '0') { ?>
                        <input type="hidden" name="prolisexc[]"  id="prolisexc_<?= $indice_lista_tecnica_item ?>" value="1" />
                        <input type="hidden" name="proprofil[]" class="item_lista_tecnica"  id="conprocod_<?= $indice_lista_tecnica_item ?>" value="<?= $lista_tecnica_item['produto_codigo'] ?>" />
                        <input type="hidden" name="qtd[]"  id="qtd_<?= $indice_lista_tecnica_item ?>" value="<?= $lista_tecnica_item['qtd'] ?>"  />
                        <input type="hidden" name="prounimed[]"  id="prounimed_<?= $indice_lista_tecnica_item ?>" value="<?= $lista_tecnica_item['fator_codigo'] ?>"  />
                    <?php } else { ?>
                        <div class='form-group linha_lista_tecnica_item row'>
                            <div class="col-md-3">
                                <?php if ($indice_lista_tecnica_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" style="margin-left: 30px;" for="proprofil_<?= $indice_lista_tecnica_item ?>">Item</label>
                                <?php } ?>
                                <div class="col-md-1">
                                    <input type="hidden" name="prolisexc[]"  id="prolisexc_<?= $indice_lista_tecnica_item ?>" value="0" />
                                    <input type="checkbox" name="prolischk[]" data-indice-lista-item="<?= $indice_lista_tecnica_item ?>" id="prolischk_<?= $indice_lista_tecnica_item ?>" />
                                </div>
                                <div class='col-md-11 col-sm-12'>
                                    <input type="hidden" data-validation='required' id="conprocod_<?= $indice_lista_tecnica_item ?>" name="proprofil[]" value="<?= $lista_tecnica_item['produto_codigo'] ?>"  />
                                    <input autocomplete="off"  data-validation='required'  type="text" class='produto_autocomplete form-control input_autocomplete item_lista_tecnica' maxlength="7"
                                           id="proprofil_<?= $indice_lista_tecnica_item ?>" value="<?= $lista_tecnica_item['filho_produto_nome'] ?>" data-produto-codigo="conprocod_<?= $indice_lista_tecnica_item ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <?php if ($indice_lista_tecnica_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" for="qtd_<?= $indice_lista_tecnica_item ?>"><?= $rot_conproqtd ?></label>
                                <?php } ?>
                                <div class='col-md-11 col-sm-12'> 
                                    <input class='form-control' data-validation='required' type="text" name="qtd[]"  id="qtd_<?= $indice_lista_tecnica_item ?>" value="<?= $lista_tecnica_item['qtd'] ?>"  />
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <?php if ($indice_lista_tecnica_item == 1) { ?>
                                    <label class="control-label col-md-12 col-sm-12" for="prounimed_<?= $indice_lista_tecnica_item ?>"><?= $rot_prounimed ?></label>
                                <?php } ?>
                                <div class='col-md-11 col-sm-12'>
                                    <select readonly="readonly" class="form-control" id="prounimed_<?= $indice_lista_tecnica_item ?>" name="prounimed[]">
                                        <option></option>
                                        <?php
                                        foreach ($unidades_medida as $item) {
                                            $selected = "";
                                            if ($item['valor'] == $lista_tecnica_item['fator_codigo'])
                                                $selected = "selected='selected'";
                                            ?>
                                            <option value="<?= $item["valor"] ?>" <?= $selected ?>> <?= $item["rotulo"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    $indice_lista_tecnica_item++;
                }
                ?>
            </div>
            <?php if (isset($lista_tecnica_itens) && sizeof($lista_tecnica_itens) > 0) { ?>
                <div class="form-group" style="margin-top: -7px; margin-left: 26px;">
                    <div class="col-md-12 col-sm-12">
                        <div class='pull-left col-md-2 col-sm-4'>
                            <b><a href="#" class="novo_item_lista_tecnica"><?= $rot_resaddpfi ?></a></b>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <div class='pull-right col-md-2 col-sm-4'> 
                        <!--Botao hidden que é clicado via js quando clica no produto autocomplete -->
                        <input class="form-control btn-primary submit-button" aria-form-id="prolismod" type="submit" id="prolisexi_exibir" style="display:none" >
                        <input class="form-control btn-primary submit-button" aria-form-id="prolismod" type="submit" id="prolismod_salvar" value="<?= $rot_gersalbot ?>" >
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>">
                    </div>
                    <div class='pull-left col-md-2 col-sm-4'>
                        <input class="form-control btn-danger submit-button seleciona_lista_item_excluidos"  aria-form-id="prolismod" type="submit" value="<?= $rot_gerqtpexc ?> item">
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>
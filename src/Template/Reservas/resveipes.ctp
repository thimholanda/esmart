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
        <form method="POST" name="reservas" id="resveipes" action="<?= $path ?>reservas/resveipes" class="form-horizontal" >
            <input type="hidden" id="pagina" value="1" name="pagina" />
            <input type="hidden" id="form_atual" value="resveipes" />
            <input type="hidden" id="form_force_submit" value="0" />
            <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
            <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
            <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />

            <div class="form-group">
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_resveipla ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input class="form-control" type="text" id="resveipla" maxlength="7" value="<?= $resveipla ?? '' ?>" name="resveipla" />
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_resveimar ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input class="form-control" type="text" id="resveimar" value="<?= $resveimar ?? '' ?>" name="resveimar" />
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_resveicor ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select class="form-control" id="resveicor" name="resveicor">
                            <option></option>
                            <?php
                            foreach ($cores_lista as $item) {
                                $selected = "";
                                if (isset($resveicor) && ($item['valor'] == $resveicor))
                                    $selected = "selected='selected'";
                                ?>
                                <option value="<?= $item["valor"] ?>" <?= $selected ?>> <?= $item["rotulo"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3" style="margin-top: 37px;">
                    <label  for="apenas_hospedados_agora">Apenas hospedados agora</label>
                    <input type="checkbox" style="margin-right:3px" class="pull-left" name="apenas_hospedados_agora" id="apenas_hospedados_agora"
                           <?php if (isset($apenas_hospedados_agora) && $apenas_hospedados_agora = '3') echo 'checked' ?> /> 
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="resquacod" ><?= $rot_resquacod ?></label>
                    <div class="col-md-11 col-sm-12">
                        <select class="no-select-all-with-search" multiple name="resquacod[]" id="resquacod">
                            <?php
                            foreach ($quarto_por_tipo as $quarto => $quarto_tipo_curto_nome) {
                                $selected = "";

                                if (isset($resquacod)) {
                                    foreach ($resquacod as $quarto_preenchido) {
                                        if ($quarto == $quarto_preenchido)
                                            $selected = "selected='selected'";
                                    }
                                }
                                ?>
                                <option data-subtext="<?= $quarto_tipo_curto_nome ?>" value="<?= $quarto ?>" <?= $selected ?>><?= $quarto ?></option>
                            <?php } ?> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
                    <label class="control-label col-md-12 col-sm-12" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_gerclitit ?> </label>
                    <div class="col-md-10 col-sm-11">    
                        <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
                    </div>  
                    <div class="col-md-1 col-sm-1">
                        <button class="<?= $ace_clicadpes ?> clicadpes btn-pequisar" type="button"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id=''>
                        </button>
                    </div>  
                </div>

                <div class="col-md-2 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" ><?= $rot_restittit ?></label>
                    <div class="col-md-11 col-sm-12">
                        <input class="form-control" type="text" id="resdocnum" value="<?= $resdocnum ?? '' ?>" name="resdocnum" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-7 form-group pull-left">

                </div>
                <div class="col-md-5 col-sm-12">
                    <div class="col-md-7 col-sm-8"></div>
                    <div class='pull-left col-md-5 col-sm-4'>
                        <input class="form-control btn-primary submit-button" aria-form-id="resveipes" type="submit" name="resveipes" value="<?= $rot_gerexebot ?>">
                    </div>
                </div>
            </div>
        </form>

        <?php
        if (isset($pesquisa_veiculos) && sizeof($pesquisa_veiculos) > 0) {
            if (count($pesquisa_veiculos) > 0) {
                ?>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <div class="col-md-12 col-sm-12">
                            <table class="table_cliclipes">                       
                                <thead>
                                    <tr class="tabres_cabecalho2">
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'placa', 'aria_form_id' => 'resveipes', 'label' => $rot_resveipla]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'marca_modelo', 'aria_form_id' => 'resveipes', 'label' => $rot_resveimar]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cor', 'aria_form_id' => 'resveipes', 'label' => $rot_resveicor]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_codigo', 'aria_form_id' => 'resveipes', 'label' => $rot_resquacod]); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'nome', 'aria_form_id' => 'resveipes', 'label' => 'Hóspede']); ?>
                                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'resveipes', 'label' => 'Reserva']); ?>
                                </thead>
                                <?php
                                foreach ($pesquisa_veiculos['results'] as $value) {
                                    ?>
                                    <tr>                                        
                                        <td class="resveiexi" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-quarto-item='<?= $value["quarto_item"] ?>'><?= $value['placa'] ?></td>                     
                                        <td class="resveiexi" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-quarto-item='<?= $value["quarto_item"] ?>'><?= $value['marca_modelo'] ?></td>                     
                                        <td class="resveiexi" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-quarto-item='<?= $value["quarto_item"] ?>'><?= Util::encontra_rotulo_gercamdom($cores_lista, $value['cor']); ?></td>                     
                                        <td class="resveiexi" aria-documento-numero='<?= $value["documento_numero"] ?>' aria-quarto-item='<?= $value["quarto_item"] ?>'><?= $value['quarto_codigo'] ?></td>
                                        <td><a href="#a" class="clicadmod link_ativo" aria-cliente-codigo = '<?= $value["cliente_codigo"] ?>'><?= $value['nome'] . ' ' . $value['sobrenome'] ?></a></td>
                                        <td><a href="#a"  class="resdocmod link_ativo" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>' ><?= $value['documento_numero'] ?></a></td>                     
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group " >
                <div class="col-md-12 col-sm-12 top1">
                    <div class='col-md-7 col-sm-4 font_12' style="float: right; padding: 0px;"> 
                        <div class="col-md-10 col-sm-12" style="float: right; " >
                            <?php echo $paginacao; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
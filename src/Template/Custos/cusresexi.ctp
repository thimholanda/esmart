<?php

use Cake\Routing\Router;
use App\Model\Entity\Geral;
use App\Utility\Util;

$geral = new Geral();
?>
<div class="content_inner">
    <div class="formulario">
        <div class="cabecalho_conta" style="padding: 8px;">
            <div class="row">
                <label><b><?= $rot_condadtit ?> <?= $documento_numero_selecionado ?></b></label>
            </div>

            <div class="row">
                <label><b><?= $rot_resquacod ?> <?= $quarto_item_selecionado ?> : 
                        <?php
                        if ($cabecalho_conta[$dados_resdocpes_quarto_item]['quarto_codigo'] != null && $cabecalho_conta[$dados_resdocpes_quarto_item]['quarto_codigo'] != "")
                            echo $cabecalho_conta[$dados_resdocpes_quarto_item]['quarto_codigo'] . ' - '
                            ?>
                        <?= $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['nome'] . ' ' . $cabecalho_conta[$dados_resdocpes_quarto_item]['hospedes'][0]['sobrenome'] ?>
                    </b></label>
            </div>
        </div>
        <div class="form-group" style="margin-top: 15px">
            <table class="table_cliclipes">                       
                <thead>
                    <tr class="tabres_cabecalho2">
                        <th>Item da conta</th>
                        <th>Data</th>                        
                        <th><?= $rot_conprocod ?></th>
                        <th><?= $rot_conproqtd ?></th>
                        <th><?= $rot_prounimed ?></th>
                        <th><?= $rot_cusunitit ?></th>
                        <th><?= $rot_custottit ?></th>
                </thead>
                <tbody>
                    <?php
                    $total_itens_custo = 0;
                    $item = 1;
                    foreach ($custo_itens as $custo_item) {
                        ?>
                        <tr>           
                            <td  class="conitemod link_ativo" aria-modo-exibicao="dialog" aria-quarto-item='<?= $quarto_item_selecionado ?>'
                                 aria-redirect-page='/custos/cusresexi/'
                                 aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-item-numero='<?= $custo_item['conta_item'] ?>'>
                                <?= $custo_item['conta_item'] ?></td>   
                            <td class="<?php if ($custo_item['custo_tipo_codigo'] == 2) { echo 'cusfolexi'; } ?>" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item_selecionado ?>' 
                                    aria-conta-item='<?= $custo_item["conta_item"] ?>' aria-documento-tipo-codigo='<?= $documento_tipo_selecionado ?>' aria-pai-produto-qtd='<?= intval($custo_item['produto_qtd']) ?>'
                                    aria-pai-variavel-fator-codigo='<?= $custo_item['fator_codigo'] ?>'
                                    aria-pai-produto-codigo='<?= $custo_item["produto_codigo"] ?>'  aria-pai-produto-nome='<?= $custo_item["nome"] ?>' 
                                    aria-pai-unidade-medida='<?= $custo_item["variavel_fator_nome"] ?>' ><?= Util::convertDataDMY($custo_item['data']) ?></td>         

                            <td class="<?php if ($custo_item['custo_tipo_codigo'] == 2) { echo 'cusfolexi'; } ?>" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item_selecionado ?>' 
                                    aria-conta-item='<?= $custo_item["conta_item"] ?>' aria-documento-tipo-codigo='<?= $documento_tipo_selecionado ?>' aria-pai-produto-qtd='<?= intval($custo_item['produto_qtd']) ?>'
                                    aria-pai-variavel-fator-codigo='<?= $custo_item['fator_codigo'] ?>'
                                    aria-pai-produto-codigo='<?= $custo_item["produto_codigo"] ?>'  aria-pai-produto-nome='<?= $custo_item["nome"] ?>' 
                                    aria-pai-unidade-medida='<?= $custo_item["variavel_fator_nome"] ?>' ><?= $custo_item['nome'] ?></td>                     
                            <td class="<?php if ($custo_item['custo_tipo_codigo'] == 2) { echo 'cusfolexi'; } ?>" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item_selecionado ?>' 
                                    aria-conta-item='<?= $custo_item["conta_item"] ?>' aria-documento-tipo-codigo='<?= $documento_tipo_selecionado ?>' aria-pai-produto-qtd='<?= intval($custo_item['produto_qtd']) ?>'
                                    aria-pai-variavel-fator-codigo='<?= $custo_item['fator_codigo'] ?>'
                                    aria-pai-produto-codigo='<?= $custo_item["produto_codigo"] ?>'  aria-pai-produto-nome='<?= $custo_item["nome"] ?>' 
                                    aria-pai-unidade-medida='<?= $custo_item["variavel_fator_nome"] ?>' ><?= intval($custo_item['produto_qtd']) ?></td>                     
                            <td class="<?php if ($custo_item['custo_tipo_codigo'] == 2) { echo 'cusfolexi'; } ?>" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item_selecionado ?>' 
                                    aria-conta-item='<?= $custo_item["conta_item"] ?>' aria-documento-tipo-codigo='<?= $documento_tipo_selecionado ?>' aria-pai-produto-qtd='<?= intval($custo_item['produto_qtd']) ?>'
                                    aria-pai-variavel-fator-codigo='<?= $custo_item['fator_codigo'] ?>'
                                    aria-pai-produto-codigo='<?= $custo_item["produto_codigo"] ?>'  aria-pai-produto-nome='<?= $custo_item["nome"] ?>' 
                                    aria-pai-unidade-medida='<?= $custo_item["variavel_fator_nome"] ?>' ><?= $custo_item['variavel_fator_nome'] ?></td>
                            <?php if ($custo_item['custo_tipo_codigo'] == 1) { ?>
                                <td><?= $geral->germoeatr() ?> <?= $geral->gersepatr($custo_item['unitario_custo']) ?></td>
                                <td><?= $geral->germoeatr() ?> <?= $geral->gersepatr($custo_item['pc_total_custo']) ?></td>
                            <?php } else { ?>
                                <td class="cusfolexi" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item_selecionado ?>' 
                                    aria-conta-item='<?= $custo_item["conta_item"] ?>' aria-documento-tipo-codigo='<?= $documento_tipo_selecionado ?>' aria-pai-produto-qtd='<?= intval($custo_item['produto_qtd']) ?>'
                                    aria-pai-variavel-fator-codigo='<?= $custo_item['fator_codigo'] ?>'
                                    aria-pai-produto-codigo='<?= $custo_item["produto_codigo"] ?>'  aria-pai-produto-nome='<?= $custo_item["nome"] ?>' 
                                    aria-pai-unidade-medida='<?= $custo_item["variavel_fator_nome"] ?>' ><?= $geral->germoeatr() ?> <?= $geral->gersepatr($custo_item['cf_unitario_custo']) ?> </td>                     
                                <td class="cusfolexi" aria-documento-numero='<?= $documento_numero_selecionado ?>' aria-quarto-item='<?= $quarto_item_selecionado ?>'
                                    aria-conta-item='<?= $custo_item["conta_item"] ?>' aria-documento-tipo-codigo='<?= $documento_tipo_selecionado ?>'  aria-pai-produto-qtd='<?= intval($custo_item['produto_qtd']) ?>'
                                    aria-pai-variavel-fator-codigo='<?= $custo_item['fator_codigo'] ?>' 
                                    aria-pai-produto-codigo='<?= $custo_item["produto_codigo"] ?>'  aria-pai-produto-nome='<?= $custo_item["nome"] ?>'  aria-pai-unidade-medida='<?= $custo_item["variavel_fator_nome"] ?>'>
                                        <?= $geral->germoeatr() ?> <?= $geral->gersepatr($custo_item['cf_total_custo']) ?> 
                                </td>                     
                            <?php } ?>
                        </tr>
                        <?php
                        $total_itens_custo += $custo_item['pc_total_custo'] + $custo_item['cf_total_custo'];
                        $item++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr  style="background: #dedddd;">
                        <td colspan="6"><strong>Total</strong></td>

                        <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($total_itens_custo) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12"  style="margin-top: 50px;">
                <div class='pull-left col-md-2 col-sm-4'>
                    <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>">
                </div>
            </div>
        </div>
    </div>
</div>
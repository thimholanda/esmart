
<?php

use App\Model\Entity\Geral;

$geral = new Geral();
if (count($pesquisa_pagamentos) > 0) {
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <table class="table_cliclipes">
                <thead>
                    <tr class="tabres_cabecalho">
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'dc.documento_numero', 'aria_form_id' => 'conpagpes', 'label' => $rot_restittit]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'data', 'aria_form_id' => 'conpagpes', 'label' => $rot_gerdattit]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'nome', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagnom]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cpf', 'aria_form_id' => 'conpagpes', 'label' => $rot_conpagcfj,'propriedade' => 'width:11%']); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'pagamento_forma_nome', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagfor]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cartao_numero', 'aria_form_id' => 'conpagpes', 'label' => $rot_rescarnum]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'banco_numero', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagbnc,'propriedade' => 'width:9%']); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'agencia_numero', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagagc]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'conta_numero', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagcco]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'deposito_referencia', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagref]); ?>
                        <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'total_valor', 'aria_form_id' => 'conpagpes', 'label' => $rot_respagval,'propriedade' => 'width:8%']); ?>

                    </tr>
                </thead>

                <?php
                $total_pago = 0;
                foreach ($pesquisa_pagamentos as $value) {
                    ?>
                    <tr class="conpagexi" data-documento-numero='<?= $value['documento_numero'] ?>' data-quarto-item='<?= $value['quarto_item'] ?>' data-item-numero='<?= $value['conta_item'] ?>'>
                        <td><?= $value['documento_numero'] . '-' . $value['quarto_item'] ?></td>
                        <td><?= date('d/m/Y', strtotime($value['data'])) ?></td>

                        <td><?= $value['nome'] . ' ' . $value['sobrenome'] ?></td>
                        <td><?php
                            if (empty($value['cpf'])) {
                                echo $value['cnpj'];
                            } else {
                                echo $value['cpf'];
                            }
                            ?></td>
                        <td><?= $value['pagamento_forma_nome'] ?></td>
                        <td><?= $value['cartao_numero'] ?></td>
                        <td><?= $value['banco_numero'] ?></td>
                        <td><?= $value['agencia_numero'] ?></td>
                        <td><?= $value['conta_numero'] ?></td>
                        <td><?= $value['deposito_referencia'] ?></td>
                          <td class='valor'><?= $geral->gersepatr($value['total_valor']) ?></td>
                    </tr>
                    <?php
                    $total_pago += $value['total_valor'];
                }
                ?>
                <tr>
                    <td colspan="10"><?php echo "<b style='font-size: 12px;'>" . $rot_gertottit . "</b>"; ?></td>
                    <td style="text-align: right;"><?php echo "<b style='font-size: 12px;text-align:center;'>" . $geral->gersepatr($total_pago) . "</b>"; ?>
                    </td>
                </tr>
            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="row top1">
    <div class="col-md-12">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="col-md-12" style="padding-bottom: 10px;">
                    <?php
                    echo $paginacao;
                    } else if (isset($pesquisa_pagamentos) && ($pesquisa_pagamentos == '0')) {?>
                     Nenhum pagamento encontrado
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use App\Model\Entity\Geral;

$geral = new Geral();
if (isset($pesquisa_creditos)) {
    $total = 0;
    if (count($pesquisa_creditos) > 0) {
        ?>
        <div style="margin-top:53px">
            <div class="col-md-9">
                <div class="cabecalho_credito" style="padding: 8px; border: 1px solid #e5e5e5;">
                    <div class="row">
                        <div class="col-md-12">
                            <b><?= $rot_gerclitit ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label><?= $rot_cliprinom ?>: <?= $cliente[0]['nome'] ?>  <?= $cliente[0]['sobrenome'] ?> </label> 
                        </div>
                        <div class="col-md-3">
                            <label><?= $rot_clicpfcnp ?>: <?= $cliente[0]['cpf'] ?? $cliente[0]['cnpj'] ??'' ?></label> 
                        </div>
                        <div class="col-md-3">
                            <label><?= $rot_clicadema ?>: <?= $cliente[0]['email'] ?></label> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b><?= $rot_conexttit ?></b>
                        </div>
                    </div>
                </div>

                <table class="table_cliclipes">
                    <thead>
                        <tr>
                            <th><?= $rot_gerdattit ?></th>
                            <th><?= $rot_resdocnum ?></th>
                            <th><?= $rot_concreexp ?></th>                       
                            <th><?= $rot_gervaltit ?> (<?= $geral->germoeatr() ?>)</th>
                        </tr>
                    </thead>
                    <?php
                    $indice = 0;
                    foreach ($pesquisa_creditos['lancamentos'] as $value) {
                        ?>
                    
                    <!--TENTAR REMOVER REDIRECT TO CONTROLLER-->
                        <tr  onclick="redirectToController('/documentocontas/conitemod/<?= $value["empresa_codigo"] ?>/<?= $value["documento_numero"] ?>/<?= $value["quarto_item"] ?>/<?= $value["conta_item"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">                      
                            <td <?php if ($value['expirado']) echo "style='color:#999'" ?>><?= date('d/m/Y', strtotime($value['data'])) ?></td>
                            <td <?php if ($value['expirado']) echo "style='color:#999'" ?>><?= $value['documento_numero'] ?></td>                           
                            <td <?php if ($value['expirado']) echo "style='color:#999'" ?>><?= date('d/m/Y', strtotime($value['expiracao_data'])) ?></td>
                            <td <?php if ($value['expirado']) echo "style='color:#999'" ?>><?= $geral->gersepatr($value['valor']) ?></td>
                        </tr>
                        <?php
                        $indice++;
                        if (!$value['expirado'])
                            $total = round($total + ($value['valor']), 2);
                    }
                    ?>

                    <tr>
                        <td colspan="3"><?php echo "<b style='font-size: 12px;'>" . $rot_gersaltit . "</b>"; ?></td>
                        <td><?php echo "<b style='font-size: 12px;'>" . $geral->gersepatr($total) . "</b>"; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <div style="padding: 8px; border: 1px solid #e5e5e5;">
                    <div class="row">
                        <div class="col-md-12">
                            <b><?= $rot_conresdex ?></b>
                        </div>
                    </div>
                    <table class="table_cliclipes">
                        <thead>
                            <tr>
                                <th><?= $rot_concreexp ?></th>   
                                <th><?= $rot_gervaltit ?> (<?= $geral->germoeatr() ?>)</th>                                         
                            </tr>
                        </thead>
                        <?php
                        $indice = 0;
                        foreach ($pesquisa_creditos['agrupados'] as $value) {
                            ?>
                            <tr onclick="redirectToController('/documentocontas/conitemod/<?= $value["empresa_codigo"] ?>/<?= $value["documento_numero"] ?>/<?= $value["quarto_item"] ?>/<?= $value["conta_item"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">                      
                                <td <?php if ($value['expirado']) echo "style='color:#999'" ?>><?= date('d/m/Y', strtotime($value['expiracao_data'])) ?></td>
                                <td <?php if ($value['expirado']) echo "style='color:#999'" ?>><?= $geral->gersepatr($value['valor']) ?></td>                            
                            </tr>
                            <?php
                            $indice++;
                            $total = round($total + ($value['valor']), 2);
                        }
                        ?>
                    </table>
                </div>
            </div>
        </table>
        </div>
        <?php
    } else if (isset($pesquisa_creditos)) {
        ?>
        <br/><br/>Nenhum item encontrado
        <?php
    }
}
?>
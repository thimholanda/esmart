<?php

use Cake\Network\Session;
use App\Utility\Util;
use App\Model\Entity\Geral;
?>

<script type="text/php">
    if (isset($pdf)) {
    $x = 240;
    $y = 815;
    $text = $PAGE_NUM .' de '. $PAGE_COUNT;
    $font = 'serif';
    $size = 11;
    $pdf->page_text($x, $y, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, array(0,0,0));

    $pdf->page_text(360, $y, 'Gerado por FastHotel em '. date('H:i d/m/Y'), $font, $size, array(0,0,0));
    }
</script>
<?php
$session = new Session();
$geral = new Geral();
$empresa_dados = $session->read("empresa_selecionada");
?>
<div class="content_inner">
    <!-- Dados da empresa -->
    <div style="text-align: center">
        <div>
            <?php if ($empresa_dados['logo'] != null && $empresa_dados['logo'] != "") { ?>
                <img style=" margin-top: -40px"   src="img/<?= $empresa_dados['logo'] ?>" width="94px" height="102px" alt="E-Smart">
            <?php } else { ?>
                <h4 style="margin-top: -60px" ><?= $empresa_dados['empresa_nome_fantasia'] ?></h4>
            <?php } ?>
        </div>
        <div><?= $empresa_dados['logradouro'] ?></div>
        <div><?= $empresa_dados['cidade'] . ', ' . $empresa_dados['estado'] . ', ' . $empresa_dados['pais_nome'] . ', ' . $empresa_dados['cep'] ?></div>
    </div>
    <?php if ($texto_filtros != "") { ?>
        <p style="margin: 0; padding: 0; font-size: 14px">
            Filtros selecionados: 

        <div style="margin: 0; margin-bottom:15px; padding: 0; margin-left: 1cm; font-size: 14px">
            <?= $texto_filtros; ?>
        </div>
    <?php } ?>
</p>

<?php if (isset($pesquisa_contas) && sizeof($pesquisa_contas) > 0) { ?>
    <table style="width:100%; font-size: 14px">
        <thead  style="margin-bottom:1px solid #000; background-color: #edeff2;">
            <tr class="tabres_cabecalho">
                <td><?= $rot_resquacod ?></td>
                <td><?= $rot_gerchitit ?></td>
                <td><?= $rot_gerchotit ?></td>
                <td><?= $rot_resdocnum ?></td>
                <td><?= $rot_resdochos ?></td>
                <td><?= $rot_consalpag ?></td>
                <td><?= $rot_contotdes ?></td>
                <td>Cartão crédito</td>
                <td>Cartão débito</td>
                <td><?= $rot_condintit ?></td>
                <td><?= $rot_contratit ?></td>
                <td> <?= $rot_conchetit ?></td>
                <td><?= $rot_confattit ?></td>
                <td> <?= $rot_contrctit ?></td>
                <td>Crédito</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $color = "#fff";
            foreach ($pesquisa_contas as $value) {
                ?>
                <tr  style="background-color: <?= $color ?>">
                    <td><?= $value['quarto_codigo'] ?></td>
                    <td><?= Util::convertDataDMY($value['inicial_data']) ?></td>
                    <td><?= Util::convertDataDMY($value['final_data']) ?></td>
                    <td><?= $value['documento_numero'] . '-' . $value['quarto_item'] ?></td>
                    <td><?= $value['nome'] . ' ' . $value['sobrenome'] ?></td>
                    <td><?= $geral->germoeatr() . ' ' . $geral->gersepatr($value['saldo_a_pagar']) ?></td>
                    <td><?= $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_despesas']) ?></td>
                    <td><?= $value['total_cartao'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_cartao']) : '' ?></td>
                    <td><?= $value['total_debito'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_debito']) : '' ?></td>
                    <td><?= $value['total_dinheiro'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_dinheiro']) : '' ?></td>
                    <td><?= $value['total_transferencia_deposito'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_transferencia_deposito']) : '' ?></td>
                    <td><?= $value['total_cheque'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_cheque']) : '' ?></td>
                    <td><?= $value['total_faturado'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_faturado']) : '' ?></td>
                    <td><?= $value['total_transferencia_entre_contas'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_transferencia_entre_contas']) : '' ?></td>
                    <td><?= $value['total_credito'] != 0 ? $geral->germoeatr() . ' ' . $geral->gersepatr($value['total_credito']) : '' ?></td>
                </tr>
                <?php
                if ($color == "#f5f5f5")
                    $color = "#fff";
                else
                    $color = "#f5f5f5";
            }
            ?>
        </tbody>
        <tfoot>
            <tr  style="margin-bottom:1px solid #000; background-color: #edeff2;">
                <td colspan="5"><strong><?= $rot_gertottit ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_saldo_a_pagar']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_despesas']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_cartao']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_debito']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_dinheiro']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_transferencia']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_cheque']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_faturado']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_transferencia_entre_contas']) ?></strong></td>
                <td><strong><?= $geral->germoeatr() . ' ' . $geral->gersepatr($somas['soma_credito']) ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <?php
}
?>
<!--
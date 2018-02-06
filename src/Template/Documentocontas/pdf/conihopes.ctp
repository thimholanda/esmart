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
            <tr  class="tabres_cabecalho">
                <td  style="padding:3px 5px; width:5%"><?= $rot_resquacod; ?></td>
                <td  style="padding:3px 5px; width:45%"><?= $rot_resdochos; ?></td>
                <td  style="padding:3px 5px; width:10%"><?= $rot_gerchitit; ?></td>
                <td  style="padding:3px 5px; width:10%"><?= $rot_gerchotit; ?></td>
                <td  style="padding:3px 5px"><?= $rot_conprocod; ?></td>
                <td  style="padding:3px 5px; width:5%; text-align: center"><?= $rot_conproqtd; ?></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $color = "#fff";
            foreach ($pesquisa_contas as $value) {
                ?>
                <tr style="background-color: <?= $color ?>">             
                    <td  style="padding:3px 5px; width:5%"><?= $value['quarto_codigo'] ?></td>
                    <td  style="padding:3px 5px; width:45%"><?= $value['hospede'] ?></td>
                    <td  style="padding:3px 5px; width:10%"><?= Util::convertDataDMY($value['inicial_data']) ?></td>
                    <td  style="padding:3px 5px; width:10%"><?= Util::convertDataDMY($value['final_data']) ?></td>                    
                    <td  style="padding:3px 5px"><?= $value['produto_nome'] ?></td>
                    <td  style="padding:3px 5px; width:5%; text-align: center"><?= intval($value['produto_qtd']) ?></td>
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
                <td style="padding:3px 5px;" colspan="5"><strong><?= $rot_gertottit ?></strong></td>
                <td style="padding:3px 5px; text-align: center"><strong><?= intval($somas['soma_produto_qtd']) ?></strong></td>
            </tr>
        </tfoot>
    </table>

<?php } ?>
</div>
<!--

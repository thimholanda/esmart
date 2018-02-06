<?php

use Cake\Network\Session;
use App\Utility\Util;
use App\Model\Entity\Geral;

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$session = new Session();
$geral = new Geral();
$empresa_dados = $session->read("empresa_selecionada");
?>
<p style="clear: both;">
    <!-- Dados da empresa -->
<div style="text-align: center">
    <div>
        <?php if ($empresa_dados['logo'] != null && $empresa_dados['logo'] != "") { ?>
            <img style=" margin-top: -40px"   src="img/<?= $empresa_dados['logo'] ?>" width="94px" height="102px" alt="E-Smart">
        <?php } else { ?>
            <h4 style="margin-top: -70px" ><?= $empresa_dados['empresa_nome_fantasia'] ?></h4>
        <?php } ?>
    </div>
    <div><?= $empresa_dados['logradouro'] ?></div>
    <div><?= $empresa_dados['cidade'] . ', ' . $empresa_dados['estado'] . ', ' . $empresa_dados['pais_nome'] . ', ' . $empresa_dados['cep'] ?></div>
</div>
<h3 style="text-align: center; margin-bottom:0px"> <?= $nome_relatorio; ?>
    </h3>

<h4 style="text-align: center; margin-top:0px"> Data: <?= $serdocdat ?> (<?= explode("-", strftime('%A', strtotime(Util::convertDataSql($serdocdat))))[0] ?>) </h4>
</p>

<script type="text/php">
    if (isset($pdf)) {
    $x = 240;
    $y = 815;
    $text = $PAGE_NUM .' de '. $PAGE_COUNT;
    $font = 'serif';
    $size = 12;
    $pdf->page_text($x, $y, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, array(0,0,0));

    $pdf->page_text(400, $y, 'Gerado por FastHotel em '. date('H:i d/m/Y'), $font, $size, array(0,0,0));
    }
</script>

<table class="table_cliclipes" style="width:100%; text-align: center" cellpadding="0" cellspacing="0">
    <thead style="margin-bottom:1px solid #000; background-color: #edeff2; text-align: left;">
        <tr>
            <th style="padding:2px;width:6%"><?= $rot_resquacod ?></th>
            <th style="padding:2px;width:8%"><?= $rot_gertiptit ?></th>
            <th style="padding:2px;width:11%"><?= $rot_gersertit ?></th>
            <th style="padding:2px;width:11%"><?= $rot_resentdat ?></th>
            <th style="padding:2px;width:7%"><?= $rot_respaxtit ?></th>
            <th style="padding:2px;width:30%"><?= $rot_gerobstit ?></th>
            <th style="padding:2px;width:31%"><?= $rot_resadisel ?></th> 
        </tr>
    </thead>
    <tbody style=" text-align: left;">
        <?php
        $color = "#fff";
        for ($i = 0; $i < sizeof($serdocpes_dados); $i++) {

            //Monta os dados de adicionais
            $adultos_qtd = 0;
            $criancas_qtd = 0;
            $inicial_data = "";
            $camareira_texto = "";
            $hoje = false;
            $adultos_qtd = $serrefexi_dados[$i]['adulto_qtd_ajustada'] ?? 0;
            $criancas_qtd = $serrefexi_dados[$i]['crianca_qtd_ajustada'] ?? 0;

            if (isset($serrefexi_dados[$i]['inicial_data']) && $serrefexi_dados[$i]['inicial_data'] == date('Y-m-d')) {
                $hoje = true;
            }

            if (isset($serrefexi_dados[$i]['inicial_data']))
                $inicial_data = Util::convertDataDMY($serrefexi_dados[$i]['inicial_data']);

            $camareira_texto = $serrefexi_dados[$i]['camareira_texto'] ?? "";
            if (isset($serrefexi_dados[$i]['adicional_texto']) && $serrefexi_dados[$i]['adicional_texto'] != "") {
                $texto_adicionais = explode(" | ", $serrefexi_dados[$i]['adicional_texto']);
                for ($k = 0; $k < sizeof($texto_adicionais); $k++) {
                    if ($texto_adicionais[$k] == " " || $texto_adicionais[$k] == "") {
                        unset($texto_adicionais[$k]); // remove item at index 0
                        $texto_adicionais = array_values($texto_adicionais);
                    }
                }
                $texto_adicionais = implode("; ", $texto_adicionais);
            } else
                $texto_adicionais = "";

            /* if (sizeof($texto_adicionais) > 0) {
              $texto_adicionais = substr($texto_adicionais, 0, -3);
              } */

            if ($color == "#f5f5f5")
                $color = "#fff";
            else
                $color = "#f5f5f5";
            ?>
            <tr class="border_bottom" style="background-color: <?= $color ?>">                    
                <td style="padding:2px;width:6%"><?= $serdocpes_dados[$i]['quarto_codigo'] ?></td> 
                <td style="padding:2px;width:8%;"><?= $serdocpes_dados[$i]['quarto_tipo_curto_nome'] ?></td> 
                <td style="padding:2px;width:11%;"><?= $serdocpes_dados[$i]['documento_tipo_nome'] ?></td>
                <td style="padding:2px;width:11%"><?php
        if ($hoje)
            echo '<b>' . $inicial_data . '</b>';
        else
            echo $inicial_data
                ?></td>
                <td style="padding:2px;width:7%"><?php echo $adultos_qtd . ' / ' . $criancas_qtd ?></td>
                <td style="padding:2px;width:30%;"><?php
                if ($serdocpes_dados[$i]['texto'] != null && $serdocpes_dados[$i]['texto'] != "" && $camareira_texto != "")
                    echo $camareira_texto . " | " . $serdocpes_dados[$i]['texto'];
                else if ($camareira_texto != "")
                    echo $camareira_texto;
                else if ($serdocpes_dados[$i]['texto'] != null && $serdocpes_dados[$i]['texto'] != "")
                    echo $serdocpes_dados[$i]['texto'];
            ?></td>
                <td style="padding:2px;width:31%;">
                    <?= str_replace(",", "", $texto_adicionais) ?>
                </td>
            </tr>

            <?php
        }
        ?>
    </tbody>
</table>
<!--
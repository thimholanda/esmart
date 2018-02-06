<?php

use App\Model\Entity\Geral;
use Cake\Network\Session;
use Cake\Routing\Router;
use App\Utility\Util;
use App\Model\Entity\Estadia;
use App\Model\Entity\Reserva;

$estadia = new Estadia();
$reserva = new Reserva();
$geral = new Geral();
$session = new Session();

$empresa_dados = $session->read("empresa_selecionada");
$existe_reserva_nao_confirmada = false;
$total_campos = 0;
if ($empresa_dados['tel_ddd'] != "" && $empresa_dados['tel_ddi'] != "" && $empresa_dados['tel_ddd'] != null && $empresa_dados['tel_ddi'] != null) {
    $total_campos++;
}
if ($empresa_dados['email'] != "" && $empresa_dados['email'] != null) {
    $total_campos++;
}
if ($empresa_dados['site'] != "" && $empresa_dados['site'] != null) {
    $total_campos++;
}

if ($total_campos == 2)
    $rodape_texto = $empresa_dados['tel_ddi'] . ' (' . $empresa_dados['tel_ddd'] . ') ' . $empresa_dados['tel_numero'] . "                              " . $empresa_dados['email'] . "                              " . $empresa_dados['site'] . "";

if ($total_campos == 3)
    $rodape_texto = $empresa_dados['tel_ddi'] . ' (' . $empresa_dados['tel_ddd'] . ') ' . $empresa_dados['tel_numero'] . "          " . $empresa_dados['email'] . "          " . $empresa_dados['site'] . "";
?>
<script type="text/php">
    if (isset($pdf)) {
    $x = 70;
    $y = 800;
    $text = $PAGE_NUM .' de '. $PAGE_COUNT;
    $font = 'serif';
    $size = 11;

    $pdf->page_text($x, $y, "<?= $rodape_texto ?> ", $font, $size, array(0,0,0));
    

    $x = 240;
    $y = 815;
    $text = $PAGE_NUM .' de '. $PAGE_COUNT;
    $font = 'serif';
    $size = 11;
    $pdf->page_text($x, $y, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, array(0,0,0));

    $pdf->page_text(360, $y, 'Gerado por FastHotel em '. date('H:i d/m/Y'), $font, $size, array(0,0,0));
    }
</script>
<div class="content_inner">
    <!-- Dados da empresa -->
    <div style="text-align: center">
        <div>
            <?php if ($empresa_dados['logo'] != null && $empresa_dados['logo'] != "") { ?>
                <img style=" margin-top: -40px"   src="img/<?= $empresa_dados['logo'] ?>" width="105px" height="102px" alt="E-Smart">
            <?php } else { ?>
                <h4 style="margin-top: -60px; margin-bottom: -15px" ><?= $empresa_dados['empresa_nome_fantasia'] ?></h4>
            <?php } ?>
        </div>
        <div><?= $empresa_dados['logradouro'] ?></div>
        <div><?= $empresa_dados['cidade'] . ', ' . $empresa_dados['estado'] . ', ' . $empresa_dados['pais_nome'] . ', ' . $empresa_dados['cep'] ?></div>
    </div>
    <div id="tab-geral" style=" margin-top: 25px"  >
        <div class="col-md-12 col-sm-12 top1">
            <table>
                <tr>
                    <td>Reserva número:  
                        <?= $reserva_dados[1]['documento_numero'] ?>
                        (<?= array_key_exists($reserva_dados[1]['agencia_codigo'], $agencias) ? $agencias[$reserva_dados[1]['agencia_codigo']] : '' ?>
                        <?php if ($reserva_dados[1]['externo_documento_numero'] != "") { ?>                      
                            - <?= $reserva_dados[1]['externo_documento_numero'] ?>
                        <?php } ?>
                        )
                </tr>
                <tr>
                    <td>Contratante:                         
                        <?= $reserva_dados[1]['nome'] . ' ' . $reserva_dados[1]['sobrenome'] ?>

                        <?php if ($reserva_dados[1]['cel_numero'] != null && $reserva_dados[1]['cel_numero'] != "") { ?>
                            <?= $reserva_dados[1]['cel_numero'] ?>
                        <?php } ?>
                        <?php if ($reserva_dados[1]['tel_numero'] != null && $reserva_dados[1]['tel_numero'] != "") { ?>
                            , <?= $reserva_dados[1]['tel_numero'] ?>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div> 
    </div> 
    <!--Percorre cada quarto item -->
    <?php
    foreach ($reserva_dados as $quarto_item) {
        if ($quarto_item['quarto_status_codigo'] < 2)
            $existe_reserva_nao_confirmada = true;

        $datas = explode("|", $reserva_dados[$quarto_item['quarto_item']]['datas']);
        ?>
        <br/>
        <div style="margin-top: 15px"><b><?= $quarto_item['quarto_item'] ?> - Quarto <?= $quarto_item['quarto_tipo_nome'] ?></b></div><br/>
        <div style="margin-top: 5px; margin-left:1.5em"><label class="col-md-12 col-sm-12">Entrada: <b><?= Util::convertDataDMY($quarto_item['inicial_data'], 'd/m/y H:i') ?></b> - Saída: <b><?= Util::convertDataDMY($quarto_item['final_data'], 'd/m/y H:i') ?></b> (<?= sizeof($datas) ?> <?php
    if (sizeof($datas) > 1)
        echo 'diárias';
    else
        echo 'diária';
        ?>)</label>  </div>
        <div  style="margin-top: 15px; margin-left:1.5em">
            <span class="col-md-12 col-sm-12 "><?= $rot_clihostit ?>: <b><?= $quarto_item['adulto_qtd_ajustada'] ?> adultos 
                    <?php if ($quarto_item['crianca_qtd_ajustada'] > 0) { ?>
                        e <?= $quarto_item['crianca_qtd_ajustada'] ?> crianças
                    <?php } ?>
                </b>
        </div>
        <div class='col-md-12 col-sm-12 ' style="margin-left:2.5em" >
            <table>
                <?php
                if (isset($quarto_item['hospedes'])) {
                    foreach ($quarto_item['hospedes'] as $quarto_item_hospede) {
                        if ($quarto_item_hospede["cliente_codigo"] != null) {
                            ?>
                            <tr>
                                <td> 
                                    <?= $quarto_item_hospede['nome'] ?? '' ?> <?= $quarto_item_hospede['sobrenome'] ?? '' ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>

            </table>
        </div> 
        <div  style="margin-top: 15px; margin-left:1.5em">
            Tarifa: <b><?= $quarto_item['tarifa_tipo_nome'] ?></b>
        </div>
        <div  style="margin-top: 15px;">
            <div style="margin-left:1.5em"> <?= $rot_resadisel ?>:</div>
            <div class='col-md-12 col-sm-12 ' style="margin-left:2.5em" >
                <table style="width:100%">
                    <?php
                    for ($i = 0; $i < sizeof($adicionais); $i++) {
                        if ($adicionais[$i]['quarto_item'] == $quarto_item['quarto_item']) {
                            ?>
                            <tr>
                                <td><?= $adicionais[$i]['nome'] . " <b>" . intval($adicionais[$i]['produto_qtd']) . ' ' . $adicionais[$i]['preco_fator_nome'] . '</b>' ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>  
            </div>
        </div>
    </div>
    <div style="margin-top: 15px; margin-left:1.5em">
        <?php
        $partidas_total = 0;
        $total_pago = 0;
        foreach ($partidas_por_quarto_item[$quarto_item['quarto_item']] as $partida) {
            $partidas_total += $partida['partida_valor'];
        }
        foreach ($contas_quarto_item[$quarto_item['quarto_item']] as $conta_quarto) {
            if ($conta_quarto['produto_tipo_codigo'] == 'PAG')
                $total_pago = round($total_pago + ($conta_quarto['total_valor']), 2);
        }
        ?>
        <table>
            <tr>
                <td>Valor total: <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($partidas_total) ?></b></td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>Pago: <b><?= $geral->germoeatr() ?>  <?= $geral->gersepatr(abs($total_pago)) ?></b></td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>A pagar: <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($partidas_total + $total_pago) ?></b></td>
            </tr>
        </table>
    </div>
    <div  style="margin-top: 15px; margin-left:1.5em">
        <table>
            <tr>
                <td style="width:30%">Status da reserva: <b><?= $quarto_item['documento_status_nome'] ?></b></td>
                <td >&nbsp;&nbsp;</td>
                <?php if ($quarto_item['confirmacao_limite'] != null) { ?>
                    <td style="width:70%">
                        <?php if (Util::comparaDatas($geral->geragodet(2), $quarto_item['confirmacao_limite']) == 1) { ?>
                            Limite para confirmação: <?= Util::convertDataDMY($quarto_item['confirmacao_limite'], 'd/m/y H:i') ?> 
                        <?php } else { ?>
                            Entre em contato com o estabelecimento imediatamente sob o risco de cancelamento da reserva
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        </table>
    </div>
    <div  style="margin-top: 15px; margin-left:1.5em">
        Cancelamento até <?php echo date('d/m/y H:i', strtotime($quarto_item['cancelamento_limite'])); ?>: <b>sem multa</b>. Cancelamento após essa data: <b>multa de <?= $moeda_simbolo . " " ?> <?= $geral->gersepatr($quarto_item['cancelamento_valor'] ?? '') ?></b>
    </div>
    <?php if ($quarto_item['texto'] != " " && $quarto_item['texto'] != null) { ?>
        <div  style="margin-top: 15px; margin-left:1.5em">
            <?= $rot_resmsghot ?>: <?= $quarto_item['texto'] ?>
        </div>
    <?php } ?>
<?php } ?>

<?php
if ($existe_reserva_nao_confirmada) {
    ?>
    <div style="margin-top: 15px;">
        Atenção: sua reserva não está confirmada. Por favor faça o pagamento para confirmá-la. O não cumprimento do prazo acarretará no cancelamento dessa reserva 
    </div>
<?php } ?>

<div style="margin-top: 52.5px; width: 85%; margin-left: auto; margin-right: auto">
    <br/>
    A equipe d<?= $empresa_dados['artigo'] ?> <?= $empresa_dados['empresa_nome_fantasia'] ?> deseja uma ótima estadia. Esperamos por você ;)
</div>
</div>
<!--
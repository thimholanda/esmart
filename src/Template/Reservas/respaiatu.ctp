<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
use App\Utility\Util;
use Cake\Network\Session;

$path = Router::url('/', true);
$geral = new Geral();
$session = new Session();
?>

<style>

    #content {
        position: relative;
        width: 100% !important;
    }

</style>

<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<form id="respaiatu" name="respaiatu" action="<?= $path ?>/reservas/respaiatu" method="post" style="top: 124px; position: fixed; z-index: 9991; background-color: #f2f2f2">

    <div class="navegador-datas">
        <input type="hidden" id="form_atual" value="respaiatu" name="form_atual" />
        <div  style="display:none" >
            <label class="control-label col-md-12 col-sm-12" for="gerordatr" ><?= $rot_gerordtip ?></label>
            <select class="form-control" name="gerordatr" id="gerordatr" aria-campo-padrao-valor ="1"  aria-padrao-valor="<?= $padrao_valor_gerordatr ?? '' ?>">
                <option <?php if ($gerordatr == 'quarto_tipo_codigo') echo 'selected' ?> value="quarto_tipo_codigo"><?= $rot_resquatip ?></option>
                <option <?php if ($gerordatr == 'quarto_codigo') echo 'selected' ?> value="quarto_codigo"><?= $rot_gerquacod ?></option>
            </select> 
        </div>
        <input type="checkbox" style="display:none" class="check_doc" id="doc_a_cancelar" value="">
        <div class="form-group row">
        </div>
        <div class="form-group row" style="margin-bottom: 10px; padding-top: 5px;">
            <label class="control-label col-md-1 col-sm-3" style="text-align: right; display: inline-block; width: 42px;" for="respaidat"><?= $rot_gerdattit ?>:</label>
            <div class="col-md-2 col-sm-3" style="width: 110px; margin-right: 70px;    margin-left: 50px;"> 
                <input maxlength="10" class="form-control datepicker data" style="margin-top: -5px;" type="text" name="respaidat" id="respaidat" value="<?= $respaidat ?? '' ?>"
                       placeholder="00/00/0000"  onchange="gerdatval(this)">
            </div>

            <label class="control-label col-md-1 col-sm-3" for="respaiper" style="text-align: right; display: inline-block;  width: 65px;"><?= $rot_respaiper . ":" ?></label>
            <div class="col-md-2 col-sm-3">
                <div class="col-md-9 row">
                    <select class="form-control" name="respaiper" id="respaiper"  style="margin-top: -5px;"  aria-campo-padrao-valor ="<?= $campo_padrao_valor_respaiper ?>"  aria-padrao-valor="<?= $padrao_valor_respaiper ?? '' ?>"> 
                        <?php
                        foreach ($respaiper_list as $item) {
                            if ($respaiper == $item["valor"]) {
                                $selected = "selected = 'selected'";
                            } else {
                                $selected = "";
                            }
                            ?>                                                                                                                                                                                                                                    <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                        <?php } ?> 
                    </select>
                </div>
            </div>
        </div>
        <input type="hidden" id="janela_atual"  name="janela_atual" value="<?= $janela_atual ?>" />
        <input id="respaiatu_submit" class="form-control btn-primary submit-button hide" aria-form-id='respaiatu' type="submit" value="<?= $rot_gerexebot ?>" >

        <a href="#" class="prev_week" id="janela_anterior_respaiatu" onclick="$('#janela_atual').val(-1)" style="position: absolute; top: 14px; left: 50px;"></a>
        <a href="#" class="next_week" id="janela_posterior_respaiatu" onclick="$('#janela_atual').val(+1)" style="position: absolute; top: 14px; left:
           200px"></a>

    </div>

    <div class="mes-ano">

        <!-- <span class="botao1 cor-txt1" id="respaiatu_hoje">Hoje</span> -->
        <span class="agenda-mes"></span>
        <span class="fundo-mes-ano"></span>

    </div>

    <div class="alocacoes-pendentes-topo">

        <div class="titulo"><img src="../img/fechar-03.png">Reservas pendentes de alocação: <span class="conta-pendencias"></span></div>

        <div style="padding: 5px; background-color: #bababa;">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td>Reserva</td>
                        <td>Check-in</td>
                        <td>Check-out</td>
                        <td>PAX</td>
                        <td>Tipo de quarto</td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="alocacoes-pendentes">

            <ul class="lista-reservas-pend"></ul>

        </div>

    </div>

    <!--<input type="button" class="btn-primary" value="DOCPDRMOD" onclick="docpdrmod(625, 1, 'bc', 1, '12', '2017-07-14', '2017-07-14', '2017-07-16', '2017-07-16')" />
    <input type="button" class="btn-primary" value="DOCPDRCRI" onclick="docpdrcri(12, 'mb', '2017-07-12', '2017-07-14')" />
    <input type="button" class="btn-primary" value="RESPDRCRI" onclick="respdrcri(2,'04', '2017-07-16', '2017-07-19')" />
    <input type="button" class="btn-primary" value="DOCPDREXC" onclick="docpdrexc(2, 1, 'rs', '2', 08)" />-->
</form>

<div class="agenda-bloco" id="agenda_painel_reserva" onkeyup="alert('teste')">

    <table cellspacing="0" class="agenda-table">

        <thead class="agenda-thead">

        </thead>

        <tbody class="agenda-tbody">

        </tbody>

    </table>

    <ul class="lista-reservas"></ul>

</div>

<div class="modal valign-container">

    <div class="valign-guide"></div>

    <div class="content-box valign-element">

        <div class="valign-guide"></div>

        <div class="content valign-element"></div>

    </div>

</div>
<div class="box-hover"></div>
<div class="box-hover-move"></div>

<?php
foreach ($agencias as $key => $agencia) {
    ?>
    <input type="hidden" id="agencia_<?= $key ?>" value="<?= $agencia ?>" />
<?php } ?>
<script>

    var processando = false,
            dragAtivado = false,
            validaDrag = true,
            scrollTop = 0;

    var jsonReservas = <?= $respaiatu_dados ?>,
            datasPeriodo = <?= json_encode($datas) ?>,
            quartosTipos = JSON.parse(<?= json_encode($quarto_tipo_nomes_curtos) ?>),
            reservasPendAloc = <?= json_encode($reservas_pendentes_alocacao) ?>,
            diasNaoUteis = <?= json_encode($dias_nao_uteis) ?>;
</script> 

<?php

if ($session->check('retorno_alert')) {
    echo "<script>alert('" . $session->read('retorno_alert') . "')</script>";
    $session->delete('retorno_alert');
}
?>


<div id="dialogs" >
    <div id="motivo-cancelamento" style="display:none"> 
        <form>
            <table style="margin-top:10px">
                <span style="font-size:15px;"><?= $rot_rescncdoc ?> <strong id="documento_quarto_item_cancelar"></strong></span>
                <input type="hidden" id="documento-numero-canc" value="" />
                <input type="hidden" id="quarto-item-canc" value="" />
                <input type="hidden" id="empresa-codigo-canc" value="" />

                <tr id="row-motivo-codigo">
                    <td><label for="cancelamento-motivo-codigo" id="cancelamento-motivo-codigo-lbl" ><?= $rot_germottit ?>:</label></td>
                    <td><select id="cancelamento-motivo-codigo" name="cancelamento_motivo_codigo">
                            <?php
                            foreach ($cancelamento_motivos as $item) {
                                ?>
                                <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> 
                            <?php } ?> 
                        </select></td></tr>
                <tr id="row-motivo-texto">
                    <td><label for="cancelamento-motivo-texto" id="cancelamento-motivo-texto-lbl" ><?= $rot_gerobstit ?>:</label></td>
                    <td><textarea cols="31" id="cancelamento-motivo-texto" name="cancelamento_motivo_texto" maxlength="50"></textarea></td>
                </tr>
            </table>
        </form>
    </div>
</div>
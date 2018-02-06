<?php

use App\Model\Entity\Geral;
use Cake\Routing\Router;
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

        <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
        <div class="form-group row">
        </div>
        <div class="form-group row" style="margin-bottom: 10px;">
            <label class="control-label col-md-1 col-sm-3" style="text-align: right; display: inline-block; padding-top: 5px; width: 42px;" for="respaidat"><?= $rot_gerdattit ?>:</label>
            <div class="col-md-2 col-sm-3" style="width: 110px; margin-right: 70px;    margin-left: 50px;"> 
                <input maxlength="10" class="form-control datepicker data" type="text" name="respaidat" id="respaidat" value="<?= $respaidat ?? '' ?>" placeholder="00/00/0000"  onchange="gerdatval(this)">
            </div>

            <label class="control-label col-md-1 col-sm-3" style="text-align: right; display: inline-block; padding-top: 5px; width: 65px;"><?= $rot_respaiper . ":" ?></label>
            <div class="col-md-2 col-sm-3">
                <div class="col-md-9 row">
                    <select class="form-control" name="respaiper" id="respaiper"> 
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

<div class="agenda-bloco">

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

    var datasPeriodo = <?= json_encode($datas) ?>,
        quartosTipos = JSON.parse(<?= json_encode($quarto_tipo_nomes_curtos) ?>),
        reservasPendAloc = <?= json_encode($reservas_pendentes_alocacao) ?>,
        diasNaoUteis = <?= json_encode($dias_nao_uteis) ?>;
</script> 

<?php
if ($session->check('retorno_footer')) {
    echo '<p class="col-xs-12 msg_footer" style=\'z-index:999999999\'>' . $session->read('retorno_footer'). '</p>';
    $session->delete('retorno_footer');
}
?>
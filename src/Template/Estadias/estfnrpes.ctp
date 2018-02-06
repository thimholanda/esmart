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
        <div style="margin-bottom: 15px">
            <form method="POST" name="estfnrpes" id="estfnrpes" action="<?= Router::url('/', true) ?>estadias/estfnrpes" class="form-horizontal">
                <input type="hidden" name="geracever_estfnrmod" value="<?= $geracever_estfnrmod ?>" />
                <input type="hidden" id="pagina" value="1" name="pagina" />
                <input type="hidden" id="form_atual" name="form_atual" value="estfnrpes" />
                <input type="hidden" id="form_force_submit" value="0" />
                <input type="hidden" id="gerdiacon_executada" value="0" />
                <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
                <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
                <input type="hidden" id="ordenacao_sistema" value="<?= $ordenacao_sistema ?? '0' ?>" name="ordenacao_sistema" />
                <input type="hidden" id="export_csv" value="0" name="export_csv" />          
                <input type="hidden" id="aria-form-id-csv" value="estfnrpes" > 
                <input type="hidden" id="title_dialog_validator" value="" >
                <input type="hidden" id="form_validator_function" name="form_validator_function" value="
                       if ($('#resdocnum').val() == ''  && $('#resesttit').val() ==  '' && ($('#c_codigo').val() == '' || $('#c_codigo').val() == '0') && $('#gerdatenv').val() == '' && $('#estfnrnum').val() == '') {
                       if (gerdiacon($('#resentdai').val(), $('#resentdaf').val(),<?= $fnrh_pesquisa_max ?>, 2, 1) == 1){
                       return true;
                       }
                       else if (gerdiacon($('#ressaidai').val(), $('#ressaidaf').val(),<?= $fnrh_pesquisa_max ?>, 2, 1) == 1){
                       return true;
                       }
                       else {
                       dialog = $('#exibe-germencri').dialog({
                       title: $('#title_dialog_validator').val(),
                       dialogClass: 'no_close_dialog',
                       autoOpen: false,
                       height: 200,
                       width: 530,
                       modal: true,
                       buttons: {
                       '<?= $rot_gerconrev ?>': function () {
                       dialog.dialog('close');
                       $('#form_force_submit').val('1');
                       $('#estfnrpes .submit-button').click();
                       },
                       '<?= $rot_gerrevdat ?>': function () {
                       dialog.dialog('close');
                       $('#gerdiacon_executada').val('0');
                       }
                       }
                       });
                       dialog.dialog('open');
                       return false;
                       }
                       }else
                       return true;
                       ">
                       <?php echo $this->element('reserva/resdocpes_form_elem', ['pagina' => 'estfnrpes']); ?>
            </form>
        </div>
    </div>
    <form id="estfnrcen" name="estfnrcen" action="<?= $path ?>/estadias/estfnrcen" method="post">
        <?php
        if (isset($pesquisa_fnrhs) && sizeof($pesquisa_fnrhs) > 0 && $pesquisar_fnrhs == 'yes') {
            if (count($pesquisa_fnrhs) > 0) {
                ?>
                <div>
                    <table class="table_cliclipes">
                        <thead>

                            <tr class='tabres_cabecalho'> 
                                <th width='1%'></th>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'estfnrpes', 'label' => $rot_resdocnum]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'snnum', 'aria_form_id' => 'estfnrpes', 'label' => $rot_estfnrtit]); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'nome', 'aria_form_id' => 'estfnrpes', 'label' => $rot_resdochos]); ?>
                            </tr>
                        </thead>
                        <?php
                        $indice = 0;
                        foreach ($pesquisa_fnrhs as $key => $fnrhs_da_reserva) {
                            $exibir_reserva = true;
                            foreach ($fnrhs_da_reserva as $fnrh_codigo => $envios_fnrh) {
                                $indice++;
                                if (is_array($envios_fnrh)) {
                                    ?>
                                    <tr>
                                        <td style="width:1%">
                                            <?php if ($exibir_reserva) { ?>
                                                <input type="checkbox" class="check_doc" name="fnrhs[]" value="<?= $fnrhs_da_reserva['lista_fnrh_codigos'] ?>" />   
                                            <?php } ?>
                                        </td>
                                        <td style="width:10%">
                                            <?php if ($exibir_reserva) { ?>
                                                <?php echo $this->Html->image($fnrhs_da_reserva['reserva_envio_icon'], array('width' => '15px', 'height' => '15px', 'margin' => '3px')); ?> <a style="cursor:pointer" class="resdocmod" aria-documento-numero ="<?= $key ?>" ><?= $key ?></a>
                                            <?php } ?>
                                        </td>
                                        <td style="width:15%">
                                            <?php
                                            echo $this->Html->image($envios_fnrh['fnrh_envio_icon'], array('id' => "exibe_envios_" . $indice,
                                                'class' => 'exibe_envios_tooltip', 'height' => '15px'));
                                            ?>

                                            <a style="cursor:pointer" onclick="estsavses('<?= $envios_fnrh['envio_status'] ?>', '<?= $envios_fnrh['retorno_msg'] ?>', '<?= Util::convertDataDMY($envios_fnrh['envio_data']) ?>');
                                                    redirectToController('/estadias/estfnrmod/<?= $fnrh_codigo ?>', 'estfnrpes', 'estadias/estfnrpes', '1')"><?php
                                                   if ($envios_fnrh['snnum'] == '' || $envios_fnrh['snnum'] == null)
                                                       echo $rot_gersemnum;
                                                   else
                                                       echo $envios_fnrh['snnum']
                                                       ?></a>
                                            <div class="table-tooltip" style="display:none;"  id="exibe_envios_<?= $indice ?>_tooltip">
                                                <table class="table_tooltip_no_border" style="width:300px; background-color: #fff; margin-top: 10px!important;
                                                       margin-left: 46px!important"  >
                                                    <tr>     
                                                        <td><b><?= $rot_resdocsta ?></b>: 
                                                            <?php
                                                            if ($envios_fnrh['envio_status'] == '1')
                                                                echo 'Recebida';
                                                            else if ($envios_fnrh['envio_status'] == '2')
                                                                echo 'Rejeitada';
                                                            else if ($envios_fnrh['envio_status'] == '0' || $envios_fnrh['envio_status'] == null)
                                                                echo 'Não enviada';
                                                            ?></td>
                                                    </tr>
                                                    <tr>     
                                                        <td><b><?= $rot_estmsgtit ?></b>: <?= $envios_fnrh['retorno_msg'] ?></td>
                                                    </tr>
                                                    <tr>     
                                                        <td><b><?= $rot_gerdattit ?></b>: <?= Util::convertDataDMY($envios_fnrh['envio_data']) ?></td>
                                                    </tr>
                                                    <tr>     
                                                        <td><b><?= $rot_gerusucod ?></b>: <?= $envios_fnrh['envio_usuario'] ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td><?= $envios_fnrh['nome'] ?></td>                        
                                    </tr>

                                    <?php
                                    $exibir_reserva = false;
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class="row">
                    <div class="pull-left col-md-2 col-sm-4">
                        <input style="float:left;  margin-right:10px"  onclick="gerpagsal('estfnrpes', 'estadias/estfnrpes', '1');
                                gerproexi('estfnrpes');" class="form-control btn-primary submit-button <?= $ace_estfnrcen ?>" type="submit" name="gerenvbot"  value="<?= $rot_gerenvbot ?>" >
                    </div>
                    <div class="pull-left col-md-2 col-sm-4">		
                        <input style="float:left; " class="form-control btn-primary submit-button <?= $ace_estfnrmod ?>" onclick="gerpagsal('estfnrpes', 'estadias/estfnrpes', '1');
                                document.estfnrcen.action = '<?= $path ?>/estadias/estfnrmoc'" type="submit" name="germodbot"  value="<?= $rot_germodbot ?>" >
                    </div>

                <?php }
                ?>
                <div class="row top1"><?php echo $paginacao; ?></div>
                <?php
            } else if (isset($pesquisa_reservas) && ($pesquisa_reservas == '0')) {
                ?>
                Nenhuma fnrh encontrada
                <?php
            }
            ?>
    </form>
</div>


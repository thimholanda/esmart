
<?php

use Cake\Network\Session;

$session = new Session();
?>

<div id="germenpes_dlg" style="display:none">

    <div id="germenpes_list"></div>
</div>



<div id="exibe-germencri" style="display: none">
    <div class="row">
        <span id="germencri_mensagem"></span>
    </div>
</div>

<div id="dialog_level_1" style="display: none">
    <div id="content_dialog_level_1">

    </div>
</div>


<div id="dialog_level_2" style="display: none">
    <div id="content_dialog_level_2">

    </div>
</div>

<div id="dialog_level_3" style="display: none">
    <div id="content_dialog_level_3">

    </div>
</div>

<div id="confirm_checkin_dialog" style="display:none">
    <div>
        <span id="mensagem_confirmacao"></span>
    </div>
    <div style="text-align: center" id="button_imprime_fnrh">
        <input class="btn btn-primary" style="margin-top:20px" type="button" value="Ok" onclick="estfnrpri()">
    </div>

</div>
<div id='gerpadexi' style='display: none; border: 1px solid;'>
    <form method="POST" name="gercampad_form" id="gercampad_form" action="" class="form-horizontal">
        <h6>Valores padrão</h6>
        <div id='gerpadexi_itens'>

        </div>
        <div class="col-md-12">
            <button style="margin-top: 15px" type='button' class="close_dialog" aria-dialog-id='gerpadexi'>Cancelar</button>&nbsp;
            <button style="margin-top: 15px" type='button' onclick="gerpadsal(<?= $session->read('usuario_codigo') ?>);">Salvar</button>
        </div>
    </form>
</div>

<div id = "exibe-reservas-cliente" style = "display: none">
    <div class = "cabecalho_conta" style = "padding: 8px; border: 1px solid #e5e5e5;">
        <div class = "row">
            <div class = "col-md-12"><label><b><?= $rot_cliclicon ?>: </b></label> <span id = "nome-contratante-conta"></span></div>
        </div>
    </div>
    <table class = "table_cliclipes">
        <thead>
            <tr><td colspan = "10"><?= $rot_restittit ?></td></tr>
            <tr><th><?= $rot_resdocnum ?></th>
                <th><?= $rot_resentdat ?></th>
                <th><?= $rot_ressaidat ?></th>
                <th><?= $rot_resquatip ?></th>
                <th><?= $rot_resdocsta ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div id="overlay">
    <?php echo $this->Html->image('icon_hotel_service.gif', array('width' => '22px', 'height' => '22px', 'id' => 'loading')); ?>
</div>
<script>
    function on() {
        document.getElementById("overlay").style.display = "block";
    }

    function off() {
        document.getElementById("overlay").style.display = "none";
    }
</script>

<!--<script>
<?php
switch ($session->read('empresa_selecionada')['pais_codigo']) {
    case 'br':
        ?>
                                                                                $.validate({ lang : 'pt'});
        <?php
        break;
    case 'us':
        ?>
                                                                                $.validate();
        <?php
        break;
    case 'es':
        ?>
                                                                                $.validate({ lang : 'es'});
        <?php
        break;
}
?>
  
</script>-->

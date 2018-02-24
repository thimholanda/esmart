<!-- Dados do cliente e da reserva -->
<div class="form-group col-md-12 col-sm-12" style="padding: 0; margin-bottom: 0;">
    <div class="col-md-6 col-sm-12" style="padding: 0;">
        <label class="col-md-12 col-sm-12" style="margin-bottom: 0px; padding: 0;"><?= $rot_resdocnum ?>: <b><?= $reserva_dados['documento_numero'] ?>-<?= $reserva_dados['quarto_item'] ?> <?= ucfirst($reserva_dados['documento_status_nome']) ?></b></label>
        <?php if ($exibe_datas == 1) { ?>
            <label class="col-md-12 col-sm-12" style="margin-bottom: 0px; padding: 0;">Data: <b><?= date('d/m/Y H:i:s', strtotime($reserva_dados['inicial_data'])) ?> - <?= date('d/m/Y H:i:s', strtotime($reserva_dados['final_data'])) ?> (<?= sizeof($datas) ?> <?php
                if (sizeof($datas) > 1)
                    echo 'diárias';
                else
                    echo 'diária';
                    ?>)</b></label>
        <?php } ?>
        <label class="col-md-12 col-sm-12" style="margin-bottom: 0px; padding: 0;"><?= $rot_clihostit ?>: <b><?= $reserva_dados['adulto_qtd_ajustada'] ?> / <?= $reserva_dados['crianca_qtd_ajustada'] ?></b></label>
    </div>
    <div class="col-md-6 col-sm-12" style="padding: 0;">
        <label class="col-md-12 col-sm-12" style="margin-bottom: 0px; padding: 0;">&nbsp;</label>
        <label class="col-md-12 col-sm-12" style="margin-bottom: 0px; padding: 0;"><?= $rot_resqticon ?>: <b><?= $reserva_dados['quarto_tipo_nome'] ?></b></label>
    </div>
</div>
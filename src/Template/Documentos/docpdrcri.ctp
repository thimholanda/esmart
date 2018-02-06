
<div class="form-group">
    <div class="col-md-12 col-sm-12">
        <div class="col-md-3 col-sm-3">
            <label class="radio-inline"><input class="resdprcri_tipo_documento" checked="checked" type="radio" name="resdprcri_tipo_documento" value="bc"
                                               onclick="$('#manutencao_criar').css('display', 'none'); $('#bloqueio_criar').css('display', 'block');"><?= $rot_serblocom ?></label>
        </div>
        <div class="col-md-4 col-sm-3">
            <label class="radio-inline"><input class="resdprcri_tipo_documento" type="radio" name="resdprcri_tipo_documento" value="mb"
                                               onclick="$('#bloqueio_criar').css('display', 'none'); $('#manutencao_criar').css('display', 'block'); $('#gertiptit').val('mb'); 
                                                   $('#gertiptit').parent().parent().css('display','none');" ><?= $rot_sermabtit ?></label>
        </div>
    </div>
</div>

<div id="bloqueio_criar">
    <?php
    echo $this->Element('../Reservas/resblocri', ['url_redirect_after' => 'reservas/respaiatu', 'modo_exibicao' => 'dialog', 'onclick_action' => "removeMarcacao()"]);
    ?>
</div>

<div id="manutencao_criar" style="display:none; padding-top: 21px;">
    <?php
    echo $this->Element('../Servicos/serdoccri', ['url_redirect_after' => 'reservas/respaiatu', 'modo_exibicao' => 'dialog', 
        'onclick_action' => "removeMarcacao()", 'gerdomsta_list' => $gerdomsta_mb_list, 'serdoctip' => 'mb']);
    ?>
</div>

<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<div class="content_inner">

    <div class="formulario">
        <form method="POST" name="sercamrel" id="sercamrel" action="<?= Router::url('/', true) ?>servicos/sercamrel/sercamrel.pdf" class="form-horizontal">
            <div class="form-group">
                <div class="col-md-3 col-sm-12">
                    <label class="control-label col-md-12 col-sm-12" for="serdocdat"><?= $rot_gerdattit ?></label>
                    <div class='col-md-12 col-sm-12'  > 
                        <input  required="required" class='form-control datepicker data data_place' maxlength="10" type="text" name="serdocdat" id="serdocdat" value="<?= $serdocdat ?>" placeholder="<?= $for_gerdattit ?>"   <?= $pro_gerdattit ?> <?= $val_gerdattit ?> />
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <label class="control-label col-md-12 col-sm-12">&nbsp;</label>
                    <div class='col-md-12 col-sm-12'>
                        <input class="form-control btn-primary submit-button" onclick="
                                $('#sercamrel').attr('target', '_blank');
                               " type="submit" value="<?= $rot_gergerrel ?>" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
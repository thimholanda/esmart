<?php

use Cake\Routing\Router;
use App\Utility\Util;
use App\Model\Entity\Geral;
use App\Model\Entity\Estadia;

$geral = new Geral();
$estadia = new Estadia();
$path = Router::url('/', true);

$datas = explode("|", $reserva_dados['datas']);
?>
<form id="estquaalo" action="<?= $path ?>/estadias/estquaalo" method="post">
    
    
    <div id="pag_aloc_quartos_aloc" style="margin-top: 15px">

        <!--Adiciona o elemento de cabecalho -->
        <?php
        echo $this->element('reserva/cabecalho_reserva', ['datas' => $datas, 'exibe_datas' => 1]);
        ?>

        <!--Elementos para alocacao multipla-->
        <?php
        echo $this->element('estadia/alocacao_multipla', ['datas' => $datas, 'quartos_alocados' => $quartos_alocados]);
        ?>

        <div class="row col-md-12 col-sm-12 quat_botoes2 text-center" style="margin-top: 20px; margin-bottom: 20px;">
            <button class="form-control btn-default close_dialog" style="width: auto; display: inline-block; vertical-align: middle; margin: 0; float: left; min-width: 177px;" type="button"><i class="fa fa-times-circle"></i><?= $rot_gerdesbot ?></button>
            <button id="estchicri_bot_2" style="width: auto; display: inline-block; vertical-align: middle; margin: 0; float: right; min-width: 177px;" class="form-control btn-primary estquaalo" type="button"><i class="fa fa-check-circle"></i> <?= $rot_geralobot ?></button>
        </div>
    </div>
</form>

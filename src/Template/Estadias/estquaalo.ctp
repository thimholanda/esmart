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

        <div class="row col-md-12 col-sm-12 quat_botoes2">
            <div class="cancel-right col-md-3 col-sm-4">
                <input class="form-control btn-default close_dialog" type="button" value="<?= $rot_gerdesbot ?>">
            </div>
            <div class="pull-left col-md-3 col-sm-4">
                <input id="estchicri_bot_2" style="float:left" class="form-control btn-primary estquaalo" type="button" value = "<?= $rot_geralobot ?>" >
            </div>
            <div class="col-md-3 col-sm-4"></div>
        </div>
    </div>
</form>

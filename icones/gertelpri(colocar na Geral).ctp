<!--
<div class="header-padrao">
    <!-- Logo
    <div class="logo">
        <?php echo $this->Html->image('logo-esmart.png', array('width' => '94px', 'height' => '32px')); ?>
    </div>
    <!-- Empresa 
    <div class="empresa">
    </div>
    <!-- Mini Dashboard 
    <div class="dashboard">
        <table>
            <tr>
                <th>Ocupação</th>
                <th class="bordaEsq">Reserv Pend</th>
                <th class="bordaEsq">Check-in</th>
                <th class="bordaEsq">Check-out</th>
            </tr>
            <tr>
                <td><!-- Inserir código da qnt de ocupação  a</td>
                <td class="bordaEsq"><!-- Inserir código da reservas prev   a</td>
                <td class="bordaEsq"><!-- Inserir código do check-in  a</td>
                <td class="bordaEsq"><!-- Inserir código do check-out  /td>
            </tr>
        </table>
    </div>
    <!-- dados do login  
    <div class="dadosL"><?= $strInfoLogin ?>
        <button style="padding-bottom: 3px;" type="button" onclick="ajaxgermenpes();
                "><span style="    transform: scale(1.5);" class="ui-icon ui-icon-mail-closed"></span></button>
    </div>
</div>

<!-- Barra de Atalhos  
<div id="atalhos">

</div>
<!-- Menu de Navegação  
<div id="menu">
    <ul id="nav">
        <li class='<?= $ace_cliente ?>' onClick="menu('.sub-nav1')">
            <a href="#"><?= $rot_cliclitit ?></a>
            <ul class="sub-nav1">
                <li class='<?= $ace_clicadcri ?>'><?= $this->Html->link($rot_gertitcri, '/clientes/clicadcri') ?></li>
                <li class='<?= $ace_clicadpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/clientes/clicadpes') ?></li>

            </ul>
        </li>
        <li class='<?= $ace_reserva ?>' onClick="menu('.sub-nav2')">
            <a href="#"><?= $rot_restittit ?></a>
            <ul class="sub-nav2">
                <li class='<?= $ace_rescriini ?>'><?= $this->Html->link($rot_gertitcri, '/reservas/rescriini') ?></li>
                <li class='<?= $ace_resdocpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/reservas/resdocpes') ?></li>
                <li class='<?= $ace_respaiatu ?>'><?= $this->Html->link($rot_respaiatu, '/reservas/respaiatu') ?></li>
                <li onClick="menu1('.sub-dropdow')">
                    <a href="#"><?= $rot_serblocom ?></a>
                    <ul class="sub-dropdow">
                        <li class='<?= $ace_serdoccri ?>'><?= $this->Html->link($rot_gertitcri, '/servicos/serdoccri?bc=1') ?></li>
                        <li class='<?= $ace_serdocpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/servicos/serdocpes?bc=1') ?></li>
                    </ul>

                </li>
            </ul>
        </li>
        <li class='<?= $ace_conta ?>' onClick="menu('.sub-nav3')">
            <a href="#"><?= $rot_gercontit ?></a>
            <ul class="sub-nav3">
                <li class='<?= $ace_concliges ?>'><?= $this->Html->link($rot_gercontit, '/documentocontas/concliges') ?></li>
                <li class='<?= $ace_concreexi ?>'><?= $this->Html->link($rot_gercretit, '/documentocontas/concreexi') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_pagamento ?>' onClick="menu('.sub-nav4')">
            <a href="#"><?= $rot_conpagtit ?></a>
            <ul class="sub-nav4">
                <li class='<?= $ace_conpagpes ?>'><?= $this->Html->link($rot_gertitexi, '/documentocontas/conpagpes') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_estadia ?>' onClick="menu('.sub-nav5')">
            <a href="#"><?= $rot_esttitit ?></a>
            <ul class="sub-nav5">
                <li class='<?= $ace_estfnrpes ?>'><?= $this->Html->link($rot_gerpesfnr, '/estadias/estfnrpes') ?></li>
                <li class='<?= $ace_estpaiatu ?>'><?= $this->Html->link($rot_estpaiocu, '/estadias/estpaiatu') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_servico ?>' onClick="menu('.sub-nav6')">
            <a href="#"><?= $rot_sersertit ?></a>
            <ul class="sub-nav6">
                <li class='<?= $ace_serdoccri ?>'><?= $this->Html->link($rot_gertitcri, '/servicos/serdoccri') ?></li>
                <li class='<?= $ace_serdocpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/servicos/serdocpes') ?></li>
                <li><?= $this->Html->link($rot_sercamtit, '/servicos/serdcarel') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_comunicacao ?>' onClick="menu('.sub-nav7')">
            <a href="#"><?= $rot_gercomtit ?></a>
            <ul class="sub-nav7">
                <li class='<?= $ace_gercompes ?>'><?= $this->Html->link($rot_gertitexi, '/geral/gercompes') ?></li>

            </ul>
        </li>
    </ul>
</div>

<!--
<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#browserList').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $path . "/ajax/ajaxData"; ?>",
            
        });
    });
</script>
 
<h1>Browser List</h1>
 
<table id="browserList">
    <thead>
        <tr>
            <th>Reserva</th>
            <th>Quarto</th>
            <th>Tipo de Quarto</th>          
            <th>Entrada</th> 
            <th>Saida</th> 
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5" class="dataTables_empty">Loading data from server...</td>
        </tr>
    </tbody>
</table>-->
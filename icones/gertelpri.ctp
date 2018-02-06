<!-- Cabeçalho e menu -->
        
<div class="header-padrao">
    <!-- Logo -->
    <div class="logo">
        <?php echo $this->Html->image('logo-esmart.png', array('width' => '94px', 'height' => '32px')); ?>
    </div>
    <!-- Empresa -->
    <div class="empresa">
    </div>
    <!-- Mini Dashboard -->
    <div class="dashboard">
        <table>
            <tr>
                <th>Ocupação</th>
                <th class="bordaEsq">Reserv Pend</th>
                <th class="bordaEsq">Check-in</th>
                <th class="bordaEsq">Check-out</th>
            </tr>
            <tr>
                <td><!-- Inserir código da qnt de ocupação-->a</td>
                <td class="bordaEsq"><!-- Inserir código da reservas prev -->a</td>
                <td class="bordaEsq"><!-- Inserir código do check-in-->a</td>
                <td class="bordaEsq"><!-- Inserir código do check-out-->a</td>
            </tr>
        </table>
    </div>
    
    <!-- dados do login -->
    <div class="dadosL">
        <div id='dv_tmp_gerpadcab'></div>
        <div class=''>
            <div class='class_info_login' id='div_info_login' align="right">
                <?= $usuario_dados['label_empresa_grupo'] ?> : <?=$usuario_dados['empresa_grupo_nome'] ?>
                <?= $usuario_dados['label_usuario'] ?> : <?= $usuario_dados['usuario_codigo'] ?> - <?= $usuario_dados['usuario_nome'] ?> <?= $usuario_dados['label_idioma']?> : <?= $usuario_dados['usuario_idioma'] ?> [<a href="<?= $usuario_dados['link_logout'] ?>" > <?=$usuario_dados['label_logout'] ?></a>]<button type="button" onClick="javascript: gerpadexi();"><?= $usuario_dados['label_padrao'] ?></button>
                    <button style='padding-bottom: 5px; padding-top: 1px;' type='button' onclick='ajaxgermenpes();'><span style='transform: scale(1.5);' class='ui-icon ui-icon-mail-closed'></span></button>
            </div>
        </div>
    </div>
</div>

<!-- Barra de Atalhos -->
<div id="atalhos">
    <?php echo '<a class="atalho_inner" href="#" alt="Home">'.$this->Html->image('home.png', array('width' => '18px', 'height' => '18px')).'</a>'; ?>
    <?php echo '<a class="atalho_inner" href="#" alt="Cliente">'.$this->Html->image('cliente.png', array('width' => '18px', 'height' => '18px')).'</a>'; ?>
    <?php echo '<a class="atalho_inner" href="#" alt="Reserva">'.$this->Html->image('reserva.png', array('width' => '18px', 'height' => '18px')).'</a>'; ?>
    <?php echo '<a class="atalho_inner" href="#" alt="Estadia">'.$this->Html->image('estadia.png', array('width' => '18px', 'height' => '18px')).'</label>'; ?>
    <?php echo '<a class="atalho_inner" href="#" alt="Serviços">'.$this->Html->image('servicos.png', array('width' => '18px', 'height' => '18px')).'</a>'; ?>
    <a class="menu_escd" onclick="menu_esconde()"><label1><<</label1></a>
    <a class="menu_exib" onclick="menu_exibe()"><label2>>></label2></a>
</div>
<!-- Menu de Navegação -->
<div id="menu">
    <ul id="nav">
        <li class='<?= $ace_cliente ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('cliente.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_cliclitit ?>
            <?php echo '<label>'.$this->Html->image('cliente.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_clicadcri ?>'><?= $this->Html->link($rot_gertitcri, '/clientes/clicadcri') ?></li>
                <li class='<?= $ace_clicadpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/clientes/clicadpes') ?></li>

            </ul>
        </li>
        <li class='<?= $ace_reserva ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('reserva.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_restittit ?>
            <?php echo '<label>'.$this->Html->image('reserva.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_rescriini ?>'><?= $this->Html->link($rot_gertitcri, '/reservas/rescriini') ?></li>
                <li class='<?= $ace_resdocpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/reservas/resdocpes') ?></li>
                <li class='<?= $ace_respaiatu ?>'><?= $this->Html->link($rot_respaiatu, '/reservas/respaiatu') ?></li>
                <li>
                    <a href="#"><?= $rot_serblocom ?></a>
                    <ul class="sub-dropdow">
                        <li class='<?= $ace_serdoccri ?>'><?= $this->Html->link($rot_gertitcri, '/servicos/serdoccri?bc=1') ?></li>
                        <li class='<?= $ace_serdocpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/servicos/serdocpes?bc=1') ?></li>
                    </ul>

                </li>
            </ul>
        </li>
        <li class='<?= $ace_conta ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('contas.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_gercontit ?>
            <?php echo '<label>'.$this->Html->image('contas.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_concliges ?>'><?= $this->Html->link($rot_gercontit, '/documentocontas/concliges') ?></li>
                <li class='<?= $ace_concreexi ?>'><?= $this->Html->link($rot_gercretit, '/documentocontas/concreexi') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_pagamento ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('pagamentos.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_conpagtit ?>
            <?php echo '<label>'.$this->Html->image('pagamentos.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_conpagpes ?>'><?= $this->Html->link($rot_gertitexi, '/documentocontas/conpagpes') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_estadia ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('estadia.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_esttittit ?>
            <?php echo '<label>'.$this->Html->image('estadia.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_estfnrpes ?>'><?= $this->Html->link($rot_gerpesfnr, '/estadias/estfnrpes') ?></li>
                <li class='<?= $ace_estpaiatu ?>'><?= $this->Html->link($rot_estpaiocu, '/estadias/estpaiatu') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_servico ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('servicos.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_sersertit ?>
            <?php echo '<label>'.$this->Html->image('servicos.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_serdoccri ?>'><?= $this->Html->link($rot_gertitcri, '/servicos/serdoccri') ?></li>
                <li class='<?= $ace_serdocpes ?>'><?= $this->Html->link($rot_gertitexi . "/" . $rot_gertitmod, '/servicos/serdocpes') ?></li>
                <li><?= $this->Html->link($rot_sercamtit, '/servicos/serdcarel') ?></li>
            </ul>
        </li>

        <li class='<?= $ace_comunicacao ?>'>
            <?php echo '<label class="label_out">'.$this->Html->image('comunicacao.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            <a href="#"><?= $rot_gercomtit ?>
            <?php echo '<label>'.$this->Html->image('comunicacao.png', array('width' => '20px', 'height' => '20px')).'</label>'; ?>
            </a>
            <ul class="sub-nav">
                <li class='<?= $ace_gercompes ?>'><?= $this->Html->link($rot_gertitexi, '/geral/gercompes') ?></li>

            </ul>
        </li>
    </ul>
</div>

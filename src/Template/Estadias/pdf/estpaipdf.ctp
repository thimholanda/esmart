<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<a  onclick="redirectToController(
        'estadias/estpaipdf', 
        'procadmod', 
        'produtos/procadmod')"
        > PDF </a>
        
<div class="content_inner">
    <div class="col-lg-2">
        <div class="esc_fil"><a class="filtro_escd" title="Esconder filtro" onclick="filtro_esconde();"></a></div>
        <div class="exb_fil"><a class="filtro_exib" title="Exibir filtro" onclick="filtro_exibe();"></a></div>
    </div>
    <div class="clear"></div>
    <div id="fil_pri" class="col-lg-2">
        <div id="filtro">
            <form action="<?= Router::url('/', true) ?>estadias/estpaiatu" name="estpaiatu" id="estpaiatu" method="post">
                <input type="hidden" name="saida_tipo" id="saida_tipo" value="p" />
                <input type="hidden" name="sessao_contador_nao_reinicia" id="sessao_contador_nao_reinicia" />
                <ul id="tipo_quarto">
                    <li>
                        <input type="checkbox" <?php if ($tipo_quarto_select_all == 1) echo 'checked' ?> id="tipo_quarto_select_all" name="tipo_quarto_select_all" class="típo_quarto_filtro" onchange="if ($(this).prop('checked')) {
                            $('.típo_quarto_filtro').prop('checked', true);
                        } else {
                            $('.típo_quarto_filtro').prop('checked', false);
                        }
                        $('.submit-button').click()">
                        <label for='tipo_quarto_select_all'><span><strong>Tipo de quarto</strong></span></label>
                    </li>
                    <?php foreach ($vetor_quarto_tipo as $quarto_tipo) { ?>
                    <li>
                        <input type="checkbox" <?php if ($filtro[$quarto_tipo['valor']] == 1) echo 'checked' ?> name="filtro_quarto_tipo_codigo[]" class="típo_quarto_filtro" onchange="$('.submit-button').click()" value="<?= $quarto_tipo['valor'] ?>"><span class='nome_filtro'><?= $quarto_tipo['rotulo'] ?></span><span class='filtro1_inner stilo_filtro1'><?= $estpaiatu_totais[$quarto_tipo['valor']] ?></span>
                    </li>
                    <?php } ?>
                </ul>
                <ul  id="situacao">
                    <li>
                        <input type="checkbox" <?php if ($situacao_select_all == 1) echo 'checked' ?>  id="situacao_select_all" name="situacao_select_all" class="situacao_filtro" onchange="if ($(this).prop('checked')) {
                            $('.situacao_filtro').prop('checked', true);
                        } else {
                            $('.situacao_filtro').prop('checked', false);
                        }
                        $('.submit-button').click()"><label for='situacao_select_all'><span><strong>Situação</strong></span></label>
                    </li>
                    <li><input type="checkbox" <?php if ($filtro['vazio'] == 1) echo 'checked' ?> name="filtro_vazio" class="situacao_filtro" onchange="$('.submit-button').click()"><span class='nome_filtro'>Vazio</span><span class='filtro1_inner stilo_filtro9'><?= $estpaiatu_totais['vazio'] ?></span></li>
                    <li><input type="checkbox" <?php if ($filtro['ocupado'] == 1) echo 'checked' ?> name="filtro_ocupado" class="situacao_filtro" onchange="$('.submit-button').click()"><span class='nome_filtro'>Ocupado</span><span class='filtro1_inner stilo_filtro6'><?= $estpaiatu_totais['ocupado'] ?></span></li>
                    <li><input type="checkbox" <?php if ($filtro['bloqueado'] == 1) echo 'checked' ?> name="filtro_bloqueado" class="situacao_filtro" onchange="$('.submit-button').click()"><span class='nome_filtro'>Bloqueado</span><span class='filtro1_inner stilo_filtro7'><?= $estpaiatu_totais['bloqueado'] ?></span></li>
                </ul>
                <ul id="atividades">
                    <li><label for='atividades_select_all'><span><strong>Atividades</strong></span></label></li>
                    <li>
                        <div class="drop_pa"><span class='nome_filtro'>Check-out</span></div>
                        <input type="hidden" id="filtro_check_out" name="filtro_check_out" value="<?= $filtro['check_out'] ?>">

                        <div class="drop_po">
                            <?php if ($filtro['check_out'] == 1) { ?>
                            <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:28px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['check_out'] == 0) { ?>
                            <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['check_out'] == -1) { ?>
                            <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:27px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_check_out').val('1');
                                    $('.submit-button').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_out').val('0');
                                    $('.submit-button').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_out').val('-1');
                                    $('.submit-button').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                            </ul>
                        </div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro2'><?= $estpaiatu_totais['check_out'] ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="drop_pa"><span class='nome_filtro'>Check-in</span></div>
                        <input type="hidden" id="filtro_check_in" name="filtro_check_in" value="<?= $filtro['check_in'] ?>">

                        <div  class="drop_po">
                            <?php if ($filtro['check_in'] == 1) { ?>
                            <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:28px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['check_in'] == 0) { ?>
                            <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['check_in'] == -1) { ?>
                            <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:27px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>

                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_check_in').val('1');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_in').val('0');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                                <li><a href="#" onclick="$('#filtro_check_in').val('-1');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                            </ul>
                        </div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro11'><?= $estpaiatu_totais['check_in'] ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="drop_pa"><span class='nome_filtro'>Serviços</div>
                        <input type="hidden" id="filtro_servico" name="filtro_servico" value="<?= $filtro['servico'] ?>">

                        <div class="drop_po">
                            <?php if ($filtro['servico'] == 1) { ?>
                            <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:28px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['servico'] == 0) { ?>
                            <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['servico'] == -1) { ?>
                            <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:27px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>                            
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_servico').val('1');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                                <li><a href="#" onclick="$('#filtro_servico').val('0');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                                <li><a href="#" onclick="$('#filtro_servico').val('-1');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                            </ul>
                        </div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro5'><?= $estpaiatu_totais['servico'] ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="drop_pa"><span class='nome_filtro'>Bloqueio</div>
                        <input type="hidden" id="filtro_bloqueio" name="filtro_bloqueio" value="<?= $filtro['bloqueio'] ?>">

                        <div class="drop_po">
                            <?php if ($filtro['bloqueio'] == 1) { ?>
                            <a href="#" id="bloqueio_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:28px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                            <?php } elseif ($filtro['bloqueio'] == 0) { ?>
                            <a href="#" id="bloqueio_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                            <?php } elseif ($filtro['bloqueio'] == -1) { ?>
                            <a href="#" id="bloqueio_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:27px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                            <?php } ?>                              
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="$('#filtro_bloqueio').val('1');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                                <li><a href="#" onclick="$('#filtro_bloqueio').val('0');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                                <li><a href="#" onclick="$('#filtro_bloqueio').val('-1');
                                    $('.submit-button').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                            </ul>
                        </div>
                        <div style="float:right; width: 22%">
                            <span class='filtro1_inner stilo_filtro10'><?= $estpaiatu_totais['bloqueio'] ?></span>
                        </div>
                    </li>   
                </ul>
                <input class="submit-button hide"  aria-form-id="estpaiatu" type="submit"  />
            </form>
        </div>
    </div>

    <div id="roms_seg" class="col-lg-10">
        <div id="rooms" >
            <?php
            foreach ($painel_ocupacao as $quarto_codigo => $quarto) {
                if ($quarto['painel_exibir'] == 1) {
                    echo '<div class="icon" >';
                    /* Verifica o servico de camareira */

                    /* Verifica a reserva atual */
                    $key = array_search('a', array_column($quarto, 'painel_posicao'));
                    //Verifica se esta ocupado
                    if ($key !== false) {
                        /* Verifica se a ocupação é por motivo de reserva */
                        $verifica_reserva = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                        if ($verifica_reserva !== false){
                            $reserva_info = $quarto[$verifica_reserva];
                            echo '<div><a class="porta porta_ocupado"><h5>' . $quarto_codigo . '</h5><label></label>';
                            echo '<div class="clear caixa_rod">';
                            echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div>';
                            echo '<div class="porta_open"><h6>Reserva</h6><p>'. $reserva_info['nome'] . $reserva_info['sobrenome'].'</p>'
                                    . '<p>'.$reserva_info['inicial_data'].' / '.$reserva_info['final_data'].'</p>'
                                    . '<p>'.$reserva_info['documento_tipo_nome'].'</p></div></a></div>';
                        }
                        /* Verifica se a ocupação é por motivo de manutenção */
                        $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));
                        if ($verifica_manutencao_com_bloqueio !== false){
                            $manutencao_com_bloqueio_info = $quarto[$verifica_manutencao_com_bloqueio];
                            echo '<div><a class="porta porta_bloqueio"><h5>' . $quarto_codigo . '</h5><label></label>';
                            echo '<div class="clear caixa_rod">';
                            echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div>';
                            echo '<div class="porta_open"><h6>Manutenção bloqueio</h6><p>'. $manutencao_com_bloqueio_info['nome'] . $manutencao_com_bloqueio_info['sobrenome'].'</p>'
                                    . '<p>'.$manutencao_com_bloqueio_info['inicial_data'].' / '.$manutencao_com_bloqueio_info['final_data'].'</p>'
                                    . '<p>'.$manutencao_com_bloqueio_info['documento_tipo_nome'].'</p></div></a></div>';
                        }
                        /* Verifica se a ocupação é por motivo de bloqueio */
                        $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                        if ($verifica_bloqueio_comercial !== false){
                            $bloqueio_comercial_info = $quarto[$verifica_bloqueio_comercial];
                            echo '<div><a class="porta porta_bloqueio"><h5>' . $quarto_codigo . '</h5><label></label>';
                            echo '<div class="clear caixa_rod">';
                            echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div>';
                            echo '<div class="porta_open"><h6>Bloqueio comercial</h6><p>'. $bloqueio_comercial_info['nome'] . $bloqueio_comercial_info['sobrenome'].'</p>'
                                    . '<p>'.$bloqueio_comercial_info['inicial_data'].' / '.$bloqueio_comercial_info['final_data'].'</p>'
                                    . '<p>'.$bloqueio_comercial_info['documento_tipo_nome'].'</p></div></a></div>';
                        }
                    } else {
                        echo '<div><a class="porta"><h5>' . $quarto_codigo . '</h5><label></label>';
                        echo '<div class="clear caixa_rod">';
                        echo '<span class="porta_quarto_tipo_nome">' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] . '</span></div></a></div>';
                    }

                    echo '<div class="caixa">';
                    //Verifica se tem algum checkout
                    $key = array_search('o', array_column($quarto, 'painel_posicao'));
                    $verifica_checkout = array_search('o', array_column($quarto, 'documento_tipo_codigo'));
                    if ($key !== false) {
                        $checkout_info = $quarto[$verifica_checkout];
                        echo '<a class="dialogo">' . $this->Html->image('co_confirmado.png', array('width' => '28px', 'height' => '28px'));
                        echo '<div class="dialogo_inner"><h6>Check out</h6><p>'. $checkout_info['nome'] . $checkout_info['sobrenome'].'</p>'
                                    . '<p>'.$checkout_info['inicial_data'].' / '.$checkout_info['final_data'].'</p>'
                                    . '<p>'.$checkout_info['documento_tipo_nome'].'</p></div></a>';
                        $checkout_existe = 1;
                    } else {
                        
                    }

                    echo '</div><div class="caixa">';
                    /* Verifica o bloqueio comercial */
                    $pos_i = array_search('i', array_column($quarto, 'painel_posicao'));
                    if ($pos_i !== false) {
                        /* Verifica o checkin */
                        $verifica_checkin = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                        /* Verifica o bloqueio comercial */
                        $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                        /* Verifica o manutencao com bloqueio */
                        $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));

                        $key = array_search('c', array_column($quarto, 'painel_posicao'));
                        //Verifica se tem alguma camareira nesse quarto
                        if ($key !== false) {
                            $camareira_info = $quarto[$key];

                            //Verifica o tipo de camareira(arrumação, faxina ou conferência, para imprimir o ícone correto
                            switch ([$camareira_info['documento_tipo_codigo'], $camareira_info['documento_status_nome']]) {
                                case ['ca', 'planejada']:
                                //Busca a imagem referente a camareira arrumação planejada
                                echo '<a href="#" class="dialogo">' . $this->Html->image('ca_planejada.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                                case ['ca', 'em execução']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('ca_execucao.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                                case ['cc', 'planejada']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('cc_planejada.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                                case ['cc', 'em execução']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('cc_execucao.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                                case ['cf', 'planejada']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('cf_planejada.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                                case ['cf', 'em execução']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('cf_execucao.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                            }
                        //Se não tem serviço de camareira
                        } else {
                        //Ação quando não se tem o serviço de camareira
                        }

                        echo '</div><div class="caixa">';
                        /* Verifica a manutenção simples */
                        $verifica_manutencao_simples = array_search('m', array_column($quarto, 'painel_posicao'));
                        //Verifica se tem alguma manutenção
                        if ($verifica_manutencao_simples !== false) {
                            $manutencao_info = $quarto[$verifica_manutencao_simples];
                            switch ([$manutencao_info['documento_tipo_codigo'], $manutencao_info['documento_status_nome']]) {
                                case ['ms', 'planejada']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('ms_planejada.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                                case ['ms', 'em execução']:
                                echo '<a href="#" class="dialogo">' . $this->Html->image('ms_execucao.png', array('width' => '28px', 'height' => '28px')).'</a>';
                                break;
                            }
                        } else {
                        //Ação quando não se tem manutencao
                        }
                        
                        echo '</div><div class="caixa">';
                        if ($verifica_checkin !== false) {
                            $checkin_info = $quarto[$verifica_checkin];
                            //Se for checkin preliminar
                            if ($checkin_info['documento_status_nome'] == 'preliminar') {
                                echo '<a class="dialogo">' . $this->Html->image('ci_preliminar.png', array('width' => '28px', 'height' => '28px'));
                                echo '<div class="dialogo_inner"><h6>Check in</h6><p>'. $checkin_info['nome'] . $checkin_info['sobrenome'].'</p>'
                                    . '<p>'.$checkin_info['inicial_data'].' / '.$checkin_info['final_data'].'</p>'
                                    . '<p>'.$checkin_info['documento_tipo_nome'].'</p></div></a>';
                             //Se for checkin confirmado
                            } else {
                                echo '<a class="dialogo">' . $this->Html->image('ci_confirmada.png', array('width' => '28px', 'height' => '28px'));
                                echo '<div class="dialogo_inner"><h6>Check in</h6><p>'. $checkin_info['nome'] . $checkin_info['sobrenome'].'</p>'
                                    . '<p>'.$checkin_info['inicial_data'].' / '.$checkin_info['final_data'].'</p>'
                                    . '<p>'.$checkin_info['documento_tipo_nome'].'</p></div></a>';
                            }
                        }else if($verifica_bloqueio_comercial !== false){
                            $bloqueio_comercial_info = $quarto[$verifica_bloqueio_comercial];
                            echo '<a class="dialogo">' . $this->Html->image('bc_planejado.png', array('width' => '28px', 'height' => '28px'));
                            echo '<div class="dialogo_inner"><h6>Bloqueio</h6><p>'. $bloqueio_comercial_info['nome'] . $bloqueio_comercial_info['sobrenome'].'</p>'
                                    . '<p>'.$bloqueio_comercial_info['inicial_data'].' / '.$bloqueio_comercial_info['final_data'].'</p>'
                                    . '<p>'.$bloqueio_comercial_info['documento_tipo_nome'].'</p></div></a>';
                        }else if($verifica_manutencao_com_bloqueio !==false){
                            $manutencao_info = $quarto[$verifica_manutencao_com_bloqueio];
                            //Verifica o tipo de manutencao(simples, bloqueio)
                            echo '<a class="dialogo">' . $this->Html->image('mb_planejada.png', array('width' => '28px', 'height' => '28px'));
                            echo '<div class="dialogo_inner"><h6>Bloqueio</h6><p>'. $manutencao_info['nome'] . $manutencao_info['sobrenome'].'</p>'
                                    . '<p>'.$manutencao_info['inicial_data'].' / '.$manutencao_info['final_data'].'</p>'
                                    . '<p>'.$manutencao_info['documento_tipo_nome'].'</p></div></a>';
                            
                            }
                        }else{
                            
                        }
                        
                        echo '</div></div>';
                    }
                }
            ?>
        </div>
    </div>
</div>
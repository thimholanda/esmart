<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<script>
    $('.export-pdf-link').click(function () {
        $("#estpaiatu").submit();
    });
</script>

<div class="content_inner">
    <table class="table_cliclipes">
        <thead>
            <tr>
                <th><?= $rot_resquacod ?></th>
                <th><?= $rot_resquatip ?></th>
                <th><?= $rot_gertipati ?></th>
                <th><?= $rot_gerdoctip ?></th>
                <th><?= $rot_gerdoctit ?></th>
                <th><?= $rot_resdocsta ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($painel_ocupacao as $quarto_codigo => $quarto) {
                if ($quarto['painel_exibir']) {
                    ?>
                    <tr>
                        <td><?= $quarto_codigo ?></td>
                        <td><?= $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] ?></td>
                        <td><?= $rot_gerocutit ?></td>
                        <!-- verifica se existe documento na posição a (ocupação) -->
                        <?php
                        $key = array_search('a', array_column($quarto, 'painel_posicao'));
                        if ($key !== false) {
                            /* Verifica se a ocupação é por motivo de reserva */
                            $verifica_reserva = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_reserva !== false) {
                                $reserva_info = $quarto[$verifica_reserva];
                                ?>
                                <td><?= $reserva_info['documento_tipo_nome'] ?></td>
                                <td><?= $reserva_info['documento_numero'] ?></td>
                                <td><?= $reserva_info['documento_status_nome'] ?></td>
                                <?php
                            }

                            /* Verifica se a ocupação é por motivo de manutenção */
                            $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_manutencao_com_bloqueio !== false) {
                                $manutencao_com_bloqueio_info = $quarto[$verifica_manutencao_com_bloqueio];
                                ?>
                                <td><?= $manutencao_com_bloqueio_info['documento_tipo_nome'] ?></td>
                                <td><?= $manutencao_com_bloqueio_info['documento_numero'] ?></td>
                                <td><?= $manutencao_com_bloqueio_info['documento_status_nome'] ?></td>
                                <?php
                            }
                            /* Verifica se a ocupação é por motivo de bloqueio */
                            $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_bloqueio_comercial !== false) {
                                $bloqueio_comercial_info = $quarto[$verifica_bloqueio_comercial];
                                ?>
                                <td><?= $manutencao_com_bloqueio_info['documento_tipo_nome'] ?></td>
                                <td><?= $bloqueio_comercial_info['documento_numero'] ?></td>
                                <td><?= $bloqueio_comercial_info['documento_status_nome'] ?></td>
                            <?php } ?>
                            <!-- se não existe ocupação -->
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td><?= $quarto_codigo ?></td>
                        <td><?= $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] ?></td>
                        <td><?= $rot_gerchotit ?></td>
                        <!-- verifica se existe documento na posição o (checkout) -->
                        <?php
                        $key = array_search('o', array_column($quarto, 'painel_posicao'));
                        if ($key !== false) {
                            $checkout_info = $quarto[$key];
                            ?>
                            <td><?= $checkout_info['documento_tipo_nome'] ?></td>
                            <td><?= $checkout_info['documento_numero'] ?></td>
                            <td><?= $checkout_info['documento_status_nome'] ?></td>                           
                            <!-- se não existe checkout -->
                        <?php } else { ?>
                            <td></td>
                            <td><?= $rot_gerocutit ?></td>s
                            <td><?= $rot_gerocutit ?></td>
                        <?php } ?>

                    </tr>
                    <tr>
                        <td><?= $quarto_codigo ?></td>
                        <td><?= $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] ?></td>
                        <td><?= $rot_sercamtit ?></td>
                        <?php
                        $key = array_search('c', array_column($quarto, 'painel_posicao'));
                        //Verifica se tem alguma camareira nesse quarto
                        if ($key !== false) {
                            $camareira_info = $quarto[$key];
                            ?>
                            <td><?= $camareira_info['documento_tipo_nome'] ?></td>
                            <td><?= $camareira_info['documento_numero'] ?></td>
                            <td><?= $camareira_info['documento_status_nome'] ?></td>   
                        <?php } else { ?>
                            <td></td>
                            <td><?= $rot_gerocutit ?></td>
                            <td><?= $rot_gerocutit ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td><?= $quarto_codigo ?></td>
                        <td><?= $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] ?></td>
                        <td><?= $rot_sermantit ?></td>
                        <?php
                        $verifica_manutencao_simples = array_search('m', array_column($quarto, 'painel_posicao'));
                        //Verifica se tem alguma manutenção
                        if ($key !== false) {
                            $manutencao_info = $quarto[$key];
                            ?>
                            <td><?= $manutencao_info['documento_tipo_nome'] ?></td>
                            <td><?= $manutencao_info['documento_numero'] ?></td>
                            <td><?= $manutencao_info['documento_status_nome'] ?></td>   
                        <?php } else { ?>
                            <td></td>
                            <td><?= $rot_gerocutit ?></td>s
                            <td><?= $rot_gerocutit ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td><?= $quarto_codigo ?></td>
                        <td><?= $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] ?></td>
                        <td><?= $rot_gerchitit ?></td>
                        <!-- verifica se existe documento na posição i (checkin) -->
                        <?php
                        $key = array_search('i', array_column($quarto, 'painel_posicao'));
                        if ($key !== false) {
                            /* Verifica o checkin */
                            $verifica_checkin = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_checkin !== false) {
                                $checkin_info = $quarto[$verifica_checkin];
                                ?>
                                <td><?= $checkin_info['documento_tipo_nome'] ?></td>
                                <td><?= $checkin_info['documento_numero'] ?></td>
                                <td><?= $checkin_info['documento_status_nome'] ?></td>
                                <?php
                            }

                            /* Verifica o bloqueio comercial */
                            $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_bloqueio_comercial !== false) {
                                $bloqueio_comercial_info = $quarto[$verifica_bloqueio_comercial];
                                ?>
                                <td><?= $bloqueio_comercial_info['documento_tipo_nome'] ?></td>
                                <td><?= $bloqueio_comercial_info['documento_numero'] ?></td>
                                <td><?= $bloqueio_comercial_info['documento_status_nome'] ?></td>
                                <?php
                            }
                            /* Verifica o manutencao com bloqueio */
                            $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_manutencao_com_bloqueio !== false) {
                                $manutencao_info = $quarto[$verifica_manutencao_com_bloqueio];
                                ?>
                                <td><?= $manutencao_info['documento_tipo_nome'] ?></td>
                                <td><?= $manutencao_info['documento_numero'] ?></td>
                                <td><?= $manutencao_info['documento_status_nome'] ?></td>
                            <?php } ?>
                            <!-- se não existe nada na posição i -->
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
<form action="<?= Router::url('/', true) ?>estadias/estpaiatu/painel.pdf" name="estpaiatu" id="estpaiatu" method="post" target="_blank">
    <div style="display: none">
        <ul id="tipo_quarto">
            <li>
                <input type="checkbox" <?php if ($tipo_quarto_select_all == 1) echo 'checked' ?> id="tipo_quarto_select_all" name="tipo_quarto_select_all" class="típo_quarto_filtro" onchange="if ($(this).prop('checked')) {
                            $('.típo_quarto_filtro').prop('checked', true);
                        } else {
                            $('.típo_quarto_filtro').prop('checked', false);
                        }
                        $('#estpaiatu-submit').click()">
                <label for='tipo_quarto_select_all'><span><strong>Tipo de quarto</strong></span></label>
            </li>
            <?php foreach ($vetor_quarto_tipo as $quarto_tipo) { ?>
                <li>
                    <input type="checkbox" <?php if ($filtro[$quarto_tipo['valor']] == 1) echo 'checked' ?> name="filtro_quarto_tipo_codigo[]" class="típo_quarto_filtro" onchange="$('#estpaiatu-submit').click()" value="<?= $quarto_tipo['valor'] ?>"><span class='nome_filtro'><?= $quarto_tipo['rotulo'] ?></span><span class='filtro1_inner stilo_filtro1'><?= $estpaiatu_totais[$quarto_tipo['valor']] ?></span>
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
                        $('#estpaiatu-submit').click()"><label for='situacao_select_all'><span><strong>Situação</strong></span></label>
            </li>
            <li><input type="checkbox" <?php if ($filtro['vazio'] == 1) echo 'checked' ?> name="filtro_vazio" class="situacao_filtro" onchange="$('#estpaiatu-submit').click()"><span class='nome_filtro'>Vazio</span><span class='filtro1_inner stilo_filtro9'><?= $estpaiatu_totais['vazio'] ?></span></li>
            <li><input type="checkbox" <?php if ($filtro['ocupado'] == 1) echo 'checked' ?> name="filtro_ocupado" class="situacao_filtro" onchange="$('#estpaiatu-submit').click()"><span class='nome_filtro'>Ocupado</span><span class='filtro1_inner stilo_filtro6'><?= $estpaiatu_totais['ocupado'] ?></span></li>
            <li><input type="checkbox" <?php if ($filtro['bloqueado'] == 1) echo 'checked' ?> name="filtro_bloqueado" class="situacao_filtro" onchange="$('#estpaiatu-submit').click()"><span class='nome_filtro'>Bloqueado</span><span class='filtro1_inner stilo_filtro7'><?= $estpaiatu_totais['bloqueado'] ?></span></li>
        </ul>
        <ul id="atividades">
            <li><label for='atividades_select_all'><span><strong>Atividades</strong></span></label></li>
            <li>
                <input type="hidden" id="filtro_check_out" name="filtro_check_out" value="<?= $filtro['check_out'] ?>">

                <div class="drop_po">
                    <?php if ($filtro['check_out'] == 1) { ?>
                        <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:25px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                    <?php } elseif ($filtro['check_out'] == 0) { ?>
                        <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                    <?php } elseif ($filtro['check_out'] == -1) { ?>
                        <a href="#" id="checkout_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:25px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                    <?php } ?>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="$('#filtro_check_out').val('1');
                                $('#estpaiatu-submit').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                        <li><a href="#" onclick="$('#filtro_check_out').val('0');
                                $('#estpaiatu-submit').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                        <li><a href="#" onclick="$('#filtro_check_out').val('-1');
                                $('#estpaiatu-submit').click()" class="sub_link_dropdown" aria-link-parent='checkout_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                    </ul>
                </div>
                <div class="drop_pa"><span class='nome_filtro'>Check-out</span></div>
                <div style="float:right; width: 22%">
                    <span class='filtro1_inner stilo_filtro2'><?= $estpaiatu_totais['check_out'] ?></span>
                </div>
            </li>
            <li>
                <input type="hidden" id="filtro_check_in" name="filtro_check_in" value="<?= $filtro['check_in'] ?>">

                <div  class="drop_po">
                    <?php if ($filtro['check_in'] == 1) { ?>
                        <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:25px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                    <?php } elseif ($filtro['check_in'] == 0) { ?>
                        <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                    <?php } elseif ($filtro['check_in'] == -1) { ?>
                        <a href="#" id="checkin_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:25px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                    <?php } ?>

                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="$('#filtro_check_in').val('1');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                        <li><a href="#" onclick="$('#filtro_check_in').val('0');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                        <li><a href="#" onclick="$('#filtro_check_in').val('-1');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='checkin_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                    </ul>
                </div>
                <div class="drop_pa"><span class='nome_filtro'>Check-in</span></div>
                <div style="float:right; width: 22%">
                    <span class='filtro1_inner stilo_filtro11'><?= $estpaiatu_totais['check_in'] ?></span>
                </div>
            </li>
            <li>
                <input type="hidden" id="filtro_servico" name="filtro_servico" value="<?= $filtro['servico'] ?>">

                <div class="drop_po">
                    <?php if ($filtro['servico'] == 1) { ?>
                        <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#FE0D03"><b style="padding-right:25px">sim </b><b class="caret" style="color:#FE0D03"></b></a>
                    <?php } elseif ($filtro['servico'] == 0) { ?>
                        <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#7E7E7E"><b>tanto faz </b><b class="caret" style="color:#7E7E7E"></b></a>
                    <?php } elseif ($filtro['servico'] == -1) { ?>
                        <a href="#" id="servico_selecionado" data-toggle="dropdown" class="dropdown-toggle" style="color:#1DB25D"><b style="padding-right:25px">não </b><b class="caret" style="color:#1DB25D"></b></a>
                    <?php } ?>                            
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="$('#filtro_servico').val('1');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                        <li><a href="#" onclick="$('#filtro_servico').val('0');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                        <li><a href="#" onclick="$('#filtro_servico').val('-1');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='servico_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                    </ul>
                </div>
                <div class="drop_pa"><span class='nome_filtro'>Serviços</div>
                <div style="float:right; width: 22%">
                    <span class='filtro1_inner stilo_filtro5'><?= $estpaiatu_totais['servico'] ?></span>
                </div>
            </li>
            <li>
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
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='sim' aria-color='#FE0D03' style="color:#FE0D03"><b>sim</b></a></li>
                        <li><a href="#" onclick="$('#filtro_bloqueio').val('0');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='tanto faz' aria-color='#7E7E7E' style="color:#7E7E7E"><b>tanto faz</b></a></li>
                        <li><a href="#" onclick="$('#filtro_bloqueio').val('-1');
                                $('#estpaiatu-submit').click()"  class="sub_link_dropdown" aria-link-parent='bloqueio_selecionado' aria-texto='não' aria-color='#1DB25D' style="color:#1DB25D"><b>não</b></a></li>
                    </ul>
                </div>
                <div class="drop_pa"><span class='nome_filtro'>Bloqueio</div>
                <div style="float:right; width: 22%">
                    <span class='filtro1_inner stilo_filtro10'><?= $estpaiatu_totais['bloqueio'] ?></span>
                </div>
            </li>   
        </ul>
        <input class="submit-button hide"  aria-form-id="estpaiatu" type="submit" id="estpaiatu-submit" />
    </div>
</form>
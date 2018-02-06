<div class="content_inner">
    <?php include('./pdf_header.php'); ?>
    <?php //debug($this); die(); ?>

    <table class="table_cliclipes"  style="font-size:12px">
        <thead>
            <tr>
                <th><?= $rot_resquacod ?></th>
                <th colspan='3'><?= $rot_gersitatu ?></th>
                <th colspan='2'><?= $rot_sercamtit ?></th>
                <th colspan='2'><?= $rot_sermantit ?></th>                
                <th colspan='2'><?= $rot_gerchitit ?></th>
            </tr>
            <tr>
                <th></th>
                <th><?= $rot_gertiptit ?></th>
                <th>Num.</th>
                <th style="white-space: nowrap"><?= $rot_gerchotit ?></th>
                <th><?= $rot_gertiptit ?></th>
                <th>Num.</th>
                <th><?= $rot_gertiptit ?></th>
                <th>Num.</th>
                <th><?= $rot_gertiptit ?></th>
                <th>Num.</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($painel_ocupacao as $quarto_codigo => $quarto) {
                if ($quarto['painel_exibir']) {
                    //debug($quarto);
                    ?>
                    <tr  style="text-align: center">
                        <td  style="text-align: left"><?= $quarto_codigo . '-' . $vetor_quarto_tipo_nome[$quarto['quarto_tipo_codigo']] ?></td>
                        <!-- verifica se existe documento na posição a (ocupação) -->
                        <?php
                        $key = array_search('a', array_column($quarto, 'painel_posicao'));
                        if ($key !== false) {
                            /* Verifica se a ocupação é por motivo de reserva */
                            $verifica_reserva = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_reserva !== false) {
                                $reserva_info = $quarto[$verifica_reserva];

                                $checkout = '';
                                $key = array_search('o', array_column($quarto, 'painel_posicao'));
                                if ($key !== false)
                                    $checkout = 'sim';
                                ?>
                                <td><?= $reserva_info['documento_tipo_nome_curto'] ?></td>
                                <td><?= $reserva_info['documento_numero'] ?></td>
                                <td><?= $checkout ?></td>
                                <?php
                            }

                            /* Verifica se a ocupação é por motivo de manutenção */
                            $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_manutencao_com_bloqueio !== false) {
                                $manutencao_com_bloqueio_info = $quarto[$verifica_manutencao_com_bloqueio];
                                ?>
                                <td><?= $manutencao_com_bloqueio_info['documento_tipo_nome_curto'] ?></td>
                                <td><?= $manutencao_com_bloqueio_info['documento_numero'] ?></td>
                                <td></td>
                                <?php
                            }
                            /* Verifica se a ocupação é por motivo de bloqueio */
                            $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                            if ($verifica_bloqueio_comercial !== false) {
                                $bloqueio_comercial_info = $quarto[$verifica_bloqueio_comercial];
                                ?>
                                <td><?= $bloqueio_comercial_info['documento_tipo_nome_curto'] ?></td>
                                <td><?= $bloqueio_comercial_info['documento_numero'] ?></td>
                                <td></td>
                            <?php } ?>
                            <!-- se não existe ocupação -->
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <?php
                        }
                        $key = array_search('c', array_column($quarto, 'painel_posicao'));
                        //Verifica se tem alguma camareira nesse quarto
                        if ($key !== false) {
                            $camareira_info = $quarto[$key];
                            ?>

                            <td><?= $camareira_info['documento_tipo_nome_curto'] ?></td>
                            <td><?= $camareira_info['documento_numero'] ?></td>
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <?php
                        }

                        $key = array_search('m', array_column($quarto, 'painel_posicao'));
                        if ($key !== false) {
                            $manutencao_info = $quarto[$key];
                            ?>

                            <td><?= $manutencao_info['documento_tipo_nome_curto'] ?></td>
                            <td><?= $manutencao_info['documento_numero'] ?></td>
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <?php
                        }

                        $pos_i = array_search('i', array_column($quarto, 'painel_posicao'));
                        if ($pos_i !== false) {
                            /* Verifica o checkin */
                            $verifica_checkin = array_search('rs', array_column($quarto, 'documento_tipo_codigo'));
                            /* Verifica o bloqueio comercial */
                            $verifica_bloqueio_comercial = array_search('bc', array_column($quarto, 'documento_tipo_codigo'));
                            /* Verifica o manutencao com bloqueio */
                            $verifica_manutencao_com_bloqueio = array_search('mb', array_column($quarto, 'documento_tipo_codigo'));

                            if ($verifica_checkin !== false) {
                                $checkin_info = $quarto[$verifica_checkin];
                                ?>
                                <td><?= $checkin_info['documento_tipo_nome_curto'] ?></td>
                                <td><?= $checkin_info['documento_numero'] ?></td>
                                <?php
                            } else if ($verifica_bloqueio_comercial !== false) {
                                $bloqueio_comercial_info = $quarto[$verifica_bloqueio_comercial];
                                ?>
                                <td><?= $bloqueio_comercial_info['documento_tipo_nome_curto'] ?></td>
                                <td><?= $bloqueio_comercial_info['documento_numero'] ?></td>
                                <?php
                            }if ($verifica_manutencao_com_bloqueio !== false) {
                                $manutencao_info = $quarto[$verifica_manutencao_com_bloqueio];
                                ?>
                                <td><?= $manutencao_info['documento_tipo_nome_curto'] ?></td>
                                <td><?= $manutencao_info['documento_numero'] ?></td>
                                <?php
                            }
                        } else {
                            ?>
                            <td></td>
                            <td></td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
<!--
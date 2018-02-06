<?php

use Cake\Network\Session;
use App\Model\Entity\Geral;

$geral = new Geral();
$session = new Session();
$empresa_dados = $session->read("empresa_selecionada");
?>
<div id="conresexi_pdf" style="display:none" >
    <?php
    if (isset($cabecalho_conta)) {
        $total = 0;
        ?>
        <div style="margin-top:20px">
            <!-- Dados da empresa -->
            <div style="padding: 8px;text-align: center">
                <div><?php echo $this->Html->image($logo_empresa, array('width' => '90px')); ?></div>
                <div><?= $empresa_dados['empresa_razao_social'] ?></div>
                <div><?= $empresa_dados['logradouro'] ?></div>
                <div><?= $empresa_dados['cidade'] . ',' . $empresa_dados['estado'] . ', ' . $empresa_dados['pais_nome'] . ',' . $empresa_dados['cep'] ?></div>
            </div>

            <?php
            foreach ($contas_quarto_item as $quarto_item => $conta_quarto) {
                $total = 0;
                $indice_cabecalho_conta = array_search($quarto_item, array_column($cabecalho_conta, 'quarto_item'));
                ?>
                <div id="conta_pdf_quarto_item_<?= $quarto_item ?>" style="display:none">
                    <!-- Dados do cliente e da reserva -->
                    <div style="padding: 8px;  border-top: 1px solid #e5e5e5; margin-top:10px ">
                        <div class="row" style="margin-top:10px">
                            <div style='width:50%; float:left'>
                                <div><?= $cabecalho_conta[$indice_cabecalho_conta]['nome'] . ' ' . $cabecalho_conta[$indice_cabecalho_conta]['sobrenome'] ?></div>
                                <div>
                                    <?php if ($cabecalho_conta[$indice_cabecalho_conta]['cel_numero'] != null && $cabecalho_conta[$indice_cabecalho_conta]['cel_numero'] != "") { ?>
                                        <?= $cabecalho_conta[$indice_cabecalho_conta]['cel_numero'] ?> 
                                    <?php } ?>
                                    <?php if ($cabecalho_conta[$indice_cabecalho_conta]['tel_numero'] != null && $cabecalho_conta[$indice_cabecalho_conta]['tel_numero'] != "") { ?>
                                        <?= $cabecalho_conta[$indice_cabecalho_conta]['tel_numero'] ?>,
                                    <?php } ?>
                                    <?= $cabecalho_conta[$indice_cabecalho_conta]['email'] ?>
                                </div>
                                <div><?= $cabecalho_conta[$indice_cabecalho_conta]['residencia_logradouro'] ?></div>
                                <div><?php
                                    if ($cabecalho_conta[$indice_cabecalho_conta]['residencia_cidade'] != null && $cabecalho_conta[$indice_cabecalho_conta]['residencia_cidade'] != "")
                                        echo $cabecalho_conta[$indice_cabecalho_conta]['residencia_cidade'] . ',';
                                    if ($cabecalho_conta[$indice_cabecalho_conta]['residencia_estado'] != null && $cabecalho_conta[$indice_cabecalho_conta]['residencia_estado'] != "")
                                        echo $cabecalho_conta[$indice_cabecalho_conta]['residencia_estado'] . ',';
                                    if ($cabecalho_conta[$indice_cabecalho_conta]['residencia_pais'] != null && $cabecalho_conta[$indice_cabecalho_conta]['residencia_pais'] != "")
                                        echo $cabecalho_conta[$indice_cabecalho_conta]['residencia_pais'] . ',';
                                    if ($cabecalho_conta[$indice_cabecalho_conta]['residencia_cep'] != null && $cabecalho_conta[$indice_cabecalho_conta]['residencia_cep'] != "")
                                        echo $cabecalho_conta[$indice_cabecalho_conta]['residencia_cep'];
                                    ?></div>
                            </div>
                            <div style='width:50%; float:left'>
                                <div><label style="margin-bottom: 0px" ><?= $rot_resdocnum ?>: <?= $cabecalho_conta[$indice_cabecalho_conta]['documento_numero'] ?></label></div>
                                <div><label style="margin-bottom: 0px" ><?= $rot_resquacod ?>: <?= $cabecalho_conta[$indice_cabecalho_conta]['quarto_codigo'] ?></label></div>
                                <div><label style="margin-bottom: 0px; letter-spacing: 0.1px" ><?= $rot_resquatip ?>: <?= $cabecalho_conta[$indice_cabecalho_conta]['quarto_tipo_nome'] ?></label></div>
                                <div><label style="margin-bottom: 0px" ><?= $rot_clihostit ?>: <?= $cabecalho_conta[$indice_cabecalho_conta]['adulto_quantidade'] ?> / <?= $cabecalho_conta[$indice_cabecalho_conta]['crianca_quantidade'] ?></label></div>
                                <div><label style="margin-bottom: 0px" ><?= $rot_resentdat ?>: <?= date('d/m/Y', strtotime($cabecalho_conta[$indice_cabecalho_conta]['inicial_data'])) ?></label></div>
                                <div><label style="margin-bottom: 0px" ><?= $rot_ressaidat ?>: <?= date('d/m/Y', strtotime($cabecalho_conta[$indice_cabecalho_conta]['final_data'])) ?> </label></div>
                            </div>
                        </div>
                    </div>

                    <strong><?= $rot_resquacod ?> <?= $quarto_item ?></strong>
                    <table class="table_cliclipes" style="width:100%;  border-top: 1px solid #e5e5e5; margin-top:5px; padding-top: 10px">
                        <div>&nbsp;&nbsp;&nbsp;</div>
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center"><?= $rot_geritetit ?></th>  
                                <th style="width: 15%; text-align: center"><?= $rot_gerdattit ?></th>
                                <th style="width: 35%; text-align: center"><?= $rot_gerdestit ?></th>
                                <th style="width: 15%; text-align: center"><?= $rot_conproqtd ?></th>
                                <th style="width: 20%; text-align: center"><?= $rot_conpretot ?> (<?= $geral->germoeatr() ?>)</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($conta_quarto as $conta) {
                            //Checa se esse item é referenciado por algum
                            $referenciado = array_search($conta['conta_item'], array_column($conta_quarto, 'referenciado_item'));
                            $referenciado_item_virtual = false;
                            if ($tela == 'resdoccan')
                                $referenciado_item_virtual = array_search($conta['conta_item'], array_column($itens_virtuais, 'referenciado_item'));
                            
                            if ($conta['pre_autorizacao'] == 0) {
                                ?>
                                <tr class="<?php if ($conta['referenciado_item'] != null || $referenciado !== false || $referenciado_item_virtual  !== false) echo 'item_estornado' ?>"">
                                    <td  style="width: 10%; text-align: center"><?= $conta['conta_item'] ?></td>
                                    <td style="width: 15%; text-align: center"><?= date('d/m/Y', strtotime($conta['data'])) ?></td>
                                    <td style="width: 35%; text-align: center"><?= $conta['produto_codigo'] ?></td>
                                    <td style="width: 15%; text-align: center"><?= $geral->gersepatr($conta['produto_qtd']) ?></td>
                                    <td style="width: 20%; text-align: center"><?php
                                        echo $geral->gersepatr($conta['total_valor'])
                                        ?></td>
                                </tr>
                                <?php
                                $total = round($total + ($conta['total_valor']), 2);
                            }
                            ?>


                        <?php } ?>
                        <tr>
                            <td style="text-align: right" colspan="4"><?php echo "" . $rot_convalpag . ""; ?></td>
                            <td style="text-align: center"><?php echo "" . $geral->gersepatr($total) . ""; ?>
                            </td>
                        </tr>
                    </table>                        
                </div>
            <?php } ?>
            <div style="margin-top: 40px">
                <?= $rot_gercamass ?>: ___________________________________________________________
            </div>
        </div>
    <?php } ?>
</div>
<?php

use App\Utility\Util;
use App\Model\Entity\Estadia;
use App\Model\Entity\Reserva;
use Cake\Routing\Router;

$estadia = new Estadia();
$reserva = new Reserva();

if (count($pesquisa_reservas['results']) > 0) {
    ?>
    <div class="form-group">
        <input type="hidden" name="dinamic_doc_num" id="dinamic_doc_num" value="0"/>
        <input type="hidden" name="dinamic_emp_cod" id="dinamic_emp_cod" value="0"/> 	
        <input type="hidden" name="label_quarto" id="label_quarto" value="<?= $rot_resquacod ?>"/>
        <input type="hidden" name="label_nome" id="label_nome" value="<?= $rot_cliprinom ?>"/>

        <div class="col-md-12 col-sm-12">
            <div class="col-md-12 col-sm-12">
                <table class="table_cliclipes">
                    <thead>
                        <tr class="tabres_cabecalho">
                            <?php if ($multiple_select) { ?>
                                <th style="width:1%">&nbsp;</th>
                            <?php } ?>  

                            <?php if (!$limited_actions) { ?>
                                <th style="width:9%">&nbsp;</th>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_numero', 'aria_form_id' => 'resdocpes', 'label' => $rot_resdocnum, 'propriedade' => 'width:5%']); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'inicial_data', 'aria_form_id' => 'resdocpes', 'label' => $rot_resentdat, 'propriedade' => 'width:6%']); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'final_data', 'aria_form_id' => 'resdocpes', 'label' => $rot_ressaidat, 'propriedade' => 'width:6%']); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_tipo_nome', 'aria_form_id' => 'resdocpes', 'label' => $rot_resquatip, 'propriedade' => 'width:12%']); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'quarto_codigo', 'aria_form_id' => 'resdocpes', 'label' => $rot_resquacod, 'propriedade' => 'width:5%']); ?>
                                <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'documento_status_nome', 'aria_form_id' => 'resdocpes', 'label' => $rot_resdocsta, 'propriedade' => 'width:6%']); ?>
                                <?php if ($limite_confirmacao) { ?>
                                    <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'confirmacao_limite', 'aria_form_id' => 'resdocpes', 'label' => $rot_reslimcnf, 'propriedade' => 'width:10%']); ?>
                                <?php } ?>
                            <?php } else if ($limited_actions) { ?>
                                <th><?= $rot_resdocnum ?></th>
                                <th><?= $rot_resentdat ?></th>
                                <th><?= $rot_ressaidat ?></th>
                                <th><?= $rot_resquatip ?></th>
                                <th><?= $rot_resquacod ?></th>                              
                                <th><?= $rot_resdocsta ?></th>
                                <?php if ($limite_confirmacao) { ?>
                                    <th style="width:10%"><?= $rot_reslimcnf ?></th>
                                <?php } ?>
                            <?php } ?>
                            <th style="width:23%"><?= $rot_clihostit ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $indice = 0;
                        if (isset($pesquisa_reservas['results'])) {
                            $quarto_itens_por_reserva = array_count_values(array_column($pesquisa_reservas['results'], 'documento_numero'));

                            //debug($pesquisa_reservas['results']);
                            foreach ($pesquisa_reservas['results'] as $value) {
                                //monta uma lista de hospedes para o cheekin
                                $codigos_hospedes = "";
                                $nomes_hospedes = "";
                                $sobrenomes_hospedes = "";
                                $emails_hospedes = "";
                                $cpfs_hospedes = "";
                                $doc_tipos_hospedes = "";
                                $doc_nums_hospedes = "";
                                $total_hospedes = 0;
                                if (isset($value['hospedes'])) {
                                    for ($p = 0; $p < sizeof($value['hospedes']); $p++) {
                                        $codigos_hospedes .= $value['hospedes'][$p]['cliente_codigo'] . "|";
                                        $nomes_hospedes .= $value['hospedes'][$p]['nome'] . "|";
                                        $sobrenomes_hospedes .= $value['hospedes'][$p]['sobrenome'] . "|";
                                        $emails_hospedes .= $value['hospedes'][$p]['email'] . "|";
                                        $cpfs_hospedes .= $value['hospedes'][$p]['cpf'] . "|";
                                        $doc_tipos_hospedes .= $value['hospedes'][$p]['cliente_documento_tipo'] . "|";
                                        $doc_nums_hospedes .= $value['hospedes'][$p]['cliente_documento_numero'] . "|";
                                        $total_hospedes++;
                                    }
                                }
                                ?>
                                <tr>
                            <input type="hidden" id="day_diff_<?= $indice ?>" value="<?= $value['day_diff'] ?>" />
                            <input type="hidden" id="hour_diff_<?= $indice ?>" value="<?= $value['hour_diff'] ?>" />
                            <?php
                            $quartos_por_datas_vetor = explode("|", $value['quartos_por_datas']);
                            $datas_por_quartos = array();
                            $quartos_tipos_alocados = array();
                            foreach ($quartos_por_datas_vetor as $quartos_por_data_item) {
                                $quartos_por_data_item = trim($quartos_por_data_item);
                                if ($quartos_por_data_item != '' && sizeof(explode(" ", $quartos_por_data_item)) >= 3 && strlen($quartos_por_data_item) > 10) {
                                    $quarto_codigo = explode(" ", $quartos_por_data_item)[0];
                                    $quarto_tipo_codigo = explode(" ", $quartos_por_data_item)[1];
                                    $quartos_tipos_alocados[$quarto_codigo] = $quarto_tipo_codigo;
                                    $data = explode(" ", $quartos_por_data_item)[2];
                                    if (!array_key_exists($quarto_codigo, $datas_por_quartos))
                                        $datas_por_quartos[$quarto_codigo][] = $data;
                                    else {
                                        $verifica_data_existe = array_search($data, $datas_por_quartos[$quarto_codigo]);
                                        //Verifica se esta ocupado
                                        if ($verifica_data_existe === false) {
                                            $datas_por_quartos[$quarto_codigo][] = $data;
                                        }
                                    }
                                }
                            }
                            //Ordena as datas internas
                            foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                                usort($datas_por_quartos[$key], array('App\Utility\Util', 'date_sort'));
                            }
                            //Ordena os quartos de acordo com suas datas
                            uasort($datas_por_quartos, function($a, $b) {
                                return new DateTime($a[0]) <=> new DateTime($b[0]);
                            });


                            //Formata as datas
                            foreach ($datas_por_quartos as $key => $datas_quarto_item) {
                                for ($i = 0; $i < sizeof($datas_por_quartos[$key]); $i++)
                                    $datas_por_quartos[$key][$i] = Util::convertDataDMY($datas_por_quartos[$key][$i]);
                            }


                            $string_alocacao = json_encode($datas_por_quartos);
                            $string_quartos_tipos_alocados = json_encode($quartos_tipos_alocados);
                            $quartos_alocados = array_keys($datas_por_quartos);
                            ?>
                            <?php if ($multiple_select) { ?>
                                <td>
                                    <input type="checkbox" class="check_doc" name="documentos[]" value="<?= $value['documento_numero'] . "-" . $value['quarto_item'] . "-" . $value['quarto_status_codigo'] . "-" . Util::convertDataDMY($value['cancelamento_limite'], 'd/m/Y H:i:s') . "-" . $value['cancelamento_valor'] ?>" />                
                                </td>
                            <?php } ?>
                            <?php if (!$limited_actions) { ?>
                                <td nowrap>

                                    <?php
                                    echo $this->element('reserva/acoes_reserva', ['quarto_item' => $value, 'string_alocacao' => $string_alocacao, 'string_quartos_tipos_alocados' => $string_quartos_tipos_alocados]);
                                    ?>
                                </td>
                            <?php } ?>

                            <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>
                                <input type="hidden" name="resdocexi" id="resdoc_<?= $value['documento_numero'] ?>" value="0">
                                <?= $value['documento_numero'] . " - " . $value['quarto_item'] ?>
                            </td>
                            <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;<?= Util::convertDataDMY($value['inicial_data']) ?></td>
                            <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;<?= Util::convertDataDMY($value['final_data']) ?></td>
                            <td class="resdocmod" nowrap aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;<?= $value['quarto_tipo_nome'] ?></td>
                            <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;


                                <a class="dialogo exibe_quartos_multiplos">
                                    <!-- Faz o implode com os valores de quartos ja alocados, diferentes de * -->
                                    <?= implode(',', array_diff($quartos_alocados, ["*"])) ?>
                                    <div class="dialogo_inner_multiplos_quartos">
                                        <?php
                                        foreach ($datas_por_quartos as $quarto => $datas) {
                                            ?>
                                            <div><b><?= $quarto ?></b> - <?= implode(', ', $datas) ?></div>
                                        <?php }
                                        ?>
                                    </div>
                                </a>
                            </td>
                            <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;<?= $value['documento_status_nome'] ?></td>
                            <?php
                            if ($limite_confirmacao) {
                                if ($value['documento_status_codigo'] != 1 || ($value['hour_diff'] == null && $value['hour_diff'] == null)) {
                                    ?>
                                    <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;</td>
                                <?php } else if ($value['day_diff'] < 0 || $value['hour_diff'] < 0) { ?> 
                                    <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'  style="color:red">&nbsp;<?php echo $value['day_diff'] . 'd ' . $value['hour_diff'] . 'h'; ?></td>
                                <?php } else if ($value['day_diff'] == 0) { ?> 
                                    <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;<?php echo $value['hour_diff'] . 'h'; ?></td>
                                <?php } else { ?>  
                                    <td class="resdocmod" aria-documento-numero="<?= $value["documento_numero"] ?>" aria-quarto-item='<?= $value["quarto_item"] ?>'>&nbsp;<?php echo $value['day_diff'] . 'd ' . $value['hour_diff'] . 'h'; ?></td>
                                <?php } ?>  
                            <?php } ?>  
                            <td style="width:115px"><?php
                                if (array_key_exists('hospedes', $value)) {
                                    if (sizeof($value['hospedes']) == 1)
                                        echo $value['hospedes'][0]['nome'] . ' ' . $value['hospedes'][0]['sobrenome'];
                                    else {
                                        ?>
                                        <div class="center">
                                            <?php echo $value['hospedes'][0]['nome'] . ' ' . $value['hospedes'][0]['sobrenome']; ?> 
                                            <span id="exibe_hospedes_<?= $indice ?>" class="ui-icon inline ui-icon-plus exibe_hospedes_tooltip pull-right" style="vertical-align: bottom"></span>
                                        </div>

                                        <div class="table-tooltip" style="display:none;"  id="exibe_hospedes_<?= $indice ?>_tooltip">
                                            <table class="table_tooltip" style="width:100px; background-color: #fff; margin-top: 10px!important;
                                                   margin-left: 46px!important">
                                                   <?php for ($p = 1; $p < sizeof($value['hospedes']); $p++) { ?>
                                                    <tr>     
                                                        <td><?= $value['hospedes'][$p]['nome'] . ' ' . $value['hospedes'][$p]['sobrenome'] ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </div>
                                        <?php
                                    }
                                }
                                ?></td>

                            </tr>

                            <?php
                            $indice++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php if (!$limited_actions) { ?>

        <div class="col-md-12 col-sm-12 mt1">
            <div class="col-md-2 col-sm-4 ui-dialog-btn-close">
                <input class="<?= $ace_resexpsel ?> form-control btn-default" type="button" name="btn_resexpsel" id="btn_resexpsel" value="<?= $rot_resexpbot ?>" onclick="javascript: resexpsel();">&nbsp;
            </div>
            <div class="col-md-2 col-sm-4 ui-dialog-btn-close">
                <input class="<?= $ace_resdoccan ?> form-control btn-secundary reserva_cancelar" type="button" name="btn_resexpcan" id="btn_resexpcan" value="<?= $rot_rescncbot ?>">&nbsp;
            </div>
            <div class="col-md-2 col-sm-3 ui-dialog-btn-close">
                <button type="button" class="form-control btn-default pad_btnPes rescriini"><?= $rot_gertitcri ?></button>
            </div>

            <div class="col-md-4 col-sm-12" style="float: right; " >
                <?php
                if (!$limited_actions) {
                    echo $paginacao;
                }
                ?>


            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="form-control col-md-12">
        Nenhuma reserva encontrada
    </div>
    <?php
}
?>


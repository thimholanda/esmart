<h1 class="titulo_pag">
    
    
</h1>
<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>

<?php if (isset($retorno) && $retorno != '') { ?>
    <div class="content_inner">
        <div style="text-align: center; margin-top: 100px;">
            <h4><b><?= substr(substr($retorno, 0, -3), 7) ?></b></h4>
        </div>
    </div>
<?php } else { ?>
    <div class="content_inner">
        <form id="estchionl" name="estchionl" action="<?= $path ?>/estadias/estchionl/?ea=<?= $empresa_codigo ?>&eac=<?= $chave ?>&eao=<?= $documento_numero ?>" method="post">
            <input type="hidden" name="fnrhs_editadas" id="fnrhs_editadas"  value="<?= $fnrhs_editadas ?? '' ?>" />
            <input type="hidden" name="total_hospedes" id="total_hospedes"  value="<?= $total_hospedes ?? '0' ?>" />
            <input type="hidden" name="aba_atual" id="aba_atual"  value='1' />
            <input type="hidden" name="empresa_codigo" id="empresa_codigo"  value="<?= $empresa_codigo ?>" />
            <input type="hidden" name="empresa_grupo_codigo" id="empresa_grupo_codigo"  value="<?= $empresa_grupo_codigo ?? 0 ?>" />
            <input type="hidden" name="documento_numero" id="documento_numero"  value="<?= $documento_numero ?>" />
            <input type="hidden" name="contratante_codigo" id="contratante_codigo"  value="<?= $contratante_codigo ?>" />
            <input type="hidden" name="fnrhs[]" id="fnrhs"  value="<?= $fnrhs_editadas ?? '' ?>" />

            <?php foreach ($dados_fnrh as $fnrh) { ?>
                <input type="hidden" name="clicadcod_<?= $fnrh['fnrh_codigo'] ?>" id="clicadcod_<?= $fnrh['fnrh_codigo'] ?>"  value="<?= $fnrh['cliente_codigo'] ?? 0 ?>" />
                <input type="hidden" name="clicadalt_<?= $fnrh['fnrh_codigo'] ?>" id="clicadalt_<?= $fnrh['fnrh_codigo'] ?>"  value="0" />
                <input type="hidden" name="clicadalt_nome_<?= $fnrh['fnrh_codigo'] ?>" id="clicadalt_nome_<?= $fnrh['fnrh_codigo'] ?>"  value="0" />
            <?php } ?>
            <div id="tabs">
                <ul>
                    <?php
                    $contador_abas = 1;
                    foreach ($dados_fnrh as $fnrh) {
                        ?>
                        <li <?php if ($contador_abas == 1) echo "class='visitada'" ?>><a onclick="
                                        $('#' + this.id).parent().addClass('visitada');
                                        document.getElementById('aba_atual').value = parseInt(<?= $contador_abas ?>);"" 
                                                                                         href="#tab-<?= $fnrh['fnrh_codigo'] ?>"><?= $fnrh['snnomecompleto'] ?></a></li>
                            <?php
                            $contador_abas++;
                        }
                        ?>
                </ul>

                <?php
                $indice = 0;
                foreach ($dados_fnrh as $fnrh) {
                    $fnrh_codigo = $fnrh['fnrh_codigo'];
                    ?>
                    <div id="tab-<?= $fnrh['fnrh_codigo'] ?>">
                        <input   type="hidden" name="fnrh_codigo_<?= $fnrh_codigo ?>" id="fnrh_codigo_<?= $fnrh_codigo ?>"  value="<?= $fnrh_codigo ?? 0 ?>"  />
                        <div class="row" id="ficha_embratur_<?= $fnrh_codigo ?>" style="width:100%; margin-bottom:20px">
                            <div class="float:left" style="float:left;width:100%; margin:auto">

                                <table  class="ficha-fnrh ficha-fnrh-mod">
                                    <tbody>
                                        <tr>
                                            <td><b><?= $rot_gerclidad ?></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><hr style="margin-top: 0px;margin-bottom: 7px;border: 0;border-top: 3px solid #eee;" /></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="width: 30%"><?= $rot_estnomcom ?>
                                                <input   class="form-control" type="text" name="snnomecompleto_<?= $fnrh_codigo ?>" id="snnomecompleto_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnomecompleto'] ?? '' ?>" onchange="document.getElementById('clicadalt_nome_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>
                                            <td colspan="2"  style="padding: 3px 10px 10px 4px !important; width: 27%">

                                                <div style="width: 100%"><?= $rot_gercelnum ?></div>
                                                <div style="float:left; font-size: 10px">
                                                    <div style="font-size: 10px">
                                                        <div style="width:25%;float:left">
                                                            <?= $rot_clicelddi ?><br/>
                                                            <select class="form-control"   style="width:90%; float:left" name="sncelularddi_<?= $fnrh_codigo ?>" id="sncelularddi_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> <option value=""></option> <?php
                                                                foreach ($dominio_ddi_lista as $item) {
                                                                    if ($item["valor"] == $fnrh['sncelularddi']) {
                                                                        ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php } else {
                                                                        ?> <option value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php
                                                                    }
                                                                }
                                                                ?> </select>
                                                        </div>
                                                        <div style="width:55%;float:left">
                                                            <?= $rot_clicelnum ?><br/>
                                                            <input   style="width:90%; float:left"  class="form-control" type="text" name="sncelularnum_<?= $fnrh_codigo ?>" id="sncelularnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncelularnum'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td colspan="2"  style="padding: 3px 10px 10px 4px !important; width: 45%">

                                                <div style="width: 100%"><?= $rot_gertelnum ?></div>
                                                <div style=" float:left; font-size: 10px">
                                                    <div style="font-size: 10px">
                                                        <div style="width:25%;float:left">
                                                            <?= $rot_clitelddi ?><br/>
                                                            <select class="form-control"   style="width:90%; float:left" name="sntelefoneddi_<?= $fnrh_codigo ?>" id="sntelefoneddi_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> <option value=""></option> <?php
                                                                foreach ($dominio_ddi_lista as $item) {
                                                                    if ($item["valor"] == $fnrh['sntelefoneddi']) {
                                                                        ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php } else {
                                                                        ?> <option value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php
                                                                    }
                                                                }
                                                                ?> </select>
                                                        </div>
                                                        <div style="width:55%;float:left">
                                                            <?= $rot_clitelnum ?><br/>
                                                            <input   style="width:90%; float:left"  class="form-control" type="text" name="sntelefonenum_<?= $fnrh_codigo ?>" id="sntelefonenum_<?= $fnrh_codigo ?>" value="<?= $fnrh['sntelefonenum'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                        </div>
                                                    </div>
                                                </div>         

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table  class="ficha-fnrh ficha-fnrh-mod">
                                    <tbody>
                                        <tr>
                                            <td style="width: 35%"><?= $rot_clicadocu ?>
                                                <input   class="form-control" type="text" name="snocupacao_<?= $fnrh_codigo ?>" id="snocupacao_<?= $fnrh_codigo ?>" value="<?= $fnrh['snocupacao'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>
                                            <td style="width:35%" colspan="2"><?= $rot_clicadnac ?>
                                                <select   class="form-control" name="snnacionalidade_<?= $fnrh_codigo ?>" id="snnacionalidade_<?= $fnrh_codigo ?>"> <option value=""></option> <?php
                                                    foreach ($dominio_nacionalidades_lista as $item) {
                                                        if ($item["rotulo"] == $fnrh['snnacionalidade']) {
                                                            ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                                            ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select>
                                            </td>

                                            <td style="width: 17%"><?= $rot_clinacdat ?>
                                                <input   class="form-control data" type="text" name="sndtnascimento_<?= $fnrh_codigo ?>" id="sndtnascimento_<?= $fnrh_codigo ?>" value="<?= $fnrh['sndtnascimento'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>
                                            <td style="padding: 3px 10px 0px 4px !important; width: 15%">
                                                <div style="width: 100%"><?= $rot_clicadsex ?>
                                                    <select    class="form-control data" name="snsexo_<?= $fnrh_codigo ?>" id="snsexo_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> 
                                                        <option <?php if ($fnrh['snsexo'] == 'M') echo " selected='selected' " ?> value="M">Masculino</option>
                                                        <option <?php if ($fnrh['snsexo'] == 'F') echo " selected='selected' " ?> value="F">Feminimo</option> 
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table  class="ficha-fnrh ficha-fnrh-mod">
                                    <tbody>
                                        <tr>
                                            <td style="padding: 3px 10px 1px 4px !important; width: 50%">
                                                <div style="width: 100%"><?= $rot_clidocide ?></div>
                                                <div style="width: 50%; float:left; font-size: 10px">
                                                    <div style="font-size: 10px">
                                                        <div style="width:50%;float:left">
                                                            <?= $rot_gertiptit ?><br/>
                                                            <input   style="width:90%; float:left" class="form-control" type="text" name="sntipdoc_<?= $fnrh_codigo ?>" id="sntipdoc_<?= $fnrh_codigo ?>" value="<?= $fnrh['sntipdoc'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                        </div>
                                                        <div style="width:50%;float:left">
                                                            <?= $rot_gernumtit ?><br/>
                                                            <input   style="width:90%; float:left"  class="form-control" type="text" name="snnumdoc_<?= $fnrh_codigo ?>" id="snnumdoc_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumdoc'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="width: 50%; float:left; font-size: 10px">
                                                    <div style="font-size: 10px"><?= $rot_estorgexp ?>
                                                        <input   class="form-control" type="text" name="snorgexp_<?= $fnrh_codigo ?>" id="snorgexp_<?= $fnrh_codigo ?>" value="<?= $fnrh['snorgexp'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 18%"><?= $rot_clicpfnum ?>
                                                <input   class="form-control cpfcnpj"  maxlength="18"  type="text" name="snnumcpf_<?= $fnrh_codigo ?>" id="snnumcpf_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumcpf'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>

                                            <td style="width:55%" colspan="2"><?= $rot_clicadema ?>
                                                <input   class="form-control" type="text" name="snemail_<?= $fnrh_codigo ?>" id="snemail_<?= $fnrh_codigo ?>" value="<?= $fnrh['snemail'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table  class="ficha-fnrh ficha-fnrh-mod" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <td><?= $rot_clicadpai ?>
                                                <select class="form-control"  name="snpaisres_<?= $fnrh_codigo ?>" id="snpaisres_<?= $fnrh_codigo ?>" 
                                                        onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1';
                                                                        gerestdet('snestadores_<?= $fnrh_codigo ?>', this.value)" > <option value=""></option> <?php
                                                            foreach ($dominio_paises_lista as $item) {
                                                                if ($item["rotulo"] == $fnrh['snpaisres']) {
                                                                    ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else { ?> 
                                                            <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select>
                                            </td>

                                            <td>
                                                <div style=" float:left; width:100%"><?= $rot_clicadcep ?>
                                                    <input   class="form-control cep" maxlength="9" type="text" name="sncep_<?= $fnrh_codigo ?>" id="sncep_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncep'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"  onblur="gerenddet(this.value, 'snresidencia_<?= $fnrh_codigo ?>', '', 'sncidaderes_<?= $fnrh_codigo ?>', 'snestadores_<?= $fnrh_codigo ?>', 'snpaisres_<?= $fnrh_codigo ?>')" />
                                                </div>
                                            </td>

                                            <td><?= $rot_estresper ?>
                                                <input   class="form-control" type="text" name="snresidencia_<?= $fnrh_codigo ?>" id="snresidencia_<?= $fnrh_codigo ?>" value="<?= $fnrh['snresidencia'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>

                                            <td><?= $rot_clicadest ?><br/>
                                                <select  style="min-width:200px" class="form-control" name="snestadores_<?= $fnrh_codigo ?>" id="snestadores_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> 
                                                    <option value=""></option> <?php
                                                    foreach ($dominio_estados_lista as $item) {
                                                        if ($item["estado_codigo"] == $fnrh['snestadores']) {
                                                            ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                                        <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select>

                                            </td>

                                            <td><?= $rot_clicadcid ?>
                                                <input   style=" float:left" aria-estado_referencia="snestadores_<?= $fnrh_codigo ?>" class="cidade_autocomplete form-control" type="text" name="sncidaderes_<?= $fnrh_codigo ?>" id="sncidaderes_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncidaderes'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>

                                <table  class="ficha-fnrh ficha-fnrh-mod">
                                    <tbody>
                                        <tr>
                                            <td><b><?= $rot_estviadad ?></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><hr style="margin-top: 0px;margin-bottom: 7px;border: 0;border-top: 3px solid #eee;" /></td>
                                        </tr>
                                        <?php
                                        if ($indice > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="5"><input type="checkbox" class="repete_dados_anteriores" onchange="estrepdad(this, <?= $fnrh_codigo_hospede_1 ?>, <?= $fnrh_codigo ?>)" /> <i><?= $rot_estfnrrep ?></i></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="2"><?= $rot_estultpro ?><br/>
                                            <div style="float:left; width:33%">
                                                    <label><?= $rot_clicadpai ?></label>
                                                    <select class="form-control"  name="bgstdscpais_<?= $fnrh_codigo ?>" id="bgstdscpais_<?= $fnrh_codigo ?>" onchange="gerestdet('bgstdscestado_<?= $fnrh_codigo ?>', this.value)"> 
                                                        <option value=""></option> <?php
                                                        foreach ($dominio_paises_lista as $item) {
                                                            if ($item["rotulo"] == $fnrh['bgstdscpais']) {
                                                                ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                            }
                                                        }
                                                        ?> </select> </div>

                                                        <div style="float:left; width:33%">
                                                    <label><?= $rot_clicadest ?></label>
                                                    <select  style="max-width:170px; min-width: 170px" class="form-control" 
                                                             name="bgstdscestado_<?= $fnrh_codigo ?>" id="bgstdscestado_<?= $fnrh_codigo ?>"> 
                                                        <option value=""></option> <?php
                                                        foreach ($dominio_estados_lista as $item) {
                                                            if ($item["estado_codigo"] == $fnrh['bgstdscestado']) {
                                                                ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                                            <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                                            }
                                                        }
                                                        ?> </select>
                                                </div>

                                                <div style="float:left; width:33%">
                                                    <label><?= $rot_clicadcid ?></label>
                                                    <input  style="width:90%"  aria-estado_referencia="bgstdscestado_<?= $fnrh_codigo ?>" class="cidade_autocomplete form-control" type="text" name="bgstdsccidade_<?= $fnrh_codigo ?>" id="bgstdsccidade_<?= $fnrh_codigo ?>" value="<?= $fnrh['bgstdsccidade'] ?? '' ?>" />
                                                </div>
                                                
                                                
                                            </td>
                                            <td colspan="2"><?= $rot_estprodes ?><br/>

                                            <div style="float:left; width:33%">
                                                    <label><?= $rot_clicadpai ?></label>

                                                    <select class="form-control"  name="bgstdscpaisdest_<?= $fnrh_codigo ?>" id="bgstdscpaisdest_<?= $fnrh_codigo ?>" onchange="gerestdet('bgstdscestadodest_<?= $fnrh_codigo ?>', this.value)"> <option value=""></option> <?php
                                                        foreach ($dominio_paises_lista as $item) {
                                                            if ($item["rotulo"] == $fnrh['bgstdscpaisdest']) {
                                                                ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                            }
                                                        }
                                                        ?> </select>
                                                </div>

                                                 <div style="float:left; width:33%">
                                                    <label><?= $rot_clicadest ?></label>

                                                    <select  style="max-width:170px; min-width: 170px" class="form-control" name="bgstdscestadodest_<?= $fnrh_codigo ?>" id="bgstdscestadodest_<?= $fnrh_codigo ?>"> 
                                                        <option value=""></option> <?php
                                                        foreach ($dominio_estados_lista as $item) {
                                                            if ($item["estado_codigo"] == $fnrh['bgstdscestadodest']) {
                                                                ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                                            <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                                            }
                                                        }
                                                        ?> </select> 
                                                </div>

                                                <div style="float:left; width:33%">
                                                    <label><?= $rot_clicadcid ?></label>
                                                    <input  style="width:90%"   aria-estado_referencia="bgstdscestadodest_" class="cidade_autocomplete form-control" type="text" name="bgstdsccidadedest_<?= $fnrh_codigo ?>" id="bgstdsccidadedest_<?= $fnrh_codigo ?>" value="<?= $fnrh['bgstdsccidadedest'] ?? '' ?>" />
                                                </div>
                                               
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table  class="ficha-fnrh ficha-fnrh-mod">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 3px 10px 1px 4px !important">
                                                <div style="width: 100%"><?= $rot_estmotvia ?>
                                                    <select   class="form-control" name="snmotvia_<?= $fnrh_codigo ?>" id="snmotvia_<?= $fnrh_codigo ?>"> 
                                                        <option value=""></option> <?php
                                                        foreach ($dominio_viagem_motivos_lista as $item) {
                                                            if ($item["valor"] == $fnrh['snmotvia']) {
                                                                ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                                                ?> <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                            }
                                                        }
                                                        ?> 
                                                    </select>
                                                </div>

                                            </td>
                                            <td colspan="2" style="padding: 3px 10px 1px 4px !important">
                                                <div style="width: 100%"><?= $rot_estmeitra ?>
                                                    <select   class="form-control" name="sntiptran_<?= $fnrh_codigo ?>" id="sntiptran_<?= $fnrh_codigo ?>"> 
                                                        <option value=""></option> <?php
                                                        foreach ($dominio_transporte_meios_lista as $item) {
                                                            if ($item["valor"] == $fnrh['sntiptran']) {
                                                                ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                                                ?> <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                            }
                                                        }
                                                        ?> 
                                                    </select>
                                                </div>

                                            </td>
                                            <td colspan="2"><?= $rot_estplaaut ?>
                                                <input   class="form-control" type="text" name="snplacaveiculo_<?= $fnrh_codigo ?>" id="snplacaveiculo_<?= $fnrh_codigo ?>" value="<?= $fnrh['snplacaveiculo'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="ficha-fnrh ficha-fnrh-mod" style="margin-top:5px; width:100%; float: left">
                                    <tr>
                                        <!--<td style="width:15%"><?= $rot_estfnrtit ?>
                                            <input   class="form-control" type="text" name="snnum_<?= $fnrh_codigo ?>" id="snnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnum'] ?? '' ?>" />
                                        </td>-->
                                        <td style="padding: 3px 10px 1px 4px !important">
                                            <div style="width: 100%"><?= $rot_resentdat ?><br/>
                                                <div style="float:left; width:50%">
                                                    <?= $rot_gerdattit ?>
                                                    <input readonly="readonly" style="width:90%" class="form-control data" type="text" name="snprevent_data_<?= $fnrh_codigo ?>" id="snprevent_data_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevent_data'] ?? '' ?>" />
                                                </div>    
                                                <div style="float:left; width:50%">
                                                    <?= $rot_gerhortit ?>
                                                    <input  readonly="readonly" style="width:90%" class="form-control" type="text" name="snprevent_hora_<?= $fnrh_codigo ?>" id="snprevent_hora_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevent_hora'] ?? '' ?>" />
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $rot_ressaidat ?><br/>
                                            <div style="float:left; width:50%">
                                                <?= $rot_gerdattit ?>
                                                <input readonly="readonly" style="width:90%" class="form-control data" type="text" name="snprevsai_data_<?= $fnrh_codigo ?>" id="snprevsai_data_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevsai_data'] ?? '' ?>" />
                                            </div>    
                                            <div style="float:left; width:50%">
                                                <?= $rot_gerhortit ?>
                                                <input readonly="readonly"  style="width:90%" class="form-control" type="text" name="snprevsai_hora_<?= $fnrh_codigo ?>" id="snprevsai_hora_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevsai_hora'] ?? '' ?>" />
                                            </div>    
                                        </td>

                                        <td><?= $rot_estresaco ?>
                                            <input readonly="readonly" class="form-control" type="text" name="snnumhosp_<?= $fnrh_codigo ?>" id="snnumhosp_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumhosp'] ?? '' ?>" />
                                        </td>

                                        <td><?= $rot_resquacod ?>
                                            <input readonly="readonly" class="form-control" type="text" name="snuhnum_<?= $fnrh_codigo ?>" id="snuhnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['snuhnum'] ?? '' ?>" />
                                        </td>
                                    </tr>
                                </table>                                                               
                            </div>
                        </div>      
                    </div>
                    <?php
                    $indice++;
                }
                ?>
            </div>
            <div class="row" style="margin-top:20px">
                <input style="float:left" class="btn-primary" type="button" onclick="estchionl2();" value="<?= $rot_gersaltod ?>" >
                <input style="float:left; margin-left: 10px" class="btn-primary" type="button" onclick="gernavaba(-1);"
                       value="<< <?= $rot_geranttit ?>" >
                <input style="float:left; margin-left: 10px" class="btn-primary" type="button" onclick="gernavaba(+1);" value="<?= $rot_gerprobot ?> >>" >
            </div>
        </form>
    </div>
<?php } ?>
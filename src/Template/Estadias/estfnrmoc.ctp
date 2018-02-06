<?php

use Cake\Routing\Router;

$path = Router::url('/', true);

?>
<h1 class="titulo_pag">
    
    
</h1>

<div class="content_inner">
    <form id="estfnrmoc" name="estfnrmoc" action="estfnrmoc" method="post">
        <input type="hidden" name="fnrhs_editadas" id="fnrhs_editadas"  value="<?= $fnrhs_editadas ?? '' ?>" />
        <input type="hidden" name="fnrhs[]" id="fnrhs"  value="<?= $fnrhs_editadas ?? '' ?>" />
        <?php foreach ($dados_fnrh as $fnrh) { ?>
            <input type="hidden" name="clicadcod_<?= $fnrh['fnrh_codigo'] ?>" id="clicadcod_<?= $fnrh['fnrh_codigo'] ?>"  value="<?= $fnrh['cliente_codigo'] ?? 0 ?>" />
            <input type="hidden" name="clicadalt_<?= $fnrh['fnrh_codigo'] ?>" id="clicadalt_<?= $fnrh['fnrh_codigo'] ?>"  value="0" />
        <?php } ?>
        <div id="tabs">
            <ul>
                <?php foreach ($dados_fnrh as $fnrh) { ?>
                    <li><a href="#tab-<?= $fnrh['fnrh_codigo'] ?>"><?= $fnrh['snnomecompleto'] ?></a></li>
                <?php } ?>
            </ul>

            <?php
            foreach ($dados_fnrh as $fnrh) {
                $fnrh_codigo = $fnrh['fnrh_codigo'];
                ?>
                <div id="tab-<?= $fnrh['fnrh_codigo'] ?>">
                    <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> type="hidden" name="fnrh_codigo_<?= $fnrh_codigo ?>" id="fnrh_codigo_<?= $fnrh_codigo ?>"  value="<?= $fnrh_codigo ?? 0 ?>"  />
                    <div class="row" id="ficha_embratur_<?= $fnrh_codigo ?>" style="width:100%; margin-bottom:20px">
                        <div class="float:left" style="float:left;width:90%; margin:auto">

                            <table  class="ficha-fnrh ficha-fnrh-mod">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="width: 30%"><?= $rot_estnomcom ?>
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snnomecompleto_<?= $fnrh_codigo ?>" id="snnomecompleto_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnomecompleto'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>
                                        <td colspan="2"  style="padding: 3px 10px 10px 4px !important; width: 30%">

                                            <div style="width: 100%"><?= $rot_gercelnum ?></div>
                                            <div style="float:left; font-size: 10px">
                                                <div style="font-size: 10px">
                                                    <div style="width:25%;float:left">
                                                        <?= $rot_clicelddi ?><br/>
                                                        <select class="form-control" <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:90%; float:left" name="sncelularddi_<?= $fnrh_codigo ?>" id="sncelularddi_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> <option value=""></option> <?php
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
                                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:90%; float:left"  class="form-control" type="text" name="sncelularnum_<?= $fnrh_codigo ?>" id="sncelularnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncelularnum'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="2"  style="padding: 3px 10px 10px 4px !important; width: 30%">

                                            <div style="width: 100%"><?= $rot_gertelnum ?></div>
                                            <div style=" float:left; font-size: 10px">
                                                <div style="font-size: 10px">
                                                    <div style="width:25%;float:left">
                                                        <?= $rot_clitelddi ?><br/>
                                                        <select class="form-control" <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:90%; float:left" name="sntelefoneddi_<?= $fnrh_codigo ?>" id="sntelefoneddi_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> <option value=""></option> <?php
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
                                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:90%; float:left"  class="form-control" type="text" name="sntelefonenum_<?= $fnrh_codigo ?>" id="sntelefonenum_<?= $fnrh_codigo ?>" value="<?= $fnrh['sntelefonenum'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
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
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snocupacao_<?= $fnrh_codigo ?>" id="snocupacao_<?= $fnrh_codigo ?>" value="<?= $fnrh['snocupacao'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>
                                        <td style="width:35%" colspan="2"><?= $rot_clicadnac ?>
                                            <select <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" name="snnacionalidade_<?= $fnrh_codigo ?>" id="snnacionalidade_<?= $fnrh_codigo ?>"> <option value=""></option> <?php
                                                foreach ($dominio_nacionalidades_lista as $item) {
                                                    if ($item["rotulo"] == $fnrh['snnacionalidade']) {
                                                        ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                                        ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                    }
                                                }
                                                ?> </select>
                                        </td>

                                        <td style="width: 17%"><?= $rot_clinacdat ?>
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control data" type="text" name="sndtnascimento_<?= $fnrh_codigo ?>" id="sndtnascimento_<?= $fnrh_codigo ?>" value="<?= $fnrh['sndtnascimento'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>
                                        <td style="padding: 3px 10px 0px 4px !important; width: 15%">
                                            <div style="width: 100%"><?= $rot_clicadsex ?>
                                                <select <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?>  class="form-control" name="snsexo_<?= $fnrh_codigo ?>" id="snsexo_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> 
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
                                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:90%; float:left" class="form-control" type="text" name="sntipdoc_<?= $fnrh_codigo ?>" id="sntipdoc_<?= $fnrh_codigo ?>" value="<?= $fnrh['sntipdoc'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                    </div>
                                                    <div style="width:50%;float:left">
                                                        <?= $rot_gernumtit ?><br/>
                                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:90%; float:left"  class="form-control" type="text" name="snnumdoc_<?= $fnrh_codigo ?>" id="snnumdoc_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumdoc'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="width: 50%; float:left; font-size: 10px">
                                                <div style="font-size: 10px"><?= $rot_estorgexp ?>
                                                    <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snorgexp_<?= $fnrh_codigo ?>" id="snorgexp_<?= $fnrh_codigo ?>" value="<?= $fnrh['snorgexp'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 18%"><?= $rot_clicpfnum ?>
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control cpfcnpj" maxlength="18"  type="text" name="snnumcpf_<?= $fnrh_codigo ?>" id="snnumcpf_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumcpf'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>

                                        <td style="width:55%" colspan="2"><?= $rot_clicadema ?>
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snemail_<?= $fnrh_codigo ?>" id="snemail_<?= $fnrh_codigo ?>" value="<?= $fnrh['snemail'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table  class="ficha-fnrh ficha-fnrh-mod" style="width:100%">
                                <tbody>
                                    <tr>
                                    <td><?= $rot_clicadpai ?>
                                            <select class="form-control" <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> name="snpaisres_<?= $fnrh_codigo ?>" id="snpaisres_<?= $fnrh_codigo ?>" 
                                                    onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'; gerestdet('snestadores_<?= $fnrh_codigo ?>', this.value)"> <option value=""></option> <?php
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
                                                <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control cep" maxlength="9" type="text" name="sncep_<?= $fnrh_codigo ?>" id="sncep_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncep'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"  onblur="gerenddet(this.value, 'snresidencia_<?= $fnrh_codigo ?>', '', 'sncidaderes_<?= $fnrh_codigo ?>', 'snestadores_<?= $fnrh_codigo ?>','snpaisres_<?= $fnrh_codigo ?>')" />
                                            </div>
                                        </td>

                                        <td><?= $rot_estresper ?>
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snresidencia_<?= $fnrh_codigo ?>" id="snresidencia_<?= $fnrh_codigo ?>" value="<?= $fnrh['snresidencia'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>
                                         <td><?= $rot_clicadest ?><br/>
                                            <select <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="min-width:200px" class="form-control" name="snestadores_<?= $fnrh_codigo ?>" id="snestadores_<?= $fnrh_codigo ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'"> 
                                                <option value=""></option> <?php
                                                foreach ($fnrh['dominio_estados_lista_snestadores'] as $item) {
                                                    if ($item["estado_codigo"] == $fnrh['snestadores']) {
                                                        ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                                    <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                                    }
                                                }
                                                ?> </select>

                                        </td>

                                        <td><?= $rot_clicadcid ?>
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style=" float:left" aria-estado_referencia="snestadores_<?= $fnrh_codigo ?>" aria-pais_referencia="snpaisres_<?= $fnrh_codigo ?>" class="cidade_autocomplete form-control" type="text" name="sncidaderes_<?= $fnrh_codigo ?>" id="sncidaderes_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncidaderes'] ?? '' ?>" onchange="document.getElementById('clicadalt_<?= $fnrh_codigo ?>').value = '1'" />
                                        </td>
                                                                   </tr>
                                </tbody>
                            </table>

                            <table  class="ficha-fnrh ficha-fnrh-mod">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><?= $rot_estultpro ?><br/>
                                        <div style="float:left; width:33%">
                                                <label><?= $rot_clicadpai ?></label>
                                                <select class="form-control" <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> name="bgstdscpais_<?= $fnrh_codigo ?>" id="bgstdscpais_<?= $fnrh_codigo ?>" onchange="gerestdet('bgstdscestado_<?= $fnrh_codigo ?>', this.value)"> 
                                                    <option value=""></option> <?php
                                                    foreach ($dominio_paises_lista as $item) {
                                                        if ($item["rotulo"] == $fnrh['bgstdscpais']) {
                                                            ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select> </div>

                                                    <div style="float:left; width:33%">
                                                <label><?= $rot_clicadest ?></label>
                                                <select <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="max-width:170px; min-width: 170px" class="form-control" 
                                                                                                                    name="bgstdscestado_<?= $fnrh_codigo ?>" id="bgstdscestado_<?= $fnrh_codigo ?>"> 
                                                    <option value=""></option> <?php
                                                    foreach ($fnrh['dominio_estados_lista_bgstdscestado'] as $item) {
                                                        if ($item["estado_codigo"] == $fnrh['bgstdscestado']) {
                                                            ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                                        <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select>
                                            </div>

                                            <div style="float:left; width:33%">
                                                <label><?= $rot_clicadcid ?></label>
                                                <input <?= $disabled ?> style="width:90%" <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> aria-estado_referencia="bgstdscestado_<?= $fnrh_codigo ?>" aria-pais_referencia="bgstdscpais_<?= $fnrh_codigo ?>" class="cidade_autocomplete form-control" type="text" name="bgstdsccidade_<?= $fnrh_codigo ?>" id="bgstdsccidade_<?= $fnrh_codigo ?>" value="<?= $fnrh['bgstdsccidade'] ?? '' ?>" />
                                            </div>
                                            
                                            
                                        </td>
                                        <td colspan="2"><?= $rot_estprodes ?><br/>
                                        <div style="float:left; width:33%">
                                                <label><?= $rot_clicadpai ?></label>

                                                <select class="form-control" <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> name="bgstdscpaisdest_<?= $fnrh_codigo ?>" id="bgstdscpaisdest_<?= $fnrh_codigo ?>"  onchange="gerestdet('bgstdscestadodest_<?= $fnrh_codigo ?>', this.value)"> <option value=""></option> <?php
                                                    foreach ($dominio_paises_lista as $item) {
                                                        if ($item["rotulo"] == $fnrh['bgstdscpaisdest']) {
                                                            ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select>
                                            </div>

                                             <div style="float:left; width:33%">
                                                <label><?= $rot_clicadest ?></label>

                                                <select <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="max-width:170px; min-width: 170px" class="form-control" name="bgstdscestadodest_<?= $fnrh_codigo ?>" id="bgstdscestadodest_<?= $fnrh_codigo ?>"> 
                                                    <option value=""></option> <?php
                                                    foreach ($fnrh['dominio_estados_lista_bgstdscestadodest'] as $item) {
                                                        if ($item["estado_codigo"] == $fnrh['bgstdscestadodest']) {
                                                            ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                                        <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                                        }
                                                    }
                                                    ?> </select> 
                                            </div>

                                            <div style="float:left; width:33%">
                                                <label><?= $rot_clicadcid ?></label>
                                                <input <?= $disabled ?> style="width:90%"  <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> aria-estado_referencia="bgstdscestadodest_<?= $fnrh_codigo ?>" aria-pais_referencia="bgstdscpaisdest_<?= $fnrh_codigo ?>" class="cidade_autocomplete form-control" type="text" name="bgstdsccidadedest_<?= $fnrh_codigo ?>" id="bgstdsccidadedest_<?= $fnrh_codigo ?>" value="<?= $fnrh['bgstdsccidadedest'] ?? '' ?>" />
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
                                                <select <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" name="snmotvia_<?= $fnrh_codigo ?>" id="snmotvia_<?= $fnrh_codigo ?>"> 
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
                                                <select <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" name="sntiptran_<?= $fnrh_codigo ?>" id="sntiptran_<?= $fnrh_codigo ?>"> 
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
                                            <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snplacaveiculo_<?= $fnrh_codigo ?>" id="snplacaveiculo_<?= $fnrh_codigo ?>" value="<?= $fnrh['snplacaveiculo'] ?? '' ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="ficha-fnrh ficha-fnrh-mod" style="margin-top:5px; width:100%; float: left">
                                <tr>
                                    <td style="width:15%"><?= $rot_estfnrtit ?>
                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snnum_<?= $fnrh_codigo ?>" id="snnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnum'] ?? '' ?>" />
                                    </td>
                                    <td style="padding: 3px 10px 1px 4px !important">
                                        <div style="width: 100%"><?= $rot_resentdat ?><br/>
                                            <div style="float:left; width:50%">
                                                <?= $rot_gerdattit ?>
                                                <input <?= $disabled ?> style="width:90%" <?php if ($fnrh['editaveis'] != 'todos' && $fnrh['editaveis'] != 'nenhum_exceto_entrada' && $fnrh['editaveis'] != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control data" type="text" name="snprevent_data_<?= $fnrh_codigo ?>" id="snprevent_data_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevent_data'] ?? '' ?>" />
                                            </div>    
                                            <div style="float:left; width:50%">
                                                <?= $rot_gerhortit ?>
                                                <input <?= $disabled ?> style="width:90%"  <?php if ($fnrh['editaveis'] != 'todos' && $fnrh['editaveis'] != 'nenhum_exceto_entrada' && $fnrh['editaveis'] != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control" type="text" name="snprevent_hora_<?= $fnrh_codigo ?>" id="snprevent_hora_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevent_hora'] ?? '' ?>" />
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $rot_ressaidat ?><br/>
                                        <div style="float:left; width:50%">
                                            <?= $rot_gerdattit ?>
                                            <input <?= $disabled ?> style="width:90%" <?php if ($fnrh['editaveis'] != 'todos' && $fnrh['editaveis'] != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control data" type="text" name="snprevsai_data_<?= $fnrh_codigo ?>" id="snprevsai_data_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevsai_data'] ?? '' ?>" />
                                        </div>    
                                        <div style="float:left; width:50%">
                                            <?= $rot_gerhortit ?>
                                            <input <?= $disabled ?> style="width:90%" <?php if ($fnrh['editaveis'] != 'todos' && $fnrh['editaveis'] != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control" type="text" name="snprevsai_hora_<?= $fnrh_codigo ?>" id="snprevsai_hora_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevsai_hora'] ?? '' ?>" />
                                        </div>    
                                    </td>

                                    <td><?= $rot_estresaco ?>
                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="snnumhosp_<?= $fnrh_codigo ?>" id="snnumhosp_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumhosp'] ?? '' ?>" />
                                    </td>

                                    <td><?= $rot_resquacod ?>
                                        <input <?= $disabled ?> <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="snuhnum_<?= $fnrh_codigo ?>" id="snuhnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['snuhnum'] ?? '' ?>" />
                                    </td>
                                </tr>
                            </table>



                                                                    <!--<table  class="ficha-fnrh ficha-fnrh-mod">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="width: 25%"><?= $rot_clicadocu ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snocupacao_<?= $fnrh_codigo ?>" id="snocupacao_<?= $fnrh_codigo ?>" value="<?= $fnrh['snocupacao'] ?? '' ?>" />
                                                                                </td>
                                                                                <td style="width: 25%"><?= $rot_gerempcod ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="gerempcod_<?= $fnrh_codigo ?>" id="gerempcod_<?= $fnrh_codigo ?>" value="<?= $fnrh['gerempcod'] ?? '' ?>" />
                                                                                </td>
                                                                                <td style="width: 50%" colspan="2"><?= $rot_clicadend ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="" id="" value="" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table  class="ficha-fnrh ficha-fnrh-mod">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2"  style="width: 60%"><?= $rot_clicadbai ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="" id="" value="" />
                                                                                </td>
                                                                                <td colspan="2" style="padding: 3px 10px 0px 4px !important; width: 40%">
                                                                                    <div style=" float:left"><?= $rot_clicadcep ?>
                                                                                        <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="" id="" value="" />
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
                                                                                        <div style="font-size: 10px"><?= $rot_estnumtip ?><br/>
                                                                                            <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:48%; float:left" class="form-control" type="text" name="sntipdoc_<?= $fnrh_codigo ?>" id="sntipdoc_<?= $fnrh_codigo ?>" value="<?= $fnrh['sntipdoc'] ?? '' ?>" />
                                                                                            <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:48%; float:left"  class="form-control" type="text" name="snnumdoc_<?= $fnrh_codigo ?>" id="snnumdoc_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumdoc'] ?? '' ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div style="width: 50%; float:left; font-size: 10px">
                                                                                        <div style="font-size: 10px"><?= $rot_estorgexp ?>
                                                                                            <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snorgexp_<?= $fnrh_codigo ?>" id="snorgexp_<?= $fnrh_codigo ?>" value="<?= $fnrh['snorgexp'] ?? '' ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td style="width: 18%"><?= $rot_clicpfnum ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snnumcpf_<?= $fnrh_codigo ?>" id="snnumcpf_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumcpf'] ?? '' ?>" />
                                                                                </td>
                                                                                <td style="width: 17%"><?= $rot_clinacdat ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control data" type="text" name="sndtnascimento_<?= $fnrh_codigo ?>" id="sndtnascimento_<?= $fnrh_codigo ?>" value="<?= $fnrh['sndtnascimento'] ?? '' ?>" />
                                                                                </td>

                                                                                <td style="padding: 3px 10px 0px 4px !important; width: 15%">
                                                                                    <div style="width: 100%"><?= $rot_clicadsex ?>
                                                                                        <select <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" name="snsexo_<?= $fnrh_codigo ?>" id="snsexo_<?= $fnrh_codigo ?>"> 
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
                                                                                <td style="width:45%" colspan="2"><?= $rot_clicadnac ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snnacionalidade_<?= $fnrh_codigo ?>" id="snnacionalidade_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnacionalidade'] ?? '' ?>" />
                                                                                </td>
                                                                                <td style="width:55%" colspan="2"><?= $rot_clicadema ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snemail_<?= $fnrh_codigo ?>" id="snemail_<?= $fnrh_codigo ?>" value="<?= $fnrh['snemail'] ?? '' ?>" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table  class="ficha-fnrh ficha-fnrh-mod">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2"><?= $rot_estresper ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snresidencia_<?= $fnrh_codigo ?>" id="snresidencia_<?= $fnrh_codigo ?>" value="<?= $fnrh['snresidencia'] ?? '' ?>" />
                                                                                </td>
                                                                                <td><?= $rot_clicadcid ?> <?= $rot_clicadest ?><br/>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:70%; float:left" class="form-control" type="text" name="sncidaderes_<?= $fnrh_codigo ?>" id="sncidaderes_<?= $fnrh_codigo ?>" value="<?= $fnrh['sncidaderes'] ?? '' ?>" />
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> style="width:25%; float:left" class="form-control" type="text" name="snestadores_<?= $fnrh_codigo ?>" id="snestadores_<?= $fnrh_codigo ?>" value="<?= $fnrh['snestadores'] ?? '' ?>" />
                                                                                </td>
                                                                                <td><?= $rot_clicadpai ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snpaisres_<?= $fnrh_codigo ?>" id="snpaisres_<?= $fnrh_codigo ?>" value="<?= $fnrh['snpaisres'] ?? '' ?>" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table  class="ficha-fnrh ficha-fnrh-mod">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2"><?= $rot_estultpro ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="bgstdsccidade_<?= $fnrh_codigo ?>" id="bgstdsccidade_<?= $fnrh_codigo ?>" value="<?= $fnrh['bgstdsccidade'] ?? '' ?>" />
                                                                                </td>
                                                                                <td colspan="2"><?= $rot_estprodes ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="bgstdsccidadedest_<?= $fnrh_codigo ?>" id="bgstdsccidadedest_<?= $fnrh_codigo ?>" value="<?= $fnrh['bgstdsccidadedest'] ?? '' ?>" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table  class="ficha-fnrh ficha-fnrh-mod">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2" style="padding: 3px 10px 1px 4px !important">
                                                                                    <div style="width: 100%"><?= $rot_estmotvia ?>
                                                                                        <select <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" name="snmotvia_<?= $fnrh_codigo ?>" id="snmotvia_<?= $fnrh_codigo ?>"> 
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
                                                                                        <select <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" name="sntiptran_<?= $fnrh_codigo ?>" id="sntiptran_<?= $fnrh_codigo ?>"> 
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
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control" type="text" name="estplaaut_<?= $fnrh_codigo ?>" id="estplaaut_<?= $fnrh_codigo ?>" value="" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="ficha-fnrh ficha-fnrh-mod" style="margin-top:5px; width:85%; float: left">
                                                                        <tr>
                                                                            <td style="padding: 3px 10px 1px 4px !important">
                                                                                <div style="width: 100%"><?= $rot_resentdat ?>
                                                                                    <input <?php if ($fnrh['editaveis'] != 'todos' && $fnrh['editaveis'] != 'nenhum_exceto_entrada' && $fnrh['editaveis'] != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control data" type="text" name="snprevent_<?= $fnrh_codigo ?>" id="snprevent_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevent'] ?? '' ?>" />
                                                                                </div>
                                                                                <div style="width: 50%; float:left; font-size: 10px">

                                                                                </div>

                                                                            </td>
                                                                            <td>14 <?= $rot_ressaidat ?>
                                                                                <input <?php if ($fnrh['editaveis'] != 'todos' && $fnrh['editaveis'] != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control" type="text" name="snprevsai_<?= $fnrh_codigo ?>" id="snprevsai_<?= $fnrh_codigo ?>" value="<?= $fnrh['snprevsai'] ?? '' ?>" />
                                                                            </td>
                                                                            <td>15 <?= $rot_estresaco ?>
                                                                                <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> class="form-control data" type="text" name="snnumhosp_<?= $fnrh_codigo ?>" id="snnumhosp_<?= $fnrh_codigo ?>" value="<?= $fnrh['snnumhosp'] ?? '' ?>" />
                                                                            </td>
                                                                        </tr>
                                                                    </table>

                                                                    <table class="ficha-fnrh ficha-fnrh-mod" style="margin-top:5px; width:85%; float: left">
                                                                        <tr>
                                                                            <td style="width:15%"><?= $rot_estfnrtit ?>
                                                                                <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?> readonly="" class="form-control" type="text" name="fnrh_codigo_<?= $fnrh_codigo ?>" id="fnrh_codigo_<?= $fnrh_codigo ?>" value="<?= $fnrh_codigo ?? '' ?>" />
                                                                            </td>
                                                                            <td style="width:17%"><?= $rot_estregtit ?>
                                                                                <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="estregtit_<?= $fnrh_codigo ?>" id="estregtit_<?= $fnrh_codigo ?>" value="" />
                                                                            </td>

                                                                        </tr>

                                                                    </table>

                                                                    <table style="    width: 150px;    height: 108px;   border-right: 1px solid #ccc;">
                                                                        <tr>
                                                                            <td style="padding: 10px;vertical-align: top;">16 <?= $rot_resquacod ?>
                                                                                <input <?php if ($fnrh['editaveis'] != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="snuhnum_<?= $fnrh_codigo ?>" id="snuhnum_<?= $fnrh_codigo ?>" value="<?= $fnrh['snuhnum'] ?? '' ?>" />
                                                                            </td>
                                                                        </tr>
                                                                    </table>-->
                        </div>
                    </div>      

                    <div class="row" style="background-color: #EEEEEE; padding:10px; margin-bottom: 7px">
                        <div id="info_envio">
                            <b><?= $rot_resdocsta ?></b> :  <?= $fnrh['info_envio']['status_fnrh'] ?? 'Não enviada' ?>
                            ;
                            <b><?= $rot_estmsgtit ?></b> : <?= $fnrh['info_envio']['mensagem_fnrh'] ?? '' ?>
                            ;
                            <b><?= $rot_gerdattit ?></b> : <?= $fnrh['info_envio']['data_fnrh'] ?? '' ?>

                        </div>

                    </div>

                </div>
            <?php } ?>

        </div>

        <div class="row" style="margin-top:20px">
            <input  style="float:left; margin-right:10px" type="button"  value="<?= $rot_gerdesbot ?>" onclick="$('.voltar').click()">
            <input style="float:left; margin-right:10px" class="btn-primary" onclick="document.estfnrmoc.action = '<?= $path ?>/ajax/ajaxestfnrcen';
                    estfnrmod_todos();" type="submit" value="<?= $rot_gerenvtod ?>" >
            <input <?= $disabled ?>  style="float:left" class="btn-primary" type="button" name="resmodbtn" onclick="estfnrmod_todos()"  value="<?= $rot_gersaltod ?>" >
        </div>
    </form>
</div>

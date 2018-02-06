<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
?>
<h1 class="titulo_pag">


</h1>

<div class="content_inner">
    <form id="estfnrmod" name="estfnrmod" action="<?= Router::url('/', true) ?>estadias/estfnrmod" method="post">
        <input type="hidden" name="fnrh_codigo" id="fnrh_codigo"  value="<?= $fnrh_codigo ?? 0 ?>" />
        <input type="hidden" name="clicadalt" id="clicadalt"  value="0" />
        <input type="hidden" name="clicadcod" id="clicadcod"  value="<?= $cliente_codigo ?? 0 ?>" />
        <input type="hidden" name="pagina_referencia" id="pagina_referencia" value="<?= $pagina_referencia ?>" />

        <div class="row" id="ficha_embratur" style="width:100%; margin-bottom:20px">
            <div class="float:left" style="float:left;width:90%; margin:auto">

                <table  class="ficha-fnrh ficha-fnrh-mod">
                    <tbody>
                        <tr>
                            <td colspan="2" style="width: 40%"><?= $rot_estnomcom ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snnomecompleto" id="snnomecompleto" value="<?= $snnomecompleto ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>
                            <td colspan="2"  style="padding: 3px 10px 10px 4px !important; width: 30%">

                                <div style="width: 100%"><?= $rot_gercelnum ?></div>
                                <div style="float:left; font-size: 10px">
                                    <div style="font-size: 10px">
                                        <div style="width:25%;float:left">
                                                <?= $rot_clicelddi ?><br/>
                                            <select class="form-control" <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:90%; float:left" name="sncelularddi" id="sncelularddi" onchange="document.getElementById('clicadalt').value = '1'"> <option value=""></option> <?php
                                                foreach ($dominio_ddi_lista as $item) {
                                                    if ($item["valor"] == $sncelularddi) {
                                                        ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php } else {
                                                        ?> <option value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php
                                                    }
                                                }
                                                ?> </select>
                                        </div>
                                        <div style="width:55%;float:left">
<?= $rot_clicelnum ?><br/>
                                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:90%; float:left"  class="form-control" type="text" name="sncelularnum" id="sncelularnum" value="<?= $sncelularnum ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
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
                                            <select class="form-control" <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:90%; float:left" name="sntelefoneddi" id="sntelefoneddi" onchange="document.getElementById('clicadalt').value = '1'"> <option value=""></option> <?php
                                                foreach ($dominio_ddi_lista as $item) {
                                                    if ($item["valor"] == $sntelefoneddi) {
                                                        ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php } else {
                                                        ?> <option value="<?= $item["valor"] ?>"><?= $item["valor"] ?> </option> <?php
                                                    }
                                                }
                                                ?> </select>
                                        </div>
                                        <div style="width:55%;float:left">
<?= $rot_clitelnum ?><br/>
                                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:90%; float:left"  class="form-control" type="text" name="sntelefonenum" id="sntelefonenum" value="<?= $sntelefonenum ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                                        </div>
                                    </div>
                                </div>         

                            </td>
                        </tr>
                    </tbody>
                </table>
                <table  class ="ficha-fnrh ficha-fnrh-mod">
                    <tbody>
                        <tr>
                            <td style="width: 35%"><?= $rot_clicadocu ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snocupacao" id="snocupacao" value="<?= $snocupacao ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>
                            <td style="width:35%" colspan="2"><?= $rot_clicadnac ?>
                                <select <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" name="snnacionalidade" id="snnacionalidade"> <option value=""></option> <?php
                                    foreach ($dominio_nacionalidades_lista as $item) {
                                        if ($item["rotulo"] == $snnacionalidade) {
                                            ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                            ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                        }
                                    }
                                    ?> </select>
                            </td>

                            <td style="width: 17%"><?= $rot_clinacdat ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control data" type="text" name="sndtnascimento" id="sndtnascimento" value="<?= $sndtnascimento ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>
                            <td style="padding: 3px 10px 0px 4px !important; width: 15%">
                                <div style="width: 100%"><?= $rot_clicadsex ?>
                                    <select <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?>  class="form-control" name="snsexo" id="snsexo" onchange="document.getElementById('clicadalt').value = '1'"> 
                                        <option <?php if ($snsexo == 'M') echo " selected='selected' " ?> value="M">Masculino</option>
                                        <option <?php if ($snsexo == 'F') echo " selected='selected' " ?> value="F">Feminimo</option> 
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
                                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:90%; float:left" class="form-control" type="text" name="sntipdoc" id="sntipdoc" value="<?= $sntipdoc ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                                        </div>
                                        <div style="width:50%;float:left">
<?= $rot_gernumtit ?><br/>
                                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:90%; float:left"  class="form-control" type="text" name="snnumdoc" id="snnumdoc" value="<?= $snnumdoc ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 50%; float:left; font-size: 10px">
                                    <div style="font-size: 10px"><?= $rot_estorgexp ?>
                                        <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snorgexp" id="snorgexp" value="<?= $snorgexp ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                                    </div>
                                </div>
                            </td>
                            <td style="width: 18%"><?= $rot_clicpfnum ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control cpfcnpj" maxlength="18"  type="text" name="snnumcpf" id="snnumcpf" value="<?= $snnumcpf ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>

                            <td style="width:55%" colspan="2"><?= $rot_clicadema ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snemail" id="snemail" value="<?= $snemail ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>


                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh ficha-fnrh-mod" style="width:100%">
                    <tbody>
                        <tr>
                            <td><?= $rot_clicadpai ?>
                                <select class="form-control" <?php if ($editaveis != 'todos') echo 'readonly' ?> name="snpaisres" id="snpaisres" onchange="document.getElementById('clicadalt').value = '1';
                                        gerestdet('snestadores', this.value)"> <option value=""></option> <?php
                                            foreach ($dominio_paises_lista as $item) {
                                                if ($item["rotulo"] == $snpaisres) {
                                                    ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option>
                                        <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                        }
                                    }
                                    ?> </select>
                            </td>
                            <td>
                                <div style=" float:left; width:100%"><?= $rot_clicadcep ?>
                                    <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control cep" maxlength="9" type="text" name="sncep" id="sncep" value="<?= $sncep ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" onblur="gerenddet(this.value, 'snresidencia', '', 'sncidaderes', 'snestadores', 'snpaisres')" />
                                </div>
                            </td>

                            <td><?= $rot_estresper ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snresidencia" id="snresidencia" value="<?= $snresidencia ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>
                            <td><?= $rot_clicadest ?><br/>

                                <select  <?php if ($editaveis != 'todos') echo 'readonly' ?> style="min-width:200px" class="form-control" name="snestadores" id="snestadores" onchange="document.getElementById('clicadalt').value = '1'"> 
                                    <option value=""></option> <?php
                                    foreach ($dominio_estados_lista_snestadores as $item) {
                                        if ($item["estado_codigo"] == $snestadores) {
                                            ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                        <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                        }
                                    }
                                    ?> </select>

                            </td>
                            <td><?= $rot_clicadcid ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> style=" float:left" aria-estado_referencia="snestadores" aria-pais_referencia="snpaisres" class="cidade_autocomplete form-control" type="text" name="sncidaderes" id="sncidaderes" value="<?= $sncidaderes ?? '' ?>" onchange="document.getElementById('clicadalt').value = '1'" />
                            </td>  </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh ficha-fnrh-mod">
                    <tbody>
                        <tr>
                            <td colspan="2"><?= $rot_estultpro ?><br/>
                                <div style="float:left; width:33%">
                                    <label><?= $rot_clicadpai ?></label>

                                    <select class="form-control" <?php if ($editaveis != 'todos') echo 'readonly' ?> name="bgstdscpais" id="bgstdscpais" onchange="gerestdet('bgstdscestado', this.value)"> <option value=""></option> <?php
                                        foreach ($dominio_paises_lista as $item) {
                                            if ($item["rotulo"] == $bgstdscpais) {
                                                ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option>
                                            <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                            }
                                        }
                                        ?> </select>
                                </div>
                                <div style="float:left; width:33%">
                                    <label><?= $rot_clicadest ?></label>
                                    <select <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:150px" class="form-control" name="bgstdscestado" id="bgstdscestado"> 
                                        <option value=""></option> <?php
                                        foreach ($dominio_estados_lista_bgstdscestado as $item) {
                                            if ($item["estado_codigo"] == $bgstdscestado) {
                                                ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                            <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                            }
                                        }
                                        ?> </select>
                                </div>

                                <div style="float:left; width:33%">
                                    <label><?= $rot_clicadcid ?></label>
                                    <input <?= $disabled ?> style="width:90%" <?php if ($editaveis != 'todos') echo 'readonly' ?> aria-estado_referencia="bgstdscestado" aria-pais_referencia="bgstdscpais" class="cidade_autocomplete form-control" type="text" name="bgstdsccidade" id="bgstdsccidade" value="<?= $bgstdsccidade ?? '' ?>" />
                                </div>

                            </td>
                            <td colspan="2"><?= $rot_estprodes ?><br/>
                                <div style="float:left; width:33%">
                                    <label><?= $rot_clicadpai ?></label>

                                    <select class="form-control" <?php if ($editaveis != 'todos') echo 'readonly' ?> name="bgstdscpaisdest" id="bgstdscpaisdest" onchange="gerestdet('bgstdscestadodest', this.value)"> <option value=""></option> <?php
                                        foreach ($dominio_paises_lista as $item) {
                                            if ($item["rotulo"] == $bgstdscpaisdest) {
                                                ?> <option selected='selected' value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php } else { ?> <option value="<?= $item["rotulo"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                            }
                                        }
                                        ?> </select>
                                </div>
                                <div style="float:left; width:33%">
                                    <label><?= $rot_clicadest ?></label>
                                    <select <?php if ($editaveis != 'todos') echo 'readonly' ?> style="width:150px" class="form-control" name="bgstdscestadodest" id="bgstdscestadodest"> 
                                        <option value=""></option> <?php
                                        foreach ($dominio_estados_lista_bgstdscestadodest as $item) {
                                            if ($item["estado_codigo"] == $bgstdscestadodest) {
                                                ?> <option selected='selected' value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> 
                                            <?php } else { ?> <option value="<?= $item["estado_codigo"] ?>"><?= $item["estado_nome"] ?> </option> <?php
                                            }
                                        }
                                        ?> </select>                                
                                </div>
                                <div style="float:left; width:33%">
                                    <label><?= $rot_clicadcid ?></label>
                                    <input <?= $disabled ?> style="width:90%"  <?php if ($editaveis != 'todos') echo 'readonly' ?> aria-estado_referencia="bgstdscestadodest" aria-pais_referencia="bgstdscpaisdest" class="cidade_autocomplete form-control" type="text" name="bgstdsccidadedest" id="bgstdsccidadedest" value="<?= $bgstdsccidadedest ?? '' ?>" />
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
                                    <select <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" name="snmotvia" id="snmotvia"> 
                                        <option value=""></option> <?php
                                        foreach ($dominio_viagem_motivos_lista as $item) {
                                            if ($item["valor"] == $snmotvia) {
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
                                    <select <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" name="sntiptran" id="sntiptran"> 
                                        <option value=""></option> <?php
                                        foreach ($dominio_transporte_meios_lista as $item) {
                                            if ($item["valor"] == $sntiptran) {
                                                ?> <option selected='selected' value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php } else {
                                                ?> <option value="<?= $item["valor"] ?>"><?= $item["rotulo"] ?> </option> <?php
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>

                            </td>
                            <td colspan="2"><?= $rot_estplaaut ?>
                                <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?> class="form-control" type="text" name="snplacaveiculo" id="snplacaveiculo" value="<?= $snplacaveiculo ?? '' ?>" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="ficha-fnrh ficha-fnrh-mod" style="margin-top:5px; width:100%; float: left">
                    <tr>
                        <td style="width:15%"><?= $rot_estfnrtit ?>
                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="snnum" id="snnum" value="<?= $snnum ?? '' ?>" />
                        </td>
                        <td style="padding: 3px 10px 1px 4px !important">
                            <div style="width: 100%"><?= $rot_resentdat ?><br/>
                                <div style="float:left; width:50%">
<?= $rot_gerdattit ?>
                                    <input <?= $disabled ?> style="width:90%" <?php if ($editaveis != 'todos' && $editaveis != 'nenhum_exceto_entrada' && $editaveis != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control data" type="text" name="snprevent_data" id="snprevent_data" value="<?= $snprevent_data ?? '' ?>" />
                                </div>    
                                <div style="float:left; width:50%">
<?= $rot_gerhortit ?>
                                    <input <?= $disabled ?>  style="width:90%"  <?php if ($editaveis != 'todos' && $editaveis != 'nenhum_exceto_entrada' && $editaveis != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control" type="text" name="snprevent_hora" id="snprevent_hora" value="<?= $snprevent_hora ?? '' ?>" />
                                </div>
                            </div>
                        </td>
                        <td><?= $rot_ressaidat ?><br/>
                            <div style="float:left; width:50%">
<?= $rot_gerdattit ?>
                                <input <?= $disabled ?> style="width:90%" <?php if ($editaveis != 'todos' && $editaveis != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control data" type="text" name="snprevsai_data" id="snprevsai_data" value="<?= $snprevsai_data ?? '' ?>" />
                            </div>    
                            <div style="float:left; width:50%">
<?= $rot_gerhortit ?>
                                <input <?= $disabled ?> style="width:90%" <?php if ($editaveis != 'todos' && $editaveis != 'nenhum_exceto_entrada_saida') echo 'readonly' ?> class="form-control" type="text" name="snprevsai_hora" id="snprevsai_hora" value="<?= $snprevsai_hora ?? '' ?>" />
                            </div>    
                        </td>                   

                        <td><?= $rot_estresaco ?>
                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="snnumhosp" id="snnumhosp" value="<?= $snnumhosp ?? '' ?>" />
                        </td>

                        <td><?= $rot_resquacod ?>
                            <input <?= $disabled ?> <?php if ($editaveis != 'todos') echo 'readonly' ?>  class="form-control" type="text" name="snuhnum" id="snuhnum" value="<?= $snuhnum ?? '' ?>" />
                        </td>
                    </tr>
                </table>

            </div>



        </div>
        <div class="row" style="background-color: #EEEEEE; padding:10px; margin-bottom: 7px">
            <div id="info_envio">
                <b><?= $rot_resdocsta ?></b> :  <?php
                if ($info_envio['envio_status'] == '1')
                    echo 'Recebida';
                else if ($info_envio['envio_status'] == '2')
                    echo 'Rejeitada';
                else if ($info_envio['envio_status'] == null)
                    echo 'Não enviada';
                ?>
                ;
                <b><?= $rot_estmsgtit ?></b> : <?= $info_envio['retorno_msg'] ?>
                ;
                <b><?= $rot_gerdattit ?></b> : <?= $info_envio['envio_data'] ?>

            </div>

        </div>
        <div class="row">
            <input style="float:left; margin-right:10px" type="button"  value="<?= $rot_gerdesbot ?>" onclick="$('.voltar').click()">
            <input <?= $disabled ?>  style="float:left" class="btn-primary  submit-button" type="button" name="resmodbtn"  value="<?= $rot_gersalbot ?>" onclick="estfnrmoc()" >
        </div>
    </form>
</div>
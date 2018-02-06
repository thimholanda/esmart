<!--MODELO ANTIGO ABAIXO-->
<?php

use Cake\Network\Session;

$session = new Session();
?>
<div id="fichas_embratur" style="display:none" >
    <div id="ficha_embratur_0" style="margin-top:-10px">
        <div style="clear: both; width:100%;">
            <div class="float:left" style="float:left;width:100%; letter-spacing: 0.1px">
                <table class="ficha-fnrh" style="width: 100%;">
                    <tr><td style="font-size: 15px; width:85%">FICHA NACIONAL DE REGISTRO DE HÓSPEDES - FNRH</td>
                        <td style="font-size: 15px; width:15%; text-align: right">  Nº _________</td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="clear: both">
           
            <div class="float:left" style="float:left;width:101%; letter-spacing: 0.1px">
                <table class="ficha-fnrh" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="width:8%"><div><?php echo $this->Html->image($logo_empresa, array('width' => '50px', 'height' => '39px')); ?></div></td>
                            <td style="text-align: left; width:35%; font-size: 16px; margin-top: 0px">POUSADA<h4 style="margin-top:0px;     margin-bottom: 0px;"><b>Campos de Provence</b></h4></td>
                            <td style="text-align: left; width:35%; font-size: 8px">
                                <div>fnrh</div>
                                <div>FICHA NACIONAL DE REGISTRO DE HÓSPEDES</div>
                                <div>GOVERNO DO ESTADO</div>
                                <div>SECRETARIA DE SEGURANÇA PÚBLICA</div>
                            </td>
                            <td style="width:10%;"><div><?php echo $this->Html->image("logo-ministerio.jpg", array('width' => '178px', 'height' => '42px')); ?></div></td>
                        </tr>
                    </thead>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0' >
                    <tbody>
                        <tr>
                            <td colspan="2" style="width: 33%;border: 1px solid #ccc !important; padding: 3px 0px 0px 4px !important; font-size: 9px;">
                                <div  style="min-height: 32px;">NOME COMPLETO - FULL NAME<br/><span style="font-size: 16px" id="nome_completo_0"></span></div>                                
                            </td>
                            <td style="width:31%;border: 1px solid #ccc !important; padding: 3px 0px 0px 4px !important;font-size: 9px;" colspan="2">
                                <div style="min-height: 32px;">
                                    E-MAIL<br/><span style="font-size: 16px; padding-right: 3px" id="email_0"></span> </div></td>
                            <td  colspan="2"  style="width: 18%;border: 1px solid #ccc !important; padding: 3px 0px 0px 4px !important; font-size: 9px;">
                                <div style="min-height: 32px;">CELULAR - CELL PHONE<br/><span style="font-size: 15px" id="celular_0"></span></div>
                            </td>
                            <td  colspan="2"  style="width: 18%;border: 1px solid #ccc !important; padding: 3px 0px 0px 4px !important; font-size: 9px;">
                                <div style="min-height: 32px;">TELEFONE - TELEPHONE<br/><span style="font-size: 15px" id="telefone_0"></span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>
                            <td style="width: 33%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">OCUPAÇÃO - OCUPATION<br/><span style="font-size: 16px" id="ocupacao_0"></span></div></td>
                            <td style="width:25%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;" colspan="2">
                                <div style="min-height: 35px;">NACIONALIDADE<br/><span style="font-size: 16px" id="nacionalidade_0"></span></div></td>
                            <td style="width: 26%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">
                                        DATA NASC - BIRTH DATE</div>
                                    <div style="width: 100%">
                                        <div style="  float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 3px;padding:7px"><span style="font-size: 16px;" id="data_nascimento_0_1">&nbsp;</span></div>
                                        <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:8px;margin-right: 3px;padding:7px"><span style="font-size: 16px" id="data_nascimento_0_2">&nbsp;</span></div>
                                        <div style=" float: left;margin-top: 8px; width: 3%">/</div>
                                        <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;margin-right: 3px;padding:7px"><span style="font-size: 16px" id="data_nascimento_0_3">&nbsp;</span></div>
                                        <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:8px;margin-right: 3px;padding:7px"><span style="font-size: 16px" id="data_nascimento_0_4">&nbsp;</span></div>
                                        <div style="float: left; margin-top: 8px; width: 3%">/</div>
                                        <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;margin-right: 3px;padding:7px"><span style="font-size: 16px" id="data_nascimento_0_5">&nbsp;</span></div>
                                        <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:8px;margin-right: 3px;padding:7px"><span style="font-size: 16px" id="data_nascimento_0_6">&nbsp;</span></div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 16%;border: 1px solid #ccc !important;padding: 3px 0px 3px 4px !important;font-size: 9px;">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">SEXO</div>
                                    <div style="width: 100%">
                                        <div style="float: left;  border: 1px solid #ccc; height:6px;  margin-right: 3px;min-width:8px; padding:8px; padding-bottom: 12px"><span style="font-size: 16px" id="sexo_0_1">&nbsp;</span></div>
                                        <div style="width: 15%;  float: left; text-align: center; margin-top: 5px;   margin-right: 3px">M</div>
                                        <div style=" float: left;  border: 1px solid #ccc; height:6px;  margin-right: 3px; min-width:8px; padding:8px; padding-bottom: 12px"><span style="font-size: 16px" id="sexo_0_2">&nbsp;</span></div>
                                        <div style="width: 15%;  float: left; text-align: center; margin-top: 5px;  margin-right: 3px">F</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>
                            <td style="width: 68%;border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">DOCUMENTO DE IDENTIDADE - TRAVEL DOCUMENT</div>
                                    <div style="width: 100%; float:left; font-size: 9px">
                                        <div style="float:left; width:10%">
                                            <div style="font-size: 9px">NÚMERO</div>
                                            <div style="font-size: 9px">NUMBER</div>

                                        </div>
                                        <div style="float:left; width:32%">
                                            <span style="font-size: 16px" id="documento_numero_0">&nbsp;</span>
                                        </div>
                                        <div style="float:left; width:6%">
                                            <div style="font-size: 9px">TIPO</div>
                                            <div style="font-size: 9px">TYPE</div>
                                        </div>

                                        <div style="float:left; width:24%">
                                            <span style="font-size: 16px" id="documento_tipo_0">&nbsp;</span>
                                        </div>
                                        <div style="float:left; width:20%">
                                            <div style="font-size: 9px">ÓRGÃO EXPEDIDOR</div>
                                            <div style="font-size: 9px">ISSUING / COUNTRY</div>
                                        </div>
                                        <div style="float:left; width:8%">
                                            <span style="font-size: 16px" id="orgao_expedidor_0">&nbsp;</span>
                                        </div>
                                    </div>  
                                </div>
                            </td>
                            <td style="width: 17%;border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important;font-size: 9px;">
                                <div style="min-height: 35px;">
                                    CPF<br/><span style="font-size: 16px" id="cpf_0"></span> </div></td>
                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>
                            <td colspan="2" style="width: 33%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    RESIDÊNCIA PERMANENTE - PERMANENT ADDRESS<br/><span style="font-size: 16px" id="residencia_logradouro_0"></span>
                                </div>
                            </td>
                            <td style="width: 17%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    CIDADE - CITY<br/><span style="font-size: 16px" id="residencia_cidade_0"></span> </div></td>
                            <td style="width: 11%;border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    ESTADO - STATE<br/><span style="font-size: 16px" id="residencia_estado_0"></span> </div></td>
                            <td style="width: 11%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    PAÍS - COUNTRY<br/><span style="font-size: 16px" id="residencia_pais_0"></span> </div></td>
                            <td colspan="2" style="width: 28%;border: 1px solid #ccc !important; padding: 0px 0px 3px 4px !important; font-size: 9px;">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%;">CEP</div>
                                    <div style="width: 100%;">
                                        <div style=" float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_1">&nbsp;</span></div>
                                        <div style=" float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_2">&nbsp;</span></div>
                                        <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_3">&nbsp;</span></div>
                                        <div style=" float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_4">&nbsp;</span></div>
                                        <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_5">&nbsp;</span></div>
                                        <div style="width: 3%;  float: left; text-align: center; margin-top: 5px; height: 23px !important;margin-right: 1px">-</div>
                                        <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_6">&nbsp;</span></div>
                                        <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_7">&nbsp;</span></div>
                                        <div style=" float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:8px;  margin-right: 1px; padding:6px"><span style="font-size: 16px" id="cep_0_8">&nbsp;</span></div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>
                            <td colspan="2" style="border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 9px;letter-spacing: 0.1px">   
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">ÚLTIMA PROCEDÊNCIA - ARRIVING FROM</div>
                                    <div style="font-size: 9px">
                                        <div style="float:left; width:10%">
                                            <div style="font-size: 9px">CIDADE</div>
                                            <div style="font-size: 9px">CITY</div>

                                        </div>
                                        <div style="float:left; width:23%">
                                            &nbsp;
                                        </div>
                                        <div style="float:left; width:10%">
                                            <div style="font-size: 9px">ESTADO</div>
                                            <div style="font-size: 9px">STATE</div>
                                        </div>

                                        <div style="float:left; width:23%">
                                            &nbsp;
                                        </div>
                                        <div style="float:left; width:15%">
                                            <div style="font-size: 9px">PAÌS</div>
                                            <div style="font-size: 9px">COUNTRY</div>
                                        </div>
                                        <div style="float:left; width:18%">
                                            &nbsp;
                                        </div>
                                    </div>   
                                </div>
                            </td>
                            <td colspan="2" style="border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 9px;letter-spacing: 0.1px">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">PRÓXIMO DESTINO - NEXT DESTINATION</div>
                                    <div style="font-size: 9px">
                                        <div style="float:left; width:10%">
                                            <div style="font-size: 9px">CIDADE</div>
                                            <div style="font-size: 9px">CITY</div>

                                        </div>
                                        <div style="float:left; width:23%">
                                            &nbsp;
                                        </div>
                                        <div style="float:left; width:10%">
                                            <div style="font-size: 9px">ESTADO</div>
                                            <div style="font-size: 9px">STATE</div>
                                        </div>

                                        <div style="float:left; width:23%">
                                            &nbsp;
                                        </div>
                                        <div style="float:left; width:15%">
                                            <div style="font-size: 9px">PAÌS</div>
                                            <div style="font-size: 9px">COUNTRY</div>
                                        </div>
                                        <div style="float:left; width:18%">
                                            &nbsp;
                                        </div>
                                    </div> 
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>
                            <td colspan="2" style="border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 7px;">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">MOTIVO DA VIAGEM - TRIP PURPOSE</div>

                                    <div style="width: 14%; float:left;  margin-right: 4px;">
                                        <div style="width: 81%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">LAZER - FÉRIAS</div>
                                            <div style="font-size: 7px">LEISURE - VACATION</div>
                                        </div>
                                        <div style="margin-top: 2px; width: 16%;float: right;border: 1px solid #ccc;height: 22px !important;">&nbsp;</div>

                                    </div>

                                    <div style="width: 9%; float:left;  margin-right: 4px">
                                        <div style="width: 60%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">NEGÓCIOS</div>
                                            <div style="font-size: 7px">BUSINESS</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 26%;float: right;border: 1px solid #ccc;height: 22px !important;">&nbsp;</div>
                                    </div>

                                    <div style="width: 13%; float:left;  margin-right: 4px">
                                        <div style="width: 79%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">CONGRESSO-FEIRA</div>
                                            <div style="font-size: 7px">CONVENTION-FAIR</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 16%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 14%; float:left; margin-right: 4px">
                                        <div style="width: 79%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">PARENTES - AMIGOS</div>
                                            <div style="font-size: 7px">RELATIVES - FRIENDS</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 16%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 13%; float:left;margin-right: 4px">
                                        <div style="width: 77%; float:left; font-size:7px; margin-top: 5px">
                                            <div style="font-size: 7px">ESTUDOS - CURSOS</div>
                                            <div style="font-size: 7px">STUDIES - COURSES</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 18%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 8%; float:left; margin-right: 4px">
                                        <div style="width: 63%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">RELIGIÃO</div>
                                            <div style="font-size: 7px">RELIGION</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 28%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 8%; float:left;margin-right: 4px">
                                        <div style="width: 63%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">SAÚDE</div>
                                            <div style="font-size: 7px">HEALTH</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 28%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 8%; float:left; margin-right: 4px">
                                        <div style="width: 63%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">COMPRAS</div>
                                            <div style="font-size: 7px">SHOPPING</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 28%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 8%; float:left;">
                                        <div style="width: 63%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">OUTRO</div>
                                            <div style="font-size: 7px">OTHER</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 28%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 0px;">&nbsp;</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>
                            <td colspan="2" style="border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 7px;">
                                <div style="min-height: 35px;">
                                    <div style="width: 100%">MEIO DE TRANSPORTE - ARRIVING BY</div>
                                    <div style="width: 12%; float:left;  margin-right: 1%">
                                        <div style="width: 50%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">AVIÃO</div>
                                            <div style="font-size: 7px">PLANE</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 21%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 12%; float:left; margin-right: 1%">
                                        <div style="width: 50%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">AUTOMÓVEL</div>
                                            <div style="font-size: 7px">CAR</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 21%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 12%; float:left; margin-right: 0px">

                                        <div style="width: 50%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">ÔNIBUS</div>
                                            <div style="font-size: 7px">BUS</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 21%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 0px;">&nbsp;</div>
                                    </div>

                                    <div style="width:12%; float:left; margin-left: 10px">
                                        <div style="width: 50%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">MOTO</div>
                                            <div style="font-size: 7px">MOTOCYCLE</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 21%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 0px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 15%; float:left;  margin-left: 10px">
                                        <div style="width: 75%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">NAVIO - BARCO</div>
                                            <div style="font-size: 7px">SHIP - FERRY BOAT</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 17%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 13%; float:left;  margin-left: 10px">
                                        <div style="width: 50%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">TREM</div>
                                            <div style="font-size: 7px">TRAIN</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 19%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>

                                    <div style="width: 8%; float:left;  margin-left: 10px">
                                        <div style="width: 50%; float:left; font-size: 7px; margin-top: 5px">
                                            <div style="font-size: 7px">OUTRO</div>
                                            <div style="font-size: 7px">OTHER</div>
                                        </div>
                                        <div style="margin-top: 2px;width: 32%;float: right;border: 1px solid #ccc;height: 22px !important;margin-right: 2px;">&nbsp;</div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <table  class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tbody>
                        <tr>  
                            <td colspan="2" style="border: 1px solid #ccc !important; width: 30%; padding: 3px 0px 0px 4px !important;font-size: 9px;">
                                <div style="min-height: 32px;">PLACA AUTOMÓVEL </div></td>
                            <td style="width:10%;border: 1px solid #ccc !important;padding: 3px 0px 0px 4px !important; width: 30%; font-size: 9px;">
                                <div style="min-height: 32px;">
                                    <div style="width: 100%">ACOMPANHANTES / COMPANIONS</div>
                                    <div style="width: 100%; float:right">
                                        <div style="float: right;  border: 1px solid #ccc; height:6px;  margin-right: 3px; padding:8px;  padding:12px"><span >&nbsp;</span></div>
                                        <div style=" float: right;  border: 1px solid #ccc; height:6px;  margin-right: 3px; padding:8px;  padding:12px"><span style="font-size: 16px" id="acompanhantes_0">&nbsp;</span></div>
                                    </div>                                
                                </div></td>
                            <td style="padding: 10px;vertical-align: top;border: 1px solid #ccc !important; padding: 3px 0px 0px 4px !important; font-size: 9px;">
                                <div style="min-height: 32px;">                                
                                    <div style="width: 100%">UH N.º / ROOM </div>
                                    <div style="width: 100%; float:right">
                                        <div style="float: right;  border: 1px solid #ccc; height:6px;  margin-right: 3px; padding:8px;  padding:12px"><span style="font-size: 16px" id="quarto_0_1">&nbsp;</span></div>
                                        <div style=" float: right;  border: 1px solid #ccc; height:6px;  margin-right: 3px; padding:8px;  padding:12px"><span style="font-size: 16px" id="quarto_0_2">&nbsp;</span></div>
                                        <div style="float: right;  border: 1px solid #ccc; height:6px;  margin-right: 3px; padding:8px;  padding:12px"><span style="font-size: 16px" id="quarto_0_3">&nbsp;</span></div>
                                        <div style=" float: right;  border: 1px solid #ccc; height:6px;  margin-right: 3px; padding:8px;  padding:12px"><span style="font-size: 16px" id="quarto_0_4">&nbsp;</span></div>
                                    </div> 

                                </div></td>
                        </tr>
                    </tbody>
                </table>

                <table class="ficha-fnrh" style="width:100%; float: left" cellpadding='0' cellspacing='0'>
                    <tr>

                        <td style="width: 50%;border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 9px;"><div style="width: 100%">
                                <div style="min-height: 40px;">
                                    <div style="width: 100%">ENTRADA / CHECK IN
                                    </div>
                                    <div style="width: 18%;  float: left;margin-top: 10px">DIA-MÊS-ANO</div>
                                    <div style="  float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_1">&nbsp;</span></div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_2">&nbsp;</span></div>
                                    <div style="width: 1%;  float: left;margin-top: 10px">/</div>
                                    <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:6px;  margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_3">&nbsp;</span></div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_4">&nbsp;</span></div>
                                    <div style="width: 1%;  float: left; margin-top: 10px">/</div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_5">&nbsp;</span></div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_6">&nbsp;</span></div>
                                    <div style=" float: left;margin-top: 10px; text-align: right;     margin-right: 7px;">HORA</div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_7">&nbsp;</span></div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_8">&nbsp;</span></div>
                                    <div style="width: 1%;  float: left; margin-top: 10px">:</div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_9">&nbsp;</span></div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="entrada_0_10">&nbsp;</span></div>

                                </div>
                            </div>
                        </td>                   
                        <td style="width:50%;border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important;font-size: 9px;">
                            <div style="min-height: 40px;">
                                <div style="width: 100%">SAÍDA / CHECK OUT</div>
                                <div style="width: 100%">
                                    <div style="width: 18%;float: left;margin-top: 10px">DIA-MÊS-ANO</div>
                                    <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_1">&nbsp;</span></div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_2">&nbsp;</span></div>
                                    <div style="width: 1%;  float: left;margin-top: 10px">/</div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_3">&nbsp;</span></div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_4">&nbsp;</span></div>
                                    <div style="width: 1%;  float: left; margin-top: 10px">/</div>
                                    <div style="float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_5">&nbsp;</span></div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px; margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_6">&nbsp;</span></div>
                                    <div style="float: left;margin-top: 10px; text-align: right;     margin-right: 7px;">HORA</div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_7">&nbsp;</span></div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_8">&nbsp;</span></div>
                                    <div style="width: 1%;  float: left; margin-top: 10px">:</div>
                                    <div style="float: left;  border: 1px solid #ccc; height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span style="font-size: 16px" id="saida_0_9">&nbsp;</span></div>
                                    <div style=" float: left;  border: 1px solid #ccc;height:13px; margin-top:0px;min-width:6px;margin-right: 2px; padding:6px"><span  style="font-size: 16px" id="saida_0_10">&nbsp;</span></div>
                                </div>
                            </div>
                        </td>

                    </tr>
                </table>           
                <table class="ficha-fnrh" style="width: 100%;" cellpadding='0' cellspacing='0'>
                    <tr>
                        <td colspan="3" style="border: 1px solid #ccc !important; padding: 3px 0px 3px 4px !important; font-size: 9px;">
                            <div style="min-height: 20px;">
                                OBSERVAÇÃO: <?= $session->read('empresa_selecionada')['fnrh_observacao'] ?> </div></td>
                    </tr>
                </table>
            </div>

        </div>
        <div style="clear: both; width:100%;">
            <div class="float:left" style="float:left;width:100%; letter-spacing: 0.1px;padding-top:8px">
                <table class="ficha-fnrh" style="width: 100%;">
                    <tr><td style="font-size: 9px;padding-bottom: 20px">ASSINATURA DO HÓSPEDE    ____________________________________________________________________________________________________</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
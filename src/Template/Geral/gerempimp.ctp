<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    Implementação de empresa
</h1>
<div class="content_inner">
    <form method="POST" name="gerempimp" id="gerempimp" action="<?= Router::url('/', true) ?>geral/gerempimp" class="form-horizontal" onkeypress="return event.keyCode != 13;">
        <input type="hidden" id="bloqueia_tela" value="1" />
        <h6><b>Empresa</b></h6>
        <div class="form-group">
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gercodref">Código empresa de referência</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gercodref" id="gercodref" data-validation="required" />
                </div>
            </div>
            
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gernovgru">Novo grupo</label>
                <input type="checkbox" name="gernovgru" id="gernovgru" onchange="if($(this).is(':checked')){$('#codigo_grupo').css('display', 'block')}else{$('#codigo_grupo').css('display', 'none')}" />
            </div>
            
            <div class="col-md-2 col-sm-12" id="codigo_grupo" style="display:none">
                <label class="control-label col-md-12 col-sm-12" for="gercodgru">Código do grupo</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gercodgru" id="gercodgru" data-validation="required" />
                </div>
            </div>
        </div>
        <div class='form-group'>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempnom">Nome fantasia</label>
                <div class='col-md-11 col-sm-12'> 
                    <input class='form-control' type="text" name="gerempnom" id="gerempnom" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempraz">Razão social</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gerempraz" id="gerempraz" />
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempraz">Inscrição estadual</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gerempins" id="gerempins" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempcnp">CNPJ</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control cpfcnpj' type="text" name="gerempcnp" id="gerempcnp" maxlength="18" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempema" >E-mail</label> 
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="gerempema" id="gerempema" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12">Telefone</label>
                <div class="col-lg-1 col-md-4 col-sm-4 size1">
                    <select class="form-control" name="geremptdd" id="geremptdd"> 
                        <option value=""></option> <?php
                        foreach ($dominio_ddi_lista as $item) {
                            ?> <option value="<?= $item["valor"] ?>" <?php if ($item['valor'] == $ddi_padrao) echo "selected='selected'" ?>><?= $item["valor"] ?> </option> <?php
                        }
                        ?> 
                    </select>
                </div>
                <div class="col-md-8 col-sm-8">
                    <input class="form-control telefone" autocomplete="off" type="text" name="geremptnu" id="geremptnu" placeholder="(99) 9999-9999" />
                </div>
            </div>
            
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="geremptax" >Taxa de turismo</label> 
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="geremptax" id="geremptax" value="0" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-1 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempcep">CEP</label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control cep" autocomplete="off" type="text" maxlength="9" id="gerempcep" name="gerempcep" onblur="gerenddet(this.value, 'geremplog', 'gerempbai', 'gerempcid', 'gerempest', 'geremppai', 'gerempnum')" />
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="geremppai">País</label>
                <div class="col-md-12 col-sm-12">
                    <select class="selectpicker" name="geremppai" id="geremppai" onchange="gerestdet('gerempest', this.value)"> 
                        <option value=""></option> 
                        <option value="Brasil" selected="selected">Brasil </option>
                        <option data-divider="true"></option> 
                        <?php
                        foreach ($dominio_paises_lista as $item) {
                            if ($item["rotulo"] != 'Brasil') {
                                ?>
                                <option value="<?= $item["rotulo"] ?>" <?php if ($item['rotulo'] == $pais_nome_padrao) echo "selected='selected'" ?>><?= $item["rotulo"] ?> </option> 
                                <?php
                            }
                        }
                        ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempest">Estado</label>
                <div class="col-md-12 col-sm-12">
                    <select class="form-control" name="gerempest" id="gerempest"> 
                        <option value=""></option> <?php
                        foreach ($dominio_estados_lista as $item) {
                            ?> <option value="<?= $item["valor"] ?>" <?php if ($item['valor'] == $estado_codigo_padrao) echo "selected='selected'" ?>><?= $item["rotulo"] ?> </option>
                            <?php
                        }
                        ?> 
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempcid" >Cidade</label>
                <input type='hidden' id='has_select' value='0' />
                <div class="col-md-12 col-sm-12">
                    <input autocomplete="off" type="text" aria-estado_referencia="gerempest" aria-pais_referencia="geremppai" class='cidade_autocomplete form-control' name="gerempcid" id="gerempcid" onblur="console.log($('#has_select').val());
                            if ($('#has_select').val() == '0')
                                this.value = '';
                           " /> 
                </div>
            </div>

            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempbai">Bairro</label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="gerempbai" id="gerempbai" />   
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="geremplog">Logradouro</label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="geremplog" id="geremplog" />       
                </div>
            </div>
            <div class="col-md-1 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerempnum">Número</label>
                <div class="col-md-12 col-sm-12">
                    <input class="form-control" autocomplete="off" type="text" name="gerempnum" id="gerempnum" />       
                </div>
            </div>
        </div>

        <h6><b>Usuários</b></h6>
        <div class='form-group linha_usuarios'>

            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerusunom">Nome</label>
                <div class='col-md-11 col-sm-12'> 
                    <input class='form-control' type="text" name="gerusunom[]" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerususob">Sobrenome</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gerususob[]"/>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerususob">E-mail</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gerusuema[]"/>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerususob">Login</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gerusulog[]" data-validation="required" maxlength="12" />
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerusucar">Cargo</label>
                <div class='col-md-11 col-sm-12'>
                    <select class="form-control" name="gerusucar[]"> 
                        <option></option>      
                        <option value="gerente">gerente</option>       
                        <option value="recepcionista">recepcionista</option>       
                        <option value="diretor">diretor</option>       
                        <option value="administrador">administrador</option>       
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class='pull-left col-md-2 col-sm-4'>
                    <input class="form-control btn-default novo_usuario" type="button" value="Mais usuários">
                </div>
            </div>
        </div>

        <h6><b>Tipos de quarto</b></h6>
        <div class='form-group  linha_tipo_quarto'>
            <div class="col-md-1">
                <label class="control-label col-md-12 col-sm-12" for="gerqticod">Código</label>
                <div class='col-md-11 col-sm-12'> 
                    <input class='form-control' type="text" id="gerqticod_1"  name="gerqticod[]" value="1" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerqtinom">Nome do tipo</label>
                <div class='col-md-11 col-sm-12'> 
                    <input class='form-control gerqtinom' id="gerqtinom_1"  type="text" name="gerqtinom[]" />
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerqtincu">Nome abreviado</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="gerqtincu[]" maxlength="12" />
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="germaxadu">Qtd max adultos</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="germaxadu[]"/>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="germaxcri">Qtd max crianças</label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text" name="germaxcri[]"/>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gertarpor">Tarifa por </label>
                <div class='col-md-11 col-sm-12'>
                    <input class='form-control' type="text"  id="gertarpor_1"  name="gertarpor[]" value="0"/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class='pull-left col-md-2 col-sm-4'>
                    <input class="form-control btn-default novo_tipo_quarto" type="button" value="Mais tipo de quarto">
                </div>
            </div>
        </div>

        <h6><b>Quartos</b></h6>
        <div class='form-group linha_quarto'>
            <div class="col-md-2">
                <label class="control-label col-md-12 col-sm-12" for="gerquanum">Número ou código</label>
                <div class='col-md-11 col-sm-12'> 
                    <input class='form-control' type="text" name="gerquanum[]" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="gerquaqti">Tipo de quarto </label>
                <div class='col-md-11 col-sm-12'> 
                    <select class='form-control gerquaqti' name="gerquaqti[]" >
                        <option></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class='pull-left col-md-2 col-sm-4'>
                    <input class="form-control btn-default novo_quarto" type="button" value="Mais quartos">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class='pull-right col-md-2 col-sm-4'>
                    <input class="form-control btn-primary" type="button" name="gerempimp" value="Implementar" onclick="cadastraEmpresa();">
                </div>

                <div class='pull-left col-md-2 col-sm-4'>
                    <input class="form-control btn-default " type="button" value="Cancelar" onclick="gerpagexi('', 1, {})">
                </div>
            </div>
        </div>
    </form>
</div>
</div>
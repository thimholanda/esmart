<?= $this->Html->script('admin') ?>
<?php

use Cake\Routing\Router;

$path = Router::url('/', true);
$complete_url = $path . "gertelger/gertelexi";
?>
<div style="margin-top: 15px">
    <form action="gertelatu" method="post">
        <input type="hidden" value="" name="elementos_criados" id="elementos_criados" />
        <b><a href="<?= $complete_url ?>">Voltar</a></b>
        <h3>Edição de tela</h3>
        <div>
            <label>Código</label>
            <input type="text" id="tela_codigo" name="tela_codigo" value="<?= $tela_codigo ?>" readonly="true"/>
            <label>Nome</label>
            <input type="text" id="tela_nome" value="<?= $tela_nome ?>" readonly="true"/>
        </div>

        <div style="float: left; width: 50%">
            <h3>Elementos existentes</h3>
            <table style="text-align: center" class="list-elementos">
                <tr>
                    <th style="width: 25%">Código</th>
                    <th style="width: 64%">Descrição</th>
                    <th style="width: 20%">Ações</th>
                </tr>
                <?php foreach ($elementos_existentes as $elemento) { ?>
                    <tr >
                        <td><?= $elemento->elemento_codigo ?></td>
                        <td><?= $elemento->elemento_nome ?></td>
                        <td><input type="button" value="Inserir" 
                                   onclick="solicita_tipo('<?= $elemento->elemento_codigo ?>', '<?= $elemento->elemento_nome ?>')" /></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div style="width: 50%; float: left">
            <h3>Elementos da tela</h3>
            <input type="button" value="Novo Elemento" style=" margin-right: 200px" onclick="novo_elemento_dialog()"  />
            <table style="text-align: center" class="list-elementos" id="list-novos-elementos">
                <tr>
                    <th style="width: 25%">Código</th>
                    <th style="width: 66%">Descrição</th>
                    <th style="width: 20%">Ações</th>
                </tr>
                <?php foreach ($elementos_da_tela as $elemento) { ?>
                    <tr id="row-<?= $elemento['elemento_codigo'] ?>">
                    <input type="hidden" value="<?= $elemento['elemento_codigo'] ?>" name="elemento_codigos[]"/>
                    <input type="hidden" value="" name="elemento_tipos[]"/>
                    <input type="hidden" value="<?= $elemento['campo_propriedade'] ?>" name="elemento_propriedades[]"/>
                    <td><?= $elemento['elemento_codigo'] ?></td>
                    <td><?= $elemento['elemento_nome'] ?> </td>
                    <td><input type='button' value='Remover' onclick ="remove_elemento('<?= $elemento['elemento_codigo'] ?>')" /> </td>
                    </tr>
                <?php } ?>
            </table>
            <input type="submit" value="Gerar e atualizar"  />
        </div>
    </form>
    <div id="seleciona-tipo" style="display:none">
        <form>
            <input type="hidden" value="" id="elemento_codigo_escolhido"/>
            <input type="hidden" value="" id="elemento_nome_escolhido"/>
            <input type="hidden" value="campo" id="tipo_escolhido"/>
            <input type="hidden" value="0" id="campo_propriedade_escolhida"/>
            <label>Tipo de campo</label>
            <select name="tipo_elemento" onchange="altera_tipo_escolhido(this)">
                <option value="texto" label="Texto">Texto</option>
                <option value="input" >Input</option>
                <option value="select" >Select</option>
            </select>    
            <label>Propriedade</label>
            <select name="campo_propriedade" onchange="altera_propriedade_escolhido(this)">
                <option value="0" selected="selected" ></option>
                <option value="1" >Oculto</option>
                <option value="2" >Apenas Leitura</option>
                <option value="3" >Opcional</option>
                <option value="4" >Obrigatório</option>
            </select>
            <input type="button" value="Ok" onclick="insere_novo_elemento()"/>
        </form>
    </div>


    <div id="novo-elemento" style="display:none">
    <form id="cadastra-novo-elemento">
        <input type="hidden" value="texto" id="tipo_escolhido"/>
        <input type="hidden" value="0" id="campo_propriedade_escolhida"/>
        <div class="form-group">
            <div class="row">
                <label class="col-md-3">Tipo de elemento</label>
                <div class="col-md-2">
                    <select class="form-control" id="select-tipo" name="tipo_elemento" onchange="altera_tipo_escolhido(this)">
                        <option value="cp">Campos</option>
                        <option value="tt" >Títulos</option>
                        <option value="bt" >Botões</option>
                        <option value="mn" >Menus</option>
                        <option value="tl" >Telas</option>
                        <option value="tb" >Tabelas</option>
                    </select> 
                </div>

                <label class="col-md-3">Elemento HTML</label>
                <div class="col-md-2">
                    <select class="form-control" name="elemento_html" onchange="altera_tipo_html_escolhido(this)">
                        <option value="texto" label="Texto">Texto</option>
                        <option value="input" >Input</option>
                        <option value="select" >Select</option>
                    </select> 
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-2">Código</label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="codigo" id="codigo"/>
                </div>

                <label class="col-md-1" id="label-nome">Nome</label>
                <div class="col-md-4" id="field-nome">
                    <input class="form-control" type="text" name="nome" id="nome"/>
                </div>

            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-md-2">Português</label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="portugues" id="portugues"/> 
                </div>

                <label class="col-md-1">Inglês</label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="ingles" id="ingles"/> 
                </div>
            </div>
        </div>

        

        <div class="form-group">
            <div class="row">
                <label class="col-md-2 " id="label-campo">Campo</label>
                <div class="col-md-3" id="field-campo">
                    <input class="form-control" type="text" name="campo" id="campo"/>
                </div>
                
                <label class="col-md-2" style="display: none"  id="label-tabela">Tabela</label>
                <div class="col-md-3" style="display: none" id="field-tabela">
                    <input class="form-control" type="text" name="tabela" id="tabela"/>
                </div>
            </div>
        </div>

        <div class="form-group" id="row-formato-validador">
            <div class="row">
                <label  class="col-md-2">Formato</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="formato" id="formato"/>
                </div>

                <label  class="col-md-2">Validador</label>
                <div class="col-md-3">
                    <div style="position:relative;width:200px;height:25px;border:0;padding:0;margin:0;">
                        <select class="form-control" style="position:absolute;top:0px;left:0px;width:200px; height:25px;line-height:20px;margin:0;padding:0;" onchange="document.getElementById('validador').value = this.options[this.selectedIndex].text;">
                            <option></option>
                            <?php foreach ($validadores as $validador) { ?>
                                <option  value="<?= $validador['valor'] ?>"><?= $validador['rotulo'] ?></option>
                            <?php } ?>
                        </select>
                        <input class="form-control" name="validador"  id="validador" style="position:absolute;top:0px;left:0px;width:183px;width:180px;width:180px;height:23px; height:21px;" onfocus="this.select()" type="text">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" id="row-dominio-propriedade">
            <div class="row">
                <!--<label class="col-md-2">Domínio</label>
                <div class="col-md-3">
                    <div style="position:relative;width:180px;height:25px;border:0;padding:0;margin:0;">
                        <select class="form-control" style="position:absolute;top:0px;left:0px;width:184px; height:25px;line-height:20px;margin:0;padding:0;" onchange="document.getElementById('dominio').value = this.options[this.selectedIndex].text;">
                            <option></option>
                            <?php foreach ($dominios as $dominio) { ?>
                                <option  value="<?= $dominio['valor'] ?>"><?= $dominio['rotulo'] ?></option>
                            <?php } ?>
                        </select>
                        <input class="form-control" name="dominio" id="dominio" style="position:absolute;top:0px;left:0px;width:167px;width:180px\9;#width:180px;height:23px; height:21px\9;#height:18px;" onfocus="this.select()" type="text">
                    </div>
                </div>-->

                <label class="col-md-2">Propriedade</label>
                <div class="col-md-3">
                    <div style="position:relative;width:200px;height:25px;border:0;padding:0;margin:0;">
                        <select class="form-control" name="campo_propriedade" onchange="altera_propriedade_escolhido(this)">
                            <option value="0" ></option>
                            <option value="1" >Oculto</option>
                            <option value="2" >Apenas Leitura</option>
                            <option value="3" >Opcional</option>
                            <option value="4" >Obrigatório</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <input type="button" value="Ok" onclick="cadastra_novo_elemento()" style="float: right"/>
            </div>
        </div>
    </form>
</div>
</div>
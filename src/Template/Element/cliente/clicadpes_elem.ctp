<?php

use Cake\Routing\Router;
?>
<?php if (!$is_dialog) { ?>
    <div class="content_inner">
        <div class="formulario">
            <form method="POST" name="clicadpes" id="clicadpes"  action="<?= Router::url('/', true) . 'clientes/clicadpes' ?>" class="form-horizontal" autocomplete="off">
                <input type="hidden" id="pagina" value="1" name="pagina" />
                <input type="hidden" id="form_atual" value="clicadpes"  />
                <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
                <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
                <input type="hidden" id="export_csv" value="0" name="export_csv" />
                <input type="hidden" id="clicadmod_redirecionar" value="1">
                <input type="hidden" id="aria-form-id-csv" value="clicadpes" >


                <div class="form-group">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="c_nome_autocomplete" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?></label>
                        <div class="col-md-11 col-sm-12"> 
                            <input  class="form-control input_autocomplete" id='c_nome_autocomplete' type="text" name="cliprinom" value="<?= $cliprinom ?? $padrao_valor_cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?>
                                    aria-campo-padrao-valor ="<?= $campo_padrao_valor_cliprinom ?>"  aria-padrao-valor="<?= $padrao_valor_cliprinom ?? '' ?>" /> 
                        </div>        
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="clicadema" <?= $pro_clicadema ?>><?= $rot_clicadema ?></label>
                        <div class="col-md-11 col-sm-12">  
                            <input class="form-control" type="text" name="clicadema" id="clicadema" value="<?= $clicadema ?? $padrao_valor_clicadema ?? '' ?>" placeholder="<?= $for_clicadema ?>"  <?= $pro_clicadema ?> <?= $val_clicadema ?>
                                   aria-campo-padrao-valor ="<?= $campo_padrao_valor_clicadema ?>"  aria-padrao-valor="<?= $padrao_valor_clicadema ?? '' ?>" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="clicpfcnp" <?= $pro_clicpfnum ?>><?= $rot_clicpfcnp ?></label>
                        <div class="col-md-11 col-sm-12"> 
                            <input class="form-control cpfcnpj"  maxlength="18"  type="text" name="clicpfcnp" id="clicpfcnp" value="<?= $clicpfcnp ?? $padrao_valor_clicpfcnp ?? '' ?>" placeholder="<?= $for_clicpfcnp ?>"<?= $pro_clicpfcnp ?> <?= $val_clicpfcnp ?>
                                   aria-campo-padrao-valor ="<?= $campo_padrao_valor_clicpfcnp ?>"  aria-padrao-valor="<?= $padrao_valor_clicpfcnp ?? '' ?>" />
                        </div>
                    </div>
					
					
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="clicadpap" <?= $pro_clicadpap ?>><?= $rot_clicadpap ?></label>
                        <div class="col-md-11 col-sm-12"> 
                            <select class="form-control" name="clicadpap" id="clicadpap" <?= $pro_clicadpap ?> aria-campo-padrao-valor ="<?= $campo_padrao_valor_clicadpap ?>"  aria-padrao-valor="<?= $padrao_valor_clicadpap ?? '' ?>"> 
                                <option value=""></option>
                                <?php
                                foreach ($cliente_papeis_lista as $item) {

                                    $selected = "";

                                    if (isset($clicadpap)) {
                                        if ($clicadpap == $item['valor'])
                                            $selected = 'selected = \"selected\"';
                                    }else if (isset($padrao_valor_clicadpap)) {
                                        if ($padrao_valor_clicadpap == $item['valor']) {
                                            $selected = 'selected = \"selected\"';
                                        }
                                    }
                                    ?>
                                    <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
					
					
					
                   
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="clidocnum"  <?= $pro_clidocnum ?>><?= $rot_clidocnum ?></label> 
                        <div class="col-md-11 col-sm-12"> 
                            <input class="form-control" maxlength="12" type="text" name="clidocnum"   data-validation-depends-on="clidoctip" data-validation="required"  id="clidocnum" value="<?= $clidocnum ?? $padrao_valor_clidocnum ?? '' ?>" placeholder="<?= $for_clidocnum ?>"  <?= $pro_clidocnum ?> <?= $val_clidocnum ?>" aria-campo-padrao-valor ="<?= $campo_padrao_valor_clidocnum ?>"  aria-padrao-valor="<?= $padrao_valor_clidocnum ?? '' ?>" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label col-md-12 col-sm-12" for="clidoctip" <?= $pro_clidoctip ?>><?= $rot_clidoctip ?></label>
                        <div class="col-md-12 col-sm-12"> 
                            <select class="form-control" name="clidoctip" id="clidoctip" <?= $pro_clidoctip ?>    data-validation-depends-on="clidocnum" data-validation="required" 
                                    aria-campo-padrao-valor ="<?= $campo_padrao_valor_clidoctip ?>"  aria-padrao-valor="<?= $padrao_valor_clidoctip ?? '' ?>"
                                    > 
                                <option value=""></option>
                                <?php
                                foreach ($documento_tipo_lista as $item) {
                                    $selected = "";

                                    if (isset($clidoctip)) {
                                        if ($clidoctip == $item['valor'])
                                            $selected = 'selected = \"selected\"';
                                    }else if (isset($padrao_valor_clidoctip)) {
                                        if ($padrao_valor_clidoctip == $item['valor']) {
                                            $selected = 'selected = \"selected\"';
                                        }
                                    }
                                    ?>
                                    <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
                   
                </div>


                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <div class="col-md-10 col-sm-8"></div>
                        <div class="pull-left col-md-2 col-sm-4">
                            <input class="form-control btn-primary submit-button" aria-form-id="clicadpes" type="submit" value="<?= $rot_gerpesbot ?>">
                        </div>
                    </div>
                </div>

                <?php if (is_array($pesquisa_clientes) && (count($pesquisa_clientes) > 0)) { ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <div class="col-md-12 col-sm-12">
                                <table class="table_cliclipes">
                                    <thead>
                                        <tr class="tabres_cabecalho">
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cliente_codigo', 'aria_form_id' => 'clicadpes', 'label' => $rot_clicadcod]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'nome', 'aria_form_id' => 'clicadpes', 'label' => $rot_cliprinom]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'sobrenome', 'aria_form_id' => 'clicadpes', 'label' => $rot_clisobnom]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'email', 'aria_form_id' => 'clicadpes', 'label' => $rot_clicadema]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cpf', 'aria_form_id' => 'clicadpes', 'label' => $rot_clicpfcnp]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cliente_documento_tipo', 'aria_form_id' => 'clicadpes', 'label' => $rot_clidoctip]); ?>
                                            <?= $this->element('ordenacao', ['ordenacao_coluna' => $ordenacao_coluna, 'ordenacao_tipo' => $ordenacao_tipo, 'aria_ordenacao_coluna' => 'cliente_documento_numero', 'aria_form_id' => 'clicadpes', 'label' => $rot_clidocnum]); ?>
                                        </tr>
                                    </thead>
                                    <?php
                                    foreach ($pesquisa_clientes as $value) {
                                        ?>
                                        <tr class="clicadmod" aria-cliente-codigo = '<?= $value["cliente_codigo"] ?>'>
                                            <td><?= $value['cliente_codigo'] ?></td>
                                            <td><?= $value['nome'] ?></td>
                                            <td><?= $value['sobrenome'] ?></td>
                                            <td><?= $value['email'] ?></td>
                                            <td><?= empty($value['cpf']) ? $value['cnpj'] : $value['cpf'] ?></td>
                                            <td><?= $value['cliente_documento_tipo'] ?></td>
                                            <td><?= $value['cliente_documento_numero'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt1">
                        <div class="col-md-12 col-sm-12 font_12" style="float: right; padding: 0px;"> 
                             <div class="col-md-10 col-sm-12" style="float: right; " >
                                     <?php echo $paginacao; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                } else if (isset($pesquisa_clientes) && (count($pesquisa_clientes) == '0')) {
                    echo "Nenhum cliente encontrado";
                }
                ?>
            </form>    
        </div>
    </div>
<?php } else { ?>
    <form method="POST" name="clicadpes" id="clicadpes"  action="<?= Router::url('/', true) . 'clientes/clicadpes' ?>" class="form-horizontal" autocomplete="off">
        <input type="hidden" id="pagina" value="1" name="pagina" />
        <input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? '' ?>" name="ordenacao_coluna" />
        <input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? '' ?>" name="ordenacao_tipo" />
        <input type="hidden" id="export_csv" value="0" name="export_csv" />
        <input type="hidden" id="aria-form-id-csv" value="clicadpes" >
        <input type="hidden" id="aria_cliente_codigo_id" name="aria_cliente_codigo_id" value="<?= $aria_cliente_codigo_id ?>" >
        <input type="hidden" id="aria_cliente_nome_id" name="aria_cliente_nome_id" value="<?= $aria_cliente_nome_id ?>" >
        <input type="hidden" id="aria_cliente_cpf_cnpj_id" name="aria_cliente_cpf_cnpj_id" value="<?= $aria_cliente_cpf_cnpj_id ?>" >

        <div class="form-group">
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="c_nome_autocomplete" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?></label>
                <div class="col-md-11 col-sm-12"> 
                    <input  class="form-control" type="text" name="cliprinom" value="<?= $cliprinom ?? $padrao_valor_cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>"  <?= $pro_cliprinom ?> <?= $val_cliprinom ?> />
                </div>        
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadema" <?= $pro_clicadema ?>><?= $rot_clicadema ?></label>
                <div class="col-md-11 col-sm-12">  
                    <input class="form-control" type="text" name="clicadema" id="clicadema" value="<?= $clicadema ?? $padrao_valor_clicadema ?? '' ?>" placeholder="<?= $for_clicadema ?>"  <?= $pro_clicadema ?> <?= $val_clicadema ?>
                           aria-campo-padrao-valor ="<?= $campo_padrao_valor_clicadema ?>"  aria-padrao-valor="<?= $padrao_valor_clicadema ?? '' ?>" />
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicpfcnp" <?= $pro_clicpfnum ?>><?= $rot_clicpfcnp ?></label>
                <div class="col-md-12 col-sm-12"> 
                    <input class="form-control cpfcnpj" maxlength="18"  type="text" name="clicpfcnp" id="clicpfcnp" value="<?= $clicpfcnp ?? $padrao_valor_clicpfcnp ?? '' ?>" placeholder="<?= $for_clicpfcnp ?>"<?= $pro_clicpfcnp ?> <?= $val_clicpfcnp ?>
                           aria-campo-padrao-valor ="<?= $campo_padrao_valor_clicpfcnp ?>"  aria-padrao-valor="<?= $padrao_valor_clicpfcnp ?? '' ?>" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clidoctip" <?= $pro_clidoctip ?>><?= $rot_clidoctip ?></label>
                <div class="col-md-11 col-sm-12"> 
                    <select class="form-control" name="clidoctip" id="clidoctip" <?= $pro_clidoctip ?>    data-validation-depends-on="clidocnum" data-validation="required" 
                            aria-campo-padrao-valor ="<?= $campo_padrao_valor_clidoctip ?>"  aria-padrao-valor="<?= $padrao_valor_clidoctip ?? '' ?>"
                            > 
                        <option value=""></option>
                        <?php
                        foreach ($documento_tipo_lista as $item) {
                            $selected = "";

                            if (isset($clidoctip)) {
                                if ($clidoctip == $item['valor'])
                                    $selected = 'selected = \"selected\"';
                            }else if (isset($padrao_valor_clidoctip)) {
                                if ($padrao_valor_clidoctip == $item['valor']) {
                                    $selected = 'selected = \"selected\"';
                                }
                            }
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                        <?php } ?>
                    </select> 
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clidocnum"  <?= $pro_clidocnum ?>><?= $rot_clidocnum ?></label> 
                <div class="col-md-11 col-sm-12"> 
                    <input class="form-control" maxlength="12" type="text" name="clidocnum"   data-validation-depends-on="clidoctip" data-validation="required"  id="clidocnum" value="<?= $clidocnum ?? $padrao_valor_clidocnum ?? '' ?>" placeholder="<?= $for_clidocnum ?>"  <?= $pro_clidocnum ?> <?= $val_clidocnum ?>" aria-campo-padrao-valor ="<?= $campo_padrao_valor_clidocnum ?>"  aria-padrao-valor="<?= $padrao_valor_clidocnum ?? '' ?>" />
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label class="control-label col-md-12 col-sm-12" for="clicadpap" <?= $pro_clicadpap ?>><?= $rot_clicadpap ?></label>
                <div class="col-md-12 col-sm-12"> 
                    <select class="form-control" name="clicadpap" id="clicadpap" <?= $pro_clicadpap ?> aria-campo-padrao-valor ="<?= $campo_padrao_valor_clicadpap ?>"  aria-padrao-valor="<?= $padrao_valor_clicadpap ?? '' ?>"> 
                        <option value=""></option>
                        <?php
                        foreach ($cliente_papeis_lista as $item) {

                            $selected = "";

                            if (isset($clicadpap)) {
                                if ($clicadpap == $item['valor'])
                                    $selected = 'selected = \"selected\"';
                            }else if (isset($padrao_valor_clicadpap)) {
                                if ($padrao_valor_clicadpap == $item['valor']) {
                                    $selected = 'selected = \"selected\"';
                                }
                            }
                            ?>
                            <option value="<?= $item["valor"] ?>" <?= $selected ?>><?= $item["rotulo"] ?> </option> 
                        <?php } ?>
                    </select> 
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-9 col-sm-4"></div>
                <div class="pull-left col-md-3 col-sm-4">
                    <input class="form-control btn-primary" type="button" value="<?= $rot_gerpesbot ?>" onclick="clicadpes_ajax()">
                </div>
            </div>
        </div>

        <?php //Se a busca a ser feita Ã© por ajax, os elementos sÃ£o preenchidos dinamicamente  ?>
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="col-md-12 col-sm-12">
                    <table class="table_cliclipes" id="table_clicadpes_ajax" style="display:none;">                       
                        <thead>
                            <tr class="tabres_cabecalho2">
                                <th><?= $rot_clicadcod ?></th>
                                <th><?= $rot_cliprinom ?></th>
                                <th><?= $rot_clisobnom ?></th>
                                <th><?= $rot_clicadema ?></th>
                                <th><?= $rot_clidoctip ?></th>
                                <th> <?= $rot_clidocnum ?></th>
                                <th><?= $rot_clicpfcnp ?></th>
                            </tr>
                        </thead>
                        <tbody id="table_cliclipes_tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

<?php } ?>

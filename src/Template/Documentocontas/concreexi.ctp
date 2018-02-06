<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>
<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>
<div class="content_inner">
    <div style="margin-bottom: 15px">
        <form method="POST" name="concreexi" id="concreexi"  action="<?= Router::url('/', true) ?>documentocontas/concreexi" class="form-horizontal">
            <input type="hidden" id="pesquisar_creditos" name="pesquisar_creditos" value="<?= $pesquisar_creditos ?? 'no' ?>">
            <input type="hidden" name="geracever_conitemod" value="<?= $geracever_conitemod ?>" />
            <input type="hidden" name="geracever_coniteexc" value="<?= $geracever_coniteexc ?>" />
            <input type="hidden" id="form_atual" name="form_atual" value="concreexi" />
            <input type="hidden" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
            <input type="hidden" id="export_csv" value="0" name="export_csv" />
            <div class="form-group" id="linha-1">
                <div class='col-md-12 col-sm-12'>
                    <b><?= $rot_gerclitit ?></b>
                </div>
                <div class="row form-group">
                    <input id='c_codigo' name='c_codigo' type="hidden" value="<?= $c_codigo ?? '' ?>" />
                    <label class="control-label col-md-1 col-sm-3" for="cliprinom" <?= $pro_cliprinom ?>><?= $rot_cliprinom ?>:</label>
                    <div class="col-md-2 col-sm-9">    
                        <input  class="form-control input_autocomplete" id='c_nome_autocomplete' required="true" type="text" name="cliprinom" value="<?= $cliprinom ?? '' ?>" placeholder="<?= $for_cliprinom ?>" <?= $pro_cliprinom ?> <?= $val_cliprinom ?> /> 
                    </div>  
                    <div class="col-md-1">
                        <button class="<?= $ace_clicadpes ?> clicadpes"  aria-cliente-codigo-id='c_codigo' aria-cliente-nome-id='c_nome_autocomplete' aria-cliente-cpf-cnpj-id='' type="button">
                            <span class='ui-icon ui-icon-search'></span>
                        </button>
                    </div>
                </div>
                <div class="pull-left">
                    <input class="form-control btn-primary  submit-button"  aria-form-id="concreexi"  type="submit" name="btn_exi" id="btn_exi_cred" value="<?= $rot_gerexebot ?>">
                </div>
            </div>
        </form>
    </div>
    <?php
    if (isset($pesquisa_creditos)) {
        echo $this->element('conta/concreexi_elem', ['pesquisa_creditos' => $pesquisa_creditos,
            'back_page' => "documentocontas/concreexi/", 'has_form' => '1', 'id_form' => 'concreexi', 'has_link' => 1, 'cliente' => $cliente_pagamento]);
    }
    ?>
</div>
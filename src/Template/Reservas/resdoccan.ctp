<form id='estchicri' action="" method="post">
    <input type="hidden" name="opened_acordions" id="opened_acordions" value="" />
    <!--<input type="hidden" name="cancelamento_limite" id="cancelamento_limite" value="<?= $cancelamento_limite ?>" />
    <input type="hidden" name="cancelamento_valor" id="cancelamento_valor" value="<?= $cancelamento_valor ?>" />-->

    <?php
    echo $this->element('conta/conresexi_elem', ['pesquisa_contas' => $pesquisa_contas, 'geracever_conitecri' => '', 'redirect_page' => '/reservas/resdocpes',
        'opcao_add_conta' => false, 'back_page' => '', 'has_form' => '0', 'form_id' => '', 'has_link' => 0, 'evento' => 2, 'modo_exibicao' => 'dialog', 'tela' => 'resdoccan']);
    ?>

    
</form>


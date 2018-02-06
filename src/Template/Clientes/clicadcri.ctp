<?php
	use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
        echo $tela_nome;
    ?>
</h1>
<div class="content_inner">

<?php
echo $this->element('cliente/clicadcri_elem', ['action_form' => Router::url('/', true).'clientes/clicadcri', 'type_button_salvar' => 'submit', 'tipo_salvar' => 'post']);

?>
</div>
<?php if (isset($retorno_footer)) {
    echo '<p class="col-xs-12 msg_footer">'.$retorno_footer['mensagem'].'</p>';
}
?>
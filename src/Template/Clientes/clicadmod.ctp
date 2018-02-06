<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">
    <?php
    echo $tela_nome;
    ?>
</h1>

<div class="content_inner">
    <div class="formulario">
        <?php
        echo $this->element('cliente/clicadmod_elem', ['action_form' => Router::url('/', true) . 'clientes/clicadmod', 'type_button_salvar' => 'submit',
            'ver_reservas' => true, 'tipo_salvar' => 'post']);
        ?>
    </div>
</div>
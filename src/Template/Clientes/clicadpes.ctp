<?php if (!$is_dialog) { ?>
    <h1 class="titulo_pag">
        <?php
        echo $tela_nome;
        ?>
    </h1>
<?php } 
    
    echo $this->element('cliente/clicadpes_elem', ['is_dialog' => $is_dialog]);

    ?>

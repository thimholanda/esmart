<?php
$colunas = explode('|', $ordenacao_coluna);
$tipos = explode('|', $ordenacao_tipo);
?>
<th class='order-link' aria-ordenacao-coluna='<?= $aria_ordenacao_coluna ?>' aria-form-id='<?= $aria_form_id ?>' style="<?= $propriedade??'' ?>">
    <div><a href='#'>
            <?= $label ?></a></div>
    <?php
    $chave = array_search($aria_ordenacao_coluna, $colunas);
    if ($chave !== false) {
        if ($tipos[$chave] == 'asc')
            echo '<i class="fa fa-sort-asc" aria-hidden="true"></i>';
        else
            echo '<i class="fa fa-sort-desc" aria-hidden="true"></i>';
    } else
        echo '<i class="fa fa-sort" aria-hidden="true"></i>';
    ?>
</th>
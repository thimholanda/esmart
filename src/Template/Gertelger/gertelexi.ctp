<?php

use Cake\Routing\Router;
?>
<?= $this->Html->script('admin') ?>
<div style="margin-top: 15px">
    <b><a href="/esmart/gertelger/index">Voltar</a></b>
    <h3>Listagem de Telas</h3>

    <div style="float: left;">
        <h3>Telas existentes</h3>
        <table style="text-align: center" class="list-elementos">
            <tr>
                <th style="width: 25%">Código</th>
                <th style="width: 64%">Nome</th>
                <th style="width: 20%">Ações</th>
            </tr>
            <?php foreach ($telas_existentes as $tela) { ?>
                <tr>
                    <td><?= $tela->tela_codigo ?></td>
                    <td><?= $tela->tela_nome ?></td>
                    <td><form action="<?= Router::url('/', true) ?>gertelger/gertelatu/<?= $tela->tela_codigo ?>"><input type="submit" value="Editar" /></form></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
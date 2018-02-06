<?= $this->Html->script('admin') ?>
<div style="margin-top: 15px">
    <b><a href="/esmart/gertelger/index">Voltar</a></b>
    <h3>Novos elementos criados</h3>

    <div >
        <code><?= $elementos_criados ?></code>    
    </div>

    <h3>SQL da relação entre elementos e telas</h3>
    <div>
        <code>
            <?php
            foreach ($consulta_1 as $consulta) {
                echo($consulta . ";<br/>");
            }
            ?>
            <?php
            foreach ($consulta_2 as $consulta) {
                echo($consulta . ";<br/>");
            }
            ?>

        </code>
    </div>



    <h3>Código gerado para a página</h3>

    <div >
        <code><?= $codigo_gerado ?></code>    
    </div>
</div>
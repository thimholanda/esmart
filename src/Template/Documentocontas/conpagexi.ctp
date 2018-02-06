<?php

use Cake\Routing\Router;
?>
<h1 class="titulo_pag">


</h1>
<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>
<form method="POST" name="conpagexi" id="conpagexi" action="<?= Router::url('/', true) ?>documentocontas/conpagexi" class="form-horizontal">
    <input type="hidden" name="pagina_referencia" id="pagina_referencia" value="<?= $pagina_referencia ?>" />
    <?php
    echo $this->element('conta/conpagexi_elem', array());
    ?>
</form>
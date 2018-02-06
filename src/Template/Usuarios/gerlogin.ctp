<?php

use Cake\Network\Session;
use Cake\Routing\Router;

$session = new Session();
?>
<form name="login" class="pms_login" method="POST" autocomplete="off" id="gerlogin" action="gerlogin">
    <label> <?= $rot_gerlogtit ?></label>
    <label>
        <input type="text" name="gerusulog" value="<?= $gerusulog??'' ?>">
    </label>
    <label> <?= $rot_gerlogsen ?></label>
    <label><input type="password" name="gerlogsen"></label>
    <?php if (isset($retorno)) { ?>
        <div>
            <span><b><?= $retorno ?></b>

            </span>
        </div>
    <?php } ?>
    <label><?= $rot_geridicod ?>:
        <select id="usuario_idioma" name="usuario_idioma" onchange="$('#gerlogin').attr('action', 'altera_idioma'); $('#gerlogin').submit();">
            <option value="pt"<?= ($usuario_idioma == "pt" ? " selected" : "") ?>>pt</option>
            <option value="en"<?= ($usuario_idioma == "en" ? " selected" : "") ?>>en</option>
        </select></label>

    <?php if (isset($redefinir_senha) && $redefinir_senha == 1) { ?>
        <input type="submit" onclick="form.action = '<?= Router::url('/', true) ?>usuarios/gerredsen?login=<?= $login_redefinir_senha ?>&empresa=<?= $empresa_redefinir_senha ?>'; form.submit()" value="<?= $rot_gerredsen ?>">
    <?php } else { ?>
        <input type="submit" value="<?= $rot_gerlogbot ?>">
    <?php } ?>
</form>
<?php

use Cake\Routing\Router;
?>

<form name="login" class="pms_login" method="POST" autocomplete="off" id="gersenmod" action="<?= Router::url('/', true) ?>usuarios/gersenmod">
    <input type="hidden" name="empresa_codigo" value="<?= $empresa_codigo ?>" />
    <input type="hidden" name="acesso_objeto" value="<?= $usuario_login ?>" />
    <input type="hidden" name="acesso_chave" value="<?= $acesso_chave ?>" />
    <input type="hidden" name="usuario_login" value="<?= $usuario_login ?>" />
    <div>
        <label>Login</label>
        <input type="text" name="gerusulog" value="<?= $usuario_login ?>" disabled="disabled" >
    </div>
    <div>
        <label>Senha</label>
        <input type="password" id="senha" name="gerususen_confirmation"  data-validation="strength" data-validation-strength="3" maxlength="12" data-validation-error-msg="<?= $senha_fraca_mensagem ?>">
        <div id="forca_senha" style="width:100%" class="strength-meter">
        </div>
    </div>
    <div>
        <label>Repetir senha</label>
        <input type="password" name="gerususen" maxlength="12" data-validation="confirmation" data-validation-error-msg="<?= $senha_incompativel_mensagem ?>">
    </div>
    <input type="submit" value="<?= $rot_gersalbot ?>">
</form>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?= $this->Html->script('jquery-form-validator') ?>
<script>
    $.validate({
        modules: 'security',
        onModulesLoaded: function () {
            var optionalConfig = {
                fontSize: '10pt',
                padding: '2px 44%',
                bad: 'Ruim',
                weak: 'Fraca',
                good: 'Boa',
                strong: 'Forte'
            };

            $('input[name="gerususen_confirmation"]').displayPasswordStrength(optionalConfig);
        }
    });
</script>

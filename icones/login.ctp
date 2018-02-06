<form name="login" class="pms_login" method="POST" autocomplete="off" id="gerlogin" action="gerlogin">
    <label<?=$pro_gerusucod ?>><input type="text" size="20" name="txtuser_" value="<?=$usuario_codigo;?>"<?=$pro_gerusucod?> placeholder="Login"></label>
    <label<?=$pro_gerlogsen?>><input type="password" size="20" name="txtpwd_" value="<?=$usuario_senha;?>"<?=$pro_gerlogsen?> placeholder="Password"></label>
    
    <label<?=$pro_geridicod?>><?=$rot_geridicod?>:
        <select id="usuario_idioma" name="usuario_idioma"<?=$pro_geridicod?> onchange="$('#gerlogin').attr('action', 'altera_idioma'); $('#gerlogin').submit();">
            <option value="pt"<?=($usuario_idioma=="pt"?" selected":"")?>>pt</option>
            <option value="en"<?=($usuario_idioma=="en"?" selected":"")?>>en</option>
        </select></label>
    <input type="submit" value="<?=$rot_gerlogbot?>">
    <!--<input type="reset" value="<?=$rot_gerlimbot?>">-->
</form>


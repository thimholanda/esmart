<form style='margin-top: 15px' name="login" class="pms_login" method="POST" autocomplete="off" id="gerlogin" action="login">
    <table width="80%" border=0>
        <tr><td colspan=2></td></tr>
        <tr><td><label<?=$pro_gerusucod ?>><?=$rot_gerusucod?>:</label></td><td><input type="text" size="20" name="txtuser_" value="<?=$usuario_codigo;?>"<?=$pro_gerusucod?>></td></tr>
        <tr><td><label<?=$pro_gerlogsen?>><?=$rot_gerlogsen?>:</label></td><td><input type="password" size="20" name="txtpwd_" value="<?=$usuario_senha;?>"<?=$pro_gerlogsen?>></td></tr>
        <tr><td colspan=2>
                <label<?=$pro_geridicod?>><?=$rot_geridicod?>:</label>
                <select id="usuario_idioma" name="usuario_idioma"<?=$pro_geridicod?> onchange="$('#gerlogin').attr('action', 'altera_idioma'); $('#gerlogin').submit();">
                    <option value="pt"<?=($usuario_idioma=="pt"?" selected":"")?>>pt</option>
                    <option value="en"<?=($usuario_idioma=="en"?" selected":"")?>>en</option>
                </select>
            </td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan=2>
                <input type="submit" value="<?=$rot_gerlogbot?>">&nbsp;
                <input type="reset" value="<?=$rot_gerlimbot?>">
            </td>
        </tr>
    </table>
</form>


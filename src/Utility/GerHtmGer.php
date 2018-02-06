<?php

namespace App\Utility;
use App\Model\Entity\Geral;


class GerHtmGer {
    /*
     * Gera os campos de determinada tela
     */

    public function gerhtmger() {
        $gerar_campos = array("clidocorg",
            "clicadocu", "clicadnac","clinacdat",
            "clicadend", "clicadbai", "clicadcid", "clicadpai", "clicadpai","clicadcep");
        $retorno_gerador = explode("|", htmlspecialchars(GerHtmGer::germulinp($gerar_campos)));
        
        $retorno_completo = "";
        for ($i = 0; $i < sizeof($retorno_gerador); $i++) {
            $retorno_completo .= $retorno_gerador[$i] . "<br/><br/>";
        }

        $retorno_completo .= htmlspecialchars(GerHtmGer::gerinpsel('clidoctip', 'documento_tipo_lista',
        'cliente_documento_tipo', 'cliente_documento_tipo',
        Geral::gercamdom('clidoctip', 'clidoctip')));
        return $retorno_completo;

    }

    /*
     * Gera múltiplos input do tipo texto
     */

    public function germulinp($elementos) {
        
        $retorno = "";
        for ($i = 0; $i < sizeof($elementos); $i++) {
            
            $retorno .= GerHtmGer::gerinptxt($elementos[$i], "|", $elementos[$i]);
        }
        return $retorno;
    }

    /*
     * Gera um input do tipo texto
     */

    public function gerinptxt($elemento_nome, $separador = "", $value = null) {
        return "<label class='control-label col-md-1 col-sm-3' "
                . "for=\"$elemento_nome\" "
                . "<?=\$pro_" . $elemento_nome . "?>>"
                . "<?=\$rot_" . $elemento_nome . "?>:
                </label>
                <div class='col-md-2 col-sm-3'>
                <input class='form-control'
                    type=\"text\"
                    name=\"$elemento_nome\"
                    id=\"$elemento_nome\" "
                . "value=\"<?=\$" . $value . "??''?>\"
                    placeholder=\"<?=\$for_" . $elemento_nome . "?>\"
                    onkeypress=\"<?= \$for_" . $elemento_nome . " != '' ? 'formatar(\''.\$for_" . $elemento_nome . ".'\',this,false)' : '' ?>\"
                    <?=\$pro_" . $elemento_nome . "?>
                    <?=\$val_" . $elemento_nome . "?> /></div>$separador";
    }

    /*
     * Gera um input do tipo select
     */

    public function gerinpsel($elemento_nome, $lista, $label_option, $item_selecionado) {
        if ($item_selecionado == "") {
            $item_selecionado = "\" \"";
        }
        return "<label class='control-label col-md-1 col-sm-3' "
                . "for=\"$elemento_nome\" "
                . "<?=\$pro_" . $elemento_nome . "?>>"
                . "<?=\$rot_" . $elemento_nome . "?>:
             </label>
             <div class='col-md-2 col-sm-3'>
        <select class='form-control'
            <?=\$pro_" . $elemento_nome . "?>
            name=\"$elemento_nome\"
            id=\"$elemento_nome\">
            <option value=\"\"></option>
            <?php foreach(\$" . $lista . " as \$item) { 
                if(\$item[\"$label_option\"] == $item_selecionado){ ?>
                     <option selected='selected' 
                        value=\"<?=\$item[\"$label_option\"]?>\"><?=\$item[\"$label_option\"]?>
                     </option>
                <?php }else{ ?>
                    <option 
                        value=\"<?=\$item[\"$label_option\"]?>\"><?=\$item[\"$label_option\"]?>
                    </option>
            <?php } } ?>
      </select></div>";
    }

    /*
     * Gera um input do tipo checkbox
     */

    public function gerinpchk($elemento_nome) {
        return "<input type='checkbox' "
                . "name=\"$elemento_nome\""
                . " id=\"$elemento_nome\""
                . " <?=\$pro_" . $elemento_nome . "?>>"
                . "<lablel "
                . "for=\"$elemento_nome\" "
                . "<?=\$pro_" . $elemento_nome . "?>><?=\$rot_" . $elemento_nome . "?>"
                . "</label>";
    }
    
    /*
     * Gera a impressão de um elemento de texto
     */
    public function gertextex($elemento_nome){
        return "<span><?= \$rot_$elemento_nome ?></span>";
    }

}

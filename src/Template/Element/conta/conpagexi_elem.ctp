<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>

<h1 class="titulo_pag" style="margin-top: -30px;">
<?= $rot_conpagdad ?>
</h1>


<!--<div class="content_inner">-->
<div style="margin-bottom: 15px">
   <div class='conteudoB mr1'>
        <div class = "col-md-12 ">
            <div class="col-md-4 conteudo">
                <?= $rot_gerdattit . ": " ?> <b><?= date('d/m/Y', strtotime($gerdattit)) ?></b>
            </div>
            <div class="col-md-4 conteudo">
                <?php if ($pagamento_forma_codigo != 7) { ?>
                   <?= $rot_resdocnum . ": " ?> <b><?= $resdocnum ?> - <?= $quarto_item ?></b>
                <?php } else { ?>
                   <?= $rot_resdocnum . ": " ?> <b><?= $transferencia_documento_numero ?> - <?= $transferencia_quarto_item ?></b>
                <?php } ?>
                <?= $rot_geritetit . ": " ?> <b><?= $geritecon ?></b>
            </div>
            <div class="col-md-4 conteudo">
                <?= $rot_respagnom . ": " ?> <b><a href="#a" class="clicadmod link_ativo close_dialog_redirect_page" aria-cliente-codigo = '<?= $respagcli ?>'><?= $pagante_nome . ' ' . $pagante_sobrenome ?></a></b>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-4 conteudo">
                <?= $rot_gervaltit . ": " ?> <b><?= $geral->germoeatr() ?> <?= $geral->gersepatr($gervaltit) ?></b>
            </div>
            <div class="col-md-4 conteudo">
                <?= $rot_respagfor . ": " ?> <b><?= $respagfor ?></b>
            </div>
            <div class="col-md-4 conteudo">
                <?= $rot_rescarvez . ": " ?> <b><?= $rescarvez ?></b>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-4 conteudo">
               <?= $rot_resnomcom . ": " ?> <b><?= $resnomcom ?></b>
            </div>
            <div class="col-md-4 conteudo">
               <?= $rot_rescarnum . ": " ?> <b><?= $rescarnum ?></b>
            </div>
            <div class="col-md-4 conteudo">
               <?= $rot_rescarval . ": " ?> <b><?= $rescarval ?></b>
            </div>

        </div>

        <div class="col-md-12">
            <div class="col-md-4 conteudo">
               <?= $rot_respagbnc . ": " ?> <b><?= $respagbnc ?></b>
            </div>
            <div class="col-md-4 conteudo">
               <?= $rot_respagagc . ": " ?> <b><?= $respagagc ?></b>
            </div>
            <div class="col-md-4 conteudo">
               <?= $rot_respagcco . "/Dígito: " ?> <b><?= $respagcco ?></b>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-4 conteudo">
               <?= $rot_conresdep . ": " ?> <b><?= $conresdep ?></b>
            </div>
        </div>

</div>
<!--</div>-->

<?php
if (count($pesquisa_fnrhs) > 0) {
    ?>
    <div>
        <table class="table_cliclipes">
            <thead>
                <tr>
                    <th><?= $rot_gerenvtit ?></th>
                    <th><?= $rot_resentdat ?></th>
                    <th><?= $rot_ressaidat ?></th>
                    <th><?= $rot_gertitmod ?></th>
                    <th><?= $rot_hostittit ?></th>
                    <th><?= $rot_clicpfnum ?></th>
                    <th><?= $rot_clicadema ?></th>
                </tr>
            </thead>
            <?php foreach ($pesquisa_fnrhs as $value) { ?>
                <tr>
                    <!-- REMOVER O REDIRECT TO CONTROLLER -->
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')"><?= date('d/m/Y H:i:s', strtotime($value['envio_data'])) ?></td>
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">&nbsp;<!--<?= date('d/m/Y', strtotime($value['inicial_data'])) ?>--></td>
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">&nbsp;<!--<?= date('d/m/Y', strtotime($value['final_data'])) ?>--></td>
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">&nbsp;<?= $value['snnumdoc'] ?></td>
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">&nbsp;<?= $value['snnomecompleto'] ?></td>
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">&nbsp;<?= $value['snnumcpf'] ?></td>
                    <td onclick="redirectToController('/estadia/estfnrmod/<?= $value["fnrh_codigo"] ?>', '<?= $id_form ?>', '<?= $back_page ?>', '<?= $has_form ?>')">&nbsp;<?= $value['snemail'] ?></td>

                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
} else if (isset($pesquisa_fnrhs) && (sizeof($pesquisa_fnrhs) == 0)) {
    ?>
    Nenhuma fnrh encontrada
    <?php
}
?>


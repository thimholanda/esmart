<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>
        <!-- Page Content -->
        <div id="content">
            <div>
                <?= $this->fetch('content') ?>
            </div>
        </div>
        <?= $this->element('footer') ?>
    </body>
    <?php

    use Cake\Network\Session;

$session = new Session();
    if ($session->check('retorno')) {
        print('<script>$(document).ready(function () {' . $session->read('retorno') . '});</script>');
        $session->delete('retorno');
    }
    ?>
</html>
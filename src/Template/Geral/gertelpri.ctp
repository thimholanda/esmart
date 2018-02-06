
<?php

use Cake\Network\Session;

$session = new Session();


if ($session->check('retorno_footer') && is_string($session->read('retorno_footer'))) {
    echo '<p class="col-xs-12 msg_footer" style=\'z-index:999999999\'>' . $session->read('retorno_footer'). '</p>';
    $session->delete('retorno_footer');
}
?>
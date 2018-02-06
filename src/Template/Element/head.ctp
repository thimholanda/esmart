<?php

use Cake\Network\Session;
use Cake\Routing\Router;

$session = new Session();

$version = 1.88;

?>
<title><?= $rot_gerteltit ?? 'e-Smart PMS' ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
<script>web_root = '<?= $this->request->webroot ?>';</script>
<script>web_root_complete = '<?php echo Router::url('/', true); ?>';</script>
<script>decimal_separador = '<?= $session->read('decimal_separador') ?>';</script>

<?= $this->Html->script('jquery-1.11') ?>
<?= $this->Html->script('bootstrap.min') ?>
<?= $this->Html->script('jquery-ui') ?>

<?= $this->Html->script('jqueryDataTable.min') ?>
<?= $this->Html->script('jquery.numeric.min') ?>
<?= $this->Html->script('jquery.mask.min') ?>

<?= $this->Html->script(['layout.js?v='.$version, 'generic.js?v='.$version, 'geral.js?v='.$version, 'reserva.js?v='.$version, 'servico.js?v='.$version,
        'documento.js?v='.$version, 'cliente.js?v='.$version, 'estadia.js?v='.$version, 'conta.js?v='.$version, 'custo.js?v='.$version, 'produto.js?v='.$version]) ?>

        <?= $this->Html->script('maskMoney') ?>
<!--<?= $this->Html->script('perfect-scrollbar.jquery.min') ?>-->
<?= $this->Html->script('perfect-scrollbar.min') ?>
<?= $this->Html->script('bootstrap-select') ?>

<?= $this->Html->css('perfect-scrollbar.min'); ?>
<?= $this->Html->css('bootstrap.min'); ?>
<?= $this->Html->css('jquery-ui'); ?>
<?= $this->Html->css('dataTable.min'); ?>
<?= $this->Html->css('sumoselect.min'); ?>
<?= $this->Html->css('font-awesome.min'); ?>
<?= $this->Html->css('bootstrap-select'); ?>
<?= $this->Html->css('main'); ?>
<?= $this->Html->css('select2'); ?>
<?= $this->Html->css(['style.css?v='.$version, 'style-fernando.css?v='.$version]) ?>
<?= $this->Html->script('moment-with-locales') ?>
<?= $this->Html->script('moment-range') ?>
<?= $this->Html->script('jquery-ui-drag-drop.min') ?>
<?= $this->Html->script('modal') ?>
<?= $this->Html->script('jquery-form-validator') ?>
<?= $this->Html->script('select2') ?>
<?= $this->Html->script('jquery-timepicker') ?>

<body>

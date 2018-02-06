<?php

use App\Model\Entity\Estadia;
use App\Model\Entity\Reserva;
use Cake\Routing\Router;
use App\Utility\Util;

$reserva = new Reserva();
$estadia = new Estadia();
?>

<a href="#" class="reservas_icones <?php
if (!$estadia->estalohab($quarto_item['final_data'], $quarto_item['documento_status_codigo']))
    echo ' disabled_icon_link ';
else
    echo 'alocacao'
    ?>" title="Alocação" aria-salva-pagina="0" aria-documento-numero='<?= $quarto_item["documento_numero"] ?>' aria-quarto-item='<?= $quarto_item["quarto_item"] ?>'
   aria-quarto-tipo-comprado='<?= $quarto_item["quarto_tipo_codigo"] ?>'  aria-string-alocacao='<?= $string_alocacao ?>' aria-string-tipos-alocados='<?= $string_quartos_tipos_alocados ?>' aria-salva-pagina = '0'><i class="fa fa-bullseye fa-lg fa-fw" style="font-size: 1.233333em;width: 0.9571429em;"></i></a>
<a href="#" class="reservas_icones <?= $ace_estchicri ?>  <?php
if (!$estadia->estchihab($quarto_item['inicial_data'], $quarto_item['final_data'], $quarto_item['documento_status_codigo']))
    echo ' disabled_icon_link ';
else
    echo 'checkin'
    ?>" aria-documento-numero='<?= $quarto_item["documento_numero"] ?>' aria-quarto-item='<?= $quarto_item["quarto_item"] ?>' 
   aria-quarto-codigo='<?= $quarto_item["quarto_codigo"] ?>'  aria-string-alocacao='<?= $string_alocacao ?>'   aria-string-tipos-alocados='<?= $string_quartos_tipos_alocados ?>'  aria-quarto-tipo-comprado='<?= $quarto_item["quarto_tipo_codigo"] ?>' title="Check-in"><i class="fa fa-sign-in fa-lg fa-fw" style="font-size: 1.233333em;width: 0.9571429em;"></i></a>
<a href="<?= Router::url('/', true) ?>?page=documentocontas/conresexi&resdocnum=<?= $quarto_item['documento_numero'] ?>&permite_busca=1&opened_acordions=<?= $quarto_item["quarto_item"] . '|' ?>" class="reservas_icones conta" aria-documento-numero="<?= $quarto_item["documento_numero"] ?>"  aria-quarto-item='<?= $quarto_item["quarto_item"] ?>'  title="Conta"><i class="fa fa-dollar fa-lg fa-fw" style="font-size: 1.233333em;width: 0.9571429em;"></i></a>

<a href="#" class="reservas_icones <?php
if (!$estadia->estchohab($quarto_item['quarto_status_codigo']))
    echo ' disabled_icon_link ';
else
    echo 'checkout'
    ?>" aria-documento-numero="<?= $quarto_item["documento_numero"] ?>" aria-quarto-item="<?= $quarto_item["quarto_item"] ?>" title="Check-out"><i class="fa fa-sign-out fa-lg fa-fw" style="font-size: 1.233333em;width: 0.9571429em;"></i></a>

<a href="#" onclick="$('#check_<?= $quarto_item['documento_numero'] ?>_<?= $quarto_item['quarto_item'] ?>').prop('checked', true);" class="reservas_icones   <?php
if (!$reserva->rescanhab($quarto_item['quarto_status_codigo']))
    echo ' disabled_icon_link ';
else
    echo 'reserva_cancelar'
    ?>" aria-documento-numero="<?= $quarto_item["documento_numero"] ?>"  aria-quarto-item='<?= $quarto_item["quarto_item"] ?>'
   title="Cancelar"><i class="fa fa-trash fa-lg fa-fw" style="font-size: 1.233333em;width: 0.9571429em;"></i></a>
<a href="#" class="reservas_icones" aria-documento-numero="<?= $quarto_item["documento_numero"] ?>" 
   aria-quarto-item='<?= $quarto_item["quarto_item"] ?>'  title="Informações"><i class="fa fa-exclamation fa-lg fa-fw" style="font-size: 1.233333em;width: 0.9571429em;"></i></a>
<a href="#" class="reservas_icones resveiexi" aria-documento-numero="<?= $quarto_item["documento_numero"] ?>" 
   aria-quarto-item='<?= $quarto_item["quarto_item"] ?>'  title="Veículos"><i class="fa fa-car fa-lg fa-fw" style="font-size: 1.233333em;width: 0.8571429em;margin-right: 6px"></i></a>

<a href="#" class="reservas_icones cusresexi" aria-documento-numero="<?= $quarto_item["documento_numero"] ?>" 
   aria-quarto-item='<?= $quarto_item["quarto_item"] ?>'  title="Custos"><i class="fa fa-industry fa-lg fa-fw" style="font-size: 1.233333em;width: 0.8571429em;margin-right: 6px"></i></a>
<input type="checkbox" style="display:none" class="check_doc" name="documentos[]" id="check_<?= $quarto_item['documento_numero'] ?>_<?= $quarto_item['quarto_item'] ?>" value="<?= $quarto_item['documento_numero'] . "-" . $quarto_item['quarto_item'] . "-" . $quarto_item['quarto_status_codigo'] . "-" . Util::convertDataDMY($quarto_item['cancelamento_limite'], 'd/m/Y H:i:s') . "-" . $quarto_item['cancelamento_valor'] ?>" />                

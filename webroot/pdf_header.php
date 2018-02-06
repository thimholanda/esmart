<?php 
    $caminho = (dirname(dirname(__FILE__)));
    $esmart_logo = '<img src="'. $caminho .'\webroot\img\logo-esmart.png" width="94px" height="32px" alt="E-Smart">';
?>

<style>
	* {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	}

	table {
		width: 100%;
	}

	table, tr, th, td {
		border-collapse: collapse;
		border: 1px solid black;
	}

	th {
		background-color: #333;
		color: white;
	}

	tr:nth-child(even) {
		background-color: #eee;
	}

	th, td {
		padding: 5px;
	}

	th {
		text-align: center;
	}

	h1 {
		margin-top: 0;
		padding-top: 0;
	}
</style>

<p style="margin-top: 0; padding-top: 0;">
    <div style="float: left;" > <h1><?= $empresa_nome; ?></h1> </div>
    
    <div style="float: right;"> <h1><?= $esmart_logo ?? 'E-Smart'; ?></h1> </div>
</p>
<p style="clear: both;">
    <h2 style="text-align: center"> <?= $nome_relatorio; ?> </h2>
</p>
<p style="margin: 0; padding: 0;">
    Filtros selecionados: 

	<div style="margin: 0; padding: 0; margin-left: 1cm;">
		<?= $texto_filtros; ?>
	</div>
</p>
<script type="text/php">
	if (isset($pdf)) {
	    $x = 240;
	    $y = 815;
	    $text = $PAGE_NUM .' de '. $PAGE_COUNT;
	    $font = 'serif';
	    $size = 12;
	    $pdf->page_text($x, $y, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, array(0,0,0));

	    $pdf->page_text(400, $y, 'Gerado em '. date('H:i d/m/Y'), $font, $size, array(0,0,0));
    }
 </script>


<?php //debug($filtro); ?>
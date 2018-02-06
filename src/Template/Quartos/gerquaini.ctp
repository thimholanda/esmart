<?php
	use App\Model\Entity\Geral;
	use Cake\Routing\Router;

?>
<style>
	.red {
		background-color: #e46e6c;;
	}
</style>

<div>
	<h1> <?= $rot_gerquatip; ?> </h1>
	<table class='table table-responsive'> 
		<tr>
			<th> <?= $rot_gerquacod; ?> </th>
			<th> <?= $rot_gerquanom; ?> </th>
		</tr>
		<?php foreach ($quartoTipos as $tipo) { 
			$classe = $tipo['excluido'] == 1 ? 'red' : ''; 
			$parametros = array($tipo['empresa_codigo'], $tipo['quarto_tipo_codigo']);
			?>
			<tr class="<?= $classe; ?>"
				onclick="redirectToController('quartos/gerqtpmod/<?= implode('/', $parametros) ?>', 
				'gerquaini', 
				'quartos/gerqtpmod', 
				1)">
				<td> <?= $tipo['quarto_tipo_codigo']; ?> </td>
				<td> <?= $tipo['quarto_tipo_nome']; ?> </td>
			</tr>
		<?php } ?>
	</table>
	<a class="btn btn-primary" onclick="redirectToController('quartos/gerqtpmod/novo', 
				'gerquaini', 
				'quartos/gerqtpmod', 
				1)" > <?= $rot_gerquanvt; ?> </a>
</div>

<div>
	<form method="POST" name="gerquaini" id="gerquaini" action="<?= Router::url('/', true); ?>quartos/gerquaini" class="form-horizontal form-atualizar" >
		<input type="hidden" id="pagina" value="1" name="pagina" />
		<input type="hidden" id="form_atual" name="form_atual" value="gerquaini" />
		<input type="hidden" id="form_force_submit" value="0" />
		<input type="hidden" id="ordenacao_coluna" value="<?= $ordenacao_coluna ?? 'quarto_codigo' ?>" name="ordenacao_coluna" />
		<input type="hidden" id="ordenacao_tipo" value="<?= $ordenacao_tipo ?? 'asc' ?>" name="ordenacao_tipo" />

		<h2> <?= $rot_gerquapsq; ?> </h2>
		<p> <?= $rot_gerquacod; ?>: <input type="text" name="gerquacod" value="<?= $pesq['gerquacod'] ?? ''; ?>" /> </p>
		<p> <?= $rot_gerquatip; ?>: 
			<select name="gerquatip">
				<option value="" > </option>
				<?php foreach ($tipoQuartos as $tipo) { ?>
					<option value="<?= $tipo['valor']; ?>"  <?php if ($tipo['valor'] == $pesq['gerquatip']) echo 'selected="selected"'; ?> > 
						<?= $tipo['rotulo']; ?>
					</option>
				<?php } ?>
			</select>
		</p>
		<p> <?= $rot_gerquaexc; ?>: <td><input type="checkbox" name="gerquaexc" value="1" <?php if ($pesq['gerquaexc'] === 1) echo 'checked="checked"'; ?> /></td> </p>

		<input type="submit" class="btn btn-primary submit-button" aria-form-id="gerquaini" id="gerquaini" name="gerquaini" value="Lupa" />
	</form>
</div>
<div>
	<h1> <?= $rot_gerquaqua; ?> </h1>
	<table class='table table-responsive'> 
		<tr>
			<th>
				<div class='order-link'>
					<a href='#' aria-ordenacao-coluna='Quartos.quarto_codigo' aria-form-id='gerquaini'> <?= $rot_gerquacod; ?> </a>
				</div>
			   <?php
					if ($ordenacao_coluna == 'Quartos.quarto_codigo') {
						if ($ordenacao_tipo == 'asc')
							echo '<div style="float:left" class="ui-icon ui-icon-triangle-1-n"></div>';
						else
							echo '<div style="float:left" class="ui-icon ui-icon-triangle-1-s"></div>';
					}
			    ?> 
			</th>
			<th>
				<div class='order-link'>
					<a href='#' aria-ordenacao-coluna='quarto_nome' aria-form-id='gerquaini'> <?= $rot_gerquanom; ?> </a>
				</div>
			   <?php
					if ($ordenacao_coluna == 'quarto_nome') {
						if ($ordenacao_tipo == 'asc')
							echo '<div style="float:left" class="ui-icon ui-icon-triangle-1-n"></div>';
						else
							echo '<div style="float:left" class="ui-icon ui-icon-triangle-1-s"></div>';
					}
			    ?> 
			</th>
			<th>
				<div class='order-link'>
					<a href='#' aria-ordenacao-coluna='quarto_tipo_nome' aria-form-id='gerquaini'> <?= $rot_gerquatip; ?> </a>
				</div>
			   <?php
					if ($ordenacao_coluna == 'quarto_tipo_nome') {
						if ($ordenacao_tipo == 'asc')
							echo '<div style="float:left" class="ui-icon ui-icon-triangle-1-n"></div>';
						else
							echo '<div style="float:left" class="ui-icon ui-icon-triangle-1-s"></div>';
					}
			    ?> 
			</th>
		</tr>
		<?php foreach ($quartos as $qua) {
			$classe = $qua['excluido'] == 1 ? 'red' : ''; 
			$parametros = array($qua['empresa_codigo'], $qua['quarto_codigo']);
			?>
			<tr class="<?= $classe; ?>"
				onclick="redirectToController('quartos/gerquamod/<?= implode('/', $parametros) ?>', 
				'gerquaini', 
				'quartos/gerquamod', 
				1)">
				<td> <?= $qua['quarto_codigo']; ?> </td>
				<td> <?= $qua['quarto_nome']; ?> </td>
				<td> <?= $qua['QuartoTipos']['quarto_tipo_nome']; ?> </td>
			</tr>
		<?php } ?>
	</table>
	<?php echo $paginacao; ?>

	<a class="btn btn-primary" onclick="redirectToController('quartos/gerquamod/novo', 
				'gerquaini', 
				'quartos/gerquamod', 
				1)" > <?= $rot_gerquanvq; ?> </a>
</div>

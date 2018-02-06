<?php
	use App\Model\Entity\Geral;
	use Cake\Routing\Router;
?>
<script>
	function validaCampos() {
		return 1;
	}
</script>
<div>
	<form method="POST" name="gerquamod" id="gerquamod" action="<?= Router::url('/', true); ?>quartos/gerquamod" class="form-horizontal form-atualizar" >
		<?php 
			$exc = '';
			if ($quarto['excluido'] == 1) {
				$exc = 'readonly="readonly" disabled="disabled"';
			}
		?>
		<p> <?= $rot_gerquacod; ?> </p>
			<input type="hidden" name="gerquausu" value="<?= $quarto['criacao_usuario']; ?>" />
			<input type="text" id="gerquacod" name="gerquacod" value="<?= $quarto['quarto_codigo']; ?>" <?= $pro_gerquacod; ?> <?php if ($quarto['quarto_codigo'] != 0) echo 'readonly="readonly"'; ?> />

		<p> <?= $rot_gerquanom; ?> </p>
			<input type="text" id="gerquanom" name="gerquanom" value="<?= $quarto['quarto_nome']; ?>" <?= $rot_gerquanom; ?> <?= $exc; ?> />

		<p> Grupo </p>
			<input type="text" id="gerquagru" name="gerquagru" value="<?= $quarto['quarto_grupo']; ?>" <?= $exc; ?> />

		<p> <?= $rot_gerquatip; ?> </p>
			<select id="gerquatip" name="gerquatip" <?= $pro_gerquatip; ?> <?= $exc; ?> >
				<option selected="selected" value=""> </option>
				<?php foreach ($tipoQuartos as $tipo) { ?>
					<option value="<?= $tipo['valor']; ?>"  <?php if ($tipo['valor'] == $quarto['quarto_tipo_codigo']) echo 'selected="selected"'; ?> > 
						<?= $tipo['rotulo']; ?>
					</option>
				<?php } ?>
			</select>

		<p>
			<br />
			<a class="btn btn-warning" onclick="gerpagexi('/quartos/gerquaini', 1, {})" > Voltar </a>
			<?php if ($quarto['quarto_codigo'] != 0) {
					if ($quarto['excluido'] == 0) { ?>
						<input type="button" class="btn btn-danger"  onclick="redirectToController(
							'quartos/gerquamod?del=1&cod=<?= $quarto['quarto_codigo']; ?>', 
							'gerquamod',
							'quartos/gerquamod'
							)" value="<?= $rot_gerquaexc; ?>" />

						<input type="submit" class="btn btn-primary submit-button" aria-form-id="gerquamod" id="gerquabtn" name="gerquabtn" value="<?= $rot_gerquaslv; ?>" onclick="return validaCampos()"/>
					<?php } else { ?>
						<input type="button" class="btn btn-success"  onclick="redirectToController(
							'quartos/gerquamod?del=0&cod=<?= $quarto['quarto_codigo']; ?>', 
							'gerquamod',
							'quartos/gerquamod'
							)" value="<?= $rot_gerquartv; ?>" />
					<?php } ?>
			<?php } else { ?> 
				<input type="submit" class="btn btn-primary submit-button" aria-form-id="gerquamod" id="gerquabtn" name="gerquabtn" value="<?= $rot_gerquaslv; ?>" onclick="return validaCampos()"/>
			<?php } ?>
		</p>
	</form>
</div>
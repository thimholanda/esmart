<?php
	use App\Model\Entity\Geral;
	use Cake\Routing\Router;
?>
<script>
	function gerarProximoCodigo() {
		callAjax('ajax/ajaxgerqtpprx', {empresa_codigo: <?= $tipo['empresa_codigo']; ?>}, function(html) {
			$("#gerqtpcod").val(html);
		});
	}

	function validaCampos() {
		return 1;
	}
</script>
<div>
	<form method="POST" name="gerqtpmod" id="gerqtpmod" action="<?= Router::url('/', true); ?>quartos/gerqtpmod" class="form-horizontal form-atualizar" >
		<?php 
			$exc = '';
			if ($tipo['excluido'] == 1) {
				$exc = 'readonly="readonly" disabled="disabled"';
			}
		?>
		<p> <?= $rot_gerqtpcod; ?> </p>
			<input type="hidden" name="gerqtpusu" value="<?= $tipo['criacao_usuario']; ?>" />
			<input type="text" id="gerqtpcod" name="gerqtpcod" value="<?= $tipo['quarto_tipo_codigo']; ?>" <?= $pro_gerqtpcod; ?> <?php if ($tipo['quarto_tipo_codigo'] != 0) echo 'readonly="readonly"'; ?> />
			<?php if ($tipo['quarto_tipo_codigo'] == 0) { ?>
				<a class="btn btn-primary" onclick="gerarProximoCodigo()" > P </a>
			<?php } ?> 

		<p> <?= $rot_gerqtpnom; ?> </p>
			<input type="text" id="gerqtpnom" name="gerqtpnom" value="<?= $tipo['quarto_tipo_nome']; ?>" <?= $pro_gerqtpnom; ?> <?= $exc; ?> />

		<p> <?= $rot_gerqtpnmc; ?> </p>
			<input type="text" id="gerqtpnmc" name="gerqtpnmc" value="<?= $tipo['quarto_tipo_curto_nome']; ?>" <?= $pro_gerqtpnmc; ?> <?= $exc; ?> />

		<p> <?= $rot_gerqtpmxa; ?> </p>
			<input type="number" id="gerqtpmxa" name="gerqtpmxa" min="0" value="<?= $tipo['adulto_quantidade']; ?>" <?= $pro_gerqtpmxa; ?> <?= $exc; ?> />

		<p> <?= $rot_gerqtpmxc; ?> </p>
			<input type="number" id="gerqtpmxc" name="gerqtpmxc" min="0" value="<?= $tipo['crianca_quantidade']; ?>" <?= $pro_gerqtpmxc; ?> <?= $exc; ?> />

		<p> <?= $rot_gerqtpacs; ?>
			<input type="checkbox" id="gerqtpacs" name="gerqtpacs" <?php if ($tipo['acesso_sequencia_codigo'] == 1) echo 'checked="checked"'; ?> <?= $exc; ?> />
		</p>

		<p>
			<br />
			<a class="btn btn-warning" onclick="gerpagexi('/quartos/gerquaini', 1, {})" > Voltar </a>
			<?php if ($tipo['quarto_tipo_codigo'] != 0) {
					if ($tipo['excluido'] == 0) { ?>
						<input type="button" class="btn btn-danger"  onclick="redirectToController(
							'quartos/gerqtpmod?del=1&cod=<?= $tipo['quarto_tipo_codigo']; ?>', 
							'gerqtpmod',
							'quartos/gerqtpmod'
							)" value="<?= $rot_gerqtpexc; ?>" />

						<input type="submit" class="btn btn-primary submit-button" aria-form-id="gerqtpmod" id="getqtpbtn" name="getqtpbtn" value="<?= $rot_gerqtpslv; ?>" onclick="return validaCampos()"/>
					<?php } else { ?>
						<input type="button" class="btn btn-success"  onclick="redirectToController(
							'quartos/gerqtpmod?del=0&cod=<?= $tipo['quarto_tipo_codigo']; ?>', 
							'gerqtpmod',
							'quartos/gerqtpmod'
							)" value="<?= $rot_gerqtprtv; ?>" />
					<?php } ?>
			<?php } else { ?> 
				<input type="submit" class="btn btn-primary submit-button" aria-form-id="gerqtpmod" id="getqtpbtn" name="getqtpbtn" value="<?= $rot_gerqtpslv; ?>" onclick="return validaCampos()"/>
			<?php } ?>
		</p>
	</form>
</div>
<?php
	use App\Model\Entity\Geral;
	use Cake\Routing\Router;
?>
<form method="POST" name="procadexi" id="procadexi" action="<?= Router::url('/', true) ?>produtos/procadexi" class="form-horizontal form-atualizar">
	<p> <?= $rot_procadtip; ?> </p>
	<select name='progrutip'>
		<option value="null" selected="selected"> Todos </option>
		<?php foreach ($produtoTipoLista as $item) { ?>
	        <option value="<?= $item["valor"] ?>" >
	            <?= $item["rotulo"] ?>
	        </option>
		<?php } ?>
	</select>
	<p> <?= $rot_procadcod; ?> </p>
		<input type="number" name="progrucod" min="0" />

	<p> <?= $rot_procadnom; ?> </p>
		<input type="text" name="progrunom" />

	<p> 
		<input type="checkbox" name="proexbexc" value="1" />
		<?= $rot_proexbexc ?? "Exibir produtos excluídos"; ?> 
	</p>

	<input type="submit" class="btn btn-primary submit-button" aria-form-id="procadexi" name="proexibtn" value="Lupa" />
</form>
<?php if ($geracever_progrucri >= 2) { ?>
	<form method="GET" name="procadmod" id="procadmod" action="<?= Router::url('/', true) ?>produtos/procadmod" class="form-horizontal form-atualizar">
		<input type="hidden" name="procadnov" value="novo" />
		<input type="submit" class="btn btn-primary submit-button" aria-form-id="procadmod" name="procadbtn" value="Novo" />
	</form>
<?php } ?>

<?php if (isset($listaProdutos)) { ?>
	<table class='table table-responsive table-hover table-striped'>
		<tr>
			<th> Código </th>
			<th> Nome </th>
			<th> Tipo </th>
		</tr>
	<?php 
		foreach ($listaProdutos as $prd) { 
			//print_r($prd);
			$parametros = array(
				$prd['Empresas']['empresa_codigo'],
				$prd['produto_codigo']
				);
			$excluido = '';
			if ($prd['excluido']) {
				$excluido = 'background-color: #e46e6c;';
			}
	?>
		<tr onclick="redirectToController('produtos/procadmod/<?= implode('/', $parametros) ?>', 
			'procadexi', 
			'produtos/procadexi', 
			1)"
			style="cursor: pointer; <?= $excluido; ?>">
			<td><?= $prd['produto_codigo'] ?></td>
			<td><?= $prd['nome'] ?></td>
			<td><?= $prd['ProdutoTipos']['produto_tipo_nome'] ?></td>
		</tr>
	<?php } // end foreach listaProdutos ?>
	</table>
<?php } //endif isset listaProdutos ?>
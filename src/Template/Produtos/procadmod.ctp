<?php
	use App\Model\Entity\Geral;
	use Cake\Routing\Router;

	$path = 'localhost/esmart';
	if ($gerchasol == 0) {
		echo "O recurso está em uso por outro usuário";
	}
	//else {
	if (1 == 1) {
		$tempo = new \DateTime(date('Y-m-d H:i:s'));
		date_add($tempo, date_interval_create_from_date_string("1 minute 30 seconds"));
		$tempo = $tempo->format("Y-m-d H:i:s");
?>
<script>
	function validaCampos() {
		var erros = 0;
		$('#progrutip').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progrucod').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progrunom').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progrutpc').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progrutxs').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progruprc').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progruftf').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#progruftv').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#proempdsc').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});
		$('#proempprc').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});		
		$('#proemptxs').validate(function(valid, elem) {
			if (!valid)
				erros++;
		});

		if (erros > 0) {
			alert("Há campos obrigatórios a serem preenchidos");
			return false;
		}

		var pdv = document.forms["procadmod"]["propdvpdv[]"];
		if (typeof pdv !== "undefined") {
			var valida = [];
			for (i = 0; i < pdv.length; i++) {
				var codigo = pdv[i].value;
				if (codigo != "0") {
					if (valida.includes(codigo)) {
						alert("Ponto de Venda " + codigo + " em duplicidade. Remova um dos elementos antes de salvar.");
						return false;
						break;
					}
					else {
						valida.push(codigo);
					}
				}
			}
		}
		return true;
	}

	function removerLinha(span) {
		var i = span.parentNode.parentNode.rowIndex;
		var tabela = document.getElementById("propdvtbl");
		if (tabela.rows.length > 1) {
			tabela.deleteRow(i);
		}
	}

	function verificarDisponibilidade() {
		var codOld = document.getElementById("procodold").value;
		var codAtu = document.getElementById("progrucod").value;
		if (codOld != codAtu) {
			callAjax("ajax/ajaxprocodver", {produto_codigo: codAtu}, function(html) {
				if (html != "1") {
					document.getElementById("procoddis").innerHTML = "Código já está em uso";
					document.getElementById("procadbtn").setAttribute("disabled", "disabled");
				}
				else {
					document.getElementById("procoddis").innerHTML = "Código liberado";
					document.getElementById("procadbtn").removeAttribute("disabled");
				}
			});
		}
		else {
			document.getElementById("procoddis").innerHTML = "Código liberado";
			document.getElementById("procadbtn").removeAttribute("disabled");
		}
	}

	function exibirFatores() {
		if ($("#progruadi").prop("checked")) {
			$("#progrufat").slideDown();
			document.getElementById("progruftf").removeAttribute("disabled");
			document.getElementById("progruftv").removeAttribute("disabled");
		}
		else {
			$("#progrufat").slideUp();
			$("#progrufat").attr("disabled", "false");
			document.getElementById("progruftf").setAttribute("disabled", "disabled");
			document.getElementById("progruftv").setAttribute("disabled", "disabled");
		}
	}

	function gerarProximoCodigo() {
		callAjax('ajax/ajaxprocodprx', {empresa_grupo_codigo: <?= $produtoGrupo['empresa_grupo_codigo']; ?>}, function(html) {
			$("#progrucod").val(html);
		});
	}

	var countDownDate = new Date("<?= $tempo; ?>").getTime();

	var x = setInterval(function() {

	  // Get todays date and time
	  var now = new Date().getTime();

	  // Find the distance between now an the count down date
	  var distance = countDownDate - now;

	  // Time calculations for days, hours, minutes and seconds
	  var min = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var sec = Math.floor((distance % (1000 * 60)) / 1000);

	  document.getElementById("timer").innerHTML = min + "m " + sec + "s ";

	  // If the count down is finished, write some text 
	  if (distance < 0) {
	    clearInterval(x);
	    document.getElementById("timer").innerHTML = "Expirado";
	  }
	}, 1000);
</script>
<style>
	.div-topo {
		position: relative;
		width: 100%;
		background-color: #333;
	}

	.timer {
		font-size: 22px;
		text-align: right;
		color: white;
		transition: 0.5s linear;
		-webkit-transition: 0.5s linear;
	}

	.timer:hover {
		background-color: black;
		transition: 0.5s linear;
		-webkit-transition: 0.5s linear;
	}
</style>
<div class="div-topo">
	<p class="timer"> Tempo restante: <span id="timer" ></span> </p>
</div>
<form method="POST" name="procadmod" id="procadmod" action="<?= Router::url('/', true) ?>produtos/procadmod" class="form-horizontal form-atualizar" onsubmit="return validaCampos()">
	<?php if (isset($produtoGrupo) && $produtoGrupo !== false) { ?>
	<?php 
		$gru_rvk = '';
		if ($geracever_progrumod != 3 || $produtoGrupo['excluido'] == 1) { 
			$gru_rvk = 'readonly="readonly" disabled="disabled"';
		}
	?>
		<div>
			<input type="hidden" name="progruemp" value="<?= $produtoGrupo['empresa_grupo_codigo']; ?>"/>
			<input type="hidden" name="progruusu" value="<?= $produtoGrupo['criacao_usuario']; ?>"/>
			<h2> <?= $rot_provisgru; ?> </h2>
			<p> <?= $rot_procadtip; ?> </p>
			<select id="progrutip" name='progrutip' <?= $gru_rvk; ?> <?= $pro_procadtip; ?>>
				<option value="" selected="selected" disabled="disabled"> </option>
				<?php foreach ($produtoTipoLista as $item) { ?>
                    <option value="<?= $item["valor"] ?>" 
                    	<?php if ($item["valor"] == $produtoGrupo['produto_tipo_codigo']) echo 'selected="selected"'; ?>
                    >
                        <?= $item["rotulo"] ?>
                    </option>
				<?php } ?>
			</select>

			<p> <?= $rot_procadcod; ?> </p>
				<input type="hidden" id="procodold" name="procodold" value="<?= $produtoGrupo['produto_codigo'] ?? 0; ?>" />
				<input type="text"   id="progrucod" name="progrucod" value="<?= $produtoGrupo['produto_codigo']; ?>" 
					onchange="verificarDisponibilidade()"  <?= $gru_rvk; ?> 
					<?= $pro_procadcod; ?>
					<?php if ($produtoGrupo['produto_codigo'] != 0) echo 'readonly="readonly"'; ?>
					/>
				<?php if ($gru_rvk == '' && $produtoGrupo['produto_codigo'] == 0) { ?>
					<a href="javascript:void(0)" class="btn btn-primary" onclick="gerarProximoCodigo()" > Seguinte </a>
				<?php } ?>
				<span id="procoddis"></span>

			<p> <?= $rot_procadnom ?> </p>
				<input type="text" id="progrunom" name="progrunom" value="<?= $produtoGrupo['nome']; ?>" <?= $gru_rvk; ?> <?= $pro_procadnom; ?> />

			<p> <?= $rot_procaddsc; ?> </p>
				<input type="text" id="progrudsc" name="progrudsc" value="<?= $produtoGrupo['descricao']; ?>" <?= $gru_rvk; ?> />

			<p> <?= $rot_procadprc; ?> </p>
				<input type="text" id="progruprc" name="progruprc" value="<?= $produtoGrupo['preco']; ?>" <?= $gru_rvk; ?> <?= $pro_procadprc; ?> />

			<p> <?= $rot_procadtxs; ?> </p>
				<select id="progrutxs" name="progrutxs" <?= $gru_rvk; ?> <?= $pro_procadtxs; ?> >
					<option value="" selected="selected" > </option>
					<option value="0" <?php if($produtoGrupo['servico_taxa_incide'] == "0") echo 'selected="selected"'; ?> > NÃ£o </option>
					<option value="1" <?php if($produtoGrupo['servico_taxa_incide'] == "1") echo 'selected="selected"'; ?> > Sim </option>
				</select>

			<p> <?= $rot_procadtpc; ?> </p>
				<select id="progrutpc" name="progrutpc" <?= $gru_rvk; ?> <?= $pro_procadtpc; ?>>
					<option value="" selected="selected" > </option>
					<option value="C" <?php if($produtoGrupo['contabil_tipo'] == "C") echo 'selected="selected"'; ?> > CrÃ©dito </option>
					<option value="D" <?php if($produtoGrupo['contabil_tipo'] == "D") echo 'selected="selected"'; ?> > DÃ©bito </option>
				</select>

			<p> 
				<?= $rot_procadadi; ?>
				<input type="checkbox" id="progruadi" name="progruadi" value="1" <?php if ($produtoGrupo['adicional'] == 1) echo 'checked="checked"'; ?> 
					onclick="exibirFatores()" <?= $gru_rvk; ?> />
			 </p>

			<div id="progrufat" style="display: none;">
				<p> <?= $rot_procadftf; ?> </p>
					<select id="progruftf" name="progruftf" <?= $gru_rvk; ?> <?= $pro_procadftf; ?> >
						<option value=""> </option>
						<?php foreach ($fatorFixoLista as $item) { ?>
		                    <option value="<?= $item["valor"] ?>" 
		                    	<?php if ($item["valor"] == $produtoGrupo['fixo_fator_codigo']) echo 'selected="selected"'; ?>
		                    >
		                        <?= $item["rotulo"] ?>
		                    </option>
						<?php } ?>
					</select>

				<p> <?= $rot_procadftv; ?> </p>
					<select id="progruftv" name="progruftv" <?= $gru_rvk; ?> <?= $pro_procadftv; ?> >
						<option value=""> </option>
						<?php foreach ($fatorVariavelLista as $item) { ?>
		                    <option value="<?= $item["valor"] ?>" 
		                    	<?php if ($item["valor"] == $produtoGrupo['variavel_fator_codigo']) echo 'selected="selected"'; ?>
		                    >
		                        <?= $item["rotulo"] ?>
		                    </option>
						<?php } ?>
					</select>
			</div>
			<?php if (isset($produtoGrupo['produto_codigo']) && $geracever_progrumod == 3)  { ?>
				<?php if ($produtoGrupo['excluido'] == 1) { ?>
					<input type="button" class="btn btn-success"  onclick="redirectToController(
						'produtos/procadmod?del=0&pro=<?= $produtoGrupo['produto_codigo']; ?>&gru=<?= $produtoGrupo['empresa_grupo_codigo']; ?>', 
						'procadmod', 
						'produtos/procadmod'
						)" value="Reativar" />
				<?php } elseif ($produtoGrupo['excluido'] == 0) { ?>
					<input type="button" class="btn btn-danger"  onclick="redirectToController(
						'produtos/procadmod?del=1&pro=<?= $produtoGrupo['produto_codigo']; ?>&gru=<?= $produtoGrupo['empresa_grupo_codigo']; ?>', 
						'procadmod',
						'produtos/procadmod'
						)" value="Excluir" />
				<?php } ?>
			<?php } ?>
		</div>
	<?php } // end if produtoGrupo ?>
	<?php if (isset($produtoEmpresa) && $geracever_proempvis) { ?>
	<?php 
		$emp_rvk = '';
		if ($geracever_proempmod != 3 || $produtoEmpresa['excluido'] == 1 || $produtoGrupo['excluido'] == 1) {
			$emp_rvk = 'readonly="readonly" disabled="disabled"';
		}
	?>
		<div>
			<input type="hidden" name="proempemp" value="<?= $produtoEmpresa['empresa_codigo']; ?>" />
			<input type="hidden" name="proempcod" value="<?= $produtoEmpresa['produto_codigo']; ?>" />
			<input type="hidden" name="proempusu" value="<?= $produtoEmpresa['criacao_usuario']; ?>"/>
			<h2> 
				<?= $rot_provisemp; ?> 
				<?php if ($produtoEmpresa['produto_codigo'] == 0) { ?>
					<span style="background-color: blue; color: white;" onclick="adicionarEmpresa(this)"> + </span> 
				<?php } ?>
			</h2>
			<div id="procademp" 
				<?php if ($produtoEmpresa['produto_codigo'] == 0) { ?>
					style="display: none;"
				<?php } ?>
			>
				<p> <?= $rot_procaddsc; ?> </p>
					<input type="text" id="proempdsc" name="proempdsc" value="<?= $produtoEmpresa['descricao']; ?>" <?= $emp_rvk; ?> <?= $pro_procaddsc; ?> />

				<p> <?= $rot_procadprc; ?> </p>
					<input type="text" id="proempprc" name="proempprc" value="<?= $produtoEmpresa['preco']; ?>" <?= $emp_rvk; ?> <?= $pro_procadprc; ?> />

				<p> <?= $rot_procadtxs; ?> </p>
					<select id="proemptxs" name="proemptxs" <?= $emp_rvk; ?> <?= $pro_procadtxs; ?> >
						<option value="" > </option>
						<option value="0" <?php if($produtoEmpresa['servico_taxa_incide'] == "0") echo 'selected="selected"'; ?>> NÃ£o </option>
						<option value="1" <?php if($produtoEmpresa['servico_taxa_incide'] == "1") echo 'selected="selected"'; ?>> Sim </option>
					</select>

			<?php if (isset($produtoEmpresa['produto_codigo']) && $geracever_proempmod == 3)  { ?>
				<?php if ($produtoEmpresa['excluido'] == 1) { ?>
					<?php if (($produtoGrupo['excluido'] == 1 && $geracever_progrumod == 3) || $produtoGrupo['excluido'] == 0) { ?>
						<input type="button" class="btn btn-success"  onclick="redirectToController(
							'produtos/procadmod?del=0&pro=<?= $produtoEmpresa['produto_codigo']; ?>&emp=<?= $produtoEmpresa['empresa_codigo']; ?>', 
							'procadmod', 
							'produtos/procadmod'
							)" value="<?= $rot_procadatv; ?>" />
					<?php } ?>
				<?php } elseif ($produtoEmpresa['excluido'] == 0) { ?>
					<input type="button" class="btn btn-danger"  onclick="redirectToController(
						'produtos/procadmod?del=1&pro=<?= $produtoEmpresa['produto_codigo']; ?>&emp=<?= $produtoEmpresa['empresa_codigo']; ?>', 
						'procadmod', 
						'produtos/procadmod'
						)" value="<?= $rot_procadexc; ?>" />
				<?php } ?>
			<?php } ?>
		
			<?php if ($geracever_proempmod > 0 && $produtoEmpresa['excluido'] == 0) { ?>
				<h3> <?= $rot_provenpon; ?> <span style="background-color: blue; color: white;" onclick="adicionarPontoVenda()"> + </span></h3>
				<table class="table table-responsive table-striped table-hover">
					<tbody id="propdvtbl">
						<tr>
							<th> <?= $rot_propdvnom; ?> </th>
							<th> <?= $rot_procadprc; ?> </th>
							<th> <?= $rot_procadtxs; ?> </th>
							<th> <?= $rot_procadexc; ?> </th>
						</tr>
						<?php if (isset($produtoPdv) && $produtoPdv !== false) { ?>
							<?php foreach ($produtoPdv as $produto) { ?>
								<tr>
									<td> 
										<select name="propdvpdv[]" <?= $emp_rvk; ?> >
											<option value="0"> </option>
											<?php foreach ($produtoPdvLista as $item) { ?>
												<option value="<?= $item["valor"] ?>" 
													<?php if ($item["valor"] == $produto['venda_ponto_codigo']) echo 'selected="selected"'; ?>
												>
													<?= $item["rotulo"] ?>
												</option>
											<?php } ?>
										</select>
									</td>
									<td> 
										<input type="text" name="propdvprc[]" value="<?= $produto['preco']; ?>" <?= $emp_rvk; ?> />
									</td>
									<td>
										<select name="propdvtxs[]" <?= $emp_rvk; ?> >
											<option value="0" selected="selected"> NÃ£o </option>
											<option value="1" <?php if ($produto['servico_taxa_incide'] == 1) echo 'selected="selected"'; ?> > Sim </option>
										</select>
										<input type="hidden" name="propdvusu[]" value="<?= $produto['criacao_usuario']; ?>"/>
									</td>
									<td> <span class="glyphicon glyphicon-cancel" onclick="removerLinha(this)" <?= $emp_rvk; ?> > X </span> </td>
								</tr>
							<?php } // end foreach produtoPdv ?>
						<?php } // end if produtoPdv ?>
					</tbody>
				</table>
			<?php } ?>
		<?php } // end if produtoEmpresa ?>
		</div>
	</div>
	<?php if ($geracever_progrumod == 3 || $geracever_proempmod == 3) { ?>
		<?php if ($produtoGrupo['excluido'] == 0 || $produtoEmpresa['excluido'] == 0) { ?>
			<input type="submit" class="btn btn-primary submit-button" aria-form-id="procadmod" id="procadbtn" name="procadbtn" value="Salvar" onclick="return validaCampos()"/>
		<?php } ?>
	<?php } ?>
</form>
<script> 
	function adicionarPontoVenda() {
		if (validaCampos()) {
			var tabela = document.getElementById("propdvtbl");
			var row = tabela.insertRow(-1);
			var tipo = row.insertCell(-1);
			var preco = row.insertCell(-1);
			var taxa = row.insertCell(-1);
			var remove = row.insertCell(-1);
			tipo.innerHTML = '<select name="propdvpdv[]" <?= $emp_rvk; ?> >' +
									'<option value="0"> </option>' +
									<?php foreach ($produtoPdvLista as $item) { ?> 
										'<option value="<?= $item["valor"] ?>"  >' +
											'<?= $item["rotulo"] ?>' +
										'</option>' +
									<?php } ?>
								'</select>';

			preco.innerHTML = '<input type="text" name="propdvprc[]" value="' + $("#proempprc").val() + '" <?= $emp_rvk; ?> />';

			var sel0 = "", sel1 = "";
			if ($("#proemptxs").val() == 0) {
				sel0 = 'selected="selected"';
			}
			else {
				sel1 = 'selected="selected"';
			}

			taxa.innerHTML = '<select name="propdvtxs[]" <?= $emp_rvk; ?> >' +
						'<option value="0" ' + sel0 + '> Não </option>' +
						'<option value="1" ' + sel1 + '> Sim </option>' +
					'</select>' +
					'<input type="hidden" name="propdvusu[]" value="0"/>';
			remove.innerHTML = '<span class="glyphicon glyphicon-cancel" onclick="removerLinha(this)" <?= $emp_rvk; ?> > X </span> </td>';
		}
	}

	function adicionarEmpresa(botao) {
		if (validaCampos()) {
			if ($("#procademp").css("display") == "none") {

				botao.style.backgroundColor = "red";
				botao.innerHTML = "-";
				$("#procademp").slideDown();
				$("#proempdsc").removeAttr("disabled");
				$("#proempdsc").val($("#progrudsc").val());
				$("#proempprc").removeAttr("disabled");
				$("#proempprc").val($("#progruprc").val());

				$("#proemptxs").removeAttr("disabled");
				var txs = $("#progrutxs").val();
				if (txs == "1") {
					$("#proemptxs option[value='1']").prop("selected", true);
				}
				else {
					$("#proemptxs option[value='0']").prop("selected", true);
				}
			}
			else {
				botao.style.backgroundColor = "blue";
				botao.innerHTML = "+";
				$("#procademp").slideUp();
				$("#proempdsc").attr("disabled", "disabled");
				$("#proempdsc").val("");

				$("#proempprc").attr("disabled", "disabled");
				$("#proempprc").val("");

				$("#proemptxs").attr("disabled", "disabled");
			}
		}
	}
	exibirFatores();
</script>
<?php } ?>
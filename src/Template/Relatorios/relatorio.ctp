<?php

use App\Model\Entity\Geral;

$geral = new Geral();
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {

        /* GRÀFICO DE RECEITAS POR PRODUTO E POR MES */
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Mês');
<?php
foreach ($receitas_por_mes_produto as $mes => $receita_mes_produto) {
    foreach ($receita_mes_produto as $receita) {
        ?>
                data.addColumn('number', '<?= str_replace("?", "á",$receita['no_produto']) ?>');
    <?php } ?>
<?php } ?>
<?php foreach ($receitas_por_mes_produto as $mes => $receita_mes_produto) { ?>
            var row = ['<?= $mes ?>'];
    <?php foreach ($receita_mes_produto as $receita) { ?>
                row.push(<?= abs($receita['sum']) ?>);
        <?php }  ?>
            data.addRow(row);
<?php } ?>

        var options = {
            chart: {
                title: 'Motivation and Energy Level Throughout the Day',
            },
            hAxis: {
                title: 'Mês',
            },
            vAxis: {
                title: 'Valor'
            },
            chartArea:{
                width: 500
            }
        };

        var materialChart = new google.visualization.ColumnChart(document.getElementById('receitas_por_produto_por_mes_grafico'));
        materialChart.draw(data, options);

        /* GRÀFICO DE RECEITAS POR MES */
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Mês');
        data.addColumn('number', 'Valor');
        data.addRows([

<?php
foreach ($receitas_por_mes as $receita_mes) {
    echo "['" . $receita_mes['no_nome_mes'] . "'," . abs($receita_mes['sum']) . "],";
}
?>
        ]);
        var options = {
            title: 'Receitas por mês'
        };
        var chart = new google.visualization.PieChart(document.getElementById('receitas_por_mes_grafico'));
        chart.draw(data, options);
        /* GRÀFICO DE RECEITAS POR PRODUTO */
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Produto');
        data.addColumn('number', 'Valor');
        data.addRows([

<?php
foreach ($receitas_por_produto as $receita_produto) {
    echo "['" . str_replace("?", "á",$receita_produto['no_produto']) . "'," . abs($receita_produto['sum']) . "],";
}
?>
        ]);
        var options = {
            title: 'Receitas por produto'
        };
        var chart = new google.visualization.PieChart(document.getElementById('receitas_por_produto_grafico'));
        chart.draw(data, options);
    }
</script>

<div class="col-md-12">

    <div class="col-md-6">
        <h3>Receitas por produto por mês</h3>
        <table class="table_cliclipes">                       
            <thead>
                <tr class="tabres_cabecalho2">
                    <th>Mês</th>
                    <th>Produto</th>
                    <th>Valor total <?= $geral->germoeatr() ?></th>
                </tr>
            </thead>
            <tbody id="table_cliclipes_tbody">

                <?php
                foreach ($receitas_por_mes_produto as $mes => $receita_mes_produto) {
                    foreach ($receita_mes_produto as $receita) {
                        ?>
                        <tr>
                            <td><?= $mes ?></td>
                            <td><?= str_replace("?", "á",$receita['no_produto']) ?></td>
                            <td><?= $geral->gersepatr($receita['sum']) ?></td>
                        </tr>

                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6" style="padding-top:56px">
        <div id="receitas_por_produto_por_mes_grafico"></div>
    </div>
</div>


<div class="col-md-12">
    <h3>Receitas por mês</h3>
    <div class="col-md-6">
        <table class="table_cliclipes">                       
            <thead>
                <tr class="tabres_cabecalho2">
                    <th>Mês</th>
                    <th>Valor total <?= $geral->germoeatr() ?></th>
                </tr>
            </thead>
            <tbody id="table_cliclipes_tbody">

                <?php foreach ($receitas_por_mes as $receita_mes) { ?>
                    <tr>
                        <td><?= $receita_mes['no_nome_mes'] ?></td>
                        <td><?= $geral->gersepatr($receita_mes['sum']) ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <div id="receitas_por_mes_grafico"></div>
    </div>
</div>


<div class="col-md-12">
    <div class="col-md-6">
        <h3>Receitas por produto</h3>
        <table class="table_cliclipes">                       
            <thead>
                <tr class="tabres_cabecalho2">
                    <th>Produto</th>
                    <th>Valor total <?= $geral->germoeatr() ?></th>
                </tr>
            </thead>
            <tbody id="table_cliclipes_tbody">

                <?php foreach ($receitas_por_produto as $receita_produto) { ?>
                    <tr>
                        <td><?= str_replace("?", "á",$receita_produto['no_produto']) ?></td>
                        <td><?= $geral->gersepatr($receita_produto['sum']) ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6"  style="padding-top:56px">
        <div id="receitas_por_produto_grafico"></div>
    </div>
</div>

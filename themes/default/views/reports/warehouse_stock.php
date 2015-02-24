<script src="<?= $assets; ?>js/highcharts.js"></script>
<script src="<?= $assets; ?>js/exporting.js"></script>
<script type="text/javascript">


    $(function () {
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: {cx: 0.5, cy: 0.3, r: 0.7},
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        });

        $('#chart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true,
                backgroundColor: '#FFF',
                headerFormat: '<span style="font-size:15px background-color: #FFF;">{point.key}</span><br>',
                pointFormat: '<span style="color:{series.color};padding:0;text-align:right;">$ <b>{point.y}</b> ({point.percentage:.2f}%)</span>',
                footerFormat: '',
                useHTML: true,
                valueDecimals: 2,
                style: {
                    fontSize: '13px',
                    padding: '10px',
                    color: '#000000'
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        //format: '<h4 style="margin:-15px 0 0 0;"><b>{point.name}</b>:<br>$ <b> {point.y} </b></h4>',
                        formatter: function () {
                            return '<h3 style="margin:-15px 0 0 0;"><b>' + this.point.name + '</b>:<br>$ <b> ' + currencyFormate(this.y) + '</b></h3>';
                        },
                        useHTML: true
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: '<?php echo $this->lang->line("stock_value"); ?>',
                    data: [
                        ['<?php echo $this->lang->line("stock_value_by_price"); ?>', <?php echo $stock->stock_by_price; ?>],
                        ['<?php echo $this->lang->line("stock_value_by_cost"); ?>', <?php echo $stock->stock_by_cost; ?>],
                        ['<?php echo $this->lang->line("profit_estimate"); ?>', <?php echo ($stock->stock_by_price - $stock->stock_by_cost); ?>],
                    ]

                }]
        });

    });
</script>

<?php if($Owner) { ?>
<div class="box" style="margin-top: 15px;">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i><?= lang('warehouse_stock'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <?php if(!empty($warehouses) && $Owner) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('purchases') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                        <li class="divider"></li>
                        <?php
                        foreach ($warehouses as $warehouse) {
                            echo '<li '.($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '').'><a href="' . site_url('reports/warehouse_stock/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                        }
                        ?>                        
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="box-content"> 
        <div class="row">            
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('warehouse_stock_heading'); ?></p>
                <div id="chart" style="width:100%; height:450px;"></div>
            </div>                        
        </div>
    </div>
</div>
<?php } ?>
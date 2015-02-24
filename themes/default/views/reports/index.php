<?php if($Owner) {
foreach ($monthly_sales as $month_sale) {
    $months[] = date('M-Y', strtotime($month_sale->month));
    $sales[] = $month_sale->sales;
    $tax1[] = $month_sale->tax1;
    $tax2[] = $month_sale->tax2;
    $purchases[] = $month_sale->purchases;
    $tax3[] = $month_sale->ptax;
}
?>   

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
            },
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: <?= json_encode($months); ?>
            },
            yAxis: {
                min: 0,
                title: ""
            },
            /*exporting: {
                url: '<?=base_url()?>assets/export/export.php'
            },*/
            tooltip: {
                shared: true,
                headerFormat: '<span style="font-size:14px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="color:{series.color};padding:0;text-align:right;"> <b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                useHTML: true,
                valueDecimals: 2,
                style: {
                    fontSize: '13px',
                    padding: '10px',
                    fontWeight: 'bold',
                    color: '#000000'
                }
            },
            series: [{
                    type: 'column',
                    name: '<?php echo $this->lang->line("sp_tax"); ?>',
                    data: [<?php
echo implode(', ', $tax1);
?>]
                },
                {
                    type: 'column',
                    name: '<?php echo $this->lang->line("order_tax"); ?>',
                    data: [<?php
echo implode(', ', $tax2);
?>]
                },
                {
                    type: 'column',
                    name: '<?php echo $this->lang->line("sales"); ?>',
                    data: [<?php
echo implode(', ', $sales);
?>]
                }, {
                    type: 'spline',
                    name: '<?php echo $this->lang->line("purchases"); ?>',
                    data: [<?php
echo implode(', ', $purchases);
?>],
                    marker: {
                        lineWidth: 2,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }, {
                    type: 'spline',
                    name: '<?php echo $this->lang->line("pp_tax"); ?>',
                    data: [<?php
echo implode(', ', $tax3);
?>],
                    marker: {
                        lineWidth: 2,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }, {
                    type: 'pie',
                    name: '<?php echo $this->lang->line("stock_value"); ?>',
                    data: [
                        ['', 0],
                        ['', 0],
                        ['<?php echo $this->lang->line("stock_value_by_price"); ?>', <?php echo $stock->stock_by_price; ?>],
                        ['<?php echo $this->lang->line("stock_value_by_cost"); ?>', <?php echo $stock->stock_by_cost; ?>],
                    ],
                    center: [80, 42],
                    size: 80,
                    showInLegend: false,
                    dataLabels: {
                        enabled: false
                    }
                }]
        });
    });
</script>
<?php } ?>
            <div class="box">
                <div class="box-header">
                    <h2 class="blue"><i class="fa fa-th"></i><span class="break"></span><?= lang('quick_links') ?></h2>
                </div>
                <div class="box-content">
                    <div class="row">
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bblue white quick-button" href="<?= site_url('reports/warehouse_stock') ?>">
                            <i class="fa fa-building"></i>
                            <p><?=lang('warehouse_stock')?></p>
                        </a>
                    </div>
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bred white quick-button" href="<?= site_url('reports/quantity_alerts') ?>">
                            <i class="fa fa-bar-chart-o"></i>
                            <p><?=lang('product_quantity_alerts')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bred white quick-button" href="<?= site_url('reports/expiry_alerts') ?>">
                            <i class="fa fa-bar-chart-o"></i>
                            <p><?=lang('product_expiry_alerts')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bblue white quick-button" href="<?= site_url('reports/products') ?>">
                            <i class="fa fa-barcode"></i>
                            <p><?=lang('products_report')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bdarkGreen white quick-button" href="<?= site_url('reports/daily_sales') ?>">
                            <i class="fa fa-calendar-o"></i>
                            <p><?=lang('daily_sales')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bdarkGreen white quick-button" href="<?= site_url('reports/monthly_sales') ?>">
                            <i class="fa fa-calendar-o"></i>
                            <p><?=lang('monthly_sales')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="bdarkGreen white quick-button" href="<?= site_url('reports/sales') ?>">
                            <i class="fa fa-heart"></i>
                            <p><?=lang('sales_report')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="blightOrange white quick-button" href="<?= site_url('reports/payments') ?>">
                            <i class="fa fa-money"></i>
                            <p><?=lang('payments_report')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="blightOrange white quick-button" href="<?= site_url('reports/profit_loss') ?>">
                            <i class="fa fa-money"></i>
                            <p><?=lang('profit_and_loss')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="blightBlue white quick-button" href="<?= site_url('reports/purchases') ?>">
                            <i class="fa fa-star"></i>
                            <p><?=lang('purchases_report')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="borange white quick-button" href="<?= site_url('reports/customers') ?>">
                            <i class="fa fa-users"></i>
                            <p><?=lang('customers_report')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="borange white quick-button" href="<?= site_url('reports/suppliers') ?>">
                            <i class="fa fa-users"></i>
                            <p><?=lang('suppliers_report')?></p>
                        </a>
                    </div>
                    
                    <div class="col-md-2 col-xs-4 padding1010">
                        <a class="borange white quick-button" href="<?= site_url('reports/staff_report') ?>">
                            <i class="fa fa-users"></i>
                            <p><?=lang('staff_report')?></p>
                        </a>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                </div>	
            </div>	

<?php if($Owner) { ?>
<div class="box" style="margin-top: 15px;">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i><?= lang('overview_chart'); ?></h2>
    </div>
    <div class="box-content"> 
        <div class="row">            
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('overview_chart_heading'); ?></p>
                <div id="chart" style="width:100%; height:450px;"></div>
                <p class="text-center"><?php echo $this->lang->line("chart_lable_toggle"); ?></p>
            </div>                        
        </div>
    </div>
</div>
<?php } ?>
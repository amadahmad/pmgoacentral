<style>
    .table th { text-align:center; }
    .ctable td { text-align:center; }
    .table a:hover { text-decoration: none; }
    .cl_wday { text-align: center; font-weight:bold; }
    .cl_equal { width: 14%; }
    td.day { width: 14%; padding: 0 !important; vertical-align: top !important; }
    .day_num { width: 100%; text-align:left; cursor:pointer; margin: 0; padding:8px; } 
    .day_num:hover { background:#F5F5F5; }
    .content { width: 100%; text-align:left; color: #428bca; padding: 8px; }
    .highlight { color: #0088CC; font-weight:bold; }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="small-box padding1010 col-sm-4 bblue">
                    <h3 class="bold"><?= isset($sales->total_amount) ? $this->sma->formatMoney($sales->total_amount) : '0.00' ?></h3>
                    <p class="bold"><?= $sales->total.' '.lang('sales') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 bdarkGreen">
                    <h3><?= isset($sales->paid) ? $this->sma->formatMoney($sales->paid) : '0.00' ?></h3>
                    <p><?= lang('total_paid') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 borange">
                    <h3><?= ( isset($sales->total_amount) || isset($sales->paid) ) ? $this->sma->formatMoney($sales->total_amount - $sales->paid) : '0.00' ?></h3>
                    <p><?= lang('due_amount') ?></p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="small-box padding1010 col-sm-4 bblue">
                    <h3 class="bold"><?= isset($purchases->total_amount) ? $this->sma->formatMoney($purchases->total_amount) : '0.00' ?></h3>
                    <p class="bold"><?= $purchases->total.' '.lang('purchases') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 blightOrange">
                    <h3><?= isset($purchases->paid) ? $this->sma->formatMoney($purchases->paid) : '0.00' ?></h3>
                    <p><?= lang('total_paid') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 borange">
                    <h3><?= ( isset($purchases->total_amount) || isset($purchases->paid) ) ? $this->sma->formatMoney($purchases->total_amount - $purchases->paid) : '0.00' ?></h3>
                    <p><?= lang('due_amount') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="clear:both;height:20px;"></div>
<ul id="myTab" class="nav nav-tabs">
    <li class=""><a href="#daily-con" class="tab-grey"><?=lang('staff_daily_sales')?></a></li>
    <li class=""><a href="#monthly-con" class="tab-grey"><?=lang('staff_monthly_sales')?></a></li>
    <li class=""><a href="#sales-con" class="tab-grey"><?=lang('staff_sales_report')?></a></li>
    <li class=""><a href="#purchases-con" class="tab-grey"><?=lang('staff_purchases_report')?></a></li>
    <li class=""><a href="#payments-con" class="tab-grey"><?=lang('staff_payments_report')?></a></li>
    <li class=""><a href="#logins-con" class="tab-grey"><?=lang('staff_logins_report')?></a></li>
</ul>

<div class="tab-content">
    <div id="daily-con" class="tab-pane fade in">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-calendar nb"></i> <?= lang('daily_sales'); ?></h2>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">

                        <p class="introtext"><?= lang("reports_calendar_text") ?></p>
                        <div>
                            <?= $calender; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div> 
    </div>
    <div id="monthly-con" class="tab-pane fade in">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-calendar nb"></i> <?= lang('monthly_sales'); ?></h2>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">

                        <p class="introtext"><?= lang("reports_calendar_text") ?></p>

                        <div class="table-responsive">
                            <table class="table table-bordered dfTable">

                                <tr class="year_roller">
                                    <th><div class="text-center"> <a href="reports/monthly_sales/<?= $year - 1; ?>">&lt;&lt;</a> </div></th>
                                    <th colspan="10"><div class="text-center"> <?= $year; ?> </div></td>
                                        <th><div class="text-center"> <a href="reports/monthly_sales/<?= $year + 1; ?>">&gt;&gt;</a> </div></th>
                                    </th>
                                </tr> 

                                <tr>
                                    <td><?= $this->lang->line("january"); ?></td>
                                    <td><?= $this->lang->line("february"); ?></td>
                                    <td><?= $this->lang->line("march"); ?></td>
                                    <td><?= $this->lang->line("april"); ?></td>
                                    <td><?= $this->lang->line("may"); ?></td>
                                    <td><?= $this->lang->line("june"); ?></td>
                                    <td><?= $this->lang->line("july"); ?></td>
                                    <td><?= $this->lang->line("august"); ?></td>
                                    <td><?= $this->lang->line("september"); ?></td>
                                    <td><?= $this->lang->line("october"); ?></td>
                                    <td><?= $this->lang->line("november"); ?></td>
                                    <td><?= $this->lang->line("december"); ?></td>
                                </tr>
                                <tr>

                                    <?php
                                    if (!empty($msales)) {

                                        foreach ($msales as $value) {
                                            $array[$value->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tr><td>" . $this->lang->line("product_tax") . "</td></tr><tr><td>" . $this->sma->formatMoney($value->tax1) . "</td></tr><tr><td>" . $this->lang->line("order_tax") . "</td></tr><tr><td>" . $this->sma->formatMoney($value->tax2) . "</td></tr><tr><td>" . $this->lang->line("total") . "</td></tr><tr><td>" . $this->sma->formatMoney($value->total) . "</td></tr></table>";
                                        }

                                        for ($i = 1; $i <= 12; $i++) {
                                            echo "<td>";
                                            if (isset($array[$i])) {
                                                echo $array[$i];
                                            } else {
                                                echo '<strong>&nbsp;</strong>';
                                            }
                                            echo "</td>";
                                        }
                                    } else {
                                        for ($i = 1; $i <= 12; $i++) {
                                            echo "<td><strong>0</strong></td>";
                                        }
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>   
    </div>
    <div id="sales-con" class="tab-pane fade in">

        <?php
        $v = "&user=".$user_id;
        if ($this->input->post('submit_sale_report')) {
            if ($this->input->post('biller')) {
                $v .= "&biller=" . $this->input->post('biller');
            }
            if ($this->input->post('warehouse')) {
                $v .= "&warehouse=" . $this->input->post('warehouse');
            }
            if ($this->input->post('csutomer')) {
                $v .= "&customer=" . $this->input->post('customer');
            }
            if ($this->input->post('start_date')) {
                $v .= "&start_date=" . $this->input->post('start_date');
            }
            if ($this->input->post('end_date')) {
                $v .= "&end_date=" . $this->input->post('end_date');
            }
        }
        ?>
        <script>
            $(document).ready(function () {
                var oTable = $('#SlRData').dataTable({
                    "aaSorting": [[1, "asc"]],
                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                    "iDisplayLength": <?= $Settings->rows_per_page ?>,
                    'bProcessing': true, 'bServerSide': true,
                    'sAjaxSource': '<?= site_url('reports/getSalesReport/?v=1' . $v) ?>',
                    'fnServerData': function (sSource, aoData, fnCallback) {
                        aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                        $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                    },
                    "aoColumns": [{"mRender": fld}, null, null, null, {"bSearchable": false},  {"mRender": currencyFormate}, {"mRender": currencyFormate}, null],
                    "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {
                        var gtotal = 0, paid = 0;
                        for (var i = 0; i < aaData.length; i++) {
                            gtotal += parseFloat(aaData[ aiDisplay[i] ][5]);
                            paid += parseFloat(aaData[ aiDisplay[i] ][6]);
                        }
                        var nCells = nRow.getElementsByTagName('th');
                        nCells[5].innerHTML = currencyFormate(parseFloat(gtotal));
                        nCells[6].innerHTML = currencyFormate(parseFloat(paid));
                    }
                }).fnSetFilteringDelay().columnFilter({aoColumns: [{type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null, null, {type: "text", bRegex: true},{type: "text", bRegex: true}]});
});
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart nb"></i> <?= lang('staff_sales_report'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>
            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown"><a href="#" class="toggle_up tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-up"></i></a></li>
                    <li class="dropdown"><a href="#" class="toggle_down tip" title="<?=lang('show_form')?>"><i class="icon fa fa-toggle-down"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="box-content">  
            <div class="row">            
                <div class="col-lg-12">

                    <p class="introtext"><?= lang('customize_report'); ?></p>

                    <div id="form">

                        <?= form_open("reports/staff_report/".$user_id.'#sales-con'); ?>
                        <div class="row">

                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php
                                echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"');
                                ?> 
                            </div></div>
                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?> 
                            </div> </div>
                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?> 
                            </div> </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("start_date", "start_date"); ?>
                                    <?= form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date"'); ?> 
                                </div>
                            </div>
                            <div class="col-sm-4"><div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?= form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?= form_submit('submit_sale_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?= form_close(); ?>

                </div>
                <div class="clearfix"></div>


                <div class="table-responsive">
                    <table id="SlRData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                            <tr>
                                <th><?= lang("date"); ?></th>
                                <th><?= lang("reference_no"); ?></th>
                                <th><?= lang("biller"); ?></th>
                                <th><?= lang("customer"); ?></th>
                                <th><?= lang("product_qty"); ?></th>
                                <th><?= lang("grand_total"); ?></th>
                                <th><?= lang("paid"); ?></th>
                                <th><?= lang("payment_status"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                            </tr>

                        </tbody>
                        <tfoot>

                            <tr>
                                <th>[<?= lang("date"); ?>]</th>
                                <th>[<?= lang("reference_no"); ?>]</th>
                                <th>[<?= lang("biller"); ?>]</th>
                                <th>[<?= lang("customer"); ?>]</th>
                                <th><?= lang("product_qty"); ?></th>
                                <th><?= lang("grand_total"); ?></th>
                                <th><?= lang("paid"); ?></th>
                                <th>[<?= lang("payment_status"); ?>]</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>                        
        </div>
    </div>
</div>
</div>
<div id="purchases-con" class="tab-pane fade in">
    <?php
    $v1 = "&user=".$user_id;
    if ($this->input->post('submit_sale_report')) {
        if ($this->input->post('biller')) {
            $v1 .= "&biller=" . $this->input->post('biller');
        }
        if ($this->input->post('warehouse')) {
            $v1 .= "&warehouse=" . $this->input->post('warehouse');
        }
        if ($this->input->post('supplier')) {
            $v1 .= "&supplier=" . $this->input->post('supplier');
        }
        if ($this->input->post('start_date')) {
            $v1 .= "&start_date=" . $this->input->post('start_date');
        }
        if ($this->input->post('end_date')) {
            $v1 .= "&end_date=" . $this->input->post('end_date');
        }
    }
    ?>
    <script>
        $(document).ready(function () {
            var oTable = $('#SPData').dataTable({
                "aaSorting": [[1, "asc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('reports/getPurchasesReport/?v1=1' . $v) ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "aoColumns": [{"mRender": fld}, null, null, null, {"bSearchable": false},  {"mRender": currencyFormate}, {"mRender": currencyFormate}, null],
                "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {
                    var gtotal = 0, paid = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        gtotal += parseFloat(aaData[ aiDisplay[i] ][5]);
                        paid += parseFloat(aaData[ aiDisplay[i] ][6]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[5].innerHTML = currencyFormate(parseFloat(gtotal));
                    nCells[6].innerHTML = currencyFormate(parseFloat(paid));
                }
            }).fnSetFilteringDelay().columnFilter({aoColumns: [{type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null, null, {type: "text", bRegex: true},{type: "text", bRegex: true}]});
});
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pform').hide();
        $('.ptoggle_down').click(function () {
            $("#pform").slideDown();
            return false;
        });
        $('.ptoggle_up').click(function () {
            $("#pform").slideUp();
            return false;
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-star nb"></i><?= lang('staff_purchases_report'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>
            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown"><a href="#" class="ptoggle_up tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-up"></i></a></li>
                    <li class="dropdown"><a href="#" class="ptoggle_down tip" title="<?=lang('show_form')?>"><i class="icon fa fa-toggle-down"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="box-content">  
            <div class="row">            
                <div class="col-lg-12">

                    <p class="introtext"><?= lang('customize_report'); ?></p>

                    <div id="pform">

                        <?= form_open("reports/staff_report/".$user_id.'#purchases-con'); ?>
                        <div class="row">

                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="supplier"><?= lang("supplier"); ?></label>
                                <?php
                                echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ""), 'class="form-control" id="supplier" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("supplier") . '"');
                                ?> 
                            </div></div>

                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?> 
                            </div> </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("start_date", "start_date"); ?>
                                    <?= form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date"'); ?> 
                                </div>
                            </div>
                            <div class="col-sm-4"><div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?= form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?= form_submit('submit_sale_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?= form_close(); ?>

                </div>
                <div class="clearfix"></div>


                <div class="table-responsive">
                    <table id="SPData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                            <tr>
                                <th><?= lang("date"); ?></th>
                                <th><?= lang("reference_no"); ?></th>
                                <th><?= lang("biller"); ?></th>
                                <th><?= lang("customer"); ?></th>
                                <th><?= lang("product_qty"); ?></th>
                                <th><?= lang("grand_total"); ?></th>
                                <th><?= lang("paid"); ?></th>
                                <th><?= lang("payment_status"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                            </tr>

                        </tbody>
                        <tfoot>

                            <tr>
                                <th>[<?= lang("date"); ?>]</th>
                                <th>[<?= lang("reference_no"); ?>]</th>
                                <th>[<?= lang("biller"); ?>]</th>
                                <th>[<?= lang("customer"); ?>]</th>
                                <th><?= lang("product_qty"); ?></th>
                                <th><?= lang("grand_total"); ?></th>
                                <th><?= lang("paid"); ?></th>
                                <th>[<?= lang("payment_status"); ?>]</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>                        
        </div>
    </div>
</div>
</div>
<div id="payments-con" class="tab-pane fade in">
    <?php
    $p = "&user=".$user_id;
    if ($this->input->post('submit_payment_report')) {
        if ($this->input->post('pay_start_date')) {
            $p .= "&start_date=" . $this->input->post('start_date');
        }
        if ($this->input->post('psupplier')) {
            $p .= "&supplier=" . $this->input->post('psupplier');
        }
        if ($this->input->post('pcustomer')) {
            $p .= "&customer=" . $this->input->post('pcustomer');
        }
        if ($this->input->post('pay_end_date')) {
            $p .= "&end_date=" . $this->input->post('end_date');
        }
    }
    ?>
    <script>
        $(document).ready(function () {
            var oTable = $('#PayRData').dataTable({
                "aaSorting": [[1, "asc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('reports/getPaymentsReport/?v=1' . $p) ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "aoColumns": [{"mRender": fsd}, null, null, null, null, {"mRender": currencyFormate}, null],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var total = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        total += parseFloat(aaData[ aiDisplay[i] ][5]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[5].innerHTML = currencyFormate(parseFloat(total));
                }
            }).fnSetFilteringDelay().columnFilter({aoColumns: [{type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null, {type: "text", bRegex: true}]});
});
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#payform').hide();
        $('.paytoggle_down').click(function () {
            $("#payform").slideDown();
            return false;
        });
        $('.paytoggle_up').click(function () {
            $("#payform").slideUp();
            return false;
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-money nb"></i><?= lang('staff_payments_report'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>
            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown"><a href="#" class="paytoggle_up tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-up"></i></a></li>
                    <li class="dropdown"><a href="#" class="paytoggle_down tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-down"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="box-content">  
            <div class="row">            
                <div class="col-lg-12">

                    <p class="introtext"><?= lang('customize_report'); ?></p>

                    <div id="payform">

                        <?= form_open("reports/staff_report/".$user_id.'#payments-con'); ?>
                        <div class="row">

                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="rcustomer"><?= lang("customer"); ?></label>
                                <?php
                                echo form_input('pcustomer', (isset($_POST['pcustomer']) ? $_POST['pcustomer'] : ""), 'class="form-control" id="rcustomer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"');
                                ?> 
                            </div></div>
                            <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="rsupplier"><?= lang("supplier"); ?></label>
                                <?php
                                echo form_input('psupplier', (isset($_POST['psupplier']) ? $_POST['psupplier'] : ""), 'class="form-control" id="rsupplier" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("supplier") . '"');
                                ?> 
                            </div></div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("start_date", "start_date"); ?>
                                    <?= form_input('pay_start_date', (isset($_POST['pay_start_date']) ? $_POST['pay_start_date'] : ""), 'class="form-control date" id="start_date"'); ?> 
                                </div>
                            </div>
                            <div class="col-sm-4"><div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?= form_input('pay_end_date', (isset($_POST['pay_end_date']) ? $_POST['pay_end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?= form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?= form_close(); ?>

                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table id="PayRData" class="table table-bordered table-hover table-striped table-condensed" style="margin-bottom: 5px;">

                        <thead>
                            <tr>
                                <th><?= lang("date"); ?></th>
                                <th><?= lang("payment_ref"); ?></th>
                                <th><?= lang("sale_ref"); ?></th>
                                <th><?= lang("purchase_ref"); ?></th>
                                <th><?= lang("paid_by"); ?></th>
                                <th><?= lang("amount"); ?></th>
                                <th><?= lang("type"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>[<?= lang("date"); ?>]</th>
                                <th>[<?= lang("payment_ref"); ?>]</th>
                                <th>[<?= lang("sale_ref"); ?>]</th>
                                <th>[<?= lang("purchase_ref"); ?>]</th>
                                <th>[<?= lang("paid_by"); ?>]</th>
                                <th><?= lang("amount"); ?></th>
                                <th>[<?= lang("type"); ?>]</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>                        
        </div>
    </div>
</div>
</div>
<div id="logins-con" class="tab-pane fade in">
    <div class="box">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-file-text nb"></i> <?= lang('staff_logins_report'); ?></h2>
            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown"><a href="#" class="logintoggle_up tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-up"></i></a></li>
                    <li class="dropdown"><a href="#" class="logintoggle_down tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-down"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">

                    <p class="introtext"><?= lang("staff_logins_report") ?></p>
                    <div id="loginform">

                        <?= form_open("reports/staff_report/".$user_id.'#logins-con'); ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("start_date", "start_date"); ?>
                                    <?= form_input('login_start_date', (isset($_POST['login_start_date']) ? $_POST['login_start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?> 
                                </div>
                            </div>
                            <div class="col-sm-4"><div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?= form_input('login_end_date', (isset($_POST['login_end_date']) ? $_POST['login_end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?= form_submit('submit_login_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?= form_close(); ?>

                </div>
                <div class="clearfix"></div>
                    <div>
                    <?php
                    if ($this->input->post('submit_login_report')) {
                        if ($this->input->post('start_date')) {
                            $l .= "&start_date=" . $this->input->post('start_date');
                        }
                        if ($this->input->post('end_date')) {
                            $l .= "&end_date=" . $this->input->post('end_date');
                        }
                    }
                    ?>
                        <script>
                            $(document).ready(function() {
                                $('#LGTable').dataTable({
                                    "aaSorting": [[0, "asc"]],
                                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                                    "iDisplayLength": <?= $Settings->rows_per_page ?>,
                                    'bProcessing': true, 'bServerSide': true,
                                    'sAjaxSource': '<?= site_url('reports/getUserLogins/' . $user_id.'/'.$l); ?>',
                                    'fnServerData': function(sSource, aoData, fnCallback) {
                                        aoData.push({"name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                                        $.ajax({'dataType': 'json','type': 'POST','url': sSource,'data': aoData,'success': fnCallback});
                                    },
                                    "aoColumns": [null,null,{"mRender":fld}]
                                }).fnSetFilteringDelay().columnFilter({aoColumns: [{type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}]});
                            });

                        </script>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#loginform').hide();
                                $('.logintoggle_down').click(function () {
                                    $("#loginform").slideDown();
                                    return false;
                                });
                                $('.logintoggle_up').click(function () {
                                    $("#loginform").slideUp();
                                    return false;
                                });
                            });
                        </script>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="LGTable" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
                                            <thead>
                                                <tr>
                                                    <th><?= lang('email'); ?></th>
                                                    <th><?= lang('ip_address'); ?></th>
                                                    <th><?= lang('time'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>[<?= lang('email'); ?>]</th>
                                                    <th>[<?= lang('ip_address'); ?>]</th>
                                                    <th>[<?= lang('time'); ?> (yyyy-mm-dd hh:mm)]</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div> 
</div>
</div>
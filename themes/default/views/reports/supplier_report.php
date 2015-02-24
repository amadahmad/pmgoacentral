<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">

                
                        <div class="small-box padding1010 col-sm-4 bblue">
                            <h3><?= isset($purchases->total_amount) ? $this->sma->formatMoney($purchases->total_amount) : '0.00' ?></h3>
                            <p><?= lang('purchases_amount') ?></p>
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
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="small-box padding1010 bblue">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $total_purchases ?></h3>
                                    <p><?= lang('total_purchases') ?></p>
                                </a>
                            </div>
                        </div>
                    </div> 
                </div>    
            </div>
        </div>
    </div>
</div>
<?php
$v = "&supplier=".$user_id;
if ($this->input->post('submit_sale_report')) {
    if ($this->input->post('biller')) {
        $v .= "&biller=" . $this->input->post('biller');
    }
    if ($this->input->post('warehouse')) {
        $v .= "&warehouse=" . $this->input->post('warehouse');
    }
    if ($this->input->post('user')) {
        $v .= "&user=" . $this->input->post('user');
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
            'sAjaxSource': '<?= site_url('reports/getPurchasesReport/?v=1' . $v) ?>',
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
<div style="clear:both;height:20px;"></div>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-star"></i> <?= lang('purchases_report'); ?> <?php
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

<?php echo form_open("reports/supplier_eport/".$user_id); ?>
                    <div class="row">
                    
                    <div class="col-sm-4"><div class="form-group">
                            <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                            <?php
                            $us[""] = "";
                            foreach ($users as $user) {
                                $us[$user->id] = $user->first_name . " " . $user->last_name;
                            }
                            echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
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
                            <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date"'); ?> 
                        </div>
                    </div>
                    <div class="col-sm-4"><div class="form-group">
                            <?= lang("end_date", "end_date"); ?>
                            <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls"> <?php echo form_submit('submit_sale_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                </div>
                <?php echo form_close(); ?>

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
<div style="clear:both;height:20px;"></div>
<?php
$p = "&supplier=".$user_id;
if ($this->input->post('submit_payment_report')) {
    if ($this->input->post('pay_user')) {
        $p .= "&user=" . $this->input->post('user');
    }
    if ($this->input->post('pay_start_date')) {
        $p .= "&start_date=" . $this->input->post('start_date');
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
        <h2 class="blue"><i class="fa-fw fa fa-money"></i><?= lang('payments_report'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="paytoggle_up tip" title="<?=lang('hide_form')?>"><i class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="paytoggle_down tip" title="<?=lang('show_form')?>"><i class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="payform">

<?php echo form_open("reports/customerReport/".$user_id); ?>
                    <div class="row">
                        
                        <div class="col-sm-4"><div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('pay_user', $us, (isset($_POST['pay_user']) ? $_POST['pay_user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?> 
                            </div> </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
<?php echo form_input('pay_start_date', (isset($_POST['pay_start_date']) ? $_POST['pay_start_date'] : ""), 'class="form-control date" id="start_date"'); ?> 
                            </div>
                        </div>
                        <div class="col-sm-4"><div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
<?php echo form_input('pay_end_date', (isset($_POST['pay_end_date']) ? $_POST['pay_end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
<?php echo form_close(); ?>

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
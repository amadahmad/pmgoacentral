<?php
$v = "";

    if ($this->input->post('product')) {
        $v .= "&product=" . $this->input->post('product');
    }
    if ($this->input->post('category')) {
        $v .= "&category=" . $this->input->post('category');
    }
    if ($this->input->post('start_date')) {
        $v .= "&start_date=" . $this->input->post('start_date');
    }
    if ($this->input->post('end_date')) {
        $v .= "&end_date=" . $this->input->post('end_date');
    }

?>
<script>
    $(document).ready(function () {
        var oTable = $('#PrRData').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getProductsReport/?v=1'.$v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [ null, null, { "mRender": formatQuantity }, { "mRender": formatQuantity }, { "mRender": currencyFormate }, { "mRender": currencyFormate }, { "mRender": currencyFormate } ]
        }).fnSetFilteringDelay().columnFilter({aoColumns: [ {type: "text", bRegex: true}, {type: "text", bRegex: true}, null, null, null, null ]});
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
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function(event, ui) {
                $('#product_id').val(ui.item.id);
               //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('products_report'); ?> <?php
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

                        <?php echo form_open("reports/products"); ?>
                    <div class="row">
                    <div class="col-sm-4">
                    <div class="form-group">
                        <?= lang("product", "product"); ?>
                        <?php echo form_input('sproduct', (isset($_POST['sproduct']) ? $_POST['sproduct'] : ""), 'class="form-control" id="product"'); ?> 
                        <input type="hidden" name="product" value="<?=isset($_POST['product']) ? $_POST['product'] : ""?>" id="product_id" />
                    </div>
                    </div>
                        <div class="col-sm-4">
                        <div class="form-group">
                        <?= lang("category", "category") ?>
                        <?php
                        $cat[''] = "";
                        foreach ($categories as $category) {
                            $cat[$category->id] = $category->name;
                        }
                        echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                        ?>
                    </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                        <?= lang("start_date", "start_date"); ?>
                        <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?> 
                    </div>
                    </div>
                    <div class="col-sm-4">
                    <div class="form-group">
<?= lang("end_date", "end_date"); ?>
                    <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
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
                    <table id="PrRData" class="table table-striped table-bordered table-condensed table-hover dfTable" style="margin-bottom:5px;">
                        <thead>
                            <tr class="active">
                                <th><?= lang("product_code"); ?></th>
                                <th><?= lang("product_name"); ?></th>
                                <th><?= lang("purchased"); ?></th>
                                <th><?= lang("sold"); ?></th>
                                <th><?= lang("purchased_amount"); ?></th>
                                <th><?= lang("sold_amount"); ?></th>
                                <th><?= lang("profit_loss"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>[<?= lang("product_code"); ?>]</th>
                                <th>[<?= lang("product_name"); ?>]</th>
                                <th><?= lang("purchased"); ?></th>
                                <th><?= lang("sold"); ?></th>
                                <th><?= lang("purchased_amount"); ?></th>
                                <th><?= lang("sold_amount"); ?></th>
                                <th><?= lang("profit_loss"); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>

            </div>                        
        </div>
    </div>
</div>

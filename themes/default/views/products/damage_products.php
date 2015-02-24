<script src="<?= $assets ?>js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $('#dmpData').dataTable({
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "aaSorting": [[0, "desc"]],
        "iDisplayLength": <?= $Settings->rows_per_page ?>,
        'sAjaxSource': '<?= site_url('products/getdamageproducts') ?>',
        'fnServerData': function(sSource, aoData, fnCallback)
        {
            aoData.push({"name": "<?php echo $this->security->get_csrf_token_name(); ?>", "value": "<?php echo $this->security->get_csrf_hash() ?>"});
            $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
        },
        "aoColumns": [
            {"mRender": fld}, null, null, null, null, {"bSortable": false}
        ]
    }).columnFilter({aoColumns: [ {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null ]});
});
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('damage_products'); ?></h2>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>
                <table id="dmpData" class="table table-bordered table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("product_code"); ?></th>
                            <th><?php echo $this->lang->line("product_name"); ?></th>
                            <th><?php echo $this->lang->line("quantity"); ?></th>
                            <th><?php echo $this->lang->line("warehouse"); ?></th>
                            <th style="min-width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>[<?php echo $this->lang->line("date"); ?> (yyyy-mm-dd)]</th>
                            <th>[<?php echo $this->lang->line("product_code"); ?>]</th>
                            <th>[<?php echo $this->lang->line("product_name"); ?>]</th>
                            <th>[<?php echo $this->lang->line("quantity"); ?>]</th>
                            <th>[<?php echo $this->lang->line("warehouse"); ?>]</th>
                            <th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th> 
                        </tr>
                    </tfoot>
                </table>
            </div>                        
        </div>
    </div>
</div>
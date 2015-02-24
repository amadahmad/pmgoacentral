
<script>
    $(document).ready(function() {
        var oTable = $('#CusData').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getSuppliers') ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null, null, null, null, null, {"bSortable": false}]
        }).fnSetFilteringDelay().columnFilter({aoColumns: [ {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null ]});
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('suppliers'); ?></h2>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">

                <p class="introtext"><?= lang('view_report_supplier'); ?></p>

                <div class="table-responsive">
                    <table id="CusData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                            <tr class="primary">
                                <th><?= lang("company"); ?></th>
                                <th><?= lang("name"); ?></th>
                                <th><?= lang("phone"); ?></th>
                                <th><?= lang("email_address"); ?></th>
                                <th><?= lang("city"); ?></th>
                                <th><?= lang("country"); ?></th>
                                <th style="width:85px;"><?= lang("actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="active">
                                <th>[<?= lang("company"); ?>]</th>
                                <th>[<?= lang("name"); ?>]</th>
                                <th>[<?= lang("phone"); ?>]</th>
                                <th>[<?= lang("email_address"); ?>]</th>
                                <th>[<?= lang("city"); ?>]</th>
                                <th>[<?= lang("country"); ?>]</th>
                                <th style="width:85px;"><?= lang("actions"); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>                        
        </div>
    </div>
</div>

	


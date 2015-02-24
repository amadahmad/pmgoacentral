<script>
    $(document).ready(function () {
        var oTable = $('#PExData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getExpiryAlerts' . ($warehouse_id ? '/' . $warehouse_id : '')) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": img_hl}, null, null, null, {"mRender": fsd}],
        }).fnSetFilteringDelay().columnFilter({aoColumns: [ null, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}]});

    });

</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar-o"></i><?= lang('product_expiry_alerts').' '.($warehouse_id ? '('. $warehouse->name .')' : ''); ?></h2>
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
                            echo '<li '.($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '').'><a href="' . site_url('reports/expiry_alerts/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
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

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="PExData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-hover table-striped dfTable">
                        <thead>
                            <tr class="active">
                                <th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>
                                <th><?php echo $this->lang->line("product_code"); ?></th>
                                <th><?php echo $this->lang->line("product_name"); ?></th>
                                <th><?php echo $this->lang->line("quantity"); ?></th>
                                <th><?php echo $this->lang->line("expiry_date"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="dataTables_empty"><?=lang('loading_data_from_server');?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>
                                <th>[<?php echo $this->lang->line("product_code"); ?>]</th>
                                <th>[<?php echo $this->lang->line("product_name"); ?>]</th>
                                <th>[<?php echo $this->lang->line("quantity"); ?>]</th>
                                <th>[<?php echo $this->lang->line("expiry_date"); ?> (yyyy-mm-dd)]</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>                        
        </div>
    </div>
</div>

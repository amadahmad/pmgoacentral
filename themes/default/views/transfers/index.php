<script>
    $(document).ready(function () {
        var oTable = $('#TOData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('transfers/getTransfers') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, {"mRender": fld}, null, null, null, {"mRender": currencyFormate}, {"mRender": currencyFormate}, {"mRender": currencyFormate}, null, {"bSortable": false}],
            'fnRowCallback' : function(nRow, aData, iDisplayIndex ){
               var oSettings = oTable.fnSettings();
               nRow.id = aData[0];
               nRow.className = "transfer_link";
               return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var row_total = 0, tax = 0, gtotal = 0;
                for (var i = 0; i < aaData.length; i++) {
                    row_total += parseFloat(aaData[ aiDisplay[i] ][5]);
                    tax += parseFloat(aaData[ aiDisplay[i] ][6]);
                    gtotal += parseFloat(aaData[ aiDisplay[i] ][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[5].innerHTML = currencyFormate(formatMoney(row_total));
                nCells[6].innerHTML = currencyFormate(formatMoney(tax));
                nCells[7].innerHTML = currencyFormate(formatMoney(gtotal));
            }
        }).fnSetFilteringDelay().columnFilter({aoColumns: [ null, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null, null, null, {type: "text", bRegex: true}, null]});
    });
</script>
<?php if ($Owner) { echo form_open('transfers/transfer_actions', 'id="action-form"'); } ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-star-o"></i><?= lang('transfers'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('transfers/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_transfer') ?></a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_transfers") ?></b>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_transfers') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="TOData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                            <tr class="active">
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkft" type="checkbox" name="check" />
                                </th>
                                <th><?php echo $this->lang->line("date"); ?></th>
                                <th><?php echo $this->lang->line("ref_no"); ?></th>
                                <th><?php echo $this->lang->line("warehouse").' ('.lang('from').')'; ?></th>
                                <th><?php echo $this->lang->line("warehouse").' ('.lang('to').')'; ?></th>
                                <th><?php echo $this->lang->line("total"); ?></th>
                                <th><?php echo $this->lang->line("product_tax"); ?></th>
                                <th><?php echo $this->lang->line("grand_total"); ?></th>
                                <th><?php echo $this->lang->line("status"); ?></th>
                                <th style="width:100px;"><?php echo $this->lang->line("actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="dataTables_empty"><?=lang('loading_data_from_server');?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="active">
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkft" type="checkbox" name="check" />
                                </th>
                                <th>[<?php echo $this->lang->line("date"); ?> (yyyy-mm-dd)]</th>
                                <th>[<?php echo $this->lang->line("ref_no"); ?>]</th>
                                <th>[<?php echo $this->lang->line("warehouse").' ('.lang('from').')'; ?>]</th>
                                <th>[<?php echo $this->lang->line("warehouse").' ('.lang('to').')'; ?>]</th>
                                <th>[<?php echo $this->lang->line("total"); ?>]</th>
                                <th>[<?php echo $this->lang->line("product_tax"); ?>]</th>
                                <th>[<?php echo $this->lang->line("grand_total"); ?>]</th>
                                <th>[<?php echo $this->lang->line("status"); ?>]</th>
                                <th style="width:100px; text-align: center;"><?php echo $this->lang->line("actions"); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>                        
        </div>
    </div>
</div>
    <?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action" />
    <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
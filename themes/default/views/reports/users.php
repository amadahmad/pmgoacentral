
<script>
    $(document).ready(function() {
        var oTable = $('#staffTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getUsers') ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push({"name": "<?= $this->security->get_csrf_token_name() ?>", "value": "<?= $this->security->get_csrf_hash() ?>"});
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [ null, null, null, null, null, {"mRender": user_status}, {"bSortable": false}]
        }).fnSetFilteringDelay().columnFilter({aoColumns: [
                {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, {type: "text", bRegex: true}, null, null
            ]});
    });
</script>
<style>.table td:nth-child(6) { text-align: center; }</style>
<?php if($Owner){ echo form_open('auth/user_actions', 'id="action-form"'); } ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('users'); ?></h2>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">
                <p class="introtext"><?= lang('view_report_staff'); ?></p>
                <div class="table-responsive">
                    <table id="staffTable" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('first_name'); ?></th>
                                <th><?php echo lang('last_name'); ?></th>
                                <th><?php echo lang('email'); ?></th>
                                <th><?php echo lang('company'); ?></th>
                                <th><?php echo lang('group'); ?></th>
                                <th style="width:100px;"><?php echo lang('status'); ?></th>
                                <th style="width:80px;"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="dataTables_empty"><?=lang('loading_data_from_server')?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="active">
                                <th>[<?php echo lang('first_name'); ?>]</th>
                                <th>[<?php echo lang('last_name'); ?>]</th>
                                <th>[<?php echo lang('email'); ?>]</th>
                                <th>[<?php echo lang('company'); ?>]</th>
                                <th>[<?php echo lang('group'); ?>]</th>
                                <th style="width:100px;"><?php echo lang('status'); ?></th>
                                <th style="width:85px;"><?= lang("actions"); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               
            </div>                           

        </div>
    </div>
</div>
<?php if($Owner){ ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action" />
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
<?= form_close() ?>

<script language="javascript">
    $(document).ready(function() {
        $('#set_admin').click(function(){
            $('#usr-form-btn').trigger('click');   
        });
        
    });
</script>

<?php } ?>
<style>ul.ui-autocomplete { z-index: 1100; }</style>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?=$product->name;?></h4>
            </div>
            <?php echo form_open("products/rack_quantity/".$product->id); ?>  
            <div class="modal-body">
                <p><?= lang('enter_info'); ?></p>
               
                    <div class="form-group">
                                <?php echo lang('rack_location', 'rack'); ?> 
                                <div class="controls">
                                    <?php echo form_input('customer', '', 'id="customer" class="form-control" required="required"'); ?>
                                </div>
                            </div>
		
		<div class="form-group">
                                <?= lang("warehouse", "warehouse") ?>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'class="form-control" id="warehouse" placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required"')
                                ?> 
                            </div>
			    
                            <div class="form-group">
                                <?php echo lang('quantity', 'quantity'); ?>
                                <div class="controls">
                                    <?php echo form_input('quantity', '', 'id="quantity" class="form-control" required="required"'); ?>
                                </div>
                            </div>

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', lang('add_rack_quantity'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?php echo form_close(); ?>  
    </div>

<script type="text/javascript">
    $(document).ready(function(){
	$("#customer").autocomplete({
            source: function(request, response) {
                $.ajax({url: "<?php echo site_url('products/racks'); ?>",
                    data: {<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash() ?>", term: $('#customer').val()},
                    dataType: "json",
                    type: "post",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                v = ui.item.value;
                cid = v.split(' #');
                $('.customer').val(cid[1]);
           },
            error: function() {
                alert('<?php echo $this->lang->line('ajax_error'); ?>');
                $('.ui-autocomplete-loading').removeClass("ui-autocomplete-loading");
            }
        });
    });
</script>    
    
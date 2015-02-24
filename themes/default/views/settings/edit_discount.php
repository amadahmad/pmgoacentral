
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_discount'); ?></h4>
        </div>
        <?php echo form_open("system_settings/edit_discount/" . $id); ?>  
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
                <div class="controls"> <?php echo form_input('name', $discount->name, 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="discount"><?php echo $this->lang->line("discount"); ?></label>
                <div class="controls"> <?php echo form_input('discount', $discount->discount, 'class="form-control" id="discount" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label for="type"><?php echo $this->lang->line("type"); ?></label>
                <div class="controls"> <?php $type = array('1' => lang('percentage'), '2' => lang('fixed')); 
                echo form_dropdown('type', $type, $discount->type, 'class="form-control" id="type" required="required"'); ?> </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_discount', lang('edit_discount'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>  
</div>
<?=$modal_js?>
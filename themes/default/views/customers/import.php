<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('import_by_csv'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("customers/import_csv", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            
	<div class="well well-small">
    <a href="<?php echo base_url(); ?>assets/uploads/csv_lib/sample.csv" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download Sample File</a>
    <span class="text-warning"><?php echo $this->lang->line("csv1"); ?></span><br /><?php echo $this->lang->line("csv2"); ?> <span class="text-info">(<?php echo $this->lang->line("name"); ?>, <?php echo $this->lang->line("email"); ?>, <?php echo $this->lang->line("phone"); ?>, <?php echo $this->lang->line("company"); ?>, <?php echo $this->lang->line("address"); ?>, <?php echo $this->lang->line("city"); ?>,  <?php echo $this->lang->line("state"); ?>, <?php echo $this->lang->line("postal_code"); ?>, <?php echo $this->lang->line("country"); ?>)</span> <?php echo $this->lang->line("csv3"); ?>
    
    </div>
<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form'); echo form_open_multipart("customer/import_csv", $attrib); ?>
            <div class="form-group">
                        <?= lang("upload_file", "csv_file") ?>
                <input id="csv_file" type="file" name="csv_file" data-bv-notempty="true" data-show-upload="false" data-show-preview="false" class="form-control file">
                    </div>
            

</div>
        <div class="modal-footer">
    <?php echo form_submit('import', lang('import'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
<?php echo form_close(); ?>  
</div>

<?=$modal_js?>
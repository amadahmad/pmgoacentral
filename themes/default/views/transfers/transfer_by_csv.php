<script type="text/javascript">
    $(document).ready(function () {
        var to_warehouse;
        $('#to_warehouse').on("select2-focus", function (e) {
            to_warehouse = $(this).val();
        }).on("select2-close", function (e) {
            if ($(this).val() == $('#from_warehouse').val()) {
                $(this).select2('val', to_warehouse);
                bootbox.alert('<?= lang('please_select_different_warehouse') ?>');
            }
        });
        var from_warehouse;
        $('#from_warehouse').on("select2-focus", function (e) {
            from_warehouse = $(this).val();
        }).on("select2-close", function (e) {
            if ($(this).val() == $('#to_warehouse').val()) {
                $(this).select2('val', from_warehouse);
                bootbox.alert('<?= lang('please_select_different_warehouse') ?>');
            }
        });
    });
</script>    
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('transfer_by_csv'); ?></h2>
    </div>
    <div class="box-content"> 
        <div class="row">            
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("transfers/transfer_by_csv", $attrib)
                ?>


                <div class="row">            
                    <div class="col-lg-12">

<?php if ($Owner) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "todate"); ?>
    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="todate" required="required"'); ?>
                                </div>
                            </div>
<?php } ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "ref"); ?>
<?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $rnumber), 'class="form-control input-tip" id="ref" required="required"'); ?> 
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("to_warehouse", "to_warehouse"); ?>
                                <?php
                                $wh[''] = '';
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('to_warehouse', $wh, (isset($_POST['to_warehouse']) ? $_POST['to_warehouse'] : ''), 'id="to_warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("to_warehouse") . '" required="required" style="width:100%;" ');
                                ?> 
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("status", "status"); ?>
                                <?php
                                $post = array('pending' => lang('pending'), 'sent' => lang('sent'), 'completed' => lang('completed'));
                                echo form_dropdown('status', $post, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" required="required" style="width:100%;" ');
                                ?> 
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                                    <div class="form-group" style="margin-bottom:5px;">
                                    <?= lang("shipping", "toshipping"); ?>
                                    <?php echo form_input('shipping', '', 'class="form-control input-tip" id="toshipping"'); ?>
                                    </div>
                                </div>

                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("from_warehouse", "from_warehouse"); ?>
<?php echo form_dropdown('from_warehouse', $wh, (isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : ''), 'id="from_warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("from_warehouse") . '" required="required" style="width:100%;" ');
?> 
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
    <a href="<?php echo $this->config->base_url(); ?>assets/csv/sample_transfer_products.csv" class="btn btn-info pull-right"><i class="icon-download icon-white"></i> Download Sample File</a>
    <span class="text-warning"><?php echo $this->lang->line("csv1"); ?></span><br /><?php echo $this->lang->line("csv2"); ?> <span class="text-info">(<?php echo $this->lang->line("product_code"); ?>, <?php echo $this->lang->line("quantity"); ?>)</span> <?php echo $this->lang->line("csv3"); ?>
    
    </div>
                            <div class="clearfix"></div>
                        </div>
                       
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                    <div class="form-group">
<?= lang("csv_file", "csv_file") ?>
                        <input id="csv_file" type="file" name="userfile" required="required" data-show-upload="false" data-show-preview="false" accept="text/*" class="form-control file">
                    </div>
                    </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <div class="form-group">
<?= lang("document", "document") ?>
                        <input id="document" type="file" name="document" data-show-upload="false" data-show-preview="false" class="form-control file">
                    </div>
                    </div>
                        
                        <div class="clearfix"></div>
                        
                        <div class="col-md-12">
                            <div class="from-group">
<?= lang("note", "tonote"); ?>
<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'id="tonote" class="form-control" style="margin-top: 10px; height: 100px;"'); ?>
                            </div>


                            <div class="from-group"><?php echo form_submit('add_transfer', $this->lang->line("submit"), 'id="add_transfer" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?></div>
                        </div>  

                    </div>  
                </div>


<?php echo form_close(); ?> 

            </div>                        

        </div>
    </div>
</div>
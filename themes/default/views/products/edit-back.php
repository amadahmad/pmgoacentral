
<script type="text/javascript">
    $(document).ready(function() {
        var v = <?=$product->category_id?>;
            if (v) {
                $.ajax({
                    type: "post",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>",
                    data: {<?= $this->security->get_csrf_token_name() ?>: "<?= $this->security->get_csrf_hash() ?>", category_id: v},
                    dataType: "json",
                    success: function(scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: scdata});
                        }
                    },
                    error: function() {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                    }

                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]});
            }
        $("#subcategory").select2('val','<?=$product->subcategory_id?>');    
        $('#category').change(function() {
            var v = $(this).val();
            $('#cloading').show();
            if (v) {
                $.ajax({
                    type: "post",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>",
                    data: {<?= $this->security->get_csrf_token_name() ?>: "<?= $this->security->get_csrf_hash() ?>", category_id: v},
                    dataType: "json",
                    success: function(scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: scdata});
                        }
                    },
                    error: function() {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#cloading').hide();
                    }

                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]});
            }
            $('#cloading').hide();
        });
    });

</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('edit_product'); ?></h2>
    </div>
    <div class="box-content"> 
        <div class="row">            
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('update_info'); ?></p>

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart('products/edit/'.$product->id, $attrib)
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?= lang("product_code", "code") ?>
<?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : $product->code), 'class="form-control tip" id="code" required="required"') ?>
                            </div>
                            <div class="form-group">
                                <?= lang("barcode_symbology", "barcode_symbology") ?>
                                <?php
                                        $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca ' => 'UPC-A', 'upce' => 'UPC-E');
                                        echo form_dropdown('barcode_symbology', $bs, $product->barcode_symbology, 'class="form-control tip" required="required" style="width:100%;"');
                                        ?>
                                    
                                </div>
                            <div class="form-group">
                                <?= lang("product_name", "name") ?>
<?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : $product->name), 'class="form-control tip" id="name" required="required"'); ?> 
                            </div>
                            <div class="form-group">
                                <?= lang("category", "category") ?>
                                <?php 
                                $cat[''] = "";
                                foreach ($categories as $category) {
                                    $cat[$category->id] = $category->name;
                                }
                                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : $product->category_id), 'class="tip chzn-select form-control" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" ')
                                ?> 
                                 
                            </div>
                            <div class="form-group">
                                    <?= lang("subcategory", "subcategory") ?>
                                <div class="controls" id="subcat_data"> <?php
                                    /*$sct[""] = '';

                                    echo form_dropdown('subcategory', $sct, isset($_POST['subcategory']) ? $_POST['subcategory'] : $product->subcategory_id, 'class="form-control" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');*/
                                echo form_input('subcategory', '', 'class="form-control" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="unit"><?= lang("product_unit") ?></label>
<?= form_input('unit', (isset($_POST['unit']) ? $_POST['unit'] : $product->unit), 'class="form-control tip" id="unit" required="required"') ?> 
                            </div>
                            <div class="form-group">
                                <?= lang("product_cost", "cost") ?>
<?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : $product->cost), 'class="form-control tip" id="cost" required="required"') ?> 
                            </div>
                            <div class="form-group">
                                <?= lang("product_price", "price") ?>
<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : $product->price), 'class="form-control tip" id="price" required="required"') ?> 
                            </div>
                            <div class="form-group">
                                <?= lang("quantity", "quantity") ?>
<?= form_input('quantity', (isset($_POST['quantity']) ? $_POST['quantity'] : $product->quantity), 'class="form-control tip" id="quantity" required="required"') ?> 
                            </div>
                            <div class="form-group">
<?= lang("alert_quantity", "alert_quantity") ?>                                
                                <div class="input-group"> <?= form_input('alert_quantity', (isset($_POST['alert_quantity']) ? $_POST['alert_quantity'] : $product->alert_quantity), 'class="form-control tip" id="alert_quantity" required="required"') ?>
                                    <span class="input-group-addon">
                                        <input type="checkbox" name="track_quantity" id="inlineCheckbox1" value="1" checked="checked">
                                    </span> 
                                </div>

                            </div>
                            <div class="form-group">
                                <?= lang("product_tax", "tax_rate") ?>
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('tax_rate', $tr, (isset($_POST['tax_rate']) ? $_POST['tax_rate'] : $product->tax_rate), 'class="form-control" id="tax_rate" placeholder="' . lang("select") . ' ' . lang("product_tax") . '" required="required" ')
                                ?> 
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div class="form-group">
<?= lang("product_image", "product_image") ?>

                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <span class="btn btn-file btn-primary"><span class="fileupload-new"><?= lang("select_image") ?></span><span class="fileupload-exists"><?= lang("change") ?></span><input type="file" name="userfile" id="product_image" /></span>
                                    <span class="fileupload-preview"></span>
                                    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="form-group">
                        <?= lang("product_details_for_invoice", "note") ?>
<?= form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $product->details), 'class="input-block-level" id="note"'); ?> 

                    </div>
                    <?=form_hidden('id', $id) ?>
                    <div class="form-group">
<?php echo form_submit('update_product', $this->lang->line("update_product"), 'class="btn btn-primary"'); ?> 
                    </div>

                </div>
<?= form_close(); ?> 

            </div>                        

        </div>
    </div>
</div>

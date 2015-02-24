<script type="text/javascript">
    $(document).ready(function() {
        $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_category_to_load') ?>'}
            ]});
        $('#category').change(function() {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>/" + v,
                    dataType: "json",
                    success: function(scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: scdata});
                        }
                    },
                    error: function() {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({placeholder: "<?= lang('select_category_to_load') ?>", data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]});
            }
            $('#modal-loading').hide();
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_product'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/add", $attrib)
                ?>

                <div class="col-md-5">
                    <div class="form-group">
                        <?= lang("product_type", "type") ?>
                        <?php $opts = array('standard' => lang('standard'), 'combo' => lang('combo'), 'digital' => lang('digital'), 'service' => lang('service'));
                        echo form_dropdown('type', $opts, (isset($_POST['type']) ? $_POST['type'] : ""), 'class="form-control" id="type" required="required"'); ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_name", "name") ?>
                        <?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ""), 'class="form-control" id="name" required="required"'); ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_code", "code") ?>
                        <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ""), 'class="form-control" id="code"  required="required"') ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("barcode_symbology", "barcode_symbology") ?>
                        <?php
                        $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca ' => 'UPC-A', 'upce' => 'UPC-E');
                        echo form_dropdown('barcode_symbology', $bs, (isset($_POST['barcode_symbology']) ? $_POST['barcode_symbology'] : 'code128'), 'class="form-control select" id="barcode_symbology" required="required" style="width:100%;"');
                        ?>

                    </div>
                    <div class="form-group all">
                        <?= lang("category", "category") ?>
                        <?php
                        $cat[''] = "";
                        foreach ($categories as $category) {
                            $cat[$category->id] = $category->name;
                        }
                        echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ""), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
                        ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("subcategory", "subcategory") ?>
                        <div class="controls" id="subcat_data"> <?php
                            echo form_input('subcategory', '', 'class="form-control" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group all">
                        <label class="control-label" for="unit"><?= lang("product_unit") ?></label>
                    <?= form_input('unit', (isset($_POST['unit']) ? $_POST['unit'] : ""), 'class="form-control tip" id="unit" required="required"') ?>
                    </div>
                    <div class="form-group standard">
                        <?= lang("product_cost", "cost") ?>
                        <?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ""), 'class="form-control tip" id="cost" required="required"') ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_price", "price") ?>
                        <?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ""), 'class="form-control tip" id="price" required="required"') ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_group", "product_group") ?>
                        <?php
                        $pg[''] = "";
                        foreach ($product_groups as $product_group) {
                            $pg[$product_group->id] = $product_group->name;
                        }
                        echo form_dropdown('product_group', $pg, (isset($_POST['product_group']) ? $_POST['product_group'] : ""), 'class="form-control select" id="product_group" placeholder="' . lang("select") . " " . lang("product_group") . '" style="width:100%"')
                        ?>
                    </div>

                    <div class="form-group all">
                        <?= lang("product_tax", "tax_rate") ?>
                        <?php
                        $tr[""] = "";
                        foreach ($tax_rates as $tax) {
                            $tr[$tax->id] = $tax->name;
                        }
                        echo form_dropdown('tax_rate', $tr, (isset($_POST['tax_rate']) ? $_POST['tax_rate'] : $Settings->default_tax_rate), 'class="form-control select" id="tax_rate" placeholder="' . lang("select") . ' ' . lang("product_tax") . '" style="width:100%"')
                        ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("tax_method", "tax_method") ?>
                        <?php
                        $tm = array('0' => lang('inclusive'), '1' => lang('exclusive'));
                        echo form_dropdown('tax_method', $tm, (isset($_POST['tax_method']) ? $_POST['tax_method'] : ''), 'class="form-control select" id="tax_method" placeholder="' . lang("select") . ' ' . lang("tax_method") . '" style="width:100%"')
                        ?>
                    </div>
                    <div class="form-group standard">
                        <?= lang("alert_quantity", "alert_quantity") ?>
                        <div
                            class="input-group"> <?= form_input('alert_quantity', (isset($_POST['alert_quantity']) ? $_POST['alert_quantity'] : ""), 'class="form-control tip" id="alert_quantity"') ?>
                            <span class="input-group-addon">
                                <input type="checkbox" name="track_quantity" id="inlineCheckbox1" value="1"
                                       checked="checked">
                            </span>
                        </div>
                    </div>
                    <div class="form-group standard">
                        <?= lang("supplier", "supplier") ?> <button type="button" class="btn btn-primary btn-xs" id="addSupplier"><i class="fa fa-plus"></i></button>
                        <div class="row" id="supplier-con"><div class="col-md-8 col-sm-8 col-xs-8">
                        <?php
                        echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control suppliers" id="supplier" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%"')
                        ?></div>
                            <div class="col-md-4 col-sm-4 col-xs-4"><?= form_input('supplier_price', (isset($_POST['supplier_price']) ? $_POST['supplier_price'] : ""), 'class="form-control tip" id="supplier_price" placeholder="'.lang('supplier_price').'"') ?></div>
                        </div>
                        <div id="ex-suppliers"></div>
                    </div>
                    
                    <div class="form-group all">
                        <?= lang("product_image", "product_image") ?>
                        <input id="product_image" type="file" name="product_image" data-show-upload="false" data-show-preview="false" accept="image/*" class="form-control file">
                    </div>

                    <div class="form-group all">
                        <?= lang("product_gallery_images", "images") ?>
                        <input id="images" type="file" name="userfile[]" multiple="true" data-show-upload="false" data-show-preview="false" class="form-control file" accept="image/*">
                    </div>
                    <div id="img-details"></div>
                </div>
                <div class="col-md-6 col-md-offset-1">
                    <div class="standard"><strong><?=lang("warehouse_quantity")?></strong><br>
                        <?php
                        if (!empty($warehouses)) {
                            echo '<div class="row"><div class="col-md-12"><div class="well">';
                            foreach ($warehouses as $warehouse) {
                                //$whs[$warehouse->id] = $warehouse->name;
                                echo '<div class="col-md-6 col-sm-6 col-xs-6" style="padding-bottom:15px;">' . lang($warehouse->name, 'wh_qty_' . $warehouse->id) . '<br><div class="form-group">' . form_hidden('wh_' . $warehouse->id, $warehouse->id) . form_input('wh_qty_' . $warehouse->id, (isset($_POST['wh_qty_' . $warehouse->id]) ? $_POST['wh_qty_' . $warehouse->id] : ''), 'class="form-control" id="wh_qty_' . $warehouse->id . '" placeholder="' . lang('quantity') . '"').'</div>';
                                if ($this->Settings->racks) {
                                    echo '<div class="form-group">' . form_input('rack_' . $warehouse->id, (isset($_POST['rack_' . $warehouse->id]) ? $_POST['rack_' . $warehouse->id] : ''), 'class="form-control" id="rack_' . $warehouse->id . '" placeholder="' . lang('rack') . '"').'</div>';
                                }
                                echo '</div>';
                            }
                            echo '<div class="clearfix"></div></div></div></div>';
                        }
                        ?>
                        <div class="clearfix"></div>
                    
                    <?php if ($this->Settings->attributes) { ?>
                   
                    <strong><?= lang("attributes", "attr") ?></strong><br>
                        <?php
                        if (!empty($attributes)) {
                            echo '<div class="row"><div class="col-md-12"><div class="well">';

                            foreach ($attributes as $attribute) {
                                echo '<div class="col-md-12"><label for="'.$attribute->id.'"><input class="checkbox attributes" type="checkbox" name="attr_'.$attribute->id.'" id="'.$attribute->id.'" value="1" '.(isset($_POST['attr_'.$attribute->id]) ? 'checked="checked"' : '').' /> ' . lang($attribute->title) . '</label><br><div id="options_'.$attribute->id.'" '.(isset($_POST['attr_'.$attribute->id]) ? '' : 'style="display:none;"').'>';
                                if($attribute->options) { $options = explode('|', $attribute->options);
                                foreach($options as $option) {
                                     echo '<div style="font-weight:bold;">'.$option.'</div><div class="clearfix"></div>';
                                     $option = url_title($option, '_');
                                    foreach ($warehouses as $warehouse) {
                                        echo '<div class="col-md-6 col-sm-6 col-xs-6"><label>'.$warehouse->name.'</label>'. form_hidden('attr_wh_'.$warehouse->id, $warehouse->id).form_hidden('option_' . url_title($option, '_').'_'. $warehouse->id, $option).form_input('qty_'.$option.'_wh_'.$warehouse->id, (isset($_POST['qty_'.$option.'_wh_'.$warehouse->id]) ? $_POST['qty_'.$option.'_wh_'.$warehouse->id] : ''), 'class="form-control" placeholder="'.lang('quantity').'"').'</div>';
                                    }
                                    echo '<div style="clear:both;height:15px;"></div>';
                                } }
                                echo '</div></div>';
                            } 
                            echo '<div class="clearfix"></div></div></div></div>';
                        }
                        ?>
                        <div class="clearfix"></div>
                    <?php } ?>
                        </div>
                    <div class="combo" style="display:none;">
                        
                    <div class="form-group">
                    <?= lang("add_product", "add_item") ?>
                    <?php echo form_input('add_item', '', 'class="form-control ttip" id="add_item" data-placement="top" data-trigger="focus" data-bv-notEmpty-message="'.lang('please_add_items_below').'" placeholder="' . $this->lang->line("add_item") . '"'); ?>
                    </div>
                        <div class="control-group table-group">
                            <label class="table-label" for="combo"><?= lang("combo_products"); ?></label>
                            <!--<div class="row"><div class="ccol-md-10 col-sm-10 col-xs-10"><label class="table-label" for="combo"><?= lang("combo_products"); ?></label></div>
                                <div class="ccol-md-2 col-sm-2 col-xs-2"><div class="form-group no-help-block" style="margin-bottom: 0;"><input type="text" name="combo" id="combo" value="" data-bv-notEmpty-message="" class="form-control" /></div></div></div>-->
                    <div class="controls table-controls">
                        <table id="prTable" class="table items table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-7 col-sm-7 col-xs-7"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                    <th class="col-md-4 col-sm-4 col-xs-4"><?= lang("quantity"); ?></th>
                                    <th class="col-md-1 col-sm-1 col-xs-1 text-center"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> 
                        
                    </div>
                    
                    <div class="digital" style="display:none;">
                        <div class="form-group digital">
                            <?= lang("digital_file", "digital_file") ?>
                            <input id="digital_file" type="file" name="digital_file" data-show-upload="false" data-show-preview="false" class="form-control file">
                        </div>
                    </div>
                    
                </div>

                <div class="col-md-12">
                    
                    <div class="form-group all">
                    <?= lang("product_details", "product_details") ?>
                    <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ''), 'class="form-control" id="details"'); ?>
                    </div>
                    <div class="form-group all">
                    <?= lang("product_details_for_invoice", "details") ?>
                    <?= form_textarea('details', (isset($_POST['details']) ? $_POST['details'] : ''), 'class="form-control" id="details"'); ?>
                    </div>

                    <div class="form-group">
                    <?php echo form_submit('add_product', $this->lang->line("add_product"), 'class="btn btn-primary"'); ?>
                    </div>

                </div>
                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var audio_success = new Audio('assets/sounds/sound2.mp3');
        var audio_error = new Audio('assets/sounds/sound3.mp3');
        var items = {};
        $('.attributes').on('ifChecked', function(event){
            $('#options_'+$(this).attr('id')).slideDown();
        });
        $('.attributes').on('ifUnchecked', function(event){
            $('#options_'+$(this).attr('id')).slideUp();
        });
        //$('#cost').removeAttr('required');
        $('#type').change(function(){
            var t = $(this).val();
            if(t !== 'standard') {
                $('.standard').slideUp();
                $('#cost').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
            } else {
                $('.standard').slideDown();
                $('#cost').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');                
            }
            if(t !== 'digital') {
                $('.digital').slideUp();
                $('#digital_file').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
            } else {
                $('.digital').slideDown();
                $('#digital_file').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
            }
            if(t !== 'combo') {
                $('.combo').slideUp();
                $('#add_item').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
            } else {
                $('.combo').slideDown();
                $('#add_item').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'add_item');
            }
        });
        
        $("#add_item").autocomplete({
            source: '<?= site_url('sales/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 5,
            response: function(event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function() {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function() {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                    
                }
            },
            select: function(event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_product_item(ui.item);
                    if (row) {
                        $(this).val('');
                        $('#add_item').removeAttr('required');
                        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
                    }
                } else {
                    audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
        $('#add_item').removeAttr('required');
        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        function add_product_item(item) {
            if(item == null) { return false; }
                item_id = item.id;
            if(items[item_id]) {
                items[item_id].qty = items[item_id].qty+1;
            } else {
                items[item_id] = item;
            }

            $("#prTable tbody").empty();
            $.each(items, function() {
                var row_no = this.id;
                var newTr = $('<tr id="row_' + row_no + '" class="item_'+this.id+'"></tr>');
                tr_html = '<td><input name="items[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.name + ' (' + this.code + ')</span></td>';
                tr_html += '<td><input class="form-control text-center" name="quantity[]" type="text" value="'+this.qty+'" data-id="'+row_no+'" data-item="'+this.id+'" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#prTable");

            });
            $('.item_' + item_id).addClass('warning');
            audio_success.play();
            return true;
            
        }
        
        $(document).on('click', '.del', function() {
            var id = $(this).attr('id');
            $(this).closest('#row_'+id).remove();
            $.each(items, function(i, v){
                if(v.id == id) {
                 delete items[i];
                } 
            });
        });
        var su = 2;
        $('#addSupplier').click( function() {
            if(su <= 5) {
                $('#supplier_1').select2('destroy');
                var html = '<div style="clear:both;height:15px;"></div><div class="row"><div class="col-md-8 col-sm-8 col-xs-8"><input type="hidden" name="supplier_'+su+'", class="form-control" id="supplier_'+su+'" placeholder="<?=lang("select").' '.lang("supplier")?>" style="width:100%" /></div><div class="col-md-4 col-sm-4 col-xs-4"><input type="text" name="supplier_'+su+'_price" class="form-control tip" id="supplier_'+su+'_price" placeholder="<?=lang('supplier_price')?>" /></div></div>';
                $('#ex-suppliers').append(html);
                var sup = $('#supplier_'+su);
                suppliers(sup);
                su++;
            } else {
                bootbox.alert('<?=lang('max_reached')?>');
                return false;
            }
        });
        
        var _URL = window.URL || window.webkitURL;
        $("input#images").on('change.bs.fileinput', function() {
            var ele = document.getElementById($(this).attr('id'));
            var result = ele.files;
            $('#img-details').empty();
            for(var x = 0;x< result.length;x++){
             var fle = result[x];
             img = new Image();
                img.onload = function () {
                    if(this.width <= site.settings.iwidth && this.height <= site.settings.iheight) {
                        $('#img-details').append( "<pre>NAME: " + fle.name + ", TYPE: " + fle.type + ", SIZE: " + fle.size + " ( "+this.width+"px X "+this.height+"px ) </pre>");
                    } else {
                        bootbox.alert("<?=lang('file_size_exceed')?> <br>NAME: " + fle.name + ", SIZE: " + fle.size + " ( "+this.width+"px X "+this.height+"px )");
                        $("input#images").fileinput('clear');
                        return false;
                    }
                };
                img.src = _URL.createObjectURL(fle);
            }
        });

    });
</script>

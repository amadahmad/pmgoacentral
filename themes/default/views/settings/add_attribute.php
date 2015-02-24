<!--<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_attribute'); ?></h4>
        </div>
        <?php
        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/add_attribute", $attrib);
        ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="form-group">
                <?= lang("title", "title") ?>
                <?= form_input('title', (isset($_POST['title']) ? $_POST['title'] : ""), 'class="form-control input-tip" id="title" required="required"'); ?>
            </div>
            <div class="form-group">
                <?= lang("options_label", "options") ?>
                <?= form_input('options', (isset($_POST['options']) ? $_POST['options'] : ""), 'class="form-control input-tip" id="options" required="required"'); ?>
            </div>
        </div>
        <div class="modal-footer">
        <?php echo form_submit('add_attribute', lang('add_attribute'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>  
</div>

<?= $modal_js ?>
-->

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_attribute'); ?></h4>
        </div>
        <?php $attrib = array('role' => 'form', 'id' => 'attrForm');
        echo form_open("system_settings/add_attribute", $attrib);
        ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>


            <div class="form-group">
                <?= lang("title", "title") ?>
<?= form_input('title', (isset($_POST['title']) ? $_POST['title'] : ""), 'class="form-control input-tip" id="title" data-bv-notempty="true"'); ?>
            </div>
            <!--<div class="form-group">
                <?= lang("category", "category") ?>
                <?php
                $cat[''] = "";
                foreach ($categories as $category) {
                    $cat[$category->id] = $category->name;
                }
                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ""), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                ?>
            </div>-->
            <div class="form-group">
                <?= lang("type", "type") ?>
                <?php
                $type = array('' => '', 'text' => lang('text'), 'checkbox' => lang('checkbox'), 'radio' => lang('radio'), 'select' => lang('select'), 'multiple' => lang('multiple'));
                echo form_dropdown('type', $type, (isset($_POST['type']) ? $_POST['type'] : ''), 'class="form-control select" id="type" placeholder="' . lang("select") . ' ' . lang("type") . '" data-bv-notempty="true" style="width:100%"')
                ?>
            </div>
            <div id="options">
                <div class="clearfix" style="margin-bottom: 15px;">
                    <strong><?=lang("options *") ?></strong>
                    <button type="button" class="btn btn-primary col-md-12 addButton"><i class="fa fa-plus"></i></button>
<?php /* for ($r = 1; $r <= 2; $r++) { ?>
                        <div class="form-group">
                            <div class="input-group">
    <?= form_input('option[]', (isset($_POST['option'][$r]) ? $_POST['option'][$r] : ""), 'class="form-control input-tip" id="option' . $r . '" data-bv-notempty="false"'); ?>
                                <span class="input-group-addon"><button type="button" class="btn btn-xs addButton">
                                        <i class="fa fa-plus"></i>
                                    </button></span>
                            </div></div>
<?php } */ ?>
                </div>
                <div class="clearfix"></div>
            
            <div class="form-group hide" id="optionTemplate">
                <div class="input-group option">
                    <label class="optionLabel"></label>
                    <input type="text" class="form-control" name="options[]" data-bv-notempty="true" />
                    <span class="input-group-addon"><button type="button" class="btn btn-xs removeButton">
                            <i class="fa fa-minus"></i>
                        </button></span>
                </div>
            </div>
</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_attribute', lang('add_attribute'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
<?php echo form_close(); ?>  
</div>

<?= $modal_js ?>

<script type="text/javascript">
    $(document).ready(function() {
        var MAX_OPTIONS = 8;
        $('#options').hide();
        $("#type").change(function() {
            var t = $(this).val();
            if (t != 'text') {
                $('.addButton').trigger('click');
                $('#options').slideDown();
            } else {
                options = $('.option');
                $.each(options, function() {
                    $(this).find('.removeButton').trigger('click');
                });
                $('#options').slideUp();

            }
        });
        $('#attrForm').bootstrapValidator()
        .on('click', '.addButton', function() {
            var $template = $('#optionTemplate'),
                    $clone = $template.clone().removeClass('hide').removeAttr('id').insertBefore($template),
                    $option = $clone.find('[name="options[]"]');
            $('#attrForm').bootstrapValidator('addField', $option);
        })
        .on('click', '.removeButton', function() {
            var $row = $(this).parents('.form-group'),
                    $option = $row.find('[name="options[]"]');
            $row.remove();
            $('#attrForm').bootstrapValidator('removeField', $option);
        })
        .on('added.field.bv', function(e, data) {
            if (data.field === 'option[]') {
                if ($('#attrForm').find(':visible[name="options[]"]').length >= MAX_OPTIONS) {
                    $('#attrForm').find('.addButton').attr('disabled', 'disabled');
                }
            }
        })
        .on('removed.field.bv', function(e, data) {
            if (data.field === 'option[]') {
                if ($('#attrForm').find(':visible[name="options[]"]').length < MAX_OPTIONS) {
                    $('#attrForm').find('.addButton').removeAttr('disabled');
                }
            }
        });
    });
</script>

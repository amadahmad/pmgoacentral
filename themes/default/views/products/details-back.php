
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?= $product->name; ?></h4>
        </div>

        <div class="modal-body">

            <div style="text-align:center; margin-bottom:15px;"><?php echo $barcode; ?></div>
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-condensed">
                <tbody>
                    <tr>
                        <td><?php echo $this->lang->line("product_code"); ?></td>
                        <td><?php echo $product->code; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("product_name"); ?></td>
                        <td><?php echo $product->name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("product_type"); ?></td>
                        <td><?php echo lang($product->type); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("category"); ?></td>
                        <td><?php echo $category->name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("product_unit"); ?></td>
                        <td><?php echo $product->unit; ?></td>
                    </tr>
                    <?php if (!$Customer) { ?>
                        <tr>
                            <td><?php echo $this->lang->line("product_cost"); ?></td>
                            <td><?php echo $product->cost; ?></td>
                        </tr>
                    <?php } if (!$Supplier) {  ?>
                    <tr>
                        <td><?php echo $this->lang->line("product_price"); ?></td>
                        <td><?php echo $product->price; ?></td>
                    </tr>
                    <?php } if (!$Supplier || !$Customer) { ?>
                    <tr>
                        <td><?php echo $this->lang->line("alert_quantity"); ?></td>
                        <td><?php echo $product->alert_quantity; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
<?php if (!$Supplier || !$Customer) { ?>       
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group"><a href="#" class="tip btn btn-primary" title="<?=lang('gallery')?>"><i class="fa fa-picture-o"></i><span class="visible-md visible-lg"> <?=lang('gallery')?></span></a></div>
                <div class="btn-group"><a data-target="#myModal2" data-toggle="modal" href="<?=site_url('products/rack_quantity/'.$product->id)?>" class="tip btn btn-primary"  title="<?=lang('rack')?>"><i class="fa fa-plus-circle"></i><span class="visible-md visible-lg"> <?=lang('rack')?></span></a></div>
                <div class="btn-group"><a data-target="#myModal2" data-toggle="modal" href="<?=site_url('products/add_damage/'.$product->id)?>" class="tip btn btn-primary"  title="<?=lang('damage')?>"><i class="fa fa-plus-circle"></i><span class="visible-md visible-lg">  <?=lang('damage')?></span></a></div>
                <div class="btn-group"><a onclick="window.open('<?=site_url('products/single_barcode/'.$product->id)?>', 'sma_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#" class="tip btn btn-primary"  title="<?=lang('barcode')?>"><i class="fa fa-print"></i><span class="visible-md visible-lg">  <?=lang('barcode')?></span></a></div>
                <div class="btn-group"><a onclick="window.open('<?=site_url('products/single_label/'.$product->id)?>', 'sma_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#" class="tip btn btn-primary" title="<?=lang('label')?>"><i class="fa fa-print"></i><span class="visible-md visible-lg">  <?=lang('label')?></span></a></div>
                <div class="btn-group"><a href="<?=site_url('products/pdf/'.$product->id)?>" class="tip btn btn-primary" title="<?=lang('pdf')?>"><i class="fa fa-download"></i><span class="visible-md visible-lg"> <?=lang('pdf')?></span></a></div>
                <div class="btn-group"><a href="<?=site_url('products/excel/'.$product->id)?>" class="tip btn btn-primary"  title="<?=lang('excel')?>"><i class="fa fa-download"></i><span class="visible-md visible-lg"> <?=lang('excel')?></span></a></div>
                <div class="btn-group"><a href="<?=site_url('products/edit/'.$product->id)?>" class="tip btn btn-warning tip" title="<?=lang('edit_product')?>"><i class="fa fa-edit"></i><span class="visible-md visible-lg"> <?=lang('edit')?></span></a></div>
                <div class="btn-group"><a href="#" class="tip btn btn-danger bpo" title="<b><?=$this->lang->line("delete_product")?></b>" data-content="<div style='width:150px;'><p><?=lang('r_u_sure')?></p><a class='btn btn-danger' href='<?=site_url('products/delete/'.$product->id)?>'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button></div>" data-html="true" data-placement="top"><i class="fa fa-trash-o"></i><span class="visible-md visible-lg"> <?= lang('delete') ?></span></a></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.tip').tooltip();
});
</script>
<?php } ?>
<?php if ($Customer) { ?>   

<?php } ?>
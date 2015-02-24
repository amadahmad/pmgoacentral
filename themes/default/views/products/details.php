 <div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file-text-o"></i><?= $product->name; ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?=site_url('products/edit/'.$product->id)?>"><i class="fa fa-edit"></i> <?=lang('edit')?></a></li>
                        <li><a onclick="window.open('<?=site_url('products/single_barcode/'.$product->id)?>', 'sma_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#"><i class="fa fa-print"></i>  <?=lang('print_barcode')?></a></li>
                        <li><a onclick="window.open('<?=site_url('products/single_label/'.$product->id)?>', 'sma_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#"><i class="fa fa-print"></i>  <?=lang('print_label')?></a></li>
                        <li><a href="<?=site_url('products/pdf/'.$product->id)?>"><i class="fa fa-download"></i> <?=lang('pdf')?></a></li>
                        <!--<li><a href="<?=site_url('products/excel/'.$product->id)?>"><i class="fa fa-download"></i> <?=lang('excel')?></a></li>-->
                        <li><a data-target="#myModal2" data-toggle="modal" href="<?=site_url('products/add_damage/'.$product->id)?>"><i class="fa fa-plus-circle"></i>  <?=lang('damage')?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?=$this->lang->line("delete_product")?></b>" data-content="<div style='width:150px;'><p><?=lang('r_u_sure')?></p><a class='btn btn-danger' href='<?=site_url('products/delete/'.$product->id)?>'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button></div>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
<p class="introtext"><?php echo lang('product_details'); ?></p>
<div class="row">
    <div class="col-sm-5">
        <img src="<?=base_url()?>assets/uploads/<?=$product->image?>" alt="<?=$product->name?>" class="img-responsive img-thumbnail" />
        <div id="multiimages" class="padding10">
<?php if (!empty($images)) { 
    echo '<a class="img-thumbnail" data-toggle="lightbox" data-gallery="multiimages" data-parent="#multiimages" href="'.base_url().'assets/uploads/' . $product->image . '" style="margin-right:5px;"><img class="img-responsive" src="'.base_url().'assets/uploads/thumbs/' . $product->image . '" alt="'.$product->image.'" style="width:'.$Settings->twidth.'px; height:'.$Settings->theight.'px;" /></a>';
                                                foreach ($images as $ph) {
                                                    echo '<a class="img-thumbnail" data-toggle="lightbox" data-gallery="multiimages" data-parent="#multiimages" href="'.base_url().'assets/uploads/' . $ph->photo . '" style="margin-right:5px;"><img class="img-responsive" src="'.base_url().'assets/uploads/thumbs/' . $ph->photo . '" alt="'.$ph->photo.'" style="width:'.$Settings->twidth.'px; height:'.$Settings->theight.'px;" /></a>';
                                                }
                                            }
                                            ?>
                <div class="clearfix"></div>            
            </div>
    </div>
    <div class="col-sm-7">
        <!--<div class="text-center padding10"><?=lang('barcode_qrcode')?> : <?php echo $barcode ?>
                        <?php $this->sma->qrcode('link', urlencode(site_url('products/view/'.$product->id)), 2); ?>
                        <img src="<?=base_url()?>assets/uploads/qrcode.png" alt="<?=$product->name?>" /></div>-->
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed dfTable">
                <tbody>
                    <tr>
                        <td><?php echo $this->lang->line("barcode_qrcode"); ?></td>
                        <td><?php echo $barcode ?>
                        <?php $this->sma->qrcode('link', urlencode(site_url('products/view/'.$product->id)), 1); ?>
                        <img src="<?=base_url()?>assets/uploads/qrcode.png" alt="<?=$product->name?>" class="pull-right" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("product_type"); ?></td>
                        <td><?php echo lang($product->type); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("product_name"); ?></td>
                        <td><?php echo $product->name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("product_code"); ?></td>
                        <td><?php echo $product->code; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("category"); ?></td>
                        <td><?php echo $category->name; ?></td>
                    </tr>
                    <?php if($product->subcategory_id) { ?>
                    <tr>
                        <td><?php echo $this->lang->line("subcategory"); ?></td>
                        <td><?php echo $subcategory->name; ?></td>
                    </tr>
                    <?php } ?>
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
                    <?php if($product->tax_rate) { ?>
                    <tr>
                        <td><?php echo $this->lang->line("tax_rate"); ?></td>
                        <td><?php echo $tax_rate->name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("tax_method"); ?></td>
                        <td><?php echo $product->tax_method == 0 ? lang('inclusive') : lang('exclusive'); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td><?php echo $this->lang->line("alert_quantity"); ?></td>
                        <td><?php echo $product->alert_quantity; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed dfTable">
                <tbody>
                   
                    <?php 
                        if($product->cf1) { echo '<tr><td>'.lang("pcf1").': '.$product->cf1.'</td></tr>'; } 
                        if($product->cf2) { echo '<tr><td>'.lang("pcf2").': '.$product->cf2.'</td></tr>'; }
                        if($product->cf3) { echo '<tr><td>'.lang("pcf3").': '.$product->cf3.'</td></tr>'; }
                        if($product->cf4) { echo '<tr><td>'.lang("pcf4").': '.$product->cf4.'</td></tr>'; }
                        if($product->cf5) { echo '<tr><td>'.lang("pcf5").': '.$product->cf5.'</td></tr>'; }
                        if($product->cf6) { echo '<tr><td>'.lang("pcf6").': '.$product->cf6.'</td></tr>'; }
                    ?>
                    
                </tbody>
            </table>
            </div>
        
    <?php if($product->type == 'combo') { ?>
        <h3><?=lang('combo_items')?></h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed dfTable">
                <tbody>
                    <?php foreach($combo_items as $combo_item) {
                     echo '<tr><td><strong>'.$combo_item->name.' ('.$combo_item->code.') '.lang('quantity').' '.$combo_item->qty.'</strong></td></tr>';   
                    } ?>
                </tbody>
            </table>
            </div>
    <?php } ?>
    
    <?php if ((!$Supplier || !$Customer) && !empty($warehouses)) { ?> 
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed dfTable">
                <tbody>
                    <?php foreach($warehouses as $warehouse) { if($warehouse->quantity != 0) {
                     echo '<tr><td>'.$warehouse->name.' ('.$warehouse->code.'): <strong>'.$warehouse->quantity.'</strong>'.($warehouse->rack ? ' ('.$warehouse->rack.')' : '').'</td></tr>';   
                    } } ?>
                </tbody>
            </table>
            </div>
    <?php } ?>
        <?php if(!empty($options)) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed dfTable">
                <tbody>
                   
                    <?php 
                        foreach($options as $option) { if($option->quantity != 0) {
                        echo '<tr><td><strong>'
                            .lang('warehouse').':</strong> '.$option->wh_name.',&nbsp;&nbsp;&nbsp; <strong>'
                            .lang('option').':</strong> '.$option->attribute.',&nbsp;&nbsp;&nbsp; <strong>'
                            .lang('quantity').':</strong> '.$option->quantity.',&nbsp;&nbsp;&nbsp; '
                            .($Owner || $Admin ? ($option->cost != 0 && (!$Customer || $this->session->userdata('show_cost')) ? 
                             '<strong>'.lang('cost').':</strong> '.$option->cost.',&nbsp;&nbsp;&nbsp; ' : '').' '
                            .($option->price != 0 && !$Supplier ? '<strong>'.lang('price').':</strong> '.$option->price.',&nbsp;&nbsp;&nbsp; ' : '') : '')
                            .'</td></tr>';   
                        } } ?>
                
                </tbody>
            </table>
            </div>
        <?php } ?>
    </div>
    
    <div class="col-sm-12">
        
            <?=$product->details ? '<div class="well well-sm content">'.$product->details.'</div>' : '';?>
            <?=$product->product_details ? '<div class="well well-sm content">'.$product->product_details.'</div>' : '';?>

    </div>
</div>
            
<?php if (!$Supplier || !$Customer) { ?>       
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group"><a href="<?=site_url('products/edit/'.$product->id)?>" class="tip btn btn-primary tip" title="<?=lang('edit_product')?>"><i class="fa fa-edit"></i> <?=lang('edit')?></a></div>
                <div class="btn-group"><a onclick="window.open('<?=site_url('products/single_barcode/'.$product->id)?>', 'sma_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#" class="tip btn btn-primary"  title="<?=lang('barcode')?>"><i class="fa fa-print"></i>  <?=lang('print_barcode')?></a></div>
                <div class="btn-group"><a onclick="window.open('<?=site_url('products/single_label/'.$product->id)?>', 'sma_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;" href="#" class="tip btn btn-primary" title="<?=lang('label')?>"><i class="fa fa-print"></i>  <?=lang('print_label')?></a></div>
                <div class="btn-group"><a href="<?=site_url('products/pdf/'.$product->id)?>" class="tip btn btn-primary" title="<?=lang('pdf')?>"><i class="fa fa-download"></i> <?=lang('pdf')?></a></div>
                <!--<div class="btn-group"><a href="<?=site_url('products/excel/'.$product->id)?>" class="tip btn btn-primary"  title="<?=lang('excel')?>"><i class="fa fa-download"></i> <?=lang('excel')?></a></div>-->
                <div class="btn-group"><a data-target="#myModal2" data-toggle="modal" href="<?=site_url('products/add_damage/'.$product->id)?>" class="tip btn btn-warning"  title="<?=lang('damage')?>"><i class="fa fa-plus-circle"></i>  <?=lang('damage')?></a></div>
                <div class="btn-group"><a href="#" class="tip btn btn-danger bpo" title="<b><?=$this->lang->line("delete_product")?></b>" data-content="<div style='width:150px;'><p><?=lang('r_u_sure')?></p><a class='btn btn-danger' href='<?=site_url('products/delete/'.$product->id)?>'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button></div>" data-html="true" data-placement="top"><i class="fa fa-trash-o"></i> <?= lang('delete') ?></a></div>
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
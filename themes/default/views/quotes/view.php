<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("quote_no") . '. ' . $inv->id; ?></h2>
        <div class="box-icon">
           <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('quotes/edit/'.$inv->id) ?>"><i class="fa fa-edit"></i> <?= lang('edit_quote') ?></a></li>
                        <li><a href="<?= site_url('quotes/email/'.$inv->id) ?>"><i class="fa fa-envelope-o"></i> <?= lang('send_email') ?></a></li>
                        <li><a href="<?= site_url('quotes/pdf/'.$inv->id) ?>"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <!--<li><a href="<?= site_url('quotes/excel/'.$inv->id) ?>"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>-->
                    </ul>
                </li>
            </ul>
        </div>
    </div> 
    <div class="box-content"> 
        <div class="row">            
            <div class="col-lg-12">

                <!--<div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>" alt="<?= $Settings->site_name; ?>">
                </div>-->
                <div class="well well-sm">
                    <div class="col-md-4 border-right">

                        <div class="col-sm-2"><i class="fa fa-3x fa-hand-o-right padding010 text-muted"></i></div>
                        <div class="col-sm-10">
                            <h2 class=""><?= $biller->company ? $biller->company : $biller->name; ?></h2>
                            <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                            <?php
                            echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;

                            echo "<p>";

                            if ($biller->cf1 != "-" && $biller->cf1 != "") {
                                echo "<br>" . lang("bcf1") . ": " . $biller->cf1;
                            }
                            if ($biller->cf2 != "-" && $biller->cf2 != "") {
                                echo "<br>" . lang("bcf2") . ": " . $biller->cf2;
                            }
                            if ($biller->cf3 != "-" && $biller->cf3 != "") {
                                echo "<br>" . lang("bcf3") . ": " . $biller->cf3;
                            }
                            if ($biller->cf4 != "-" && $biller->cf4 != "") {
                                echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
                            }
                            if ($biller->cf5 != "-" && $biller->cf5 != "") {
                                echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
                            }
                            if ($biller->cf6 != "-" && $biller->cf6 != "") {
                                echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
                            }

                            echo "</p>";
                            echo lang("tel") . ": " . $biller->phone . "<br>" . lang("email") . ": " . $biller->email;
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-md-4 border-right">

                        <div class="col-sm-2"><i class="fa fa-3x fa-user padding010 text-muted"></i></div>
                        <div class="col-sm-10">
                            <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                            <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                            <?php
                            echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;

                            echo "<p>";

                            if ($customer->cf1 != "-" && $customer->cf1 != "") {
                                echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                            }
                            if ($customer->cf2 != "-" && $customer->cf2 != "") {
                                echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                            }
                            if ($customer->cf3 != "-" && $customer->cf3 != "") {
                                echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                            }
                            if ($customer->cf4 != "-" && $customer->cf4 != "") {
                                echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                            }
                            if ($customer->cf5 != "-" && $customer->cf5 != "") {
                                echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                            }
                            if ($customer->cf6 != "-" && $customer->cf6 != "") {
                                echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                            }

                            echo "</p>";
                            echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-md-4">
                        <div class="col-sm-2"><i class="fa fa-3x fa-building-o padding010 text-muted"></i></div>
                        <div class="col-sm-10">
                            <h2 class=""><?= $Settings->site_name; ?></h2>
                            <?= $warehouse->name ?>

                            <?php
                            echo $warehouse->address . "<br>";
                            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                            ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                    <div class="col-md-4">

                        <div class="col-sm-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>
                        <div class="col-sm-10">
                            <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>
                            <p style="font-weight:bold;"><?= lang("date"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->date)); ?></p>
                            <p style="font-weight:bold;"><?= lang("status"); ?>: <?= $inv->status; ?></p>
                            <p>&nbsp;</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                        
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">

                                    <thead> 

                                        <tr> 
                                            <th><?= lang("no"); ?></th> 
                                            <th><?= lang("description"); ?> (<?= lang("code"); ?>)</th> 
                                            <th><?= lang("quantity"); ?></th>
                                            <th style="padding-right:20px;"><?= lang("unit_price"); ?></th> 
                                            <!--<?php
                                            if ($Settings->tax1) {
                                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("tax") . '</th>';
                                            }
                                            ?>-->
                                            <?php
                                            if ($Settings->product_discount) {
                                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("discount") . '</th>';
                                            }
                                            ?>
                                            <th style="padding-right:20px;"><?= lang("subtotal"); ?></th> 
                                        </tr> 

                                    </thead> 

                                    <tbody> 

<?php $r = 1;
foreach ($rows as $row):
    ?>
                                            <tr>
                                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                                <td style="vertical-align:middle;"><?= $row->product_name . " (" . $row->product_code . ")"; ?></td>
                                                <td style="width: 120px; text-align:center; vertical-align:middle;"><?= $row->quantity; ?></td>
                                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney($row->net_unit_price); ?></td>
                                                <!--<?php
                                                if ($Settings->tax1) {
                                                    echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $row->tax . ')</small> ' . $row->item_tax . '</td>';
                                                }
                                                ?>-->
                                                <?php
                                                if ($Settings->product_discount) {
                                                    echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $row->discount . ')</small> ' . $row->item_discount . '</td>';
                                                }
                                                ?>
                                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney($row->subtotal); ?></td> 
                                            </tr> 
                                            <?php
                                            $r++;
                                        endforeach;
                                        ?>
                                            </tbody>
                                            <tfoot>
                                        <?php
                                        $col = 4;
                                        if ($Settings->product_discount) {
                                            $col += 1;
                                        }
                                        ?>


                                            <tr><td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px;"><?= lang("total"); ?> (<?= $default_currency; ?>)</td><td style="text-align:right; padding-right:10px;"><?= $this->sma->formatMoney($inv->total); ?></td></tr>
                                            <?php if($inv->product_discount != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_discount") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_discount) . '</td></tr>'; } ?>
    <?php if($Settings->tax1 && $inv->product_tax != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_tax") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_tax) . '</td></tr>'; } ?>
                                            <?php if($inv->order_discount != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->order_discount) . '</td></tr>'; } ?>
                                            <?php if($Settings->tax2 && $inv->order_tax != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_tax") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->order_tax) . '</td></tr>'; } ?>
                                            <?php if($inv->shipping != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>'; } ?>
                                        <tr><td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("total_amount"); ?> (<?= $default_currency; ?>)</td><td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->sma->formatMoney($inv->grand_total); ?></td></tr>

                                    </tfoot> 
                                </table> 
                            
                        </div>


                        <div class="row">
                        <div class="col-md-7">    
<?php if ($inv->note || $inv->note != "") { ?>
                                <div class="well well-sm">
                                    <p class="bold"><?= lang("note"); ?>:</p>
                                    <div><?= $this->sma->decode_html($inv->note); ?></div>
                                </div>
<?php } ?>
                        </div>



                        <div class="col-md-4 col-md-offset-1"> 
                            <div class="well well-sm">
                                <p><?= lang("created_by"); ?>: <?= $user->first_name.' '.$user->last_name; ?> </p>
                                <p><?= lang("date"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->date)); ?></p>
                                <?php if($inv->updated_by) { ?>
                                <p><?= lang("updated_by"); ?>: <?php $inv->updated_by; ?></p>
                                <p><?= lang("update_at"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->updated_at)); ?></p>
                                <?php } ?>
                            </div>
                            
                        </div>
                    </div>
                        

                </div>
            </div>                        
            <?php if (!$Supplier || !$Customer) { ?>       
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                <div class="btn-group"><a href="<?=site_url('quotes/edit/'.$inv->id)?>" class="tip btn btn-warning tip" title="<?=lang('edit')?>"><i class="fa fa-edit"></i> <?=lang('edit')?></a></div>
                <div class="btn-group"><a href="<?=site_url('quotes/email/'.$inv->id)?>" data-toggle="modal" data-target="#myModal" class="tip btn btn-info tip" title="<?=lang('email')?>"><i class="fa fa-envelope-o"></i> <?=lang('email')?></a></div>
                <div class="btn-group"><a href="<?=site_url('quotes/pdf/'.$inv->id)?>" class="tip btn btn-primary" title="<?=lang('download_pdf')?>"><i class="fa fa-download"></i> <?=lang('pdf')?></a></div>
                <!--<div class="btn-group"><a href="<?=site_url('quotes/excel/'.$inv->id)?>" class="tip btn btn-primary"  title="<?=lang('download_excel')?>"><i class="fa fa-download"></i> <?=lang('excel')?></a></div>-->
                </div>
            </div>
<?php } ?>
        </div>
    </div>

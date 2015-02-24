<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("purchase_no") . '. ' . $inv->id; ?></h2>
        <div class="box-icon">
           <ul class="btn-tasks">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                    <li><a href="<?= site_url('purchases/payments/'.$inv->id) ?>" data-target="#myModal" data-toggle="modal"><i class="fa fa-money"></i> <?= lang('view_payments') ?></a></li>
                    <li><a href="<?= site_url('purchases/add_payment/'.$inv->id) ?>" data-target="#myModal" data-toggle="modal"><i class="fa fa-money"></i> <?= lang('add_payment') ?></a></li>
                    <li><a href="<?= site_url('purchases/edit/'.$inv->id) ?>"><i class="fa fa-edit"></i> <?= lang('edit_purchase') ?></a></li>
                    <li><a href="<?= site_url('purchases/email/'.$inv->id) ?>"><i class="fa fa-envelope-o"></i> <?= lang('send_email') ?></a></li>
                    <li><a href="<?= site_url('purchases/pdf/'.$inv->id) ?>"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <!--<li><a href="<?= site_url('purchases/excel/'.$inv->id) ?>"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?= site_url('purchases/add_payment/'.$inv->id) ?>"><i class="fa fa-money"></i> <?= lang('add_payment') ?></a></li>-->
                    </ul>
                </li>
            </ul>
        </div>
    </div> 
    <div class="box-content"> 
        <div class="row">            
            <div class="col-lg-12">

                <div class="well well-sm">
                    <div class="col-md-4 border-right">

                        <div class="col-sm-2"><i class="fa fa-3x fa-hand-o-right padding010 text-muted"></i></div>
                        <div class="col-sm-10">
                            <h2 class=""><?= $supplier->company ? $supplier->company : $supplier->name; ?></h2>
                            <?= $supplier->company ? "" : "Attn: " . $supplier->name ?>

                            <?php
                            echo $supplier->address . "<br />" . $supplier->city . " " . $supplier->postal_code . " " . $supplier->state . "<br />" . $supplier->country;

                            echo "<p>";

                            if ($supplier->cf1 != "-" && $supplier->cf1 != "") {
                                echo "<br>" . lang("scf1") . ": " . $supplier->cf1;
                            }
                            if ($supplier->cf2 != "-" && $supplier->cf2 != "") {
                                echo "<br>" . lang("scf2") . ": " . $supplier->cf2;
                            }
                            if ($supplier->cf3 != "-" && $supplier->cf3 != "") {
                                echo "<br>" . lang("scf3") . ": " . $supplier->cf3;
                            }
                            if ($supplier->cf4 != "-" && $supplier->cf4 != "") {
                                echo "<br>" . lang("scf4") . ": " . $supplier->cf4;
                            }
                            if ($supplier->cf5 != "-" && $supplier->cf5 != "") {
                                echo "<br>" . lang("scf5") . ": " . $supplier->cf5;
                            }
                            if ($supplier->cf6 != "-" && $supplier->cf6 != "") {
                                echo "<br>" . lang("scf6") . ": " . $supplier->cf6;
                            }

                            echo "</p>";
                            echo lang("tel") . ": " . $supplier->phone . "<br />" . lang("email") . ": " . $supplier->email;
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-md-4">

                        <div class="col-sm-2"><i class="fa fa-3x fa-truck padding010 text-muted"></i></div>
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
                    <div class="col-md-4 border-left">

                        <div class="col-sm-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>
                        <div class="col-sm-10">
                            <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>
                            <p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?></p>
                            <p style="font-weight:bold;"><?= lang("status"); ?>: <?= $inv->status; ?></p>
                        </div>
                        <div class="col-sm-12">
                            <?php $br = $this->sma->save_barcode($inv->reference_no, 'code39', 35, false); ?>
                            <img src="<?=base_url()?>assets/uploads/barcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$inv->reference_no?>" />
                            <?php $this->sma->qrcode('link', urlencode(site_url('sales/view/'.$inv->id)), 1); ?>
                            <img src="<?=base_url()?>assets/uploads/qrcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$inv->reference_no?>" />
                        </div>
                        <div class="clearfix"></div>


                    </div>
                    <div class="clearfix"></div>
                </div>


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
                                            <!--<?php
                                            if ($row->item_discount != 0) {
                                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("discount") . '</th>';
                                            }
                                            ?>-->
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
                                            <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney($row->net_unit_cost); ?></td>
                                                <!--<?php
                                                if ($Settings->tax1) {
                                                    echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $row->tax . ')</small> ' . $row->item_tax . '</td>';
                                                }
                                                ?>-->
                                                <!--<?php
                                                if ($row->item_discount != 0) {
                                                    echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $row->discount . ')</small> ' . $row->item_discount . '</td>';
                                                }
                                                ?>-->
                                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney($row->subtotal); ?></td> 
                                            </tr> 
                                            <?php
                                            $r++;
                                            endforeach;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <?php
                                            $col = 3;
                                            if ($Settings->tax1) {
                                                $col += 1;
                                            }
                                            ?>


                                            <tr>
                                                <td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px;">
                                                    <?= lang("total"); ?> (<?= $default_currency->code; ?>)</td><td style="text-align:right; padding-right:10px;"><?= $this->sma->formatMoney($inv->total); ?>
                                                </td>
                                            </tr>
                                            <?php if($inv->product_discount != 0) { 
                                                echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_discount) . '</td></tr>'; 
                                            } ?>
                                            <?php if($Settings->tax1 && $inv->product_tax != 0) {
                                               echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_tax) . '</td></tr>'; 
                                           } ?>
                                           <?php if($inv->order_discount != 0) { 
                                            echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->order_discount) . '</td></tr>'; 
                                        } ?>
                                        <?php if($Settings->tax2 && $inv->order_tax != 0) {
                                           echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->order_tax) . '</td></tr>';
                                       } ?>
                                       <?php if($inv->shipping != 0) {
                                           echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>'; 
                                       } ?>
                                       <tr>
                                        <td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px; font-weight:bold;">
                                            <?= lang("total_amount"); ?> (<?= $default_currency->code; ?>)
                                        </td>
                                        <td style="text-align:right; padding-right:10px; font-weight:bold;">
                                            <?= $this->sma->formatMoney($inv->grand_total); ?>
                                        </td>
                                    </tr>

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
                                    <p><?= lang("order_by"); ?>: <?= $inv->created_by; ?> </p>
                                    <p><?= lang("date"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->date)); ?></p>
                                    <p><?= lang("updated_by"); ?>: <?php $inv->updated_by; ?></p>
                                    <p><?= lang("update_at"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->updated_at)); ?></p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th><?=lang('date')?></th>
                                        <th><?=lang('payment_reference')?></th>
                                        <th><?=lang('paid_by')?></th>
                                        <th><?=lang('amount')?></th>
                                        <th><?=lang('created_by')?></th>
                                        <th><?=lang('type')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments as $payment) { ?>
                                    <tr>
                                        <td><?=$this->sma->hrld($payment->date)?></td>
                                        <td><?=$payment->reference_no;?></td>
                                        <td><?=$payment->paid_by;?></td>
                                        <td><?=$payment->amount;?></td>
                                        <td><?=$payment->first_name.' '.$payment->last_name;?></td>
                                        <td><?=$payment->type;?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if (!$Supplier || !$Customer) { ?>       
                <div class="buttons">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group"><a href="<?=site_url('purchases/add_payment/'.$inv->id)?>" class="tip btn btn-primary tip" title="<?=lang('add_payment')?>" data-target="#myModal" data-toggle="modal"><i class="fa fa-money"></i> <?=lang('add_payment')?></a></div>
                        <div class="btn-group"><a href="<?=site_url('purchases/edit/'.$inv->id)?>" class="tip btn btn-warning tip" title="<?=lang('edit')?>"><i class="fa fa-edit"></i> <?=lang('edit')?></a></div>
                        <div class="btn-group"><a href="<?=site_url('purchases/email/'.$inv->id)?>" data-toggle="modal" data-target="#myModal" class="tip btn btn-info tip" title="<?=lang('email')?>"><i class="fa fa-envelope-o"></i> <?=lang('email')?></a></div>
                        <div class="btn-group"><a href="<?=site_url('purchases/pdf/'.$inv->id)?>" class="tip btn btn-primary" title="<?=lang('download_pdf')?>"><i class="fa fa-download"></i> <?=lang('pdf')?></a></div>
                        <!--<div class="btn-group"><a href="<?=site_url('purchases/excel/'.$inv->id)?>" class="tip btn btn-primary"  title="<?=lang('download_excel')?>"><i class="fa fa-download"></i> <?=lang('excel')?></a></div>-->
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

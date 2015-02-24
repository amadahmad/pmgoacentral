<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $this->lang->line("purchase") . " " . $inv->reference_no; ?></title>
        <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">
        <style type="text/css">
            html, body { height: 100%; background: #FFF; }
            body:before, body:after { 
                display: none !important; 
            }
            .table th { text-align:center; padding: 5px; }
            .table td { padding: 4px; }
        </style>
    </head>

    <body>
        <div id="wrap">
            <div class="row">            
                <div class="col-lg-12">
                    <?php if($logo) { ?>
                    <div class="text-center" style="margin-bottom:20px;">
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>" alt="<?= $Settings->site_name; ?>">
                    </div>
                    <?php } ?>
                    <div class="well well-sm">
                        <div class="row bold">
                    <div class="col-xs-4"><?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?>
                        <br><?= lang("ref"); ?>: <?= $inv->reference_no; ?></div>
                    <div class="col-xs-6 pull-right text-right">
                        <?php $br = $this->sma->save_barcode($inv->reference_no, 'code39', 35, false); ?>
                    <img src="<?=base_url()?>assets/uploads/barcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$inv->reference_no?>" />
                    <?php $this->sma->qrcode('link', urlencode(site_url('purchases/view/'.$inv->id)), 1); ?>
                    <img src="<?=base_url()?>assets/uploads/qrcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$inv->reference_no?>" />
                    </div>
                    <div class="clearfix"></div>
                </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row padding10">
                        <div class="col-xs-5">
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
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-xs-5">
                            <h2 class=""><?= $Settings->site_name; ?></h2>
                            <?= $warehouse->name ?>

                            <?php
                            echo $warehouse->address . "<br>";
                            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                            ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <p>&nbsp;</p>
                    
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead> 
                                <tr class="active"> 
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
                                <?php
                                $r = 1;
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
                                <?php $col = 3; if ($Settings->tax1) { $col += 1; }
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
                        <div class="col-xs-7 pull-left">    
                            <?php if ($inv->note || $inv->note != "") { ?>
                                <div class="well well-sm">
                                    <p class="bold"><?= lang("note"); ?>:</p>
                                    <div><?= $this->sma->decode_html($inv->note); ?></div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-xs-4 col-xs-offset-1 pull-right"> 
                            <p><?= lang("order_by"); ?>: <?= $inv->created_by; ?> </p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <hr>
                            <p><?= lang("stamp_sign"); ?></p>
                        </div>
                    </div>
                    
                </div>
            </div> 
        </div>
    </body>
</html>
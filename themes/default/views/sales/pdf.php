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
                    <!--<img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>" alt="<?= $Settings->site_name; ?>">-->
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?=$biller->company?>">
                </div>
                <?php } ?>
                <div class="clearfix"></div>
                <div class="row padding10">
                    <div class="col-xs-5">
                        <h2 class=""><?= $biller->company ? $biller->company : $biller->name; ?></h2>
                        <?= $biller->company ? "" : "Attn: " . $biller->name ?>
                        <?php
                        echo $biller->address . "<br />" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br />" . $biller->country;
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
                        echo lang("tel") . ": " . $biller->phone . "<br />" . lang("email") . ": " . $biller->email;
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-5">
                        <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                        <?= $customer->company ? "" : "Attn: " . $customer->name ?>
                        <?php
                        echo $customer->address . "<br />" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br />" . $customer->country;
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
                        echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                        ?>

                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="row padding10">
                    <div class="col-xs-5">
                        <span class="bold"><?= $Settings->site_name; ?></span><br>
                        <?= $warehouse->name ?>

                        <?php
                        echo $warehouse->address . "<br>";
                        echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-5">
                        <div class="bold">
                            <?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?><br>
                            <?= lang("ref"); ?>: <?= $inv->reference_no; ?>
                            <div class="clearfix"></div>
                            <?php $this->sma->qrcode('link', urlencode(site_url('sales/view/'.$inv->id)), 1); ?>
                            <img src="<?=base_url()?>assets/uploads/qrcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$inv->reference_no?>" class="pull-right" />
                            <?php $br = $this->sma->save_barcode($inv->reference_no, 'code39', 50, false); ?>
                            <img src="<?=base_url()?>assets/uploads/barcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$inv->reference_no?>" class="pull-left" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
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
                                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney($row->net_unit_price + ($row->item_tax/$row->quantity)); ?></td>
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
                                                <?php 
                                                $col = 5;
                                                if ($Settings->product_discount) {
                                                    $col += 1;
                                                }

                                                if($return_sale && $return_items) { $r = 1; echo '<tr class="warning"><td colspan="'.$col.'"><strong>'.lang('returned_items').'</strong></td></tr>';
                                                foreach ($return_items as $ritem) { ?>
                                                <tr class="warning">
                                                    <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                                    <td style="vertical-align:middle;"><?= $ritem->product_name . " (" . $ritem->product_code . ")"; ?></td>
                                                    <td style="width: 120px; text-align:center; vertical-align:middle;"><?= -$ritem->quantity; ?></td>
                                                    <td style="text-align:right; width:120px; padding-right:10px;"><?= -$this->sma->formatMoney($ritem->net_unit_price + ($ritem->item_tax/$ritem->quantity)); ?></td>
                                                    <!--<?php
                                                    if ($Settings->tax1) {
                                                        echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $ritem->tax . ')</small> ' . $ritem->item_tax . '</td>';
                                                    }
                                                    ?>-->
                                                    <?php
                                                    if ($Settings->product_discount) {
                                                        echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $ritem->discount . ')</small> ' . $ritem->item_discount . '</td>';
                                                    }
                                                    ?>
                                                    <td style="text-align:right; width:120px; padding-right:10px;"><?= -$this->sma->formatMoney($ritem->subtotal); ?></td> 
                                                </tr> 
                                                <?php    }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <?php
                                            $col = 4;
                                            if ($Settings->product_discount) {
                                                $col += 1;
                                            }
                                            $product_discount = ($return_sale && $return_sale->product_discount != 0) ? ($inv->product_discount-$return_sale->product_discount) : $inv->product_discount;
                                            $order_discount = ($return_sale && $return_sale->order_discount != 0) ? ($inv->order_discount-$return_sale->order_discount) : $inv->order_discount;
                                            $order_tax = ($return_sale && $return_sale->order_tax != 0) ? ($inv->order_tax-$return_sale->order_tax) : $inv->order_tax;
                                            $grand_total = ($return_sale && $return_sale->grand_total != 0) ? ($inv->grand_total-$return_sale->grand_total) : $inv->grand_total;
                                            ?>

                                            <tr><td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px;"><?= $return_sale ? lang("total_before_return") : lang("total"); ?> (<?= $default_currency->code; ?>)</td><td style="text-align:right; padding-right:10px;"><?= $this->sma->formatMoney($inv->total+$inv->product_tax); ?></td></tr>
                                            <?php if($return_sale && $return_sale->surcharge != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($return_sale->surcharge) . '</td></tr>'; } 
                                            ?>
                                            <?php if($return_sale && $return_sale->grand_total != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_amount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($return_sale->grand_total) . '</td></tr>'; } 
                                            ?>
                                            <?php if($product_discount != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($product_discount) . '</td></tr>'; } 
                                            ?>
                                            <!--<?php if($Settings->tax1 && $inv->product_tax != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_tax) . '</td></tr>'; } ?>-->
                                            <?php if($order_discount != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($order_discount) . '</td></tr>'; } 
                                            ?>
                                            <?php if($Settings->tax2 && $order_tax != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($order_tax) . '</td></tr>'; } 
                                            ?>
                                            <?php if($inv->shipping != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>'; } 
                                            ?>
                                            <tr><td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("total_amount"); ?> (<?= $default_currency->code; ?>)</td><td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->sma->formatMoney($grand_total); ?></td></tr>

                                        </tfoot> 
                                    </table> 
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">    
                                        <?php if ($inv->note || $inv->note != "") { ?>
                                        <div class="well well-sm">
                                            <p class="bold"><?= lang("note"); ?>:</p>
                                            <div><?= $this->sma->decode_html($inv->note); ?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-4  pull-left"> 
                                        <p style="height: 80px;"><?= lang("seller"); ?>: <?= $biller->company ? $biller->company : $biller->name; ?> </p>
                                        <hr>
                                        <p><?= lang("stamp_sign"); ?></p>
                                    </div>
                                    <div class="col-xs-4  pull-right"> 
                                        <p style="height: 80px;"><?= lang("customer"); ?>: <?= $customer->company ? $customer->company : $customer->name; ?> </p>
                                        <hr>
                                        <p><?= lang("stamp_sign"); ?></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </body>
                </html>
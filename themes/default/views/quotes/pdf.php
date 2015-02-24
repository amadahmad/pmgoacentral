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
                                echo "<br>" . lang("scf6") . ": " . $customer->cf6;
                            }
                            echo "</p>";
                            echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                            ?>
                            
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
                    <div class="row padding10">
                    <div class="col-xs-5">
                            <h2 class=""><?= $Settings->site_name; ?></h2>
                            <?= $warehouse->name ?>

                            <?php
                            echo $warehouse->address . "<br>";
                            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                            ?>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-xs-5">
                                <div class="row bold">
                                <?= lang("date"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->date)); ?><br>
                                <?= lang("ref"); ?>: <?= $inv->reference_no; ?>
                                </div>
                            <div class="clearfix"></div>
                        </div>
                        </div>

  
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
                                        <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney((($row->net_unit_price*$row->quantity)+$row->item_tax)/$row->quantity); ?></td>
                                        <!--<td style="text-align:right; width:120px; padding-right:10px;"><?= $this->sma->formatMoney($row->net_unit_price); ?></td>-->
                                        <!--<?php
                                        if($Settings->tax1) {
                                            echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><small>(' . $row->tax . ')</small> ' . $row->item_tax . '</td>';
                                        }
                                        ?>-->
                                        <!--<?php
                                        if($Settings->product_discount) {
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
                                <?php $col = 4; //f($Settings->tax1) { $col += 1; } if($Settings->product_discount) { $col += 1; }
                                ?>

                                <!--<tr><td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px;"><?= lang("total"); ?> (<?= $default_currency; ?>)</td><td style="text-align:right; padding-right:10px;"><?= $this->sma->formatMoney($inv->total); ?></td></tr>
                                            <?php if($inv->product_discount != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_discount") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_discount) . '</td></tr>'; } ?>
    <?php if($Settings->tax1 && $inv->product_tax != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("product_tax") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->product_tax) . '</td></tr>'; } ?>-->
                                            <?php if($inv->order_discount != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->order_discount) . '</td></tr>'; } ?>
                                            <?php if($Settings->tax2 && $inv->order_tax != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_tax") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->order_tax) . '</td></tr>'; } ?>
                                            <?php if($inv->shipping != 0) { echo  '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>'; } ?>
                                        <tr><td colspan="<?= $col; ?>" style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("total_amount"); ?> (<?= $default_currency; ?>)</td><td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->sma->formatMoney($inv->grand_total); ?></td></tr>

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
                            <p><?= lang("seller"); ?>: <?= $biller->company ? $biller->company : $biller->name; ?> </p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <hr>
                            <p><?= lang("stam_sign"); ?></p>
                        </div>
                        <div class="col-xs-4  pull-right"> 
                            <p><?= lang("customer"); ?>: <?= $customer->company ? $customer->company : $customer->name; ?> </p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <hr>
                            <p><?= lang("stam_sign"); ?></p>
                        </div>
                    </div>
                    
                </div>
            </div> 
        </div>
    </body>
</html>
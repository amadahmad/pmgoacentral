<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>" alt="<?= $Settings->site_name; ?>">
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-4"><?= lang("date"); ?>: <?= $this->sma->hrld($transfer->date); ?>
                        <br><?= lang("ref"); ?>: <?= $transfer->transfer_no; ?></div>
                    <div class="col-xs-6 pull-right text-right">
                        <?php $br = $this->sma->save_barcode($transfer->transfer_no, 'code39', 35, false); ?>
                    <img src="<?=base_url()?>assets/uploads/barcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$transfer->transfer_no?>" />
                    <?php $this->sma->qrcode('link', urlencode(site_url('transfers/view/'.$transfer->id)), 1); ?>
                    <img src="<?=base_url()?>assets/uploads/qrcode<?=$this->session->userdata('user_id')?>.png" alt="<?=$transfer->transfer_no?>" />
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
                     
            <div class="row">
                <div class="col-sm-6">

                    <?php echo $this->lang->line("from"); ?>:
                    <h3><?php echo $from_warehouse->name . " ( " . $from_warehouse->code . " )"; ?></h3>
                    <?php echo "<p>" . $from_warehouse->address . "</p><p>" . $from_warehouse->phone . "<br>" . $from_warehouse->email . "</p>";
                    ?>
                </div>
                <div class="col-sm-6">

                    <?php echo $this->lang->line("to"); ?>:<br />
                    <h3><?php echo $to_warehouse->name . " ( " . $to_warehouse->code . " )"; ?></h3>
                    <?php echo "<p>" . $to_warehouse->address . "</p><p>" . $to_warehouse->phone . "<br>" . $to_warehouse->email . "</p>";
                    ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead> 
                        <tr> 
                            <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("no"); ?></th> 
                            <th style="vertical-align:middle;"><?php echo $this->lang->line("description"); ?></th> 
                            <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("quantity"); ?></th>
                            <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("unit_price"); ?></th> 
                            <?php if ($this->Settings->tax1) {
                                echo '<th style="text-align:center; vertical-align:middle;">' . $this->lang->line("tax") . '</th>';
                            } ?>
                            <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("subtotal"); ?></th> 
                        </tr> 
                    </thead> 
                    
                    <tbody> 
                        <?php $r = 1;
                        foreach ($rows as $row): ?>
                            <tr>
                                <td style="text-align:center; width:25px;"><?php echo $r; ?></td>
                                <td style="text-align:left;"><?php echo $row->product_name; ?>
                                    (<?php echo $row->product_code; ?>)</td>
                                <td style="text-align:center; width:80px; "><?php echo $row->quantity; ?></td>
                                <td style="width: 100px; text-align:right; padding-right:10px; vertical-align:middle;"><?php echo $this->sma->formatMoney($row->net_unit_cost); ?></td>
                                <?php if ($this->Settings->tax1) {
                                echo '<td style="width: 80px; text-align:right; vertical-align:middle;"><!--<small>(' . $row->tax . ')</small>--> ' . $this->sma->formatMoney($row->item_tax) . '</td>';
                            } ?>
                                <td style="width: 100px; text-align:right; padding-right:10px; vertical-align:middle;"><?php echo $this->sma->formatMoney($row->subtotal); ?></td>
                            </tr> 
                            <?php $r++;
                        endforeach; ?>
                    </tbody>
                    <tfoot>
                        <?php $col = 4;
                        if ($this->Settings->tax1) {
                            $col += 1;
                        } ?>

                        <?php if ($this->Settings->tax1) { ?>
                            <tr><td colspan="<?php echo $col; ?>" style="text-align:right; padding-right:10px;"><?php echo $this->lang->line("total"); ?> (<?php echo $default_currency; ?>)</td><td style="text-align:right; padding-right:10px;"><?php echo $this->sma->formatMoney($transfer->total); ?></td></tr>
                            <?php echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . $this->lang->line("product_tax") . ' (' . $default_currency . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($transfer->total_tax) . '</td></tr>';
} ?>
                        <tr><td colspan="<?php echo $col; ?>" style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo $this->lang->line("total_amount"); ?> (<?php echo $default_currency; ?>)</td><td style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo $this->sma->formatMoney($transfer->grand_total); ?></td></tr>
                    </tfoot> 
                </table> 
            </div>

            <div class="row">
                <div class="col-xs-12">    
                    <?php if ($transfer->note || $transfer->note != "") { ?>
                        <div class="well well-sm">
                            <p class="bold"><?= lang("note"); ?>:</p>
                            <div><?= $this->sma->decode_html($transfer->note); ?></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-4 pull-left">    
                    <p><?= lang("created_by"); ?>: <?= $transfer->created_by; ?> </p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <hr>
                    <p><?= lang("stam_sign"); ?></p>
                </div>
                <div class="col-xs-4 col-xs-offset-1 pull-right"> 
                    <p><?= lang("received_by"); ?>:  </p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <hr>
                    <p><?= lang("stam_sign"); ?></p>
                </div>
            </div>

        </div>
    </div>
</div>

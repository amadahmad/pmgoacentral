<script>$(document).ready(function(){ CURI = '<?= site_url('reports/profit_loss'); ?>'; });</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('profit_loss'); ?></h2>
        <div class="box-icon"> 
            <div class="form-group choose-date hidden-xs">
                <div class="controls">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" value="<?= ($start ? $this->sma->hrld($start) : '').' - '.($end ? $this->sma->hrld($end) : '');?>" id="daterange" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">
                <p class="introtext"><?= lang('view_pl_report'); ?></p>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6"><div class="small-box padding1010 borange">
                            <h4 class="bold"><?=lang('purchases')?></h4>
                            <i class="fa fa-star"></i>
                            <h3 class="bold"><?=$total_purchases->total_amount?></h3>
                            <p><?=$total_purchases->total.' '.lang('purchases')?> & <?=$total_purchases->paid.' '.lang('paid')?> & <?=$total_purchases->tax.' '.lang('tax')?></p>
                        </div></div>
                        <div class="col-sm-6"><div class="small-box padding1010 bdarkGreen">
                            <h4 class="bold"><?=lang('sales')?></h4>
                            <i class="fa fa-heart"></i>
                            <h3 class="bold"><?=$total_sales->total_amount?></h3>
                            <p><?=$total_sales->total.' '.lang('purchases')?> & <?=$total_sales->paid.' '.lang('paid')?> & <?=$total_sales->tax.' '.lang('tax')?> </p>
                        </div></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-8"><div class="small-box padding1010 bmGreen">
                            <h4 class="bold"><?=lang('payments_received')?></h4>
                            <i class="fa fa-usd"></i>
                            <h3 class="bold"><?=$total_sales->total_amount?></h3>
                            <p class="bold"><?=$total_received_cash->total_amount.' '.lang('cash')?>, <?=$total_received_cc->total_amount.' '.lang('CC')?>, <?=$total_received_cheque->total_amount.' '.lang('cheque')?>, <?=$total_received_ppp->total_amount.' '.lang('paypal_pro')?>, <?=$total_received_stripe->total_amount.' '.lang('stripe')?> </p>
                        </div></div>
                        <div class="col-sm-4"><div class="small-box padding1010 bpurple">
                            <h4 class="bold"><?=lang('payments')?></h4>
                            <i class="fa fa-usd"></i>
                            <h3 class="bold"><?=$total_received->total_amount-$total_paid->total_amount-$total_returned->total_amount?></h3>
                            <p><?=$total_received->total_amount.' '.lang('received')?> - <?=$total_paid->total_amount.' '.lang('paid')?>  - <?=$total_returned->total_amount.' '.lang('returned')?></p>
                        </div></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-4"><div class="small-box padding1010 bred">
                            <h4 class="bold"><?=lang('profit_loss')?></h4>
                            <i class="fa fa-money"></i>
                            <h3 class="bold"><?=$total_sales->total_amount-$total_purchases->total_amount?></h3>
                            <p><?=$total_sales->total_amount.' '.lang('sales')?> - <?=$total_purchases->total_amount.' '.lang('purchases')?></p>
                        </div></div>
                        <div class="col-sm-4"><div class="small-box padding1010 bpink">
                            <h4 class="bold"><?=lang('profit_loss')?></h4>
                            <i class="fa fa-money"></i>
                            <h3 class="bold"><?=$total_sales->total_amount-$total_purchases->total_amount-$total_sales->tax?></h3>
                            <p><?=$total_sales->total_amount.' '.lang('sales')?> - <?=$total_sales->tax.' '.lang('tax')?> - <?=$total_purchases->total_amount.' '.lang('purchases')?> </p>
                        </div></div>
                        <div class="col-sm-4"><div class="small-box padding1010 bblue">
                            <h4 class="bold"><?=lang('profit_loss')?></h4>
                            <i class="fa fa-money"></i>
                            <h3 class="bold"><?=($total_sales->total_amount-$total_sales->tax)-($total_purchases->total_amount-$total_purchases->tax)?></h3>
                            <p>(<?=$total_sales->total_amount.' '.lang('sales')?> - <?=$total_sales->tax.' '.lang('tax')?>) - (<?=$total_purchases->total_amount.' '.lang('purchases')?> - <?=$total_purchases->tax.' '.lang('tax')?>)</p>
                        </div></div>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>

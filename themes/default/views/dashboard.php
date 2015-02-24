    <div class="row">	
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2 class="blue"><i class="fa fa-th"></i><span class="break"></span><?= lang('quick_links') ?></h2>
                </div>
                <div class="box-content">
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bblue white quick-button small" href="<?= site_url('products') ?>">
                            <i class="fa fa-barcode"></i>
                            <p><?=lang('products')?></p>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bdarkGreen white quick-button small" href="<?= site_url('sales') ?>">
                            <i class="fa fa-heart"></i>
                            <p><?=lang('sales')?></p>
                        </a>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="blightOrange white quick-button small" href="<?= site_url('quotes') ?>">
                            <i class="fa fa-heart-o"></i>
                            <p><?=lang('quotes')?></p>
                        </a>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bred white quick-button small" href="<?= site_url('purchases') ?>">
                            <i class="fa fa-star"></i>
                            <p><?=lang('purchases')?></p>
                        </a>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bpink white quick-button small" href="<?= site_url('transfers') ?>">
                            <i class="fa fa-star-o"></i>
                            <p><?=lang('transfers')?></p>
                        </a>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bgrey white quick-button small" href="<?= site_url('customers') ?>">
                            <i class="fa fa-users"></i>
                            <p><?=lang('customers')?></p>
                        </a>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bgrey white quick-button small" href="<?= site_url('suppliers') ?>">
                            <i class="fa fa-users"></i>
                            <p><?=lang('suppliers')?></p>
                        </a>
                    </div>
                    <?php if($Owner || $Admin) { ?>
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="blightBlue white quick-button small" href="<?= site_url('notifications') ?>">
                            <i class="fa fa-comments"></i>
                            <p><?=lang('notifications')?></p>
                            <!--<span class="notification green">4</span>-->
                        </a>
                    </div>
                    <?php } if($Owner) { ?>
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bblue white quick-button small" href="<?= site_url('auth/users') ?>">
                            <i class="fa fa-group"></i>
                            <p><?=lang('users')?></p>
                            <!--<span class="notification blue">7</span>-->
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bblue white quick-button small"  href="<?= site_url('system_settings') ?>">
                            <i class="fa fa-cogs"></i>
                            <p><?=lang('settings')?></p>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>	
            </div>	
        </div>

    </div>

<?php if($this->Customer) { ?>
<div class="row">
                        <div class="col-md-4">
                            <!-- small box -->
                            <div class="small-box bblue white">
                                <div class="inner">
                                    <div class="col-md-4">
                                    <h3><?='0.00'?></h3>
                                    <p>Invoice <br>Amount</p>
                                    </div>
                                    <div class="col-md-4">
                                    <h3><?='0.00'?></h3>
                                    <p>Total <br>Paid</p>
                                    </div>
                                    <div class="col-md-4">
                                    <h3><?='0.00'?></h3>
                                    <p>Rest <br>Amount</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-md-8">
                            <div class="row">
                            <div class="col-md-2">
                            <div class="small-box blightOrange">
                                <div class="inner clearfix">
                                    <a href="<?=site_url('orders/drafts')?>">
                                    <h3><?='0.00'?></h3>
                                    <p>Drafts<br>Orders</p>
                                    </a>
                                </div>
                            </div>
                        </div> 
                                <div class="col-md-2">
                            <div class="small-box blightBlue">
                                <div class="inner clearfix">
                                    <a href="<?=site_url('orders/opened_orders')?>">
                                    <h3><?='0.00'?></h3>
                                    <p>Opened<br>Orders</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                                <div class="col-md-2">
                            <div class="small-box bred">
                                <div class="inner clearfix">
                                    <a href="<?=site_url('orders/cancelled_orders')?>">
                                    <h3><?='0.00'?></h3>
                                    <p>Cancelled<br>Orders</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                                <div class="col-md-2">
                            <div class="small-box bdarkGreen">
                                <div class="inner clearfix">
                                    <a href="<?=site_url('orders/completed_orders')?>">
                                    <h3><?='0.00'?></h3>
                                    <p>Completed<br>Orders</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                                <div class="col-md-2">
                            <div class="small-box bgrey">
                                <div class="inner clearfix">
                                    <a href="<?=site_url('orders/back_orders')?>">
                                    <h3><?='0.00'?></h3>
                                    <p>Back <br>Orders</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                                <div class="col-md-2">
                            <div class="small-box borange">
                                <div class="inner clearfix">
                                    <a href="<?=site_url('quotes')?>">
                                    <h3><?='0.00'?></h3>
                                    <p>Quotations<br>&nbsp;</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>    
                    </div>
</div>
<?php } ?>

<div class="row padding10">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-tasks"></i> <?= lang('latest_five') ?></h2>
            </div>
            <div class="box-content">    
                <div class="row">            
                    <div class="col-md-12">

                        <ul id="dbTab" class="nav nav-tabs">
                            <li class=""><a href="#sales"><?= lang('sales') ?></a></li>
                            <li class=""><a href="#quotes"><?= lang('quotes') ?></a></li>
                            <li class=""><a href="#purchases"><?= lang('purchases') ?></a></li>
                            <li class=""><a href="#transfers"><?= lang('transfers') ?></a></li>
                            <li class=""><a href="#customers"><?= lang('customers') ?></a></li>
                            <li class=""><a href="#suppliers"><?= lang('suppliers') ?></a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="sales" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="sales-tbl" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("date"); ?></th>
                                                        <th><?= $this->lang->line("reference_no"); ?></th>
                                                        <th><?= $this->lang->line("customer"); ?></th>
                                                        <th><?= $this->lang->line("total"); ?></th>
                                                        <th><?= $this->lang->line("payment_status"); ?></th>
                                                        <th><?= $this->lang->line("paid"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($sales)) { 
                                                        $r = 1;
                                                        foreach($sales as $order) {
                                                            echo '<tr id="'.$order->id.'" class="invoice_link"><td>'.$r.'</td>
                                                                <td>'.$this->sma->hrld($order->date).'</td>
                                                                <td>'.$order->reference_no.'</td>
                                                                <td>'.$order->customer.'</td>
                                                                <td>'.$order->total.'</td>
                                                                <td>'.$order->payment_status.'</td>
                                                                <td>'.$order->paid.'</td>
                                                                </tr>';
                                                            $r++;
                                                        }        
                                                    } else { ?>
                                                    <tr>
                                                        <td colspan="7" class="dataTables_empty"><?=lang('no_data_available')?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="quotes" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="quotes-tbl" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("date"); ?></th>
                                                        <th><?= $this->lang->line("reference_no"); ?></th>
                                                        <th><?= $this->lang->line("customer"); ?></th>
                                                        <th><?= $this->lang->line("status"); ?></th>
                                                        <th><?= $this->lang->line("amount"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($quotes)) { 
                                                        $r = 1;
                                                        foreach($quotes as $quote) {
                                                            echo '<tr id="'.$quote->id.'" class="quote_link"><td>'.$r.'</td>
                                                                <td>'.$this->sma->hrld($quote->date).'</td>
                                                                <td>'.$quote->reference_no.'</td>
                                                                <td>'.$quote->customer.'</td>
                                                                <td>'.$quote->status.'</td>
                                                                <td>'.$quote->total.'</td>
                                                                </tr>';
                                                            $r++;
                                                        }        
                                                    } else { ?>
                                                    <tr>
                                                        <td colspan="6" class="dataTables_empty"><?=lang('no_data_available')?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="purchases" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="purchases-tbl" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("date"); ?></th>
                                                        <th><?= $this->lang->line("reference_no"); ?></th>
                                                        <th><?= $this->lang->line("supplier"); ?></th>
                                                        <th><?= $this->lang->line("status"); ?></th>
                                                        <th><?= $this->lang->line("amount"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($purchases)) { 
                                                        $r = 1;
                                                        foreach($purchases as $purchase) {
                                                            echo '<tr id="'.$purchase->id.'" class="purchase_link"><td>'.$r.'</td>
                                                                <td>'.$this->sma->hrld($purchase->date).'</td>
                                                                <td>'.$purchase->reference_no.'</td>
                                                                <td>'.$purchase->supplier.'</td>
                                                                <td>'.$purchase->status.'</td>
                                                                <td>'.$purchase->grand_total.'</td>
                                                                </tr>';
                                                            $r++;
                                                        }        
                                                    } else { ?>
                                                    <tr>
                                                        <td colspan="6" class="dataTables_empty"><?=lang('no_data_available')?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="transfers" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="transfers-tbl" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("date"); ?></th>
                                                        <th><?= $this->lang->line("reference_no"); ?></th>
                                                        <th><?= $this->lang->line("from"); ?></th>
                                                        <th><?= $this->lang->line("to"); ?></th>
                                                        <th><?= $this->lang->line("status"); ?></th>
                                                        <th><?= $this->lang->line("amount"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($transfers)) { 
                                                        $r = 1;
                                                        foreach($transfers as $transfer) {
                                                            echo '<tr id="'.$transfer->id.'" class="transfer_link"><td>'.$r.'</td>
                                                                <td>'.$this->sma->hrld($transfer->date).'</td>
                                                                <td>'.$transfer->transfer_no.'</td>
                                                                <td>'.$transfer->from_warehouse_name.'</td>
                                                                <td>'.$transfer->to_warehouse_name.'</td>
                                                                <td>'.$transfer->status.'</td>
                                                                <td>'.$transfer->grand_total.'</td>
                                                                </tr>';
                                                            $r++;
                                                        }        
                                                    } else { ?>
                                                    <tr>
                                                        <td colspan="7" class="dataTables_empty"><?=lang('no_data_available')?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="customers" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="customers-tbl" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("company"); ?></th>
                                                        <th><?= $this->lang->line("name"); ?></th>
                                                        <th><?= $this->lang->line("email"); ?></th>
                                                        <th><?= $this->lang->line("phone"); ?></th>
                                                        <th><?= $this->lang->line("address"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($customers)) { 
                                                        $r = 1;
                                                        foreach($customers as $customer) {
                                                            echo '<tr id="'.$customer->id.'" class="customer_link pointer"><td>'.$r.'</td>
                                                                <td>'.$customer->company.'</td>
                                                                <td>'.$customer->name.'</td>
                                                                <td>'.$customer->email.'</td>
                                                                <td>'.$customer->phone.'</td>
                                                                <td>'.$customer->address.'</td>
                                                                </tr>';
                                                            $r++;
                                                        }        
                                                    } else { ?>
                                                    <tr>
                                                        <td colspan="6" class="dataTables_empty"><?=lang('no_data_available')?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="suppliers" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="suppliers-tbl" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("company"); ?></th>
                                                        <th><?= $this->lang->line("name"); ?></th>
                                                        <th><?= $this->lang->line("email"); ?></th>
                                                        <th><?= $this->lang->line("phone"); ?></th>
                                                        <th><?= $this->lang->line("address"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($suppliers)) { 
                                                        $r = 1;
                                                        foreach($suppliers as $supplier) {
                                                            echo '<tr id="'.$supplier->id.'" class="supplier_link pointer"><td>'.$r.'</td>
                                                                <td>'.$supplier->company.'</td>
                                                                <td>'.$supplier->name.'</td>
                                                                <td>'.$supplier->email.'</td>
                                                                <td>'.$supplier->phone.'</td>
                                                                <td>'.$supplier->address.'</td>
                                                                </tr>';
                                                            $r++;
                                                        }        
                                                    } else { ?>
                                                    <tr>
                                                        <td colspan="6" class="dataTables_empty"><?=lang('no_data_available')?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>


                    </div>

                </div>                         

            </div>
        </div>
    </div>
    
</div> 

<script type="text/javascript">
$(document).ready(function(){
    $('.order').click(function(){
        window.location.href = '<?=site_url()?>orders/view/'+$(this).attr('id')+'#comments';
    });
    $('.invoice').click(function(){
        window.location.href = '<?=site_url()?>orders/view/'+$(this).attr('id');
    });
    $('.quote').click(function(){
        window.location.href = '<?=site_url()?>quotes/view/'+$(this).attr('id');
    });
});
</script>


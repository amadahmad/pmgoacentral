<style>
    .table th, .table td { width: 8.333%; text-align:center; vertical-align: middle; }
    .data tr:nth-child(odd) td { color: #2FA4E7; text-align: left; border-bottom: 0 !important; }
    .data tr:nth-child(even) td { text-align: right; font-weight:bold; }
    @media (max-width: 767px) {
        .tabel thead, .year_roller { display: none !important; visibility:hidden; }	
        .table tr:first-child td { padding: 10px; }
    }
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('monthly_sales'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang("reports_calendar_text") ?></p>

                <div class="table-responsive">
                    <table class="table table-bordered dfTable">
                        
                            <tr class="year_roller">
                                <th><div class="text-center"> <a href="reports/monthly_sales/<?php echo $year - 1; ?>">&lt;&lt;</a> </div></th>
                        <th colspan="10"><div class="text-center"> <?php echo $year; ?> </div></td>
                        <th><div class="text-center"> <a href="reports/monthly_sales/<?php echo $year + 1; ?>">&gt;&gt;</a> </div></th>
                        </th>
                        </tr> 
                       
                        <tr>
                            <th><?php echo $this->lang->line("january"); ?></th>
                            <th><?php echo $this->lang->line("february"); ?></th>
                            <th><?php echo $this->lang->line("march"); ?></th>
                            <th><?php echo $this->lang->line("april"); ?></th>
                            <th><?php echo $this->lang->line("may"); ?></th>
                            <th><?php echo $this->lang->line("june"); ?></th>
                            <th><?php echo $this->lang->line("july"); ?></th>
                            <th><?php echo $this->lang->line("august"); ?></th>
                            <th><?php echo $this->lang->line("september"); ?></th>
                            <th><?php echo $this->lang->line("october"); ?></th>
                            <th><?php echo $this->lang->line("november"); ?></th>
                            <th><?php echo $this->lang->line("december"); ?></th>
                        </tr>
                        <tr>

                            <?php
                            if (!empty($sales)) {

                                foreach ($sales as $value) {
                                    $array[$value->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tr><td>" . $this->lang->line("product_tax") . "</td></tr><tr><td>" . $this->sma->formatMoney($value->tax1) . "</td></tr><tr><td>" . $this->lang->line("order_tax") . "</td></tr><tr><td>" . $this->sma->formatMoney($value->tax2) . "</td></tr><tr><td>" . $this->lang->line("total") . "</td></tr><tr><td>" . $this->sma->formatMoney($value->total) . "</td></tr></table>";
                                }

                                for ($i = 1; $i <= 12; $i++) {
                                    echo "<td>";
                                    if (isset($array[$i])) {
                                        echo $array[$i];
                                    } else {
                                        echo '<strong>&nbsp;</strong>';
                                    }
                                    echo "</td>";
                                }
                            } else {
                                for ($i = 1; $i <= 12; $i++) {
                                    echo "<td><strong>0</strong></td>";
                                }
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>   

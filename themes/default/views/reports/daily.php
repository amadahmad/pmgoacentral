<style>
    .table th { text-align:center; }
    .table td { text-align:center; }
    .table a:hover { text-decoration: none; }
    .cl_wday { text-align: center; font-weight:bold; }
    .cl_equal { width: 14%; }
    td.day { width: 14%; padding: 0 !important; vertical-align: top !important; }
    .day_num { width: 100%; text-align:left; cursor:pointer; margin: 0; padding:8px; } 
    .day_num:hover { background:#F5F5F5; }
    .content { width: 100%; text-align:left; color: #428bca; padding: 8px; }
    .highlight { color: #0088CC; font-weight:bold; }
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('daily_sales'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang("reports_calendar_text") ?></p>
                <div>
                    <?php echo $calender; ?>
                </div>
            </div>

        </div>
    </div>
</div>   

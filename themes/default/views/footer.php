<div class="clearfix"></div>
<?= '</div></div></div></div></div>'; ?>
<div class="clearfix"></div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<footer><a href="#" id="toTop" class="blue" style="position: fixed; bottom: 30px; right: 30px; font-size: 30px; display: none;"><i class="fa fa-chevron-circle-up"></i></a>
    <p style="text-align:center;">&copy; <?= date('Y')." ".$Settings->site_name; ?><?php if($_SERVER["SERVER_NAME"] == 'localhost') { echo ' - Page rendered in <strong>{elapsed_time}</strong> seconds'; } ?></p>
</footer>
<?= '</div>'; ?>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification); ?>
<script type="text/javascript">var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;</script>
<script type="text/javascript" src="<?=$assets?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="<?=$assets?>js/select2.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/custom.js"></script>
<script type="text/javascript" src="<?=$assets?>js/core.js"></script>
<script type="text/javascript" src="<?=$assets?>js/perfect-scrollbar.min.js"></script>
<?= ($m == 'purchases' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="'.$assets.'js/purchases.js"></script>' : ''; ?>
<?= ($m == 'transfers' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="'.$assets.'js/transfers.js"></script>' : ''; ?>
<?= ($m == 'sales' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="'.$assets.'js/sales.js"></script>' : ''; ?>
<?= ($m == 'quotes' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="'.$assets.'js/quotes.js"></script>' : ''; ?>

<script type="text/javascript"  charset="UTF-8">
$(document).ready(function(){ 
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>; 
    $.extend(true,$.fn.dataTable.defaults,{"oLanguage":<?=$dt_lang?>});
});
$(window).load(function() {
    $('.mm_<?=$m?>').addClass('active');
    $('.mm_<?=$m?>').find("ul").first().slideToggle();
    $('#<?=$m?>_<?=$v?>').addClass('active');
    $('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
});
</script>
</body>
</html>

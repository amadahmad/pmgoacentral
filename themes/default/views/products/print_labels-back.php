<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <base href="<?php echo base_url(); ?>" />
        <title><?php echo $page_title . " | " . $Settings->site_name; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="<?= $assets; ?>styles/theme.css" type="text/css" charset="utf-8">
        <link rel="stylesheet" href="<?= $assets; ?>styles/style.css" type="text/css" charset="utf-8">
        <script src="<?= $assets; ?>js/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#category').change(function() {
                    window.location.replace("<?php echo site_url('products/print_labels'); ?>/" + $(this).val());
                    return false;
                });
            });
        </script>
        <style>
             body { font-size: 13px; text-align:center; color: #000; background: #FFF; }
             body:before, body:after { display: none; }
            .container { width: 1000px !important; margin: 0 auto; }
            .labels { text-align: center; font-size: 12px; color: #000; }
            h4 { margin:5px; padding:0; }
            .table td { width: 20%; }

            @media print
            {
                .container { width: auto !important; }
                h3 { margin-top: 0; }
                .container p,
                .pagination, .well { display: none; }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="well">
                <h4><?php echo $Settings->site_name . ' - ' . $page_title; ?></h4>
                
                <div class="col-md-6 col-md-offset-3">
                    <?= lang("category", "category"); ?>
                    <?php
                    $cat[''] = $this->lang->line("select") . " " . $this->lang->line("category");
                    foreach ($categories as $category) {
                        $cat[$category->id] = $category->name;
                    }
                    echo form_dropdown('category', $cat, (isset($_GET['category_id']) ? $_GET['category_id'] : ""), 'class="tip form-control" id="category" placeholder="' . $this->lang->line("select") . " " . $this->lang->line("category") . '" required="required"');
                    ?>
                    <span style="margin-top:15px; display: block;"><div class="btn-group">
                            <a class="btn btn-success" href="<?=site_url()?>"><i class="fa fa-home"></i> <?=lang('home')?></a>
                            <a class="btn btn-info" onclick="window.history.back()"><i class="fa fa-arrow-circle-left"></i> <?=lang('go_back'); ?></a> 
                            <a class="btn btn-primary" href="#" onclick="window.print(); return false;"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></a>
                    </div></span>
                </div>
                <div class="clearfix"></div>
               
                    
            </div>
            <?php if ($r != 1) { ?> 
                <p><?php echo $this->lang->line('print_barcode_heading'); ?></p>
                <?php if(!empty($links)) { echo '<div class="pagination pagination-centered">'.$links.'</div>'; } ?>
                <?php echo $html; ?>
                <?php if(!empty($links)) { echo '<div class="pagination pagination-centered">'.$links.'</div>'; } ?>
<?php } else {
    echo '<h3>' . $this->lang->line('empty_category') . '</h3>';
} ?>
        </div>

    </body>
</html>	
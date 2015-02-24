<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $heading; ?></title>
        <style>
            body{ width: 90%; margin: 0 auto; padding: 0; font-family: sans-serif;  }
            #error{display:block; position:relative; width:100%; margin:50px 0; text-align:center;}
            #error .mid{display:block; width:85%; margin:0 auto; padding:20px; border:1px solid #428BCA; background-color:#428BCA; color:#FFF;}
            #error .mid h1{margin:0 0 0 20px; padding:0; display:inline; font-size:3em; text-transform:uppercase;}
            #error .mid p{margin:25px 0 0 0; padding:0; font-size:16px;}
            #error a.go-back, #error a.go-home{display:block; position:absolute; top:30px; width:120px; padding:20px 0; font-size:20px; font-weight: bold; text-decoration: none; text-transform:uppercase; color:#428BCA; background-color:#FFF;}
            #error a.go-back{left:0;}
            #error a.go-home{right:0;}
            #error .mid, #error a.go-back, #error a.go-home{ border-radius:10px;}
            #error .mid p:nth-child(4) {font-family:monospace;text-align:justify;border:1px solid #ccc; border-radius: 10px; padding: 20px; line-height: 1.5em;background-color:#FFF; color:#428BCA;}
            @media only screen and (max-width: 998px) {
                #error { margin: 20px 0;}
                #error a.go-back{position:static; margin: 10px auto;}
                #error a.go-home{position:static; margin: 10px auto;}
            }
        </style>
    </head>
    <body>

        <section id="error" class="clear">

            <a class="go-back" href="javascript:history.go(-1)">&laquo; Back</a>
            <div class="mid">
                <h1><?php echo $heading; ?></h1>
                <?php echo $message; ?>
            </div>
            <a class="go-home" href="<?= config_item('base_url') ?>">Home &raquo;</a>

        </section>

    </body>
</html>

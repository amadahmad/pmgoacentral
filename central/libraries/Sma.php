<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 *  ============================================================================== 
 *  Author  : Axess Tech
 *  Email   : info@axesstech.com
 *  For     : PMGOA 
 *  Web     : http://axesstech.com
 *  ============================================================================== 
 */  

class Sma { 

    public function __construct() {

    }
    
    public function __get($var) {
        return get_instance()->$var;
    }

    public function formatMoney($number, $currency = '') {
        $decimal = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return $currency.number_format( $number, $decimal, $ds, $ts );
    }

    public function clear_tags($str) {
        return htmlentities(
                strip_tags($str, 
                        '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
                        ), 
                ENT_QUOTES | ENT_XHTML | ENT_HTML5, 
                'UTF-8'
                );
    }
    
    public function decode_html($str) {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }
    
    public function roundMoney($num, $nearest = 0.05) {
        return round($num * ( 1 / $nearest)) * $nearest;
    }
    
    public function unset_data($ud) { 
        if($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return FALSE;
    }
    
    public function hrsd($sdate) {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }
    
    public function hrld($ldate) {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }
    
    public function fsd($inv_date) {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate) {
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = $date[1];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from = NULL, $from_name = NULL, $attachment = NULL, $cc = NULL, $bcc = NULL) {
        $this->load->library('email');
        $config['protocol'] = $this->Settings->protocol;
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        if ($this->protocol == 'smtp') {
            $config['smtp_host'] = $this->Settings->smtp_host;
            $config['smtp_user'] = $this->Settings->smtp_user;
            $config['smtp_pass'] = $this->encrypt->decode($this->Settings->smtp_pass);
            $config['smtp_port'] = $this->Settings->smtp_port;
        }
        $this->email->initialize($config);

        if ($from && $from_name) {
            $this->email->from($from, $from_name);
        } elseif($from) {
            $this->email->from($from, $this->Settings->site_name);
        }else {
            $this->email->from($this->Settings->default_email, $this->Settings->site_name);
        }

        $this->email->to($to);
        if ($cc) {
            $this->email->cc($cc);
        }
        if ($bcc) {
            $this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
        if ($attachment) {
            if(is_array($attachment)) {
                $this->email->attach($attachment['file'], '', $attachment['name'], $attachment['mine']);
            } else {
                $this->email->attach($attachment);
            }
        }

        if ($this->email->send()) {
            //echo $this->email->print_debugger(); die();
            return TRUE;
        } else {
            //echo $this->email->print_debugger(); die();
            return FALSE;
        }
    }

    public function checkPermissions($action = NULL, $js = NULL, $module = NULL) {
        if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if($js) { 
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . ( isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome') ) . "'; }, 0);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = NULL, $module = NULL) {
        if ($this->Owner || $this->Admin) {
            if($this->Admin && stripos($action, 'delete') !== false) {
                return FALSE;
            }
            return TRUE;
        } elseif($this->Customer || $this->Supplier) { 
            return false;
        } else {
            if (!$module) { $module = $this->m; }
            if (!$action) { $action = $this->v; }
            $gp = $this->site->checkPermissions();
            if ($gp[0][$module . '-' . $action] == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    public function save_barcode($text = NULL, $bcs = 'code39', $height = 56, $stext = 1, $width = 256) {
        $drawText = ($stext != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText); 
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle'); //'width' => $width
        $image = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
        //header ('Content-type: image/png');
	if(imagepng($image, 'assets/uploads/barcode'.$this->session->userdata('user_id').'.png')) {
            imagedestroy($image);
            $bc = file_get_contents('assets/uploads/barcode'.$this->session->userdata('user_id').'.png');
            $bcimage = base64_encode($bc);
            return $bcimage;
        }
        return FALSE;
    }
    
    public function qrcode($type = 'text', $text = 'PHP QR Code', $size = 2, $level = 'H', $file_name = NULL) {
        //header("Content-Type: image/png");
        $file_name = 'assets/uploads/qrcode'.$this->session->userdata('user_id').'.png';
        if($type == 'link') { $text = urldecode($text); }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
        $this->phpqrcode->generate($config);
        $qr = file_get_contents('assets/uploads/qrcode'.$this->session->userdata('user_id').'.png');
        $qrimage = base64_encode($qr);
        return $qrimage;
    }
    
    public function generate_pdf($content, $name = 'download.pdf', $output_type = 'D', $margin_top = 10, $margin_bottom =10 ) { 
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4', '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $pdf->debug = false;
        $pdf->useOnlyCoreFonts = true;
        $pdf->SetProtection(array('print'));
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $pdf->SetAutoFont();
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($content);
        if($output_type == 'S') {
            return $pdf->Output('', 'S');
        } else {
            $pdf->Output($name, $output_type);
        } 
    }
    
    public function print_arrays() {
        $args = func_get_args();
        echo "<pre>";
        foreach($args as $arg){
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in() {
        return (bool) $this->session->userdata('identity');
    }

    public function in_group($check_group, $id = false) {
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if($group->name === $check_group) {
            return TRUE;
        }
        return FALSE;
    }

    public function log_payment($msg, $val = NULL) {
        $this->load->library('logs');
        return (bool) $this->logs->write('payments', $msg, $val);
    }
    
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        define("DEMO", 0);
        
        $this->Settings = $this->site->get_setting();
        $this->config->set_item('language', $this->Settings->language);
        $this->lang->load('sma', $this->Settings->language);
        $this->theme = $this->Settings->theme.'/views/';
        if (is_dir(VIEWPATH.$this->Settings->theme.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR)) {
            $this->data['assets'] = base_url() . 'themes/' . $this->Settings->theme . '/assets/';
        } else {
            $this->data['assets'] = base_url() . 'themes/default/assets/';
        }

        $this->data['Settings'] = $this->Settings;
        $this->loggedIn = $this->sma->logged_in();

        if ($this->loggedIn) {
            $this->default_currency = $this->site->getCurrencyByCode($this->Settings->default_currency);
            $this->data['default_currency'] = $this->default_currency;
            $this->Owner = $this->sma->in_group('owner') ? TRUE : NULL;
            $this->data['Owner'] = $this->Owner;
            $this->Customer = $this->sma->in_group('customer') ? TRUE : NULL;
            $this->data['Customer'] = $this->Customer;
            $this->Supplier = $this->sma->in_group('supplier') ? TRUE : NULL;
            $this->data['Supplier'] = $this->Supplier;
            $this->Admin = $this->sma->in_group('admin') ? TRUE : NULL;
            $this->data['Admin'] = $this->Admin;

            if ($sd = $this->site->getDateFormat($this->Settings->dateformat)) {
                $dateFormats = array(
                    'js_sdate' => $sd->js,
                    'php_sdate' => $sd->php,
                    'mysq_sdate' => $sd->sql,
                    'js_ldate' => $sd->js . ' hh:ii', //' hh:ii:ss P'
                    'php_ldate' => $sd->php . ' H:i', //' h:i:s A'
                    'mysql_ldate' => $sd->sql . ' %H:%i' //' %r'
                );
            } else {
                $dateFormats = array(
                    'js_sdate' => 'mm-dd-yyyy',
                    'php_sdate' => 'm-d-Y',
                    'mysq_sdate' => '%m-%d-%Y',
                    'js_ldate' => 'mm-dd-yyyy hh:ii:ss',
                    'php_ldate' => 'm-d-Y H:i:s',
                    'mysql_ldate' => '%m-%d-%Y %T'
                );
            }
            if (is_dir(VIEWPATH.$this->Settings->theme.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'pos')) {
                define("POS", 1);
            } else {
                define("POS", 0);
            }
            $this->dateFormats = $dateFormats;
            $this->data['dateFormats'] = $dateFormats;
            //$this->default_currency = $this->Settings->currency_code;
            //$this->data['default_currency'] = $this->default_currency;
            $this->m = strtolower($this->router->fetch_class());
            $this->v = strtolower($this->router->fetch_method());
            $this->data['m']= $this->m;
            $this->data['v'] = $this->v;
            $this->data['dt_lang'] = json_encode(array("sEmptyTable" => lang('sEmptyTable'), "sInfo" => lang('sInfo'), "sInfoEmpty" => lang('sInfoEmpty'), "sInfoFiltered" => lang('sInfoFiltered'), "sInfoPostFix" => lang('sInfoPostFix'), "sInfoThousands" => lang('sInfoThousands'), "sLengthMenu" => lang('sLengthMenu'), "sLoadingRecords" => lang('sLoadingRecords'), "sProcessing" => lang('sProcessing'), "sSearch" => lang('sSearch'), "sZeroRecords" => lang('sZeroRecords'), "oPaginate" => array("sFirst" => lang('sFirst'), "sLast" => lang('sLast'), "sNext" => lang('sNext'), "sPrevious" => lang('sPrevious')), "oAria" => array("sSortAscending" => lang('sSortAscending'), "sSortDescending" => lang('sSortDescending'))));
            $this->data['dp_lang'] = json_encode(array('days' => array(lang('sunday'), lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'), lang('sunday')), 'daysShort' => array(lang('sun'), lang('mon'), lang('tue'), lang('wed'), lang('thu'), lang('fri'), lang('sat'), lang('sun')), 'daysMin' => array(lang('su'), lang('mo'), lang('tu'), lang('we'), lang('th'), lang('fr'), lang('sa'), lang('su')), 'months' => array(lang('january'), lang('february'), lang('march'), lang('april'), lang('may'), lang('june'), lang('july'), lang('august'), lang('september'), lang('october'), lang('november'), lang('december')), 'monthsShort' => array(lang('jan'), lang('feb'), lang('mar'), lang('apr'), lang('may'), lang('jun'), lang('jul'), lang('aug'), lang('sep'), lang('oct'), lang('nov'), lang('dec')), 'today' => lang('today')));

        }
    }

    function page_construct($page, $meta = array(), $data = array()) {
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        $meta['info'] = $this->site->getNotifications();
        $meta['events'] = $this->site->getUpcomingEvents();
        $meta['ip_address'] = $this->input->ip_address();
        $meta['Owner'] = $data['Owner'];
        $meta['Admin'] = $data['Admin'];
        $meta['Supplier'] = $data['Supplier'];
        $meta['Customer'] = $data['Customer'];
        $meta['Settings'] = $data['Settings'];
        $meta['dateFormats'] = $data['dateFormats'];
        $meta['assets'] = $data['assets'];
        //$meta['dt_lang'] = $data['dt_lang'];
        //$meta['dp_lang'] = $data['dp_lang'];
        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');
    }
    
}

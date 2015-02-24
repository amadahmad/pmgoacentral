<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation'); 
        $this->load->model('db_model');
    }

    public function index() {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
        $this->page_construct('dashboard', $meta, $this->data);
        
    }

    function set_data($ud, $value) {
        $this->session->set_userdata($ud, $value);
        echo true;
    }
    
    function hideNotification($id = NULL)
    {
        $this->session->set_userdata('hiden'.$id, 1);
        echo true;
    }

    function promotions()
    {
        $this->load->view($this->theme.'promotions', $this->data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/Welcome.php */
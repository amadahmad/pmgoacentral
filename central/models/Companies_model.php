<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companies_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getAllBillerCompanies() {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomerCompanies() {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    
    public function getAllSupplierCompanies() {
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomerGroups() {
        $q = $this->db->get('customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            
            return $data;
        }
    }
    
    public function getCompanyUsers($company_id) {
        $q = $this->db->get_where('users', array('company_id' => $company_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCompanyByID($id) {

        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCompanyByEmail($email) {

        $q = $this->db->get_where('companies', array('email' => $email), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    public function addCompany($data = array()) {

        if ($this->db->insert('companies', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateCompany($id, $data = array()) {

        $this->db->where('id', $id);
        if ($this->db->update('companies', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function addCompanies($data = array()) {

        if ($this->db->insert_batch('companies', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCompany($id) {
        if ($this->db->delete('companies', array('id' => $id)) && $this->db->delete('users', array('company_id' => $id))) {
            return true;
        }
        return FALSE;
    }
    
    public function getBillerSuggestions($term, $limit = 10) {
        $this->db->select("id, (CASE WHEN company = '-' THEN name ELSE company END) as text");
        $this->db->where(" (id LIKE '%".$term."%' OR name LIKE '%".$term."%' OR company LIKE '%".$term."%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'biller'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCustomerSuggestions($term, $limit = 10) {
        $this->db->select("id, (CASE WHEN company = '-' THEN name ELSE company END) as text");
        $this->db->where(" (id LIKE '%".$term."%' OR name LIKE '%".$term."%' OR company LIKE '%".$term."%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'customer'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    
    public function getSupplierSuggestions($term, $limit = 10) {
        $this->db->select('id, company as text');
        $this->db->where(" (id LIKE '%".$term."%' OR name LIKE '%".$term."%' OR company LIKE '%".$term."%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

}

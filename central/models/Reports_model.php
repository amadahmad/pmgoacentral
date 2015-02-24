<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5) {
        $this->db->select('id, code, name')
                ->like('name', $term, 'both')->or_like('code', $term, 'both');
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaff() {
        $this->db->where('group_id !=', 1)->where('group_id !=', 3)->where('group_id !=', 4);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }
    
    public function getSalesTotals($customer_id) {

        $this->db->select('COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid', FALSE)
            ->where('customer_id', $customer_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    public function getCustomerSales($customer_id) {
        $this->db->from('sales')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }
    
    public function getCustomerQuotes($customer_id) {
        $this->db->from('quotes')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }

    public function getStockValue() {
        $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select COALESCE(sum(warehouses_products.quantity), 0)*price as by_price, COALESCE(sum(warehouses_products.quantity), 0)*cost as by_cost FROM products JOIN warehouses_products ON warehouses_products.product_id=products.id GROUP BY products.id )a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getWarehouseStockValue($id) {

        $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select COALESCE(sum(warehouses_products.quantity), 0)*price as by_price, COALESCE(sum(warehouses_products.quantity), 0)*cost as by_cost FROM products JOIN warehouses_products ON warehouses_products.product_id=products.id WHERE warehouses_products.warehouse_id = ? GROUP BY products.id )a", array($id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getmonthlyPurchases() {
        $myQuery = "SELECT (CASE WHEN date_format( date, '%b' ) Is Null THEN 0 ELSE date_format( date, '%b' ) END) as month, SUM( COALESCE( total, 0 ) ) AS purchases FROM purchases WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH ) GROUP BY date_format( date, '%b' ) ORDER BY date_format( date, '%m' ) ASC";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getChartData() {
        $myQuery = "SELECT S.month,
					   COALESCE(S.sales, 0) as sales,
					   COALESCE( P.purchases, 0 ) as purchases,
					   COALESCE(S.tax1, 0) as tax1,
					   COALESCE(S.tax2, 0) as tax2,
					   COALESCE( P.ptax, 0 ) as ptax
					FROM (	SELECT	date_format(date, '%Y-%m') Month,
								SUM(total) Sales,
								SUM(product_tax) tax1,
								SUM(order_tax) tax2
						FROM sales
						WHERE sales.date >= date_sub( now( ) , INTERVAL 12 MONTH )
						GROUP BY date_format(date, '%Y-%m')) S
					LEFT JOIN (	SELECT	date_format(date, '%Y-%m') Month,
									SUM(product_tax) ptax,
                                                                        SUM(order_tax) otax,
									SUM(total) purchases
							FROM purchases
							GROUP BY date_format(date, '%Y-%m')) P
					ON S.Month = P.Month
					GROUP BY S.Month
					ORDER BY S.Month";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    /* public function getDailySales() 
      {
      $year = '2013'; $month = '3';
      $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( total, 0 ) ) AS sales, SUM( COALESCE( total_tax, 0 ) ) as tax1, SUM( COALESCE( total_tax2, 0 ) ) as tax2
      FROM sales
      WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '2013-4'
      GROUP BY DATE_FORMAT( date,  '%e' )";
      $q = $this->db->query($myQuery);
      if($q->num_rows() > 0) {
      foreach (($q->result()) as $row) {
      $data[] = $row;
      }

      return $data;
      }
      } */

    public function getAllWarehouses() {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomers() {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllBillers() {
        $q = $this->db->get('billers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllSuppliers() {
        $q = $this->db->get('suppliers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getDailySales($year, $month) {

        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount
			FROM sales
			WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
			GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getMonthlySales($year) {

        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total
			FROM sales
			WHERE DATE_FORMAT( date,  '%Y' ) =  '{$year}' 
			GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getStaffDailySales($user_id, $year, $month) {

        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount
            FROM sales
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getStaffMonthlySales($user_id, $year) {

        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total
            FROM sales
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}' 
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getPurchasesTotals($supplier_id) {

        $this->db->select('COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid', FALSE)
            ->where('supplier_id', $supplier_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    public function getSupplierPurchases($supplier_id) {
        $this->db->from('purchases')->where('supplier_id', $supplier_id);
        return $this->db->count_all_results();
    }

    public function getStaffPurchases($user_id) {

        $this->db->select('count(id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid', FALSE)
            ->where('created_by', $user_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    public function getStaffSales($user_id) {

        $this->db->select('count(id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid', FALSE)
            ->where('created_by', $user_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalSales($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid, COALESCE(sum(total_tax), 0) as tax', FALSE)
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalPurchases($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid, COALESCE(sum(total_tax), 0) as tax', FALSE)
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalPaidAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'sent')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReceivedAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'received')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReceivedCashAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'received')->where('paid_by', 'cash')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReceivedCCAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'received')->where('paid_by', 'CC')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReceivedChequeAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'received')->where('paid_by', 'Cheque')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReceivedPPPAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'received')->where('paid_by', 'ppp')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReceivedStripeAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'received')->where('paid_by', 'stripe')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTotalReturnedAmount($start, $end) {

        $this->db->select('count(id) as total, COALESCE(sum(amount), 0) as total_amount', FALSE)
        ->where('type', 'returned')
        ->where('date BETWEEN '.$start.' and '.$end);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

}

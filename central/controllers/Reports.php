  <?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  class Reports extends MY_Controller {

  	function __construct() {
  		parent::__construct();

  		if (!$this->loggedIn) {
  			$this->session->set_userdata('requested_page', $this->uri->uri_string());
  			redirect('login');
  		}
  		if (!$this->Owner && !$this->Admin) {
  			$this->session->set_flashdata('warning', lang('access_denied'));
  			redirect('welcome');
  		}
  		$this->lang->load('reports', $this->Settings->language);
  		$this->load->library('form_validation'); 
  		$this->load->model('reports_model');

  	}

  	function index() {

  		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
  		$this->data['monthly_sales'] = $this->reports_model->getChartData();
  		$this->data['stock'] = $this->reports_model->getStockValue();
  		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('reports')));
  		$meta = array('page_title' => lang('reports'), 'bc' => $bc);
  		$this->page_construct('reports/index', $meta, $this->data);


  	}

  	function warehouse_stock($warehouse = NULL) {

  		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
  		if($this->input->get('warehouse')){ $warehouse = $this->input->get('warehouse'); }
  		if(!$warehouse) { $warehouse = $this->Settings->default_warehouse; }

  		$this->data['stock'] = $this->reports_model->getWarehouseStockValue($warehouse);
  		$this->data['warehouses'] = $this->reports_model->getAllWarehouses();
  		$this->data['warehouse_id'] = $warehouse;

  		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('reports')));
  		$meta = array('page_title' => lang('reports'), 'bc' => $bc);
  		$this->page_construct('reports/warehouse_stock', $meta, $this->data);


  	}

  	function expiry_alerts($warehouse_id = NULL) {

  		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
  		if(!$warehouse_id) { $warehouse_id = $this->Settings->default_warehouse; }
  		if ($this->Owner) {
  			$this->data['warehouses'] = $this->site->getAllWarehouses();
  			$this->data['warehouse_id'] = $warehouse_id;
  			$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
  		} else {
  			$user = $this->site->getUser();
  			$this->data['warehouses'] = NULL;
  			$this->data['warehouse_id'] = $user->warehouse_id;
  			$this->data['warehouse'] = $user->warehouse_id ? $this->site->getWarehouseByID($user->warehouse_id) : NULL;
  		}

  		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('product_expiry_alerts')));
  		$meta = array('page_title' => lang('product_expiry_alerts'), 'bc' => $bc);
  		$this->page_construct('reports/expiry_alerts', $meta, $this->data);
  	}

  	function getExpiryAlerts($warehouse_id = NULL) {
  		$date = date('Y-m-d', strtotime('+3 months'));

  		if (!$this->Owner && !$warehouse_id) {
  			$user = $this->site->getUser();
  			$warehouse_id = $user->warehouse_id;
  		}

  		$this->load->library('datatables');

  		$this->datatables
  		->select("products.image, product_code, product_name, quantity_balance, expiry")
  		->from('purchase_items')
  		->join('products', 'products.id=purchase_items.product_id', 'left')
  		->where('warehouse_id', $warehouse_id)->where('expiry <', $date);

  		echo $this->datatables->generate();
  	}

  	function quantity_alerts($warehouse_id = NULL) {

  		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
  		if(!$warehouse_id) { $warehouse_id = $this->Settings->default_warehouse; }
  		if ($this->Owner) {
  			$this->data['warehouses'] = $this->site->getAllWarehouses();
  			$this->data['warehouse_id'] = $warehouse_id;
  			$this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
  		} else {
  			$user = $this->site->getUser();
  			$this->data['warehouses'] = NULL;
  			$this->data['warehouse_id'] = $user->warehouse_id;
  			$this->data['warehouse'] = $user->warehouse_id ? $this->site->getWarehouseByID($user->warehouse_id) : NULL;
  		}

  		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('product_quantity_alerts')));
  		$meta = array('page_title' => lang('product_quantity_alerts'), 'bc' => $bc);
  		$this->page_construct('reports/quantity_alerts', $meta, $this->data);
  	}

  	function getQuantityAlerts($warehouse_id = NULL) {
  		if (!$this->Owner && !$warehouse_id) {
  			$user = $this->site->getUser();
  			$warehouse_id = $user->warehouse_id;
  		}

  		$this->load->library('datatables');

  		$this->datatables
  		->select('products.image as image, products.code, products.name, warehouses_products.quantity, alert_quantity')
  		->from('products')->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
  		->where('alert_quantity > warehouses_products.quantity', NULL)
  		->where('warehouse_id', $warehouse_id)
  		->where('track_quantity', 1);

  		echo $this->datatables->generate();

  	}

  	function suggestions() {
  		$term = $this->input->get('term', TRUE);
  		if (strlen($term) < 1) {
  			die();
  		}

  		$rows = $this->reports_model->getProductNames($term);
  		if ($rows) {
  			foreach ($rows as $row) {
  				$pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")");

  			}
  			echo json_encode($pr);
  		} else {
  			echo FALSE;
  		}
  	}

  	function products()
  	{

  		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
  		$this->data['categories'] = $this->site->getAllCategories();
  		if($this->input->post('start_date')){ $dt = "From ".$this->input->post('start_date')." to ".$this->input->post('end_date'); } else { $dt = "Till ".$this->input->post('end_date'); }
  		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('products_report')));
  		$meta = array('page_title' => lang('products_report'), 'bc' => $bc);
  		$this->page_construct('reports/products', $meta, $this->data);
  	}
  	function getProductsReport()
  	{
  		if($this->input->get('product')){ $product = $this->input->get('product'); } else { $product = NULL; }
  		if($this->input->get('category')){ $category = $this->input->get('category'); } else { $category = NULL; }
  		if($this->input->get('start_date')){ $start_date = $this->input->get('start_date'); } else { $start_date = NULL; }
  		if($this->input->get('end_date')){ $end_date = $this->input->get('end_date'); } else { $end_date = NULL; }
  		if($start_date) {
  			$start_date = $this->sma->fld($start_date);
  			$end_date = $end_date ? $this->sma->fld($end_date) : date('Y-m-d');

  			$pp =    "( SELECT pi.product_id, SUM( pi.quantity ) purchasedQty, SUM( pi.subtotal ) totalPurchase, p.date as pdate from purchases p JOIN purchase_items pi on p.id = pi.purchase_id where p.date >= '{$start_date}' and p.date < '{$end_date}' group by pi.product_id ) PCosts";
  			$sp = "( SELECT si.product_id, SUM( si.quantity ) soldQty, SUM( si.subtotal ) totalSale, s.date as sdate from sales s JOIN sale_items si on s.id = si.sale_id where s.date >= '{$start_date}' and s.date < '{$end_date}' group by si.product_id ) PSales";
  		} else {
  			$pp ="( SELECT pi.product_id, SUM( pi.quantity ) purchasedQty, SUM( pi.subtotal ) totalPurchase from purchase_items pi group by pi.product_id ) PCosts";
  			$sp = "( SELECT si.product_id, SUM( si.quantity ) soldQty, SUM( si.subtotal ) totalSale from sale_items si group by si.product_id ) PSales";
  		}

  		$this->load->library('datatables');
  		$this->datatables
  		->select("p.code, p.name,
  			COALESCE( PCosts.purchasedQty, 0 ) as PurchasedQty,
  			COALESCE( PSales.soldQty, 0 ) as SoldQty,
  			COALESCE( PCosts.totalPurchase, 0 ) as TotalPurchase,
  			COALESCE( PSales.totalSale, 0 ) as TotalSales,
  			(COALESCE( PSales.totalSale, 0 ) - COALESCE( PCosts.totalPurchase, 0 )) as Profit", FALSE)
  		->from('products p', FALSE)
  		->join($sp, 'p.id = PSales.product_id', 'left')
  		->join($pp, 'p.id = PCosts.product_id', 'left');
						   // ->group_by('p.id');

  		if($product) { $this->datatables->where('p.id', $product); }
  		if($category) { $this->datatables->where('p.category_id', $category); }
	  /*if($start_date) { 
	  $this->datatables->where('PCosts.pdate >=', $start_date); 
	  $this->datatables->where('PCosts.pdate <=', $end_date); 
	  $this->datatables->where('PSales.sdate >=', $start_date); 
	  $this->datatables->where('PSales.sdate <=', $end_date); 
	}*/

	echo $this->datatables->generate();

}

function daily_sales($year = NULL, $month = NULL)
{
	if(!$year) { $year = date('Y'); }
	if(!$month) { $month = date('m'); }

	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
	$config = array (
		'show_next_prev'  => TRUE,
		'next_prev_url'   => site_url('reports/daily_sales')
		);
	$config['translated_day_names'] = array(lang("sunday"), lang("monday"), lang("tuesday"), lang("wednesday"), lang("thursday"), lang("friday"), lang("saturday"));
	$config['translated_month_names'] = array('01' => lang("january"), '02' => lang("february"), '03' => lang("march"), '04' => lang("april"), '05' => lang("may"), '06' => lang("june"), '07' => lang("july"), '08' => lang("august"), '09' => lang("september"), '10' => lang("october"), '11' => lang("november"), '12' => lang("december"));

	$config['template'] = '{table_open}<table border="0" cellpadding="0" cellspacing="0" class="table table-bordered dfTable">{/table_open}
	{heading_row_start}<tr>{/heading_row_start}
	{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
	{heading_title_cell}<th colspan="{colspan}" id="month_year">{heading}</th>{/heading_title_cell}
	{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
	{heading_row_end}</tr>{/heading_row_end}
	{week_row_start}<tr>{/week_row_start}
	{week_day_cell}<td class="cl_wday">{week_day}</td>{/week_day_cell}
	{week_row_end}</tr>{/week_row_end}
	{cal_row_start}<tr class="days">{/cal_row_start}
	{cal_cell_start}<td class="day">{/cal_cell_start}
	{cal_cell_content}
	<div class="day_num">{day}</div>
	<div class="content">{content}</div>
	{/cal_cell_content}
	{cal_cell_content_today}
	<div class="day_num highlight">{day}</div>
	<div class="content">{content}</div>
	{/cal_cell_content_today}
	{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}
	{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}
	{cal_cell_blank}&nbsp;{/cal_cell_blank}
	{cal_cell_end}</td>{/cal_cell_end}
	{cal_row_end}</tr>{/cal_row_end}
	{table_close}</table>{/table_close}';

	$this->load->library('month_cal', $config);

	$sales = $this->reports_model->getDailySales($year, $month);

		//$num = cal_days_in_month(CAL_GREGORIAN, $month, $year);

	if(!empty($sales)) {
		foreach($sales as $sale) {
			$daily_sale[$sale->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tr><td>".lang("discount")."</td><td>". $this->sma->formatMoney($sale->discount) ."</td></tr><tr><td>".lang("product_tax")."</td><td>". $this->sma->formatMoney($sale->tax1) ."</td></tr><tr><td>".lang("order_tax")."</td><td>". $this->sma->formatMoney($sale->tax2) ."</td></tr><tr><td>".lang("total")."</td><td>". $this->sma->formatMoney($sale->total) ."</td></tr></table>";	
		}
	} else { $daily_sale = array(); }

	$this->data['calender'] = $this->month_cal->generate($year, $month, $daily_sale);

	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('daily_sales_report')));
	$meta = array('page_title' => lang('daily_sales_report'), 'bc' => $bc);
	$this->page_construct('reports/daily', $meta, $this->data);

}


function monthly_sales($year = NULL) {
	if(!$year) { $year = date('Y'); }
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
	$this->data['year'] = $year;
	$this->data['sales'] = $this->reports_model->getMonthlySales($year);
	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('monthly_sales_report')));
	$meta = array('page_title' => lang('monthly_sales_report'), 'bc' => $bc);
	$this->page_construct('reports/monthly', $meta, $this->data);

}

function sales() {
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');	   
	$this->data['users'] = $this->reports_model->getStaff();
	$this->data['warehouses'] = $this->site->getAllWarehouses();
	$this->data['billers'] = $this->site->getAllCompanies('biller');
	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('sales_report')));
	$meta = array('page_title' => lang('sales_report'), 'bc' => $bc);
	$this->page_construct('reports/sales', $meta, $this->data);
}

function getSalesReport()
{
		//if($this->input->get('product')){ $product = $this->input->get('product'); } else { $product = NULL; }
	if($this->input->get('user')){ $user = $this->input->get('user'); } else { $user = NULL; }
	if($this->input->get('customer')){ $customer = $this->input->get('customer'); } else { $customer = NULL; }
	if($this->input->get('biller')){ $biller = $this->input->get('biller'); } else { $biller = NULL; }
	if($this->input->get('warehouse')){ $warehouse = $this->input->get('warehouse'); } else { $warehouse = NULL; }
	if($this->input->get('reference_no')){ $reference_no = $this->input->get('reference_no'); } else { $reference_no = NULL; }
	if($this->input->get('start_date')){ $start_date = $this->input->get('start_date'); } else { $start_date = NULL; }
	if($this->input->get('end_date')){ $end_date = $this->input->get('end_date'); } else { $end_date = NULL; }
	if($start_date) {
		$start_date = $this->sma->fld($start_date);
		$end_date = $this->sma->fld($end_date);
	}
	$this->load->library('datatables');
	$this->datatables
	->select("date, reference_no, biller, customer, GROUP_CONCAT(CONCAT(sale_items.product_name, ' (', sale_items.quantity, ')') SEPARATOR '<br>') as iname, grand_total, paid, payment_status", FALSE)
	->from('sales')
	->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
	->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
	->group_by('sales.id');


	if($user) { $this->datatables->where('sales.created_by', $user); }
			//if($product) { $this->datatables->like('sale_items.product_id', $$product); }
	if($biller) { $this->datatables->where('sales.biller_id', $biller); }
	if($customer) { $this->datatables->where('sales.customer_id', $customer); }
	if($warehouse) { $this->datatables->where('sales.warehouse_id', $warehouse); }
	if($reference_no) { $this->datatables->like('sales.reference_no', $reference_no, 'both'); }
	if($start_date) { $this->datatables->where('sales.date BETWEEN "'. $start_date. '" and "'.$end_date.'"'); }


	echo $this->datatables->generate();
}

function purchases() {
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');	   
	$this->data['users'] = $this->reports_model->getStaff();
	$this->data['warehouses'] = $this->site->getAllWarehouses();

	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('purchases_report')));
	$meta = array('page_title' => lang('purchases_report'), 'bc' => $bc);
	$this->page_construct('reports/purchases', $meta, $this->data);
}

function getPurchasesReport() {
	//if($this->input->get('product')){ $product = $this->input->get('product'); } else { $product = NULL; }
	if($this->input->get('user')){ $user = $this->input->get('user'); } else { $user = NULL; }
	if($this->input->get('supplier')){ $supplier = $this->input->get('supplier'); } else { $supplier = NULL; }
	if($this->input->get('warehouse')){ $warehouse = $this->input->get('warehouse'); } else { $warehouse = NULL; }
	if($this->input->get('reference_no')){ $reference_no = $this->input->get('reference_no'); } else { $reference_no = NULL; }
	if($this->input->get('start_date')){ $start_date = $this->input->get('start_date'); } else { $start_date = NULL; }
	if($this->input->get('end_date')){ $end_date = $this->input->get('end_date'); } else { $end_date = NULL; }
	if($start_date) {
		$start_date = $this->sma->fld($start_date);
		$end_date = $this->sma->fld($end_date);
	}
	$this->load->library('datatables');
	$this->datatables
	->select("purchases.date, reference_no, warehouses.name as wname, supplier, GROUP_CONCAT(CONCAT(purchase_items.product_name, ' (', purchase_items.quantity, ')') SEPARATOR '<br>') as iname, grand_total, paid, purchases.status", FALSE)
	->from('purchases')
	->join('purchase_items', 'purchase_items.purchase_id=purchases.id', 'left')
	->join('warehouses', 'warehouses.id=purchases.warehouse_id', 'left')
	->group_by('purchases.id');

	if($user) { $this->datatables->where('purchases.created_by', $user); }
			//if($product) { $this->datatables->like('purchase_items.product_id', $product); }
	if($supplier) { $this->datatables->where('purchases.supplier_id', $supplier); }
	if($warehouse) { $this->datatables->where('purchases.warehouse_id', $warehouse); }
	if($reference_no) { $this->datatables->like('purchases.reference_no', $reference_no, 'both'); }
	if($start_date) { $this->datatables->where('purchases.date BETWEEN "'. $start_date. '" and "'.$end_date.'"'); }

	echo $this->datatables->generate();
}

function payments() {
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');	   
	$this->data['users'] = $this->reports_model->getStaff();
	$this->data['billers'] = $this->site->getAllCompanies('biller'); 
	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('payments_report')));
	$meta = array('page_title' => lang('payments_report'), 'bc' => $bc);
	$this->page_construct('reports/payments', $meta, $this->data);
}

function getPaymentsReport() {
  //if($this->input->get('product')){ $product = $this->input->get('product'); } else { $product = NULL; }
	if($this->input->get('user')){ $user = $this->input->get('user'); } else { $user = NULL; }
	if($this->input->get('supplier')){ $supplier = $this->input->get('supplier'); } else { $supplier = NULL; }
	if($this->input->get('customer')){ $customer = $this->input->get('customer'); } else { $customer = NULL; }
	if($this->input->get('biller')){ $biller = $this->input->get('biller'); } else { $biller = NULL; }
	if($this->input->get('payment_ref')){ $payment_ref = $this->input->get('payment_ref'); } else { $payment_ref = NULL; }
	if($this->input->get('sale_ref')){ $sale_ref = $this->input->get('sale_ref'); } else { $sale_ref = NULL; }
	if($this->input->get('purchase_ref')){ $purchase_ref = $this->input->get('purchase_ref'); } else { $purchase_ref = NULL; }
	if($this->input->get('start_date')){ $start_date = $this->input->get('start_date'); } else { $start_date = NULL; }
	if($this->input->get('end_date')){ $end_date = $this->input->get('end_date'); } else { $end_date = NULL; }
	if($start_date) {
		$start_date = $this->sma->fsd($start_date);
		$end_date = $this->sma->fsd($end_date);
	}
	$this->load->library('datatables');
	$this->datatables
	->select("payments.date, payments.reference_no as payment_ref, sales.reference_no as sale_ref, purchases.reference_no as purchase_ref, paid_by, amount, type")
	->from('payments')
	->join('sales', 'payments.sale_id=sales.id', 'left')
	->join('purchases', 'payments.purchase_id=purchases.id', 'left')
	->group_by('payments.id');

	if($user) { $this->datatables->where('purchases.created_by', $user); }
	if($customer) { $this->datatables->where('sales.customer_id', $customer); }
	if($supplier) { $this->datatables->where('purchases.supplier_id', $supplier); }
	if($biller) { $this->datatables->where('sales.biller_id', $biller); }
	if($customer) { $this->datatables->where('sales.customer_id', $customer); }
	if($payment_ref) { $this->datatables->like('payments.reference_no', $payment_ref, 'both'); }
	if($sale_ref) { $this->datatables->like('sales.reference_no', $sale_ref, 'both'); }
	if($purchase_ref) { $this->datatables->like('purchases.reference_no', $purchase_ref, 'both'); }
	if($start_date) { $this->datatables->where('payments.date BETWEEN "'. $start_date. '" and "'.$end_date.'"'); }

	echo $this->datatables->generate();
}

function customers() {
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');	   

	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('customers_report')));
	$meta = array('page_title' => lang('customers_report'), 'bc' => $bc);
	$this->page_construct('reports/customers', $meta, $this->data);
}

function getCustomers() {
	$this->load->library('datatables');
	$this->datatables
	->select("id, company, name, phone, email, city, country")
	->from("companies")
	->where('group_name', 'customer')
	->add_column("Actions", "<div class='text-center'><a class=\"tip\" title='" . lang("view_report") . "' href='" . site_url('reports/customer_report/$1') . "'><span class='label label-primary'>" . lang("view_report") . "</span></a></div>", "id")
	->unset_column('id');
	echo $this->datatables->generate();
}

function customer_report($user_id = NULL) {

	if(!$user_id) { 
		$this->session->set_flashdata('error', lang("no_customer_selected"));
		redirect('reports/customers');
	}

	$this->data['sales'] = $this->reports_model->getSalesTotals($user_id);
	$this->data['total_sales'] = $this->reports_model->getCustomerSales($user_id);
	$this->data['total_quotes'] = $this->reports_model->getCustomerQuotes($user_id);
	$this->data['users'] = $this->reports_model->getStaff();
	$this->data['warehouses'] = $this->site->getAllWarehouses();
	$this->data['billers'] = $this->site->getAllCompanies('biller');

	$this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

	$this->data['user_id'] = $user_id;
	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('customers_report')));
	$meta = array('page_title' => lang('customers_report'), 'bc' => $bc);
	$this->page_construct('reports/customer_report', $meta, $this->data);

}

function suppliers() {
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');    

	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('suppliers_report')));
	$meta = array('page_title' => lang('suppliers_report'), 'bc' => $bc);
	$this->page_construct('reports/suppliers', $meta, $this->data);
}

function getSuppliers() {
	$this->load->library('datatables');
	$this->datatables
	->select("id, company, name, phone, email, city, country")
	->from("companies")
	->where('group_name', 'supplier')
	->add_column("Actions", "<div class='text-center'><a class=\"tip\" title='" . lang("view_report") . "' href='" . site_url('reports/supplier_report/$1') . "'><span class='label label-primary'>" . lang("view_report") . "</span></a></div>", "id")
	->unset_column('id');
	echo $this->datatables->generate();
}

function supplier_report($user_id = NULL) {

	if(!$user_id) { 
		$this->session->set_flashdata('error', lang("no_supplier_selected"));
		redirect('reports/suppliers');
	}

	$this->data['purchases'] = $this->reports_model->getPurchasesTotals($user_id);
	$this->data['total_purchases'] = $this->reports_model->getSupplierPurchases($user_id);
	$this->data['users'] = $this->reports_model->getStaff();
	$this->data['warehouses'] = $this->site->getAllWarehouses();

	$this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

	$this->data['user_id'] = $user_id;
	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('suppliers_report')));
	$meta = array('page_title' => lang('suppliers_report'), 'bc' => $bc);
	$this->page_construct('reports/supplier_report', $meta, $this->data);

} 

function users(){
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');    

	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('staff_report')));
	$meta = array('page_title' => lang('staff_report'), 'bc' => $bc);
	$this->page_construct('reports/users', $meta, $this->data);
}

function getUsers() {
	$this->load->library('datatables');
	$this->datatables
	->select("users.id as id, first_name, last_name, email, company, groups.name, active")
	->from("users")
	->join('groups', 'users.group_id=groups.id', 'left')
	->group_by('users.id')
	->where('company_id', NULL);
	if(!$this->Owner) { $this->datatables->where('group_id !=', 1); }
	$this->datatables
	->edit_column('active', '$1__$2', 'active, id')
	->add_column("Actions", "<div class='text-center'><a class=\"tip\" title='" . lang("view_report") . "' href='" . site_url('reports/staff_report/$1') . "'><span class='label label-primary'>" . lang("view_report") . "</span></a></div>", "id")
	->unset_column('id');
	echo $this->datatables->generate();
}

function staff_report($user_id = NULL, $year = NULL, $month = NULL) {

	if(!$user_id) { 
		$this->session->set_flashdata('error', lang("no_user_selected"));
		redirect('reports/users');
	}
	$this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
	$this->data['purchases'] = $this->reports_model->getStaffPurchases($user_id);
	$this->data['sales'] = $this->reports_model->getStaffSales($user_id);
	$this->data['billers'] = $this->site->getAllCompanies('biller');
	$this->data['warehouses'] = $this->site->getAllWarehouses();

	if(!$year) { $year = date('Y'); }
	if(!$month) { $month = date('m'); }

	$config = array (
		'show_next_prev'  => TRUE,
		'next_prev_url'   => site_url('reports/daily_sales')
		);
	$config['translated_day_names'] = array(lang("sunday"), lang("monday"), lang("tuesday"), lang("wednesday"), lang("thursday"), lang("friday"), lang("saturday"));
	$config['translated_month_names'] = array('01' => lang("january"), '02' => lang("february"), '03' => lang("march"), '04' => lang("april"), '05' => lang("may"), '06' => lang("june"), '07' => lang("july"), '08' => lang("august"), '09' => lang("september"), '10' => lang("october"), '11' => lang("november"), '12' => lang("december"));

	$config['template'] = '{table_open}<table border="0" cellpadding="0" cellspacing="0" class="table table-bordered dfTable">{/table_open}
	{heading_row_start}<tr>{/heading_row_start}
	{heading_previous_cell}<th class="text-center"><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
	{heading_title_cell}<th class="text-center" colspan="{colspan}" id="month_year">{heading}</th>{/heading_title_cell}
	{heading_next_cell}<th class="text-center"><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
	{heading_row_end}</tr>{/heading_row_end}
	{week_row_start}<tr>{/week_row_start}
	{week_day_cell}<td class="cl_wday">{week_day}</td>{/week_day_cell}
	{week_row_end}</tr>{/week_row_end}
	{cal_row_start}<tr class="days">{/cal_row_start}
	{cal_cell_start}<td class="day">{/cal_cell_start}
	{cal_cell_content}
	<div class="day_num">{day}</div>
	<div class="content">{content}</div>
	{/cal_cell_content}
	{cal_cell_content_today}
	<div class="day_num highlight">{day}</div>
	<div class="content">{content}</div>
	{/cal_cell_content_today}
	{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}
	{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}
	{cal_cell_blank}&nbsp;{/cal_cell_blank}
	{cal_cell_end}</td>{/cal_cell_end}
	{cal_row_end}</tr>{/cal_row_end}
	{table_close}</table>{/table_close}';

	$this->load->library('month_cal', $config);

	$sales = $this->reports_model->getStaffDailySales($user_id, $year, $month);

	if(!empty($sales)) {
		foreach($sales as $sale){
			$daily_sale[$sale->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tr><td>".lang("discount")."</td><td>". $this->sma->formatMoney($sale->discount) ."</td></tr><tr><td>".lang("product_tax")."</td><td>". $this->sma->formatMoney($sale->tax1) ."</td></tr><tr><td>".lang("order_tax")."</td><td>". $this->sma->formatMoney($sale->tax2) ."</td></tr><tr><td>".lang("total")."</td><td>". $this->sma->formatMoney($sale->total) ."</td></tr></table>"; 
		}
	} else { $daily_sale = array(); }

	$this->data['calender'] = $this->month_cal->generate($year, $month, $daily_sale);

	$this->data['year'] = $year;
	$this->data['msales'] = $this->reports_model->getStaffMonthlySales($user_id, $year);
	$this->data['user_id'] = $user_id;
	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('staff_report')));
	$meta = array('page_title' => lang('staff_report'), 'bc' => $bc);
	$this->page_construct('reports/staff_report', $meta, $this->data);

} 

function getUserLogins($id = NULL) {
	if($this->input->get('login_start_date')){ $login_start_date = $this->input->get('login_start_date'); } else { $login_start_date = NULL; }
	if($this->input->get('login_end_date')){ $login_end_date = $this->input->get('login_end_date'); } else { $login_end_date = NULL; }
	if($login_start_date) {
		$login_start_date = $this->sma->fld($login_start_date);
		$login_end_date = $login_end_date ? $this->sma->fld($login_end_date) : date('Y-m-d H:i:s');
	}
	$this->load->library('datatables');
	$this->datatables
	->select("login, ip_address, time")
	->from("user_logins")
	->where('user_id', $id);
	if($login_start_date) { $this->datatables->where('time BETWEEN "'. $login_start_date. '" and "'.$login_end_date.'"', FALSE); }
	echo $this->datatables->generate();
}

function getCustomerLogins($id = NULL) {
	if($this->input->get('login_start_date')){ $login_start_date = $this->input->get('login_start_date'); } else { $login_start_date = NULL; }
	if($this->input->get('login_end_date')){ $login_end_date = $this->input->get('login_end_date'); } else { $login_end_date = NULL; }
	if($login_start_date) {
		$login_start_date = $this->sma->fld($login_start_date);
		$login_end_date = $login_end_date ? $this->sma->fld($login_end_date) : date('Y-m-d H:i:s');
	}
	$this->load->library('datatables');
	$this->datatables
	->select("login, ip_address, time")
	->from("user_logins")
	->where('customer_id', $id);
	if($login_start_date) { $this->datatables->where('time BETWEEN "'. $login_start_date. '" and "'.$login_end_date.'"'); }
	echo $this->datatables->generate();
}

function profit_loss($start_date = NULL, $end_date = NULL) {
	if(!$start_date) { $start = $this->db->escape(date('Y-m').'-1'); $start_date = date('Y-m').'-1'; } else { $start = $this->db->escape($start_date); }
	if(!$end_date) { $end = $this->db->escape(date('Y-m-d H:i')); $end_date = date('Y-m-d H:i'); } else { $end = $this->db->escape($end_date); }
	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');    

	$this->data['total_purchases'] = $this->reports_model->getTotalPurchases($start, $end);
	$this->data['total_sales'] = $this->reports_model->getTotalSales($start, $end);
	$this->data['total_paid'] = $this->reports_model->getTotalPaidAmount($start, $end);
	$this->data['total_received'] = $this->reports_model->getTotalReceivedAmount($start, $end);
	$this->data['total_received_cash'] = $this->reports_model->getTotalReceivedCashAmount($start, $end);
	$this->data['total_received_cc'] = $this->reports_model->getTotalReceivedCCAmount($start, $end);
	$this->data['total_received_cheque'] = $this->reports_model->getTotalReceivedChequeAmount($start, $end);
	$this->data['total_received_ppp'] = $this->reports_model->getTotalReceivedPPPAmount($start, $end);
	$this->data['total_received_stripe'] = $this->reports_model->getTotalReceivedStripeAmount($start, $end);
	$this->data['total_returned'] = $this->reports_model->getTotalReturnedAmount($start, $end);

	$this->data['start'] = $start_date;
	$this->data['end'] = $end_date;

	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('profit_loss')));
	$meta = array('page_title' => lang('profit_loss'), 'bc' => $bc);
	$this->page_construct('reports/profit_loss', $meta, $this->data);
}


}
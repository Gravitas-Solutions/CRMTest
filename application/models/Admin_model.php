<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

/*Users*/
	public function get_states_toll()
	{
		$date = date('Y-m-d');
		$this->db->select('state_name, agency_invoice.state_code, SUM(toll) AS state_toll');
		$this->db->from('agency_invoice');
		$this->db->join('states', 'states.state_code = agency_invoice.state_code, left');
		$this->db->where('exit_date_time LIKE', $date.'%');
		$this->db->group_by('state_code');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_users($trashed = 0)
	{
		$this->db->from('users');
		$this->db->where('deleted', $trashed);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_clients()
	{
		$this->db->from('clients');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function fetch_clients($limit, $offset, $status) 
	{
		$this->db->select('clients.client_id as id, organization, logo');
      	$this->db->from('clients');
        if($status == 1){$this->db->where('client_status', 1);}
        $this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}
	
	public function get_clients_id($client)
	{
		$this->db->select('client_id');
		$this->db->from('clients');
		$this->db->where('organization', $client);
		//$this->db->where('client_status', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_clients_list(){
		return $this->db->select('client_id as id, org_email as email, organization')->from('clients')->where('client_status', 1)->get()->result();
	}

	public function client_profile($client_id)
	{
		$this->db->select('clients.client_id as id, org_email, address, email, clients.phone as company_phone, organization, client_status as status, demo_acc, contacts.phone as contact_phone, first_name, last_name, title, active, logo, client_categories.category_id as category_id, category_name');
		$this->db->from('clients');
		$this->db->join('contacts', 'clients.client_id = contacts.client_id');
		$this->db->join('users', 'contacts.user_id = users.id');
		$this->db->join('client_categories', 'clients.category_id = client_categories.category_id');
		return $this->db->where('clients.client_id', $client_id)->where('contacts.default_user', 1)->get()->row();
	}

	public function save_client($data)
	{
		$this->db->insert('clients', $data);
		return $this->db->insert_id();
	}
	/*Invoices*/
	public function client_invoice($data)
	{
		$this->db->insert('client_invoice', $data);
		return $this->db->insert_id();
	}

	public function update_client_invoice($where, $data)
	{
		$this->db->update('client_invoice', $data, $where);
		return $this->db->affected_rows();
	}
	
    public function get_invoices()
    {
        
        $this->db->from('client_invoice');
        $this->db->join('departments', 'departments.dept_id = client_invoice.dept');        
		$this->db->join('clients', 'client_invoice.client_name = clients.organization');
		$this->db->where('clients.client_status', 1);
        $query = $this->db->get();
        return $query->result();
    } 

    public function get_invoice_month($id)
    {
        $this->db->from('invoice_month');
         $this->db->join('departments', 'departments.dept_id = invoice_month.dept_id');
        $this->db->where('invoice_month.invoice_month_id', $id);
        $query = $this->db->get();
        return $query->row();
    }  
    
    public function client_invoice_month($client, $dept_id)
    {
        $this->db->from('invoice_month');
        $this->db->where('client_name LIKE', '%'.$client.'%');
        $this->db->where('dept_id', $dept_id);
        $query = $this->db->get();
        return $query->row();
    }  

    public function get_invoices_month()
    {
        $this->db->from('invoice_month');
        $this->db->join('departments', 'departments.dept_id = invoice_month.dept_id');
        $this->db->join('clients', 'invoice_month.client_name = clients.organization');
		$this->db->where('clients.client_status', 1);
        $query = $this->db->get();
        return $query->result();
    } 

    public function current_month_invoice($org, $month)
	{
		$this->db->select('SUM(invoice_amount) AS invoice_amount');
		$this->db->from('client_invoice');
		$this->db->where('client_name LIKE', '%'.$org.'%');
		$this->db->where('invoice_date LIKE', $month.'%');
		$query = $this->db->get();
		return $query->row()->invoice_amount;
	}  

    public function get_invoice($id)
    {        
        $this->db->from('client_invoice');
        $this->db->join('departments', 'departments.dept_id = client_invoice.dept');
        $this->db->where('invoice_id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function delete_invoice($id)
	{
		$this->db->where('invoice_id', $id);
		$this->db->delete('client_invoice');
	}

	public function client_dump($client_dump)
	{
		$this->db->insert('excel_dump', $client_dump);
		return $this->db->insert_id();
	}	
	public function vehicle_dump($vehicle_dump)
	{
		$this->db->insert('vehicle_excel_dump', $vehicle_dump);
		return $this->db->insert_id();
	}

	public function save_client_contact($data)
	{
		$this->db->insert('contacts', $data);
		return $this->db->insert_id();
	}

	public function update_client($where, $data)
	{
		$this->db->update('clients', $data, $where);
		return $this->db->affected_rows();
	}

	public function update_client_contact($where, $data)
	{
		$this->db->update('contacts', $data, $where);
		return $this->db->affected_rows();
	}

	public function get_departments($id)
	{
		$this->db->select('dept_id, departments.logo AS logo, clients.logo AS client_logo, dept_name');
		$this->db->from('departments');
		$this->db->join('clients', 'departments.client_id = clients.client_id');
		$this->db->where('departments.client_id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_client_departments($id, $has_contact, $has_category)
	{
		if ($has_contact && $has_category) {
			$this->db->select('dept_id, dept_email as org_email, dept_address as address, email, dept_phone as company_phone, dept_name as organization, dept_status as status, contacts.phone as contact_phone, first_name, last_name, title, active, departments.logo as logo, departments.category_id as category_id, category_name');
			$this->db->from('departments');
			$this->db->join('contacts', 'departments.dept_id = contacts.department_id');
			$this->db->join('users', 'contacts.user_id = users.id');
			$this->db->join('client_categories', 'departments.category_id = client_categories.category_id');
			return $this->db->where('departments.dept_id', $id)->get()->row();
		} else if($has_contact && !$has_category) {
			$this->db->select('dept_id, dept_email as org_email, dept_address as address, email, dept_phone as company_phone, dept_name as organization, dept_status as status, contacts.phone as contact_phone, first_name, last_name, title, active, departments.logo as logo');
			$this->db->from('departments');
			$this->db->join('contacts', 'departments.dept_id = contacts.department_id');
			$this->db->join('users', 'contacts.user_id = users.id');
			return $this->db->where('departments.dept_id', $id)->get()->row();
		}else{
			$this->db->select('dept_id, dept_email as org_email, dept_address as address, dept_phone as company_phone, dept_name as organization, dept_status as status, logo');
			$this->db->from('departments');
			return $this->db->where('departments.dept_id', $id)->get()->row();
		}	
	}

	

	public function get_departments_paginator($limit, $offset, $id) 
	{
		$this->db->select('dept_id, departments.logo AS logo, clients.logo AS client_logo, dept_name');
      	$this->db->from('departments');
      	$this->db->join('clients', 'departments.client_id = clients.client_id');
        $this->db->where('departments.client_id', $id);
        $this->db->limit($limit, $offset);
		return $this->db->get()->result();
	}

	public function get_sub_departments($id)
	{
		$this->db->select('sub_dept_id, sub_dept_name');
		$this->db->from('sub_departments');
		$this->db->where('dept_id', $id);
		$query = $this->db->get();
		return $query->result();
	}	

	public function dept_by_id($id)
	{
		$this->db->select('dept_id, logo, dept_name');
		$this->db->from('departments');
		$this->db->where('dept_id', $id);
		$query = $this->db->get();
		if ($query->row()->logo) {
			return $query->row();
		} else {
			$this->db->select('dept_id, clients.logo, dept_name');
			$this->db->from('departments');
			$this->db->join('clients', 'departments.client_id = clients.client_id');
			$this->db->where('dept_id', $id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_sub_dept($id){
		return $this->db->get_where('sub_departments', ['sub_dept_id' => $id])->row();
	}

	public function save_dept($data)
	{
		$this->db->insert('departments', $data);
		return $this->db->insert_id();
	}
	public function save_sub_dept($data)
	{
		$this->db->insert('sub_departments', $data);
		return $this->db->insert_id();
	}

	public function update_dept($where, $data)
	{
		$this->db->update('departments', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_dept($id)
	{
		return ($this->db->delete('departments', ['dept_id' => $id])) ? true : false;		
	}

	public function update_sub_dept($where, $data)
	{
		$this->db->update('sub_departments', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_sub_dept($sub_dept_id)
	{
		return ($this->db->delete('sub_departments', ['sub_dept_id' => $sub_dept_id])) ? true : false;		
	}

	public function get_signups()
	{
		$this->db->from('signups');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_signup_id($id)
	{
		$this->db->from('signups');
		$this->db->where('signup_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function sign_up($data)
	{
		$this->db->insert('signups', $data);
		return $this->db->insert_id();
	}

	public function update_signup($where, $data)
	{
		$this->db->update('signups', $data, $where);
		return $this->db->affected_rows();
	}

/*Values*/
	public function get_values($date)
	{
		$next_day = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		$this->db->select('create_date, SUM(toll_amount) AS toll_amount');
		$this->db->from('agency_invoice');
		$this->db->where('create_date >=', $date);
		$this->db->where('create_date <', $next_day);
		$this->db->group_by('create_date');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_member_values($member, $date)
	{
		$next_day = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		$this->db->select('create_date, SUM(toll_amount) AS toll_amount');
		$this->db->from('agency_invoice');
		$this->db->where('create_date >=', $date);
		$this->db->where('create_date <', $next_day);
		$this->db->where('member_id', $member);
		$this->db->group_by('create_date');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_member_values_by_vehicle($member, $date)
	{
		$next_day = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		$this->db->select('license_plate, SUM(toll_amount) AS toll_amount');
		$this->db->from('agency_invoice');
		$this->db->join('vehicles', 'vehicles.vehicle_id = agency_invoice.vehicle_id');
		$this->db->where('agency_invoice.create_date >=', $date);
		$this->db->where('agency_invoice.create_date <', $next_day);
		$this->db->where('agency_invoice.member_id', $member);
		$this->db->group_by('agency_invoice.vehicle_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function excel_dumps()
	{
		$y = date('Y-m');
		$this->db->select('excel_dump_id, client_name, uploaded_date, date_for, uploaded_by, total_row, filename, dept_name, is_deleted, account_id');
		$this->db->from('excel_dump');
		$this->db->join('clients', 'excel_dump.client_name = clients.organization');
		$this->db->join('departments', 'excel_dump.dept_id = departments.dept_id', 'left');
		$this->db->where('excel_dump.uploaded_date LIKE ', $y.'%');
		$this->db->where('clients.client_status', 1);
		//$this->db->limit(200);
		$this->db->order_by('uploaded_date', 'desc');
		$query = $this->db->get();
		return $query->result();
	}	
	public function vehicle_excel_dumps()
	{
		$this->db->select('vehicle_excel_dump_id, draft, client_name, dept_name, sub_dept_name, uploaded_date, uploaded_by, total_row, filename');
		$this->db->from('vehicle_excel_dump');
		$this->db->join('departments', 'departments.dept_id = vehicle_excel_dump.vehicle_dept', 'left');
		$this->db->join('sub_departments', 'sub_departments.sub_dept_id = vehicle_excel_dump.vehicle_sub_dept', 'left');
		$query = $this->db->get();
		return $query->result();
	}	
	public function get_excel_dump($excel_dump_id)
	{
		$this->db->from('excel_dump');
		$this->db->where('excel_dump_id', $excel_dump_id);
		$query = $this->db->get()->row();
		return $query;
	}	

	public function get_vehicle_excel_dump($vehicle_excel_dump_id)
	{
		$this->db->from('vehicle_excel_dump');
		$this->db->where('vehicle_excel_dump_id', $vehicle_excel_dump_id);
		$query = $this->db->get()->row();
		return $query;
	}

	public function vehicle_excel_dumps_delete($last_id, $first_id)
	{
		$this->db->where('vehicle_id >=', $first_id);
		$this->db->where('vehicle_id <=', $last_id);
		$this->db->delete('vehicles');
	}	
	public function excel_dumps_delete($last_id, $first_id, $client_table)
	{
		$this->db->where('invoice_id >=', $first_id);
		$this->db->where('invoice_id <=', $last_id);
		$this->db->delete($client_table);
	}	

	public function check_dumper($date_for, $client, $dept){
		$this->db->from('excel_dump');
		$this->db->where('date_for', $date_for.'%');
		$this->db->where('client_name', $client);
		$this->db->where('is_deleted', 0);
		if ($dept != 0) {
			$this->db->where('dept_id', $dept);
		}
		return $this->db->get()->result();
	}

	public function range_check_dumper($date_from, $date_to, $client, $dept){
		$this->db->from('excel_dump');
		$this->db->where('DATE(date_for) BETWEEN "'.$date_from.'%'.'" AND "'.$date_to.'%'.'"');
		// $this->db->where('date_for', $date_for.'%');
		$this->db->where('client_name', $client);
		$this->db->where('is_deleted', 0);
		if ($dept != 0) {
			$this->db->where('dept_id', $dept);
		}
		return $this->db->get()->result();
	}

	public function delete_previous_transactions($date_for, $client, $dept){
		$this->db->from($client.'_invoice');
		$this->db->where('date_for', $date_for.'%');
		if ($dept != 0) {
			$this->db->where('dept_id', $dept);
		}
		return $this->db->delete();
	}

	public function undo_dumps($date_for, $client, $dept, $deleted){
		$this->db->where('date_for', $date_for);
		$this->db->where('client_name', $client);
		if ($dept != 0) {
			$this->db->where('dept_id', $dept);
		}
		$this->db->update('excel_dump', $deleted);
	}

	public function excel_listing_delete($excel_dump_id)
	{
		$this->db->where('excel_dump_id', $excel_dump_id);
		$this->db->delete('excel_dump');
	}

	
	public function account_id_delete($account_id)
	{
		$this->db->where('account_id', $account_id);
		$this->db->delete('accounts');
	}	

	public function vehicle_excel_listing_delete($vehicle_excel_dump_id)
	{
		$this->db->where('vehicle_excel_dump_id', $vehicle_excel_dump_id);
		$this->db->delete('vehicle_excel_dump');
	}

	public function get_last_inserted_row($client_table){
		return $this->db->from($client_table)
					->limit(1)
					->order_by('invoice_id', 'DESC')
					->get()->row();
	}	
	public function get_last_inserted_vehicle($client){
	    $this->db->from('vehicles');
	    $this->db->where('client_id', $client);
	    $this->db->order_by('vehicle_id', 'DESC');
	    $query = $this->db->get();
	    return $query->row();
	}

/*Vehicles*/	
	public function get_vehicles()
	{
		$this->db->from('vehicles');
		$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('agencies', 'agencies.state_id = vehicles.agency_id');
		$this->db->join('states', 'states.state_id = agencies.state_id');
		$this->db->where('clients.client_status', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function vehicle_by_id($id)
	{
		$this->db->from('vehicles');
		$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		$this->db->where('clients.client_status', 1);
		$this->db->where('vehicle_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function vehicles_by_status($status)
	{
		$this->db->from('vehicles');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		$this->db->where('clients.client_status', 1);
		$this->db->where('vehicle_status', $status);
		$query = $this->db->get();
		return $query->result();
	}

	public function client_vehicles($client, $dept)
	{
		$this->db->from('vehicles');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		$this->db->where('clients.client_status', 1);
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
		if($client != 0){$this->db->where('vehicles.client_id', $client);}
		$query = $this->db->get();
		return $query->result();
	}

	public function dept_vehicles($dept)
	{
		$this->db->select('license_plate');
		$this->db->from('vehicles');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		$this->db->where('departments.dept_id', $dept);
		$query = $this->db->get();
		return $query->result();
	}

	public function save_vehicle($data)
	{
		$this->db->insert('vehicles', $data);
		return $this->db->insert_id();
	}

	public function update_vehicle($where, $data)
	{
		$this->db->update('vehicles', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_vehicle($id)
	{
		$this->db->where('vehicle_id', $id);
		$this->db->delete('vehicles');
	}

/*Roads*/
	public function get_roads_by_client($org, $month)
	{
		if ($org == 'amazon') {$this->db->select('exit_name, SUM(toll) AS toll');
		}else{$this->db->select('exit_location, SUM(toll) AS toll');}
		$this->db->from($org.'_invoice');
		if($month != 0){$this->db->where('dump_date LIKE', $month.'%');}
		if ($org == 'amazon') {$this->db->group_by('exit_name');
		}else{$this->db->group_by('exit_location');}	
		$query = $this->db->get();
		return $query->result();
	}

	public function get_roads_by_client_dept($org, $dept = 0)
	{
		if ($org == 'amazon') {$this->db->select('exit_name, SUM(toll) AS toll');
		}else{$this->db->select('exit_location, SUM(toll) AS toll');}
		$this->db->from($org.'_invoice');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if ($org == 'amazon') {$this->db->group_by('exit_name');
		}else{$this->db->group_by('exit_location');}	
		$query = $this->db->get();
		return $query->result();
	}

	

	public function get_tolls_by_agency($org, $month)
	{
		$this->db->select('agency_name, SUM(toll) AS toll');
		$this->db->from($org.'_invoice');
		$this->db->where('dump_date LIKE', $month.'%');
		$this->db->group_by('agency_name');	
		$query = $this->db->get();
		return $query->result();
	}

	public function get_tolls_by_agency_dept($org, $dept = 0)
	{
		$this->db->select('agency_name, SUM(toll) AS toll');
		$this->db->from($org.'_invoice');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		$this->db->group_by('agency_name');	
		$query = $this->db->get();
		return $query->result();
	}

	

	public function get_tolls_by_state($org, $month)
	{
		$this->db->select('agency_name, SUM(toll) AS toll');
		$this->db->from($org.'_invoice');
		$this->db->where('dump_date LIKE', $month.'%');
		$this->db->group_by('agency_name');	
		$query = $this->db->get();
		return $query->result();
	}
	public function get_agency_state($agency)
	{
		$this->db->select('state_name');
		$this->db->from('states');
		$this->db->join('agencies', 'agencies.state_id = states.state_id');
		$this->db->where('agency_name', $agency);
		$query = $this->db->get();
		return $query->row();
	}
/*States*/	
	public function get_states()
	{
		$this->db->from('states');
		$query = $this->db->get();
		return $query->result();
	}

	public function state_by_id($id)
	{
		$this->db->from('states');
		$this->db->where('state_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function save_state($data)
	{
		$this->db->insert('states', $data);
		return $this->db->insert_id();
	}

	public function update_state($where, $data)
	{
		$this->db->update('states', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_state($id)
	{
		return $this->db->delete('states', ['state_id' => $id]) ? true : false;
	}

/*Agencies*/
	public function get_agency_tolls()
	{
		$date = date('Y-m-d');
		$this->db->select('agency_name, SUM(toll) AS agency_toll');
		$this->db->from('agency_invoice');
		$this->db->where('exit_date_time LIKE', $date.'%');
		$this->db->group_by('agency_name');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_agencies()
	{
		$this->db->from('agencies');
		$this->db->join('states', 'states.state_id = agencies.state_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function agency_by_id($id)
	{
		$this->db->from('agencies');
		$this->db->where('agency_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function save_agency($data)
	{
		$this->db->insert('agencies', $data);
		return $this->db->insert_id();
	}

	public function update_agency($where, $data)
	{
		$this->db->update('agencies', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_agency($id)
	{
		$this->db->where('agency_id', $id);
		$this->db->delete('agencies');
	}

/*Tags*/
	public function get_tags()
	{
		$this->db->from('tagtypes');
		$query = $this->db->get();
		return $query->result();
	}

	public function tag_by_id($id)
	{
		$this->db->from('tagtypes');
		$this->db->where('tag_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function tag_by_name($name)
	{
		$this->db->from('tagtypes');
		$this->db->where('tag_type', $name);
		$query = $this->db->get();
		return $query->row()->tag_id;
	}

	public function save_tag($data)
	{
		$this->db->insert('tagtypes', $data);
		return $this->db->insert_id();
	}

	public function update_tag($where, $data)
	{
		$this->db->update('tagtypes', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_tag($id)
	{
		return ($this->db->delete('tagtypes', ['tag_id' => $id])) ? true : false;
	}

	public function departments($client)
	{
		$this->db->select('dept_id, dept_name, group_id');
		$this->db->from('departments');
		$this->db->where('client_id', $client);
		$query = $this->db->get();
		return $query->result();
	}	
	public function client_sub_departments($dept)
	{
		$this->db->select('sub_dept_id, sub_dept_name');
		$this->db->from('sub_departments');
		$this->db->where('dept_id', $dept);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_citations()
	{
		$this->db->from('citations');		
		$this->db->join('clients', 'citations.organization = clients.organization');
		$this->db->join('departments', 'citations.dept_id = departments.dept_id', 'left');
		$this->db->where('clients.client_status', 1);
		$query = $this->db->get();
		return $query->result();
	}	

	public function get_account_balance()
	{
		$this->db->select('organization, dept_name, balance, accounts_balance.dept_id, account_balance_id');
		$this->db->from('accounts_balance');
		$this->db->join('departments', 'departments.dept_id = accounts_balance.dept_id');
		$this->db->join('clients', 'clients.client_id = departments.client_id');
		$this->db->where('clients.client_status', 1);
		return $this->db->get()->result();
	}	
	public function get_account_detail($id){
		$this->db->select('balance, accounts_balance.dept_id as dept_id, dept_name, account_balance_id, clients.organization as organization');
		$this->db->from('accounts_balance');
		$this->db->join('departments', 'departments.dept_id = accounts_balance.dept_id');
		$this->db->join('clients', 'clients.client_id = departments.client_id');
		$this->db->where('accounts_balance.dept_id', $id);
		$query =  $this->db->get()->row();
		return $query;
	}	
	public function get_client_account_detail($id){
				return $this->db->from('accounts')
					->limit(1)
					->order_by('account_id', 'DESC')
					->where('dept_id', $id)
					->get()->row();
	}
	public function get_data_account_id($account_id)
	{
		$this->db->from('accounts');
		$this->db->where('account_id', $account_id);
		$query =  $this->db->get()->row();
		return $query;
	}	
	public function get_data_account_balance_id($dept_id)
	{
		$this->db->from('accounts_balance');
		$this->db->where('dept_id', $dept_id);
		$query =  $this->db->get()->row();
		return $query;
	}

	public function view_accounts_details($month, $dept_id)
	{
		$this->db->select('account_id, amount, transaction_date, source, organization, accounts.dept_id');
		$this->db->from('accounts_balance');
		$this->db->join('departments', 'departments.dept_id = accounts_balance.dept_id');
		$this->db->join('clients', 'clients.client_id = departments.client_id');
		$this->db->join('accounts', 'accounts_balance.dept_id = accounts.dept_id');
		$this->db->where('transaction_date LIKE', $month.'%');
		$this->db->where('accounts.dept_id', $dept_id);	
		$query = $this->db->get();
		return $query->result();
	}

	public function add_balance($data)
	{
		$this->db->insert('accounts', $data);
		return $this->db->insert_id();
	}
	public function add_client_balance($data)
	{
		$this->db->insert('accounts_balance', $data);
		return $this->db->insert_id();
	}	

	public function add_mounth_invoice_amount($data)
	{
		$this->db->insert('invoice_month', $data);
		return $this->db->insert_id();
	} 

	public function update_account_balance($data, $where)
	{
		$this->db->update('accounts_balance', $data, $where);
		return $this->db->affected_rows();
	}

	public function update_mounth_invoice_amount($data, $where)
	{
		$this->db->update('invoice_month', $data, $where);
		return $this->db->affected_rows();
	}		

	public function get_sum_last_dump($client, $first_id, $last_id)
	{
		$this->db->select('SUM(toll) AS transactions');
		$this->db->from($client.'_invoice');
		$this->db->where('invoice_id >= ', $first_id);
		$this->db->where('invoice_id <= ', $last_id);
		$query = $this->db->get()->row()->transactions;
		return $query;
	}	
	public function get_citation($id)
	{
		$this->db->from('citations');
		$this->db->where('citation_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function save_citation($data)
	{
		$this->db->insert('citations', $data);
		return $this->db->insert_id();
	}

	public function update_citation($id, $data)
	{
		$this->db->where('citation_id', $id);
		$this->db->update('citations', $data);
		return $this->db->affected_rows();
	}

	/*Client users queries*/
	function get_client_users($client)
	{
		$this->db->from('clients');
		$this->db->join('contacts', 'clients.client_id = contacts.client_id');
		$this->db->join('users', 'users.id = contacts.user_id');
		$this->db->join('departments', 'contacts.department_id = departments.dept_id');
		$this->db->where('contacts.client_id', $client);
		return $this->db->get()->result();
	}	
	

	function get_dept_users($dept)
	{
		$this->db->select('first_name, last_name, sub_dept_name, default_user, can_update, id, active, modules, title, email, phone');
		$this->db->from('contacts');
		$this->db->join('departments', 'departments.dept_id = contacts.department_id');
		$this->db->join('users', 'users.id = contacts.user_id');
		$this->db->join('sub_departments', 'sub_departments.sub_dept_id = contacts.sub_dept_id', 'left');
		$this->db->where('departments.dept_id', $dept);
		return $this->db->get()->result();
	}

	

	function get_client_user($client)
	{
		$this->db->select('first_name, last_name, users.email as user_email, contacts.phone as user_phone, title, users.id as user_id, department_id, modules, active, group_id');
		$this->db->from('users');
		$this->db->join('contacts', 'users.id = contacts.user_id', 'left');
		$this->db->where('users.id', $client);
		return $this->db->get()->row();
	}

	public function get_dump_stats()
	{
		$current_month = date('Y-m', now());
		$this->db->select('uploaded_date, uploaded_by, email, count(*) as count');
		$this->db->from('excel_dump');
		$this->db->join('users', 'excel_dump.uploaded_by = users.id');
		$this->db->where('DATE(uploaded_date) LIKE', $current_month.'%');
		$this->db->group_by('uploaded_by, DATE(uploaded_date)');
		$this->db->order_by('DATE(uploaded_date)', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_dump_stats_totals()
	{
		$current_month = date('Y-m', now());
		$this->db->select('email, count(*) as total');
		$this->db->from('excel_dump');
		$this->db->join('users', 'excel_dump.uploaded_by = users.id');
		$this->db->where('DATE(uploaded_date) LIKE', $current_month.'%');
		$this->db->group_by('email');
		$query = $this->db->get();
		return $query->result();
	}

	public function add_group($data)
	{
		$this->db->insert('department_grouping', $data);
		return $this->db->insert_id();
	}

		public function dept_by_group_id($id)
	{
		$this->db->select('dept_id');
		$this->db->from('departments');
		$this->db->where('group_id', $id);
		return $this->db->get()->result();
		
	}

	public function delete_group($id)
	{
		return ($this->db->delete('department_grouping', ['group_id' => $id])) ? true : false;		
	}

	public function get_dept_grouping($id)
	{
		$this->db->select('group_id, group_name');
		$this->db->from('department_grouping');
		$this->db->where('client_id', $id);
		return  $this->db->get()->result();
	}	

	public function update_group($data, $group_id){
		$this->db->where('group_id', $group_id);
		$this->db->update('department_grouping', $data);
		return $this->db->affected_rows();
	}

	public function update_dept_group($data, $group_id){
		$this->db->where('group_id', $group_id);
		$this->db->update('departments', $data);
		return $this->db->affected_rows();
	}

	public function select_dept_group($data, $dept_id){
		$this->db->where('dept_id', $dept_id);
		$this->db->update('departments', $data);
		return $this->db->affected_rows();
	}

	function get_client_groups($client)
	{
		$this->db->from('department_grouping');
		$this->db->where('client_id', $client);
		return $this->db->get()->result();
	}
		/*Fulfilment*/
    public function get_fulfilment()
    {
        $this->db->from('fulfilment');
        $this->db->join('departments', 'departments.dept_id = fulfilment.dept_id');
        $this->db->join('clients', 'fulfilment.client_id = clients.client_id');
        $this->db->where('clients.client_status', 1);
        $query = $this->db->get();
        return $query->result();
    }
    public function update_fulfilment_status($id, $status, $processor)
	{
		$this->db->update('fulfilment', ['status' => $status, 'processor' => $processor], ['fulfilment_id' => $id]);
		return $this->db->affected_rows();
	}
		function allposts_count($status,$client,$dept)
    {   
        $this->db->from('vehicles');
        if($status != -2){$this->db->where('vehicle_status', $status);}
        if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
        if($client != 0){$this->db->where('vehicles.client_id', $client);}
        $query = $this->db->get();
    
        return $query->num_rows();  

    }
    
    function allposts($limit,$start,$col,$dir,$status,$client,$dept)
    {   
       $this->db->from('vehicles');
       	$this->db->limit($limit,$start);
        $this->db->order_by($col,$dir); 
        $this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		if($status != -2){$this->db->where('vehicle_status', $status);}
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
        if($client != 0){$this->db->where('vehicles.client_id', $client);}
		/*$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');*/
        $query = $this->db->get();
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function posts_search($limit,$start,$search,$col,$dir,$status,$client,$dept)
    {
       $this->db->from('vehicles');
        $this->db->like('license_plate',$search);
		$this->db->or_like('vehicle_id',$search);
		$this->db->or_like('color',$search);
		$this->db->or_like('make',$search);
		$this->db->or_like('model',$search);
		$this->db->or_like('unit',$search);
		$this->db->or_like('tolltag',$search);
		$this->db->or_like('vin_no',$search);
		$this->db->or_like('store',$search);
		$this->db->or_like('location',$search);
		$this->db->or_like('start_date',$search);
		$this->db->or_like('end_date',$search);
		$this->db->or_like('organization',$search);
		$this->db->or_like('dept_name',$search);
		$this->db->or_like('vehicle_status',$search);
        $this->db->limit($limit,$start);
        $this->db->order_by($col,$dir);
        $this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		if($status != -2){$this->db->where('vehicle_status', $status);}
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
        if($client != 0){$this->db->where('vehicles.client_id', $client);}
		// $this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		$query = $this->db->get();
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function posts_search_count($search,$status,$client,$dept)
    {
        $this->db->from('vehicles');
		$this->db->like('license_plate',$search);
		$this->db->or_like('vehicle_id',$search);
		$this->db->or_like('color',$search);
		$this->db->or_like('make',$search);
		$this->db->or_like('model',$search);
		$this->db->or_like('unit',$search);
		$this->db->or_like('tolltag',$search);
		$this->db->or_like('vin_no',$search);
		$this->db->or_like('store',$search);
		$this->db->or_like('location',$search);
		$this->db->or_like('start_date',$search);
		$this->db->or_like('end_date',$search);
		$this->db->or_like('organization',$search);
		$this->db->or_like('dept_name',$search);
		$this->db->or_like('vehicle_status',$search);
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		if($status != -2){$this->db->where('vehicle_status', $status);}
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
        if($client != 0){$this->db->where('vehicles.client_id', $client);}
		// $this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		$query = $this->db->get();
		return $query->num_rows();
    } 

    /*Toll Tags*/

	public function get_toll_tags()
	{
		$this->db->from('tag');
		$this->db->join('clients', 'clients.client_id = tag.client_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function delete_toll_tag($id)
	{
		$this->db->where('tag_id', $id);
		$this->db->delete('tag');
	}
	public function save_toll_tag($data)
	{
		$this->db->insert('tag', $data);
		return $this->db->insert_id();
	}
	public function toll_tag_by_id($id)
	{
		$this->db->from('tag');
		$this->db->where('tag_id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	public function update_toll_tag($where, $data)
	{
		$this->db->update('tag', $data, $where);
		return $this->db->affected_rows();
	}

	
	public function get_cards()
	{
		$this->db->from('card_info');
		$this->db->join('clients', 'clients.client_id = card_info.client_id');
		$this->db->join('card_type', 'card_type.card_type_id = card_info.card_type');
		$query = $this->db->get();
		return $query->result();
	}

	public function card_by_id($id)
	{
		$this->db->from('card_info');
		$this->db->where('card_info_id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function delete_card_info($id)
	{
		$this->db->where('card_info_id', $id);
		$this->db->delete('card_info');
	}
	public function update_card_info($where, $data)
	{
		$this->db->update('card_info', $data, $where);
		return $this->db->affected_rows();
	}

	public function save_card_info($data)
	{
		$this->db->insert('card_info', $data);
		return $this->db->insert_id();
	}
	public function get_card_type_list(){
		return $this->db->select('*')->from('card_type')->get()->result();
	}

	//weekly report

	public function get_week_list()
	{
		$this->db->from('weeks');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_report_type_list()
	{
		$this->db->from('report_types');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_reports()
	{
		$this->db->from('weekly_report');
		$this->db->join('weeks', 'weeks.week_id = weekly_report.week_id');
		$this->db->join('report_types', 'report_types.report_type_id = weekly_report.report_type_id');
		$this->db->join('clients', 'clients.client_id = weekly_report.client_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function save_weekly_report($data)
	{
		$this->db->insert('weekly_report', $data);
		return $this->db->insert_id();
	}

	public function update_weekly_report($where, $data)
	{
		$this->db->update('weekly_report', $data, $where);
		return $this->db->affected_rows();
	}
	public function delete_weekly_report($id)
	{
		$this->db->where('weekly_report_id', $id);
		$this->db->delete('weekly_report');
	}
	public function weekly_report_by_id($id)
	{
		$this->db->from('weekly_report');
		$this->db->where('weekly_report_id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	
}
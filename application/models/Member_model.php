<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model
{
	public function default_data($client)
	{
		$client_id = $this->db->get_where('clients', ['organization' => $client])->row()->client_id;
		$this->db->from('contacts');
		$this->db->where('client_id', $client_id);
		$this->db->where('default_user', 1);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	public function toll_tag()
	{
		$this->db->distinct(); 
		$this->db->from('tag');
		$this->db->join('clients', 'clients.client_id = tag.client_id');
		$this->db->where('tag.status', 0);
		$this->db->order_by('tag', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}
	public function update_toll_status($where, $data)
	{
		$this->db->update('tag', $data, $where);
		return $this->db->affected_rows();
	}

/*Vehicles*/
	public function member_vehicles($client)
	{
		$this->db->from('vehicles');
		$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		$this->db->where('client_id', $client);
		$this->db->where('draft', 0);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function client_vehicles($client, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('vehicles.dept_id ', $group_depts);
		}
		$this->db->from('vehicles');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('vehicles.sub_dept_id', $sub_dept);}
		$this->db->where('draft', 0);
		$this->db->where('vehicles.client_id', $client);
		$query = $this->db->get();
		return $query->result();
	}	

	public function vehicles_by_status($status, $client, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if ($status == 1) {
			$this->db->where_in('vehicle_status', ['0', '1', '2', '3']);
		} else {
			$this->db->where('vehicle_status', '-1');
		}
		$this->db->where('draft', 0);
		$this->db->where('client_id', $client);
		$query = $this->db->count_all_results('vehicles');
		return $query;
	}

	public function vehicle_by_id($id)
	{
		
		$exist = 0;
		if ($this->db->select('tolltag')->from('vehicles')->where('vehicle_id', $id)->get()->row()->tolltag) {
			$tolltag = $this->db->select('tolltag')->from('vehicles')->where('vehicle_id', $id)->get()->row()->tolltag;
			if ($this->db->select('tag')->from('tag')->where('tag', $tolltag)->count_all_results() > 0) {
				$record_exist = $this->db->select('tag')->from('tag')->where('tag', $tolltag)->count_all_results();
				$exist = $record_exist;
			}
		}
		($this->db->get_where('vehicles', ['vehicle_id' => $id])->row()->sub_dept_id) ? $sub_dept_exist = 1 : $sub_dept_exist = 0;
		$this->db->from('vehicles');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		if ($sub_dept_exist != 0) {
		$this->db->join('sub_departments', 'sub_departments.sub_dept_id = vehicles.sub_dept_id');
		}
		if ($exist != 0) {
			$this->db->join('tag', 'vehicles.tolltag = tag.tag');
		}
		$this->db->where('clients.client_status', 1);
		$this->db->where('vehicle_id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	public function update_vehicle($where, $data)
	{
		$this->db->update('vehicles', $data, $where);
		return $this->db->affected_rows();
	}

	public function account_balance($dept, $group_id)
	{
        if ($group_id != 0) {
        	$depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
			$group_depts =  array(); 
			foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
        } else {
        	$this->db->where('dept_id', $dept);
        }
        $this->db->select('sum(balance) as balance');
		$this->db->from('accounts_balance');
        $query = $this->db->get();
        return $query->row()->balance;
	}
	
	public function get_month($org, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		$this->db->select_max('date_for', 'last_dump');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
		$query = $this->db->get($org.'_invoice');
		return $query->row()->last_dump;
	}

	public function top_vehicles($org, $month, $client, $dept, $sub_dept, $group_id)
	{
		//$this->db->select($org.'_invoice.license_plate, tag_type, state_code, SUM(toll) AS amount');

		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		$this->db->select($org.'_invoice.license_plate, state_code, axles, SUM(toll) AS amount');
		$this->db->from($org.'_invoice');
		$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate');
		//$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		$this->db->where(''.$org.'_invoice.date_for LIKE', $month.'%');
		$this->db->where('client_id', $client);
		$this->db->where('draft', 0);
		$this->db->where_in('vehicle_status', ['0', '1', '2', '3']);
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->group_by($org.'_invoice.license_plate, state_code, axles');
		$this->db->order_by('amount', 'DESC');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}

	public function vehicle_plates($client, $month)
	{
		$this->db->select('license_plate, SUM(toll_amount) AS amount');
		$this->db->from('invoices');
		$this->db->join('vehicles', 'vehicles.vehicle_id = invoices.vehicle_id');
		$this->db->where('vehicles.member_id', $client);
		$this->db->where_in('vehicle_status', ['0', '1', '2', '3']);
		$this->db->where('draft', 0);
		$this->db->where('create_date LIKE', $month.'%');
		$this->db->group_by('vehicles.license_plate');
		$query = $this->db->get();
		return $query->result();
	}

/*Invoices*/
    public function member_invoices($org, $dept, $group_id)
    {
        if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
        $this->db->from('client_invoice');
        $this->db->join('departments', 'departments.dept_id = client_invoice.dept');
        $this->db->where('client_name LIKE', '%'.$org.'%');
        if($dept != 0){$this->db->where('dept', $dept);}
        $query = $this->db->get();
        return $query->result();
    }

	public function get_invoices($invoice_id){

	    $this->db->from('invoices');
        $this->db->where('invoice_id', $invoice_id);
        $query = $this->db->get();
        return $query->result();
     
 	}

	public function transactions($org, $dept, $sub_dept, $group_id)
	{
		$last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, '.$org.'_invoice.license_plate, tolltag, invoice_id, state_code, agency_name, exit_date_time, date_for, toll, '.$org.'_invoice.unit'.(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		} else {
			$this->db->select($org.'_invoice.license_plate, state_code, invoice_id, agency_name, vin_no, tolltag, exit_date_time, date_for, toll, exit_lane, exit_location, '.$org.'_invoice.unit' .(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		}
		
		$this->db->from($org.'_invoice');
		$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$this->db->where($org.'_invoice.date_for like ', $last_dump.'%');
		$this->db->where('dispute', 0);
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->limit(1024);
		$query = $this->db->get();
		return $query->result();
	}	
	public function date_date_transactions($org, $dept, $sub_dept, $group_id, $post_month)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, '.$org.'_invoice.license_plate, tolltag, invoice_id, state_code, agency_name, exit_date_time, date_for, toll, '.$org.'_invoice.unit'.(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		} else {
			$this->db->select($org.'_invoice.license_plate, state_code, invoice_id, agency_name, vin_no, tolltag, exit_date_time, date_for, toll, exit_lane, exit_location, '.$org.'_invoice.unit'.(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		}
		
		$this->db->from($org.'_invoice');
		$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$this->db->where($org.'_invoice.date_for like ', $post_month.'%');
		$this->db->where('dispute', 0);
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$query = $this->db->get();
		return $query->result();
	}
	
	public function disputes($org, $dept, $sub_dept, $group_id)
	{
		$last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, license_plate, invoice_id, state_code, agency_name, exit_date_time, date_for, toll, unit'.(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		} else {
			$this->db->select('license_plate, state_code, invoice_id, agency_name, exit_date_time, date_for, toll, exit_lane, exit_location, unit'.(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		}
		
		$this->db->from($org.'_invoice');
		$this->db->where($org.'_invoice.date_for like ', $last_dump.'%');
		$this->db->where('dispute', 1);
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->limit(1024);
		$query = $this->db->get();
		return $query->result();
	}
	public function update_dispute_status($data, $org, $where)
	{
		$this->db->update($org.'_invoice', $data, $where);
		return $this->db->affected_rows();
	}
	public function resolve_dispute_status($data, $org, $where)
	{
		$this->db->update($org.'_invoice', $data, $where);
		return $this->db->affected_rows();
	}
	public function date_range_transactions($org, $dept, $sub_dept, $from, $to, $group_id, $date_type)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('invoice_id, exit_name, '.$org.'_invoice.license_plate, tolltag, state_code, agency_name, exit_date_time, date_for, toll, '.$org.'_invoice.unit');
		} else {
			$this->db->select($org.'_invoice.license_plate, invoice_id, state_code, agency_name, tolltag, vin_no, exit_date_time, date_for, toll, exit_lane, exit_location, '.$org.'_invoice.unit'.(($org == 'clay_cooley_dealerships') ? ', processed' : ''));
		}
		$this->db->from($org.'_invoice');
		$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
		$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		$this->db->where('dispute', 0);
		/*$this->db->where('exit_date_time >=', $from.'%');
		$this->db->where('exit_date_time <=', $to.'%');*/
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$query = $this->db->get();
		return $query->result();
	}

	function process_tolls($tolls, $client){
		$this->db->where_in('invoice_id', $tolls);
		$this->db->update($client.'_invoice', ['processed' => 1]);
		return $this->db->affected_rows() ?? false;
	}

	public function toll_transactions($org, $month, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}	
		$this->db->select('SUM(toll) AS transactions');
		$this->db->where('date_for LIKE', $month.'%');	
		if($org == 'clay_cooley_dealerships'){$this->db->where('processed !=', 1);}
		$query = $this->db->get($org.'_invoice');
		return $query->row()->transactions;
	}
	
	public function total_transactions($org, $month, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
		$this->db->where('date_for LIKE', $month.'%');
		$query = $this->db->count_all_results($org.'_invoice');
		return $query;
	}

	public function current_month_invoice($org, $month, $dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		$this->db->select('month, invoice_amount');
		$this->db->from('invoice_month');
		$this->db->where('client_name LIKE', '%'.$org.'%');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		$query = $this->db->get();
		return $query->row();
	}

	public function daily_toll_transactions($org, $month, $day, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		$this->db->select('SUM(toll) AS toll');
		$this->db->from($org.'_invoice');
		$this->db->where('date_for LIKE', $month.'-'.$day.'%');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
		$query = $this->db->get();
		return (is_null($query->row()->toll)) ? 0.00: $query->row()->toll;
	}

	public function agency_tolls($org, $month, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		$this->db->select('agency_name, SUM(toll) as toll');
		$this->db->from($org.'_invoice');
		$this->db->where('date_for LIKE', $month.'%');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
		if($org == 'clay_cooley_dealerships'){$this->db->where('processed !=', 1);}
		$this->db->group_by('agency_name');
		$query = $this->db->get();
		return $query->result();
	}

	public function monthly_toll($org, $year, $month, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		$this->db->select('SUM(toll) as toll');
		$this->db->from($org.'_invoice');
		$this->db->where('date_for LIKE', $year.'-'.$month.'%');
		if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
		if($org == 'clay_cooley_dealerships'){$this->db->where('processed !=', 1);}
		$query = $this->db->get();
		return (is_null($query->row()->toll)) ? 0.00: $query->row()->toll;
	}

	public  function member_logo($client, $dept)
	{
		if($dept != 0){
		$this->db->select('logo');
		$this->db->from('departments');
		$this->db->where('dept_id', $dept);
		$query = $this->db->get();

		if ($query->row()->logo) {
			return $query->row()->logo;
		} else {
			return $this->db->get_where('clients', ['client_id' => $client])->row()->logo;
		}
	}
		return $this->db->get_where('clients', ['client_id' => $client])->row()->logo;

	}

	public function client_citations($client, $dept, $sub_dept, $group_id)
	{
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('citations.dept_id ', $group_depts);
		}
		$this->db->select('citations.license_plate, license_plate_state, citation_type, violation_no, violation_date, violation_state, paid_date, citation_amount');
		$this->db->from('citations');
		$this->db->join('vehicles', 'citations.license_plate = vehicles.license_plate', 'left');
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('vehicles.sub_dept_id', $sub_dept);}
		$this->db->where('citations.organization', $client);
		$query = $this->db->get();
		return $query->result();
	}

	public function member_searches_logs($client)
	{
		$this->db->select('member_searches_logs.*, first_name, last_name, dept_name', FALSE);
		$this->db->from('member_searches_logs');
		$this->db->join('users', 'member_searches_logs.user = users.id', 'left');
		$this->db->join('contacts', 'member_searches_logs.user = contacts.user_id', 'left');
		$this->db->join('departments', 'contacts.department_id = departments.dept_id', 'left');
		$this->db->join('clients', 'contacts.client_id = clients.client_id', 'left');
		$this->db->where('member_searches_logs.client_id', $client);
		return $this->db->get()->result();
	}

	public function delete_logs($client)
	{
		return $this->db->delete('member_searches_logs', ['client_id' => $client]) ? true : false;
	}
	public function upload_fulfilment($data)
	{
		$this->db->insert('fulfilment', $data);
		return $this->db->insert_id();
	}

	/*Invoices*/
    public function member_fulfilment($org, $dept, $group_id)
    {
        if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
        $this->db->from('fulfilment');
        $this->db->join('departments', 'departments.dept_id = fulfilment.dept_id');
        $this->db->where('fulfilment.client_id', $org);
        if($dept != 0){$this->db->where('fulfilment.dept_id', $dept);}
        $query = $this->db->get();
        return $query->result();
    }

    public function get_fulfilments($id)
	{
		$this->db->from('fulfilment');
		 $this->db->join('departments', 'departments.dept_id = fulfilment.dept_id');
        $this->db->join('clients', 'fulfilment.client_id = clients.client_id');
		$this->db->where('clients.client_status', 1);
		$this->db->where('fulfilment_id', $id);
		$query = $this->db->get();
		return $query->row();
	}
	public function delete_fulfilments($id)
	{
		return $this->db->delete('fulfilment', ['fulfilment_id' => $id]) ? true : false;
	}
	public function update_fulfilments($where, $data)
	{
		$this->db->update('fulfilment', $data, $where);
		return $this->db->affected_rows();
	}

		function allposts_count($client,$dept,$sub_dept,$group_id)
    {   
        if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('dept_id ', $group_depts);
		}
		$this->db->from('vehicles');
		$this->db->where('draft', 0);
        $this->db->where('client_id', $client);
        if($dept != 0){$this->db->where('dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('sub_dept_id', $sub_dept);}
        $query = $this->db->get();
    
        return $query->num_rows();  

    }
    
    function allposts($limit,$start,$col,$dir,$client,$dept,$sub_dept,$group_id)
    {   
       if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('vehicles.dept_id ', $group_depts);
		}
		$this->db->from('vehicles');
       	$this->db->limit($limit,$start);
        $this->db->order_by($col,$dir); 
        $this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id');
		$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('vehicles.sub_dept_id', $sub_dept);}
		$this->db->where('draft', 0);
		$this->db->where('vehicles.client_id', $client);
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
   
    function posts_search($limit,$start,$search, $col, $dir, $client, $dept, $sub_dept, $group_id)
    {
        if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('vehicles.dept_id ', $group_depts);
		}
		$this->db->from('vehicles');
		//$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id', 'left');
		$this->db->join('tagtypes', 'tagtypes.tag_id = vehicles.tag_id');
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('vehicles.sub_dept_id', $sub_dept);}
		$this->db->where('draft', 0);
		$this->db->where('vehicles.client_id', $client);
		$this->db->group_start();
        $this->db->like('license_plate',$search);
		$this->db->or_like('model',$search);
        $this->db->or_like('location',$search);
        $this->db->or_like('make',$search);
        $this->db->or_like('tolltag',$search);
        $this->db->or_like('vin_no',$search);
        $this->db->or_like('color',$search);
        $this->db->or_like('start_date',$search);
        $this->db->group_end();
        $this->db->limit($limit,$start);
        $this->db->order_by($col,$dir);		
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

    function posts_search_count($search, $client, $dept, $sub_dept, $group_id)
    {
        if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in('vehicles.dept_id ', $group_depts);
		}
		$this->db->from('vehicles');
		$this->db->join('departments', 'departments.dept_id = vehicles.dept_id', 'left');
		$this->db->join('clients', 'clients.client_id = vehicles.client_id');
		if($dept != 0){$this->db->where('vehicles.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where('vehicles.sub_dept_id', $sub_dept);}
		$this->db->where('draft', 0);
		$this->db->where('vehicles.client_id', $client);
		$this->db->group_start();
		$this->db->like('license_plate',$search);
		$this->db->or_like('model',$search);
        $this->db->or_like('location',$search);
        $this->db->or_like('make',$search);
        $this->db->or_like('color',$search);
        $this->db->or_like('start_date',$search);
        $this->db->group_end();
		$query = $this->db->get();
		return $query->num_rows();
    } 
    function t_allposts_sum($org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {   
        $last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		$this->db->select('SUM(toll) AS toll_sum');
		
		$this->db->from($org.'_invoice');
		$this->db->where('dispute', 0);
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$query = $this->db->get();
    
        return $query->row();  

    } 
    function t_searchedposts_sum($search,$org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {   
        $last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		$this->db->select('SUM(toll) AS toll_sum');
		$this->db->from($org.'_invoice');
		//$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$this->db->where('dispute', 0);
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->group_start();
		$this->db->like($org.'_invoice.license_plate',$search);
		$this->db->or_like('state_code',$search);
        $this->db->or_like('agency_name',$search);
        $this->db->or_like('exit_date_time',$search);
         if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->or_like('exit_name',$search);
		} else {
			$this->db->or_like('exit_lane',$search);//LIKE '%xxxx%'
			$this->db->or_like('exit_location',$search);
		}
		if ($org == 'caliber_auto_glass' || $org == 'protech_as') {
			$this->db->or_like('unit',$search);
		}
        $this->db->or_like('toll',$search);
		$this->db->group_end();
		$query = $this->db->get();
        return $query->row();  

    } 
    
    function t_allposts_count($org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {   
        $last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, '.$org.'_invoice.license_plate, unit, invoice_id, state_code, agency_name, exit_date_time, toll');
		} else {
			$this->db->select($org.'_invoice.license_plate, state_code, unit, invoice_id, agency_name, exit_date_time, toll, exit_lane, exit_location');
		}
		
		$this->db->from($org.'_invoice');
		//$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$this->db->where('dispute', 0);
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
			
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$query = $this->db->get();
    
        return $query->num_rows();  

    }
    
    function t_allposts($limit,$start,$col,$dir,$org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {   
       
		$last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, '.$org.'_invoice.license_plate, unit, invoice_id, state_code, agency_name, exit_date_time, toll, dept_name, class');
		} else {
			$this->db->select($org.'_invoice.license_plate, state_code, unit, invoice_id, agency_name, exit_date_time, toll, exit_lane, exit_location, dept_name');
		}
		
		$this->db->from($org.'_invoice');
		$this->db->where('dispute', 0);
		$this->db->join('departments', 'departments.dept_id = '.$org.'_invoice.dept_id');
		//$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->limit($limit,$start);
        $this->db->order_by($col,$dir);
		
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
   
    function t_posts_search($limit,$start,$search,$col,$dir,$org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {
       $last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, '.$org.'_invoice.license_plate, unit, invoice_id, state_code, agency_name, exit_date_time, toll, dept_name');
		} else {
			$this->db->select($org.'_invoice.license_plate, state_code, unit, invoice_id, agency_name, exit_date_time, toll, exit_lane, exit_location, dept_name');
		}
		
		$this->db->from($org.'_invoice');
		$this->db->join('departments', 'departments.dept_id = '.$org.'_invoice.dept_id');
		//$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$this->db->where('dispute', 0);
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->group_start();
		$this->db->like($org.'_invoice.license_plate',$search);
		$this->db->or_like('state_code',$search);
        $this->db->or_like('agency_name',$search);
        $this->db->or_like('exit_date_time',$search);
        if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->or_like('exit_name',$search);
		} else {
			$this->db->or_like('exit_lane',$search);
			$this->db->or_like('exit_location',$search);
		}
		if ($org == 'caliber_auto_glass' || $org == 'protech_as') {
			$this->db->or_like('unit',$search);
		}
        $this->db->or_like('toll',$search);
        $this->db->group_end();
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


   
    function t_posts_search_sum($limit,$start,$search,$col,$dir,$org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {
       $last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		$this->db->select('SUM(toll) AS toll_sum');
		$this->db->from($org.'_invoice');
		$this->db->where('dispute', 0);
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->group_start();
		$this->db->like($org.'_invoice.license_plate',$search);
		$this->db->or_like('state_code',$search);
        $this->db->or_like('agency_name',$search);
        $this->db->or_like('exit_date_time',$search);
         if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->or_like('exit_name',$search);
		} else {
			$this->db->or_like('exit_lane',$search);
			$this->db->or_like('exit_location',$search);
		}
		if ($org == 'caliber_auto_glass' || $org == 'protech_as') {
			$this->db->or_like('unit',$search);
		}
        
        $this->db->or_like('toll',$search);
        $this->db->group_end();
		$query = $this->db->get();
       
        return $query->row();
    }

    function t_posts_search_count($search,$org,$dept,$sub_dept,$group_id,$from,$to,$post_month,$date_type)
    {
        $last_dump = nice_date($this->db->select_max('date_for', 'last_dump')->get($org.'_invoice')->row()->last_dump, 'Y-m');
		if ($group_id != 0) {
	 	 $depts = $this->db->select('dept_id')->from('departments')->where('group_id', $group_id)->get()->result();
		 $group_depts =  array(); 
		 foreach ($depts as $group_dept) {
		 	$group_depts[] = $group_dept->dept_id;
		 }
		 $this->db->where_in($org.'_invoice.dept_id ', $group_depts);
		}
		
		if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->select('exit_name, '.$org.'_invoice.license_plate, unit, invoice_id, state_code, agency_name, exit_date_time, toll');
		} else {
			$this->db->select($org.'_invoice.license_plate, state_code, unit, invoice_id, agency_name, exit_date_time, toll, exit_lane, exit_location');
		}
		
		$this->db->from($org.'_invoice');
		//$this->db->join('vehicles', 'vehicles.license_plate = '.$org.'_invoice.license_plate', 'left');
		$this->db->where('dispute', 0);
		if ($from != 0 && $to != 0) {
			$type = ($date_type != 0) ? 'date_for' : 'exit_date_time';
			$this->db->where('DATE('.$type.') BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		} else {
			if ($post_month != 0) {
				$this->db->where('date_for like ', $post_month.'%');
			} else {
				$this->db->where('date_for like ', $last_dump.'%');
			}
		}
		if($dept != 0){$this->db->where($org.'_invoice.dept_id', $dept);}
		if($sub_dept != 0){$this->db->where($org.'_invoice.sub_dept_id', $sub_dept);}
		$this->db->group_start();
		$this->db->like($org.'_invoice.license_plate',$search);
		$this->db->or_like('state_code',$search);
        $this->db->or_like('agency_name',$search);
        $this->db->or_like('exit_date_time',$search);
        if ($org == 'amazon' || $org == 'fleet_serv_pro') {
			$this->db->or_like('exit_name',$search);
		} else {
			$this->db->or_like('exit_lane',$search);
			$this->db->or_like('exit_location',$search);
		}
		if ($org == 'caliber_auto_glass' || $org == 'protech_as') {
			$this->db->or_like('unit',$search);
		}
        $this->db->or_like('toll',$search);
		$this->db->group_end();
		$query = $this->db->get();
    
        return $query->num_rows();  
    } 
    public function get_sub_departments($id)
	{
		$this->db->select('sub_dept_id, sub_dept_name');
		$this->db->from('sub_departments');
		$this->db->where('dept_id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	public function save_dept($data)
	{
		$this->db->insert('departments', $data);
		return $this->db->insert_id();
	}

/*		public function get_reports($client_id)
	{
		$this->db->from('weekly_report');
		$this->db->join('weeks', 'weeks.week_id = weekly_report.week_id');
		$this->db->join('report_types', 'report_types.report_type_id = weekly_report.report_type_id');
		$this->db->join('clients', 'clients.client_id = weekly_report.client_id');
		$this->db->where('weekly_report.client_id', $client_id);
		$query = $this->db->get();
		return $query->result();
	}*/

	public function amazon_report_sum($client_id, $month, $type)
    {
        $this->db->select('SUM(amount) AS amount');
        $this->db->join('report_types', 'report_types.report_type_id = weekly_report.report_type_id');
        $this->db->where('client_id', $client_id);
        $this->db->where('report_type', $type);
        $this->db->where('end_week_date LIKE', $month.'%');
		$query = $this->db->get('weekly_report');
		return $query->row()->amount;

    }

    public function get_reports($client_id, $from, $to)
	{
		$this->db->from('weekly_report');
		$this->db->join('weeks', 'weeks.week_id = weekly_report.week_id');
		$this->db->join('report_types', 'report_types.report_type_id = weekly_report.report_type_id');
		$this->db->join('clients', 'clients.client_id = weekly_report.client_id');
		$this->db->where('weekly_report.client_id', $client_id);
		if ($from != 0 && $to != 0) {
		$this->db->where('DATE(end_week_date) BETWEEN "'.$from.'%'.'" AND "'.$to.'%'.'"');
		}
		$query = $this->db->get();
		return $query->result();
	}

}
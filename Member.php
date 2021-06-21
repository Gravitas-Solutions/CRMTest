<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Member extends Auth_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model(['admin_model', 'member_model']);
        $this->load->helper('text');
        $this->data['states'] = $this->admin_model->get_states();
        $c = $this->db->get_where('contacts', ['user_id' => $this->ion_auth->user()->row()->id])->row()->client_id;   
        $this->data['demo_acc'] = $this->admin_model->client_profile($c)->demo_acc; 
    }

    private function _checkuser()
    {
        if(!$this->ion_auth->is_admin())
            {
                redirect('/');
            }
    }

    private function _check_dept($user_id){
        $client = $this->db->get_where('contacts', ['user_id' => $user_id])->row();
        $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        
        //default and non default master admin && no dept posted
        if ((($client->default_user && $client->department_id == $overview_dept && $client->group_id == NULL) || (!$client->default_user && $client->department_id == $overview_dept && $client->group_id == NULL)) && !$this->input->post('member_dept')) {
            $dept = 0;
            $sub_dept = 0;
            $group = 0;
        } 

        //default and non default master admin && dept posted
        if ((($client->default_user && $client->department_id == $overview_dept && $client->group_id == NULL) || (!$client->default_user && $client->department_id == $overview_dept  && $client->group_id == NULL)) && $this->input->post('member_dept')) {
            $dept = $this->input->post('member_dept');
            $sub_dept = 0;
            $group = 0;
        } 
        //attached to group && no dept posted
        if ((!$client->default_user && $client->department_id == $overview_dept && $client->group_id !== NULL) && !$this->input->post('member_dept')){
            $dept = 0;
            $sub_dept = 0;
            $group = $client->group_id;
        } 

        //attached to group && dept posted
        if ((!$client->default_user && $client->department_id == $overview_dept  && $client->group_id !== NULL) && $this->input->post('member_dept')) {
            $dept = $this->input->post('member_dept');
            $sub_dept = 0;
            $group = 0;
        } 

        //has sub_cdepartment
        //dept admin (default and not default) && no sub-dept posted
        if (((($has_sub_depts && !$client->default_user && $client->department_id !== $overview_dept)) || ($has_sub_depts && $client->default_user && $client->department_id !== $overview_dept)) && !$this->input->post('member_sub_dept')) {
            $dept = $client->department_id;
            $sub_dept = 0;
            $group = 0;
        } 

        //dept admin (default and not default) && sub-dept posted
       if (((($has_sub_depts && !$client->default_user && $client->department_id !== $overview_dept)) || ($has_sub_depts && $client->default_user && $client->department_id !== $overview_dept)) && $this->input->post('member_sub_dept')) {
            $dept = $client->department_id;
            $sub_dept = $this->input->post('member_sub_dept');
            $group = 0;
        }

        //sub_dept users
        if (!$client->default_user && $client->department_id !== $overview_dept && $client->sub_dept_id !== NULL) {
            $dept = $client->department_id;
            $sub_dept = $client->sub_dept_id;
            $group = 0;
        }     

        return array('dept' => $dept, 'sub_dept' => $sub_dept, 'client' => $client, 'group' => $group);
    }    

    function index()
    {
        $this->_checkuser();
        $this->data['title'] = "Members";
        $this->data['top'] = 1;
        $this->data['members'] = $this->admin_model->get_users(0);
        $this->render('user/index');
    }

    function dashboard()
    {        
        $this->data['title'] = "Member &raquo; Dashboard";
        $member = $this->ion_auth->user()->row()->id;

        $client = $this->_check_dept($member)['client'];
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['action'] = 'member/dashboard';
        $this->load->model('member_model');
        $this->data['active_vehicles'] = $this->member_model->vehicles_by_status(1, $client->client_id, $dept, $sub_dept, $group_id);
        $this->data['inactive_vehicles'] = $this->member_model->vehicles_by_status(0, $client->client_id, $dept, $sub_dept, $group_id);
        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['organization'] = ($dept) ?  $this->db->get_where('departments', ['dept_id' => $dept])->row()->dept_name : $org;

        if ($has_sub_depts) {
           $acc_dept = $dept;
        } else {
           $acc_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        }
        
        $this->data['balance'] = ($this->member_model->account_balance($acc_dept, $group_id)) ?? 0.00;

       /* $this->data['month'] = $month = ($this->member_model->get_month($org, $dept, $sub_dept, 0)) ? (($client->group_id != NULL) ?  nice_date($this->member_model->get_month($org, 0, $sub_dept, $client->group_id), 'Y-m') : nice_date($this->member_model->get_month($org, $dept, $sub_dept, 0), 'Y-m')) : date('Y-m', now());*/
       $this->data['month'] = $month = (($client->group_id != NULL) ? (($this->member_model->get_month($org, 0, $sub_dept, 0)) ? nice_date($this->member_model->get_month($org, 0, $sub_dept, $client->group_id), 'Y-m') : date('Y-m', now())) : (($this->member_model->get_month($org, $dept, $sub_dept, 0)) ? nice_date($this->member_model->get_month($org, $dept, $sub_dept, 0), 'Y-m') : date('Y-m', now())));
        
        $year = date('Y');
        
        $this->data['toll_transactions'] = $this->member_model->toll_transactions($org, $month, $dept, $sub_dept, $group_id); 
        $this->data['total_transactions'] = $this->member_model->total_transactions($org, $month, $dept, $sub_dept, $group_id);
       // $invoice_month_dept = $this->db->where('client_name LIKE', '%'.$org.'%')->where('dept', $dept)->count_all_results('invoice_month');
        $client_invoice_month = $this->db->where('client_name LIKE', '%'.$org.'%')->count_all_results('invoice_month');
        $this->data['current_invoice_month'] = ($client_invoice_month > 0) ? (($this->member_model->current_month_invoice($org, $month, $dept, $group_id) == null) ? 'None' : $this->member_model->current_month_invoice($org, $month, $dept, $group_id)->month) : 'None';
        $this->data['current_invoice_amount'] = ($client_invoice_month > 0) ? (($this->member_model->current_month_invoice($org, $month, $dept, $group_id) == null) ? 0 : $this->member_model->current_month_invoice($org, $month, $dept, $group_id)->invoice_amount) : 0;
        $this->data['top_vehicles'] = $this->member_model->top_vehicles($org, $month, $client->client_id, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept);
        
        /*$this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result(); */ 
        if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result(); 

                $this->data['group_name'] = $this->db->select('group_name')->from('department_grouping')->where('group_id', $client->group_id)->get()->row()->group_name;

            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }
        

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        } 

        $this->data['user_modules'] = explode(',', $client->modules);

        /*Toll amount per day for current month*/
        $this->load->helper('date');
        $y = nice_date($month, 'Y');
        $m = nice_date($month, 'm');
        $days = days_in_month($m, $y);
        $this->data['daily_tolls'] = '';
        for ($i=1; $i <= $days; $i++) { 
            $this->data['daily_tolls'] .= "{ day:'".$i."', toll:".$this->member_model->daily_toll_transactions($org, $month, str_pad($i, 2, '0', STR_PAD_LEFT), $dept, $sub_dept, $group_id)."}, "; 
        }

        /*MTD Toll Road Spending by Agency*/
        $agency_tolls = $this->member_model->agency_tolls($org, $month, $dept, $sub_dept, $group_id);
        $this->data['agency_toll'] = '';
        foreach ($agency_tolls as $at) {
            $this->data['agency_toll'] .= "{ label:'".$at->agency_name."', value:".$at->toll."}, "; 
        }

        /*YTD Toll Road Spending by Month*/
        $this->data['monthly_toll'] = '';
        for ($i=1; $i <= 12; $i++) {
            $this->data['monthly_toll'] .= "{ month:'".date('M', strtotime($year.'-'.$i))."', toll:".$this->member_model->monthly_toll($org, $year, str_pad($i, 2, '0', STR_PAD_LEFT), $dept, $sub_dept, $group_id)."}, "; 
        }

        /*Red light & Parking*/
        $this->data['red_light_amount'] = $this->db->select('SUM(citation_amount) AS amount')->from('citations')->where('organization', $org)->where('citation_type', 'rl')->get()->row()->amount;
        $this->data['red_light_no'] = $this->db->select('COUNT(citation_id) AS citation')->from('citations')->where('organization', $org)->where('citation_type', 'rl')->get()->row()->citation;

        $this->data['parking_amount'] = $this->db->select('SUM(citation_amount) AS amount')->from('citations')->where('organization', $org)->where('citation_type', 'pk')->get()->row()->amount;
        $this->data['parking_no'] = $this->db->select('COUNT(citation_id) AS citation')->from('citations')->where('organization', $org)->where('citation_type', 'pk')->get()->row()->citation;

        $this->data['speeding_ticket_amount'] = $this->db->select('SUM(citation_amount) AS amount')->from('citations')->where('organization', $org)->where('citation_type', 'st')->get()->row()->amount;
        $this->data['speeding_ticket_no'] = $this->db->select('COUNT(citation_id) AS citation')->from('citations')->where('organization', $org)->where('citation_type', 'st')->get()->row()->citation;

        $this->render('member/dashboard');
    } 

    function vehicles()
    {
        $this->data['title'] = "Member &raquo Vehicles";
        $member = $this->ion_auth->user()->row()->id;
        $this->data['can_update'] = $this->db->get_where('contacts', ['user_id' => $member])->row()->can_update;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;
        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'member/vehicles';
        $this->data['vehicles'] = $this->member_model->client_vehicles($client->client_id, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);     
        /*$this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();  */
        if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result();
            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        }

        if ($org == 'clay_cooley_dealerships' || $org == 'huffines_plano' || $org == 'linda_auto_group') {
            $this->data['org'] = 1;
        }

        $this->data['user_modules'] = explode(',', $client->modules);
        $this->render('member/vehicles');
    } 

    //Vehicle update - client side
    function edit_vehicle($id){
        $vehicle = $this->member_model->vehicle_by_id($id);
        if($vehicle){
            echo json_encode(array('status' => TRUE, 'vehicle' => $vehicle));
        }else{
        echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    } 

    function update_vehicle(){
        $updates = array(
            'license_plate' => $this->input->post('license_plate', TRUE),
            'location' => $this->input->post('state', TRUE),
            'tolltag' => $this->input->post('tolltag', TRUE),
            'vin_no' => $this->input->post('vin_no', TRUE),
            'color' => $this->input->post('color', TRUE),
            'make' => $this->input->post('make', TRUE),
            'model' => $this->input->post('model', TRUE),
            'vehicle_status' => $this->input->post('status', TRUE)
        );
        $updated = $this->db->update('vehicles', $updates, ['vehicle_id' => $this->input->post('vehicle_id', TRUE)]);
        if (!$updated) {
            echo json_encode(['status' => false, 'msg' => 'Error updating vehicles']);
        } else {
            echo json_encode(['status' => true, 'msg' => 'Vehicle updated successfully']);
        }        
    }
       
    function citations()
    {
        $this->data['title'] = "Member &raquo citations";
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
         $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'member/citations';
        $this->data['citations'] = $this->member_model->client_citations($org, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);        
        /*$this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();  */
        if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result();
            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        }

        $this->data['user_modules'] = explode(',', $client->modules);
        $this->render('member/citations');
    }

     function transactions()
    {
        $this->data['title'] = "Member &raquo Transactions";
        $this->data['filtered'] = false;
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;        
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'member/transactions';
        $this->data['transactions'] = $this->member_model->transactions($org, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);
       /* $this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();*/

       if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result();
            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        }
        if ($org == 'clay_cooley_dealerships' || $org == 'huffines_plano' || $org == 'linda_auto_group') {
            $this->data['org'] = 1;
        }

        $last_dump_date = $this->db->select_max('exit_date_time', 'last_dump')->get($org.'_invoice')->row()->last_dump;
        $this->data['last_dump_date'] = ($last_dump_date !== null) ? nice_date($last_dump_date, 'Y-m-d') : date('Y-m-d') ;
        $this->data['user_modules'] = explode(',', $client->modules);
        $this->data['search_logs'] = $this->member_model->member_searches_logs($client->client_id);
        $this->data['client_id'] = $client->client_id;
        $this->render('member/transactions');
    }

     function test_transactions()
    {
        $this->data['title'] = "Member &raquo Transactions";
        $this->data['filtered'] = false;
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;        
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'member/transactions';
        $this->data['transactions'] = $this->member_model->transactions($org, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);
       /* $this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();*/

       if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result();
            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        }
        if ($org == 'clay_cooley_dealerships' || $org == 'huffines_plano' || $org == 'linda_auto_group') {
            $this->data['org'] = 1;
        }

        $last_dump_date = $this->db->select_max('exit_date_time', 'last_dump')->get($org.'_invoice')->row()->last_dump;
        $this->data['last_dump_date'] = ($last_dump_date !== null) ? nice_date($last_dump_date, 'Y-m-d') : date('Y-m-d') ;
        $this->data['user_modules'] = explode(',', $client->modules);
        $this->data['search_logs'] = $this->member_model->member_searches_logs($client->client_id);
        $this->data['client_id'] = $client->client_id;
        $this->render('member/transactions_edits');
    }

    function date_range_transactions()
    {
        $this->data['title'] = "Member &raquo Transactions: Date Range";
        //$this->data['filtered'] = true;
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];        
        $this->data['filtered'] = (bool)$this->db->get_where('contacts', ['user_id' => $member])->row()->can_update;
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization; 
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;       
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'member/transactions';
        $this->data['start_date'] = $from = $this->input->post('start_date');
        $this->data['end_date'] = $to = $this->input->post('end_date');
        $this->data['transactions'] = $this->member_model->date_range_transactions($org, $dept, $sub_dept, $from, $to, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);
        /*$this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();*/
        if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result();
            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        }

         if ($org == 'clay_cooley_dealerships' || $org == 'huffines_plano' || $org == 'linda_auto_group') {
            $this->data['org'] = 1;
        }

        $this->data['user_modules'] = explode(',', $client->modules);
        $this->data['search_logs'] = $this->member_model->member_searches_logs($client->client_id);
        $this->data['client_id'] = $client->client_id;
        $this->render('member/transactions');
    } 

    function charge_back(){
        $tolls = $this->input->post('id', TRUE);
        $client = $this->input->post('c', TRUE);
        $ids = [];
        for ($i = 0; $i < count($tolls); $i++) {
           $ids[] = (int)$tolls[$i]; 
        }
        if (!$this->member_model->process_tolls($ids, $client)) {
            echo json_encode(['status' => false, 'msg' => 'Error processing selected transactions']);
        } else {
            echo json_encode(['status' => true, 'msg' => 'Selected transactions have been processed']);
        }
    }   

    function invoices()
    {
        $this->data['title'] = "Member &raquo Invoices";
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id ;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
         $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'member/invoices';
        $this->data['invoices'] = $this->member_model->member_invoices($org, $dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept);
       /* $this->data['departments'] = ($client->default_user == 1) ? $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result() : $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();*/  
       if ($client->default_user == 1) {
            $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('client_id', $client->client_id)->get()->result();
        } else {
            if ($client->group_id != NULL) {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('group_id', $client->group_id)->get()->result();
            } else {
                $this->data['departments'] = $this->db->select('dept_id, dept_name')->from('departments')->where('dept_id', $client->department_id)->get()->result();
            }
        }

        if ($dept != 0) {
            $this->data['sub_departments'] = $this->db->where('dept_id', $dept)->get('sub_departments')->result();
        }


        $this->data['user_modules'] = explode(',', $client->modules);
        $this->render('member/invoices');
    }

    function invoice($invoice_id)
    {
        $data['invoice'] = $this->db->where('invoice_id', $invoice_id)->get('invoices')->row();
        $base = base_url('assets/images/inn_toll.png');

        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');

        $data = '<h2 align="center">Toll Invoice</h2>
            <table border="1" width="100%" cellspacing="0" cellpadding="4">
                <tr>
                     <th  colspan="2"><img src="'.$base.'" alt="logo" /></th>
                     <th  colspan="2">INNOVATIVE TOLL</th>
                </tr>
                <tr>
                    <th  colspan="1">Transaction #</th>
                    <th  colspan="1">Average Toll</th>
                    <th  colspan="1">Toll Fees</th>
                    <th  colspan="1">Total</th>
                </tr>
                 <tr>
                        <td colspan="1">'.$data['invoice']->invoice_id.'</td>
                        <td colspan="1">$'.$data['invoice']->toll_amount.'</td>
                        <td colspan="1">$'.$data['invoice']->toll_fees.'</td>
                        <td colspan="1">$'.$data['invoice']->toll_amount.'</td>
                 </tr>
            </table>';
        $dompdf->loadHtml($data);
        $dompdf->render();
        $dompdf->stream('toll_invoice.pdf');
    } 

    function log_member_searches()
    {
        $user_id = $this->ion_auth->user()->row()->id;
        $client_id = $this->db->get_where('contacts', ['user_id' => $user_id])->row()->client_id;
        $logs = array();
        $t = 0;
        $log_datetime = nice_date($this->input->post('log_datetime', TRUE), 'Y-m-d H:i:s');
        foreach($this->input->post(NULL, TRUE) as $k){
            ++$t;
            $logs = array(
                'search_type' => $this->input->post('search_type', TRUE),
                'search_phrase' => $this->input->post('search_phrase', TRUE),
                'user' => $user_id,
                'client_id' => $client_id,
                'log_datetime' => $log_datetime
            );
            if ($t == 1) {break;}
        }
        $tbl = 'member_searches_logs';
        $q = $this->db->get_where($tbl, ['log_datetime' => $log_datetime]);

        if ( $q->num_rows() > 0 ){
          $this->db->where('log_datetime', $log_datetime);
          $this->db->update($tbl, $logs);
        } else {
          $this->db->insert($tbl, $logs);
        }
    }

    function delete_logs($client){
        if(!$this->member_model->delete_logs($client)){
            echo json_encode(['status' => false, 'msg' => 'Error emptying search & export logs']);
        }else{
            echo json_encode(['status' => true, 'msg' => 'Search & export logs emptied']);
        }
    }

    function upload_vehicles(){
        if ($_FILES["vehicles_file"]["name"] == '') {
            echo json_encode(['status' => false, 'msg' => 'Select a file to upload']);
        } else {
            $ext = (new SplFileInfo($_FILES["vehicles_file"]["name"]))->getExtension();
            if($ext == 'xls' || $ext == 'xlsx'){
                $uploader = $this->db->get_where('contacts', ['user_id' => $this->ion_auth->user()->row()->id])->row();
                $client = $this->db->get_where('clients', ['client_id' => $uploader->client_id])->row()->organization;
                $dept = $this->db->get_where('departments', ['dept_id' => $uploader->department_id])->row()->dept_name;
                $config["upload_path"] = './uploads/client_uploads/';
                $config["allowed_types"] = 'xls|xlsx';
                $config['max_size']     = '1024';
                $config['file_name']     = time().'@'.$uploader->first_name.'_'.$uploader->last_name.'@'.$client.'@'.$dept;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('vehicles_file')){
                    echo json_encode(['status' => false, 'msg' => $this->upload->display_errors('', '')]);
                }else{
                    echo json_encode(['status' => true, 'msg' => 'File uplaoded. Admin will review & add the vehicles onto the system']);
                }
            }else{
                echo json_encode(['status' => false, 'msg' => 'Only excel files with extension .xls or .xlsx are allowed']);
            }
        }
    }

}

//NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
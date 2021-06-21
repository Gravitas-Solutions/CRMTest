<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Member extends Auth_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model(['admin_model', 'member_model']);
        $this->load->helper(['text', 'date']);
        $this->data['states'] = $this->admin_model->get_states();
        $c = $this->db->get_where('contacts', ['user_id' => $this->ion_auth->user()->row()->id])->row()->client_id;
        $this->data['demo_acc'] = $this->admin_model->client_profile($c)->demo_acc;
    }

    private function _checkuser()
    {
        if ($this->ion_auth->is_admin()) {
            redirect('/');
        }
    }

    private function _check_dept($user_id)
    {
        if ($this->input->post('client_name')) {
            $client = $this->member_model->default_data($this->input->post('client_name'));
        } else {
            if ($this->input->post('member_dept')) {
                $client_id = $this->db->where('dept_id', $this->input->post('member_dept'))->get('departments')->row()->client_id;
                $client = $this->member_model->default_data($this->admin_model->client_profile($client_id)->organization);
            } else {
                $client = $this->db->get_where('contacts', ['user_id' => $user_id])->row();
            }
        }
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
        if ((!$client->default_user && $client->department_id == $overview_dept && $client->group_id !== NULL) && !$this->input->post('member_dept')) {
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

    function dashboard()
    {
        $this->data['title'] = "Member &raquo; Dashboard";
        $member = $this->ion_auth->user()->row()->id;

        $client = $this->_check_dept($member)['client'];
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['action'] = 'home';
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


        $this->data['month'] = $month = ($this->input->post('filter_month')) ? date('Y-m', strtotime($this->input->post('filter_month'))) : date('Y-m');
        $year = ($this->input->post('filter_month')) ? date('Y', strtotime($this->input->post('filter_month'))) : date('Y');
        /*$year = date('Y');*/

        $this->data['toll_transactions'] = $toll_transactions = $this->member_model->toll_transactions($org, $month, $dept, $sub_dept, $group_id);
        $this->data['saving'] = $toll_transactions * 0.085;
        $this->data['total_transactions'] = $this->member_model->total_transactions($org, $month, $dept, $sub_dept, $group_id);
        $client_invoice_month = $this->db->where('client_name LIKE', '%' . $org . '%')->count_all_results('invoice_month');
        $this->data['current_invoice_month'] = ($client_invoice_month > 0) ? (($this->member_model->current_month_invoice($org, $month, $dept, $group_id) == null) ? 'None' : $this->member_model->current_month_invoice($org, $month, $dept, $group_id)->month) : 'None';
        $this->data['current_invoice_amount'] = ($client_invoice_month > 0) ? (($this->member_model->current_month_invoice($org, $month, $dept, $group_id) == null) ? 0 : $this->member_model->current_month_invoice($org, $month, $dept, $group_id)->invoice_amount) : 0;
        $this->data['top_vehicles'] = $this->member_model->top_vehicles($org, $month, $client->client_id, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept);



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
        $this->data['daily_labels'] = '';
        $this->data['daily_tolls'] = '';
        for ($i = 1; $i <= $days; $i++) {
            $this->data['daily_labels'] .= "'" . $i . "', ";
            $this->data['daily_tolls'] .= $this->member_model->daily_toll_transactions($org, $month, str_pad($i, 2, '0', STR_PAD_LEFT), $dept, $sub_dept, $group_id) . ', ';
        }

        /*MTD Toll Road Spending by Agency*/
        $agency_tolls = $this->member_model->agency_tolls($org, $month, $dept, $sub_dept, $group_id);
        $this->data['agency_labels'] = '';
        $this->data['agency_tolls'] = '';
        $this->data['agency_colors'] = '';
        foreach ($agency_tolls as $at) {
            $this->data['agency_labels'] .= "'" . $at->agency_name . "', ";
            $this->data['agency_tolls'] .= $at->toll . ", ";
            $this->data['agency_colors'] .= "'#" . dechex(rand(0, 10000000)) . "', ";
        }

        /*YTD Toll Road Spending by Month*/
        $this->data['monthly_labels'] = '';
        $this->data['monthly_tolls'] = '';
        for ($i = 1; $i <= 12; $i++) {
            $this->data['monthly_labels'] .= "'" . date('M', strtotime($year . '-' . $i)) . "', ";
            $this->data['monthly_tolls'] .= $this->member_model->monthly_toll($org, $year, str_pad($i, 2, '0', STR_PAD_LEFT), $dept, $sub_dept, $group_id) . ", ";
        }

        /*Red light & Parking*/
        $this->data['red_light_amount'] = $this->db->select('SUM(citation_amount) AS amount')->from('citations')->where('organization', $org)->where('citation_type', 'rl')->get()->row()->amount;
        $this->data['red_light_no'] = $this->db->select('COUNT(citation_id) AS citation')->from('citations')->where('organization', $org)->where('citation_type', 'rl')->get()->row()->citation;

        $this->data['parking_amount'] = $this->db->select('SUM(citation_amount) AS amount')->from('citations')->where('organization', $org)->where('citation_type', 'pk')->get()->row()->amount;
        $this->data['parking_no'] = $this->db->select('COUNT(citation_id) AS citation')->from('citations')->where('organization', $org)->where('citation_type', 'pk')->get()->row()->citation;

        $this->data['speeding_ticket_amount'] = $this->db->select('SUM(citation_amount) AS amount')->from('citations')->where('organization', $org)->where('citation_type', 'st')->get()->row()->amount;
        $this->data['speeding_ticket_no'] = $this->db->select('COUNT(citation_id) AS citation')->from('citations')->where('organization', $org)->where('citation_type', 'st')->get()->row()->citation;

        $this->render('frontend/dashboard');
    }

    function vehicles()
    {
        $this->data['title'] = "Vehicles";
        $member = $this->ion_auth->user()->row()->id;
        $this->data['can_update'] = $this->db->get_where('contacts', ['user_id' => $member])->row()->can_update;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'vehicles';
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['vehicles'] = $this->member_model->client_vehicles($client->client_id, $dept, $sub_dept, $group_id);
        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);
        $this->data['tags'] = $this->member_model->toll_tag();
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
        if ($org == 'clay_cooley_dealerships' || $org == 'huffines_plano') {
            $this->render('frontend/vehicles');
        } else {
            $this->render('frontend/vehicles1');
            // $this->render('frontend/transaction1');
        }
    }

    //Vehicle update - client side
    function edit_vehicle($id)
    {
        $vehicle = $this->member_model->vehicle_by_id($id);
        if ($vehicle) {
            echo json_encode(array('status' => TRUE, 'vehicle' => $vehicle));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    function add_vehicle()
    {
        if ($this->_vehicle_validate() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<span>', ' | </span>')));
            exit;
        } else {
            $member = $this->ion_auth->user()->row()->id;

            if ($this->input->post('vehicle_client_name')) {
                $client_name = $this->input->post('vehicle_client_name');
                $client_id = $this->db->select('client_id')->from('clients')->where('organization', $client_name)->get()->row()->client_id;
            } else {
                $client_row = $this->_check_dept($member)['client'];
                $client_id = $client_row->client_id;
            }
            if ($this->input->post('dept') === 'other' && !empty($this->input->post('dept2'))) {
                $dept = array('dept_name' => $this->input->post('dept2'), 'client_id' => $client_id);
                $dept_id = $this->member_model->save_dept($dept);
            }else{
                $dept_id = $this->input->post('dept');
            }
            $color = 'WHITE';

            $tolltag = ($this->input->post('tolltag') != 'other') ? $this->input->post('tolltag') : $this->input->post('tolltag2');
            $vehicle = array(
                'license_plate' => trim($this->input->post('license_plate')),
                'model' => $this->input->post('model'),
                'make' => $this->input->post('make'),
                'color' => empty($this->input->post('color')) ? $color : $this->input->post('color'),
                'axles' => $this->input->post('axles'),
                'start_date' => ($this->input->post('start_date')) ? date('Y-m-d', strtotime($this->input->post('start_date'))) : date('Y-m-d'),
                'end_date' => ($this->input->post('end_date')) ? date('Y-m-d', strtotime($this->input->post('end_date'))) : '0000-00-00',
                'tag_id' => $this->input->post('tagtype'),
                'store' => $this->input->post('store'),
                'location' => $this->input->post('location'),
                'unit' => $this->input->post('unit'),
                'year' => $this->input->post('year'),
                'client_id' => $client_id,
                'dept_id' => $dept_id,
                'sub_dept_id' => ($this->input->post('sub_dept')) ?? NULL,
                'tolltag' => $tolltag,
                'vin_no' => ($this->input->post('vin_no')) ?? NULL,
                'dump_date' => date('Y-m-d H:i:s'),
                'vehicle_status' => $this->input->post('status', TRUE)
            );
            if (!$this->admin_model->save_vehicle($vehicle)) {
                echo json_encode(array('status' => FALSE, 'msg' => 'Vehicle save failed!'));
            } else {
                //test custom id exits in unsed transponder list
                if (($this->input->post('tolltag') != 'other') && $this->input->post('tolltag2')) {
                    if ($this->member_model->toll_tag_exists($this->input->post('tolltag2')) > 0) {
                        echo json_encode(array('status' => TRUE, 'msg' => 'Vehicle saved'));
                    } else {
                        $toll_tag = array('client_id' => $client_id, 'tag' => $this->input->post('tolltag2'));
                        $this->admin_model->save_toll_tag($toll_tag);
                        echo json_encode(array('status' => TRUE, 'msg' => 'Vehicle saved'));
                    }
                } else {
                    echo json_encode(array('status' => TRUE, 'msg' => 'Vehicle saved'));
                }
            }
        }
    }
    function update_vehicle()
    {
        if ($this->_vehicle_validate() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<span>', ' | </span>')));
            exit;
        } else {
            if ($this->input->post('dept') === 'other' && !empty($this->input->post('dept2'))) {
                $client_id = $this->input->post('client_id');
                $dept = array('dept_name' => $this->input->post('dept2'), 'client_id' => $client_id);
                $dept_id = $this->member_model->save_dept($dept);
            }else{
                $dept_id = $this->input->post('dept');
            }
            $tolltag = ($this->input->post('tolltag') != 'other') ? $this->input->post('tolltag') : $this->input->post('tolltag2');
            $color = 'WHITE';
            $update = array(
                'license_plate' => trim($this->input->post('license_plate')),
                'model' => $this->input->post('model'),
                'make' => $this->input->post('make'),
                'color' => empty($this->input->post('color')) ? $color : $this->input->post('color'),
                'axles' => $this->input->post('axles'),
                'start_date' => ($this->input->post('start_date')) ? date('Y-m-d', strtotime($this->input->post('start_date'))) : date('Y-m-d'),
                'end_date' => ($this->input->post('end_date')) ? date('Y-m-d', strtotime($this->input->post('end_date'))) : '0000-00-00',
                'tag_id' => $this->input->post('tagtype'),
                'store' => $this->input->post('store'),
                'location' => $this->input->post('location'),
                'unit' => $this->input->post('unit'),
                'year' => $this->input->post('year'),
                'dept_id' => $dept_id,
                'sub_dept_id' => ($this->input->post('sub_dept')) ?? NULL,
                'tolltag' => $tolltag,
                'vin_no' => ($this->input->post('vin_no')) ?? NULL,
                'vehicle_status' => $this->input->post('status', TRUE)
            );
            $this->admin_model->update_vehicle(array('vehicle_id' => $this->input->post('id')), $update);
            echo json_encode(array('status' => TRUE, 'msg' => 'Vehicle updated'));
        }
    }
    private function _vehicle_validate()
    {
        $this->form_validation->set_rules('license_plate', 'License plate', 'trim|required', array('required' => 'Provide license plate'));
        // $this->form_validation->set_rules('model', 'Vehicle model','trim|required', array('required' => 'Provide model'));
        // $this->form_validation->set_rules('make', 'Vehicle make','trim|required', array('required' => 'Provide make'));
        //$this->form_validation->set_rules('color', 'Color','trim|required', array('required' => 'Provide color'));
        // $this->form_validation->set_rules('start_date', 'Start date','trim|required', array('required' => 'Provide start date'));
        $this->form_validation->set_rules('axles', 'Number of axles', 'trim|required', array('required' => 'Provide number of axles'));
        $this->form_validation->set_rules('tagtype', '', 'trim|required', array('required' => 'Select tag type'));
        /*$this->form_validation->set_rules('store', 'Store','trim|required', array('required' => 'Provide store'));*/
        $this->form_validation->set_rules('location', 'Location', 'trim|required', array('required' => 'Provide vehicle State'));
        $this->form_validation->set_rules('year', 'Vehicle year', 'trim|required', array('required' => 'Provide year'));
        if($this->input->post('dept') === 'other')
        {
            $this->form_validation->set_rules('dept2', 'Unit', 'trim|required', array('required' => 'Provide the custom Department/cost center'));
        }
        else
        {
            $this->form_validation->set_rules('dept', 'Unit', 'trim|required', array('required' => 'Provide Department/cost center'));
        }


        if ($this->form_validation->run() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
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
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['action'] = 'citations';
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
        $this->render('frontend/citation');
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
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transactions';

        $this->data['logo'] = $this->member_model->member_logo($client->client_id, $dept, $sub_dept);

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
        $last_dump_date = $this->db->select_max('exit_date_time', 'last_dump')->get($org . '_invoice')->row()->last_dump;
        $this->data['last_dump_date'] = ($last_dump_date !== null) ? nice_date($last_dump_date, 'Y-m-d') : date('Y-m-d');
        $this->data['user_modules'] = explode(',', $client->modules);
        $this->data['search_logs'] = $this->member_model->member_searches_logs($client->client_id);
        $this->data['client_id'] = $client->client_id;
        if ($org == 'clay_cooley_dealerships' || $org == 'huffines_plano') {
            $this->data['org'] = 1;
            $this->data['transactions'] = $this->member_model->transactions($org, $dept, $sub_dept, $group_id);
            $this->render('frontend/transaction');
        } else {
            if ($org == 'caliber_auto_glass' || $org == 'protech_as') {
                $this->render('frontend/transaction_draft');
            } else {
                $this->render('frontend/transaction_copy');
            }
        }
    }

    function disputes()
    {
        $this->data['title'] = "Member &raquo Dispute Transactions";
        $this->data['filtered'] = false;
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transactions';
        $this->data['transactions'] = $this->member_model->disputes($org, $dept, $sub_dept, $group_id);
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

        $last_dump_date = $this->db->select_max('exit_date_time', 'last_dump')->get($org . '_invoice')->row()->last_dump;
        $this->data['last_dump_date'] = ($last_dump_date !== null) ? nice_date($last_dump_date, 'Y-m-d') : date('Y-m-d');
        $this->data['user_modules'] = explode(',', $client->modules);
        $this->data['search_logs'] = $this->member_model->member_searches_logs($client->client_id);
        $this->data['client_id'] = $client->client_id;
        $this->render('frontend/dispute');
    }
    public function dispute_status($id, $client)
    {
        $dispute = array('dispute' => 1);
        if (!$this->member_model->update_dispute_status($dispute, $id, $client)) {
            echo json_encode(['status' => false, 'msg' => 'Error in updating transaction']);
        } else {
            echo json_encode(['status' => true, 'msg' => 'Transaction filed as disputed']);
        }
    }
    public function resolve_dispute($id, $client)
    {
        $dispute = array('dispute' => 1);
        if (!$this->member_model->resolve_dispute_status($dispute, $id, $client)) {
            echo json_encode(['status' => false, 'msg' => 'Error in updating transaction']);
        } else {
            echo json_encode(['status' => true, 'msg' => 'Transaction filed as disputed']);
        }
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
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transactions';
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

        $last_dump_date = $this->db->select_max('exit_date_time', 'last_dump')->get($org . '_invoice')->row()->last_dump;
        $this->data['last_dump_date'] = ($last_dump_date !== null) ? nice_date($last_dump_date, 'Y-m-d') : date('Y-m-d');
        $this->data['user_modules'] = explode(',', $client->modules);
        $this->data['search_logs'] = $this->member_model->member_searches_logs($client->client_id);
        $this->data['client_id'] = $client->client_id;
        $this->render('frontend/transactions_edits');
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
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transactions';
        $this->data['start_date'] = $from = date('Y-m-d', strtotime($this->input->post('start_date')));
        $this->data['end_date'] = $to = date('Y-m-d', strtotime($this->input->post('end_date')));
        $date_type = $this->input->post('date_type');
        $this->data['date_type'] = ($date_type != 0) ? 'For Posted ' : 'For Transaction ';
        $this->data['transactions'] = $this->member_model->date_range_transactions($org, $dept, $sub_dept, $from, $to, $group_id, $date_type);
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
        $this->render('frontend/transaction');
    }

    function date_post_transactions()
    {
        $this->data['title'] = "Member &raquo Transactions: Post Month";
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
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transactions';
        $this->data['post_month'] = $post_month = date('Y-m', strtotime($this->input->post('post_month')));
        $date_type = $this->input->post('date_type');
        $this->data['transactions'] = $this->member_model->date_date_transactions($org, $dept, $sub_dept, $group_id, $post_month);
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
        $this->render('frontend/transaction');
    }



    function charge_back()
    {
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
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'invoices';
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
        $this->render('frontend/invoice');
    }

    function log_member_searches()
    {
        $user_id = $this->ion_auth->user()->row()->id;
        $client_id = $this->db->get_where('contacts', ['user_id' => $user_id])->row()->client_id;
        $logs = array();
        $t = 0;
        $log_datetime = nice_date($this->input->post('log_datetime', TRUE), 'Y-m-d H:i:s');
        foreach ($this->input->post(NULL, TRUE) as $k) {
            ++$t;
            $logs = array(
                'search_type' => $this->input->post('search_type', TRUE),
                'search_phrase' => $this->input->post('search_phrase', TRUE),
                'user' => $user_id,
                'client_id' => $client_id,
                'log_datetime' => $log_datetime
            );
            if ($t == 1) {
                break;
            }
        }
        $tbl = 'member_searches_logs';
        $q = $this->db->get_where($tbl, ['log_datetime' => $log_datetime]);

        if ($q->num_rows() > 0) {
            $this->db->where('log_datetime', $log_datetime);
            $this->db->update($tbl, $logs);
        } else {
            $this->db->insert($tbl, $logs);
        }
    }

    function delete_logs($client)
    {
        if (!$this->member_model->delete_logs($client)) {
            echo json_encode(['status' => false, 'msg' => 'Error emptying search & export logs']);
        } else {
            echo json_encode(['status' => true, 'msg' => 'Search & export logs emptied']);
        }
    }

    function upload_vehicles()
    {
        if ($_FILES["vehicles_file"]["name"] == '') {
            echo json_encode(['status' => false, 'msg' => 'Select a file to upload']);
        } else {
            $ext = (new SplFileInfo($_FILES["vehicles_file"]["name"]))->getExtension();
            if ($ext == 'xls' || $ext == 'xlsx') {
                $uploader = $this->db->get_where('contacts', ['user_id' => $this->ion_auth->user()->row()->id])->row();
                $client = $this->db->get_where('clients', ['client_id' => $uploader->client_id])->row()->organization;
                $dept = $this->db->get_where('departments', ['dept_id' => $uploader->department_id])->row()->dept_name;
                $config["upload_path"] = './uploads/client_uploads/';
                $config["allowed_types"] = 'xls|xlsx';
                $config['max_size']     = '1024';
                $config['file_name']     = time() . '@' . $uploader->first_name . '_' . $uploader->last_name . '@' . $client . '@' . $dept;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('vehicles_file')) {
                    echo json_encode(['status' => false, 'msg' => $this->upload->display_errors('', '')]);
                } else {
                    echo json_encode(['status' => true, 'msg' => 'File uplaoded. Admin will review & add the vehicles onto the system']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Only excel files with extension .xls or .xlsx are allowed']);
            }
        }
    }

    function transponders()
    {
        $this->data['title'] = "Member &raquo transponders fulflment";
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transponder-fulfilment';
        $this->data['orders'] = $this->member_model->member_fulfilment($client->client_id, $dept, $group_id);
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
        $this->render('frontend/fulfilment');
    }

    function order()
    {
        $this->data['title'] = "Member &raquo Order transponders";
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'transponder';
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
        $this->render('frontend/order_transponder');
    }

    public function save_transponder($status)
    {
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|required');
        $this->form_validation->set_rules('velcro', 'velcron', 'trim|required');
        $this->form_validation->set_rules('domicile_terminal', 'Domicile Terminal', 'trim|required');
        /*$this->form_validation->set_rules('shipping_address', 'Shipping address','trim|required'); */
        $this->form_validation->set_rules('assets', 'Asset (Rental OR AFP Owned)', 'trim|required');
        /*if (empty($_FILES['asset_file']['name'])) {
            $this->form_validation->set_rules('asset_file', 'Asset File','trim|required'); 
        }
        if (empty($_FILES['shipping_list']['name'])) {
             $this->form_validation->set_rules('shipping_list', 'Shipping List','trim|required');
        }*/

        /*$this->form_validation->set_rules('instructions', 'Fees Type','trim|required');*/
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => false, 'msg' => validation_errors('<li class="list-inline"><i class="fa fa-exclamation-circle"></i> ', '</li>')));
        } else {
            if (!$this->_upload("asset_file", "./uploads/fulfiment/")) {
                echo json_encode(array('status' => true, 'msg' => '<p class="text-danger text-center"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</strong></p>'));
            } else {
                $asset_file = '';
                $shipping_file = '';

                if (!empty($_FILES['shipping_list']['name'])) {
                    if (!$this->_upload("shipping_list", "./uploads/fulfiment/")) {
                        echo json_encode(array('status' => true, 'msg' => '<p class="text-success text-center"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</strong></p>'));
                    } else {
                        $shipping_data = $this->upload->data();
                        $shipping_file = $shipping_data['file_name'];
                    }
                }
                if (!empty($_FILES['asset_file']['name'])) {
                    if (!$this->_upload("asset_file", "./uploads/fulfiment/")) {
                        echo json_encode(array('status' => true, 'msg' => '<p class="text-danger text-center"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</strong></p>'));
                    } else {
                        $asset_data = $this->upload->data();
                        $asset_file = $asset_data['file_name'];
                    }
                }
                $member = $this->ion_auth->user()->row()->id;
                $client = $this->_check_dept($member)['client'];
                $fulfilment_data = array(
                    'quantity' => $this->input->post('quantity'),
                    'velcro' => $this->input->post('velcro'),
                    'domicile_terminal' => $this->input->post('domicile_terminal'),
                    'shipping_address' => $this->input->post('shipping_address'),
                    'assets' => $this->input->post('assets'),
                    'client_id' => $client->client_id,
                    'dept_id' => ($this->input->post('dept')) ?  $this->input->post('dept') : NULL,
                    'status' => $status,
                    'instructions' => $this->input->post('instructions')
                );
                if (!empty($asset_file)) {
                    $fulfilment_data['asset_file'] = $asset_file;
                }
                if (!empty($shipping_file)) {
                    $fulfilment_data['shipping_list'] = $shipping_file;
                }

                $this->member_model->upload_fulfilment($fulfilment_data);
                echo json_encode(array('status' => true, 'msg' => '<p class="text-success text-center"><strong>Transponder order saved successfully</strong></p>'));
            }
        }
    }

    private function _upload($file, $path)
    {
        if (isset($_FILES[$file]["name"])) {
            $config["upload_path"] = $path;
            $config["allowed_types"] = 'xls|xlsx';
            $config['max_size']     = '1024';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
    public function edit_transponder($id)
    {
        $this->data['title'] = "Member &raquo Update transponders";
        $member = $this->ion_auth->user()->row()->id;
        $client = $this->_check_dept($member)['client'];
        $this->data['default_user'] = $client->default_user;
        $this->data['client_dept'] = $dept = $this->_check_dept($member)['dept'];
        $this->data['client_sub_dept'] = $sub_dept = $this->_check_dept($member)['sub_dept'];
        $this->data['client_sub'] = $this->_check_dept($member)['client'];
        $this->data['has_sub_depts'] = $has_sub_depts = $this->db->get_where('clients', ['client_id' => $client->client_id])->row()->sub_dept_exist;
        $this->data['overview_dept'] =  $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client->client_id)->get('departments')->row()->dept_id;
        $this->data['has_group'] = $group_id = $this->_check_dept($member)['group'];
        $this->data['client'] = $this->admin_model->client_profile($client->client_id)->organization;

        $org = $this->db->select('organization')->from('clients')->where('client_id', $client->client_id)->get()->row()->organization;
        $this->data['client_dept'] = $dept;
        $this->data['dept_name'] = ($dept == 0) ? 'All departments' : ucfirst($this->db->select('dept_name')->where('dept_id', $dept)->get('departments')->row()->dept_name);
        $this->data['action'] = 'frontend/member/edit_transponder';
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
        $this->data['fulfilment'] = $fulfilment = $this->member_model->get_fulfilments($id);
        $this->render('frontend/edit_transponder');
    }

    public function delete_fulfilment($id)
    {
        $fulfilment = $this->member_model->get_fulfilments($id);
        if ($this->member_model->delete_fulfilments($id)) {
            if ($fulfilment->asset_file) {
                unlink(FCPATH . '/uploads/fulfiment/' . $fulfilment->asset_file);
            }
            if ($fulfilment->shipping_list) {
                unlink(FCPATH . '/uploads/fulfiment/' . $fulfilment->shipping_list);
            }
            $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>Transponder order deleated sucessfully</strong><hr /></p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Transponder order deleate failed</strong><hr /></p>');
        }
        redirect('frontend/member/transponders');
    }
    public function update_transponder($status)
    {
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|required');
        $this->form_validation->set_rules('velcro', 'velcron', 'trim|required');
        $this->form_validation->set_rules('domicile_terminal', 'Domicile Terminal', 'trim|required');
        /*$this->form_validation->set_rules('shipping_address', 'Shipping address','trim|required'); */
        $this->form_validation->set_rules('assets', 'Asset (Rental OR AFP Owned)', 'trim|required');
        /*if (empty($_FILES['asset_file']['name'])) {
            $this->form_validation->set_rules('asset_file', 'Asset File','trim|required'); 
        }
        if (empty($_FILES['shipping_list']['name'])) {
             $this->form_validation->set_rules('shipping_list', 'Shipping List','trim|required');
        }*/

        /*$this->form_validation->set_rules('instructions', 'Fees Type','trim|required');*/

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => false, 'msg' => validation_errors('<li class="list-inline"><i class="fa fa-exclamation-circle"></i> ', '</li>')));
        } else {
            $asset_file = '';
            $shipping_file = '';

            if (!empty($_FILES['shipping_list']['name'])) {
                if (!$this->_upload("shipping_list", "./uploads/fulfiment/")) {
                    echo json_encode(array('status' => true, 'msg' => '<p class="text-success text-center"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</strong></p>'));
                } else {
                    $shipping_data = $this->upload->data();
                    $shipping_file = $shipping_data['file_name'];
                }
            }
            if (!empty($_FILES['asset_file']['name'])) {
                if (!$this->_upload("asset_file", "./uploads/fulfiment/")) {
                    echo json_encode(array('status' => true, 'msg' => '<p class="text-danger text-center"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</strong></p>'));
                } else {
                    $asset_data = $this->upload->data();
                    $asset_file = $asset_data['file_name'];
                }
            }
            $member = $this->ion_auth->user()->row()->id;
            $client = $this->_check_dept($member)['client'];
            $fulfilment_id = $this->input->post('fulfilment_id');
            $fulfilment_data = array(
                'quantity' => $this->input->post('quantity'),
                'velcro' => $this->input->post('velcro'),
                'domicile_terminal' => $this->input->post('domicile_terminal'),
                'shipping_address' => $this->input->post('shipping_address'),
                'assets' => $this->input->post('assets'),
                'client_id' => $client->client_id,
                'dept_id' => ($this->input->post('dept')) ?  $this->input->post('dept') : NULL,
                'status' => $status,
                'instructions' => $this->input->post('instructions')
            );
            if (!empty($asset_file)) {
                $fulfilment_data['asset_file'] = $asset_file;
            }
            if (!empty($shipping_file)) {
                $fulfilment_data['shipping_list'] = $shipping_file;
            }

            $this->member_model->update_fulfilments(['fulfilment_id' => $fulfilment_id], $fulfilment_data);
            echo json_encode(array('status' => true, 'msg' => '<p class="text-success text-center"><strong>Transponder order saved successfully</strong></p>'));
        }
    }
    public function posts()
    {

        $columns = array(
            0 => 'vehicle_id',
            1 => 'location',
            2 => 'tolltag',
            3 => 'vin_no',
            4 => 'unit',
            5 => 'color',
            6 => 'make',
            7 => 'model',
            8 => 'end_date',
            9 => 'license_plate',
        );


        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $client_id = $this->db->select('client_id')->from('clients')->where('organization', $this->input->post('client_name'))->get()->row()->client_id;
        $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client_id)->get('departments')->row()->dept_name;
        // $dept = $this->_check_dept($member)['dept'];
        $dept = ($this->input->post('member_dept')) ?? 0;
        // $sub_dept = $this->_check_dept($member)['sub_dept'];
        $sub_dept = ($this->input->post('member_sub_dept')) ?? 0;
        $group_id = 0;

        $totalData = $this->member_model->allposts_count($client_id, $dept, $sub_dept, $group_id);

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->member_model->allposts($limit, $start, $order, $dir, $client_id, $dept, $sub_dept, $group_id);
        } else {
            $search = $this->input->post('search')['value'];

            $posts =  $this->member_model->posts_search($limit, $start, $search, $order, $dir, $client_id, $dept, $sub_dept, $group_id);

            $totalFiltered = $this->member_model->posts_search_count($search, $client_id, $dept, $sub_dept, $group_id);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $nestedData['license_plate'] = $post->license_plate;
                $nestedData['dept'] = ($post->dept_name !== $overview_dept) ? $post->dept_name : "Overview";
                $nestedData['location'] = $post->location;
                $nestedData['tolltag'] = ($post->tolltag !== null) ? $post->tolltag : "<center>-</center>";
                $nestedData['vin_no'] = $post->vin_no;
                $nestedData['unit'] = ($post->unit !== null) ? $post->unit : "<center>-</center>";
                $nestedData['color'] = $post->color;
                $nestedData['make'] = $post->make;
                $nestedData['model'] = $post->model;
                $nestedData['start_date'] = date('m/d/Y', strtotime($post->start_date));
                $nestedData['end_date'] = ($post->end_date !== '0000-00-00') ? date('j M Y h:i a', strtotime($post->end_date)) : "<center>-</center>";
                $nestedData['action'] =
                    '<button type="button" onclick="edit_vehicle(' . $post->vehicle_id . ')" class="btn btn-sm btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit Vehicle"><i class="flaticon-pen"></i>
                                                            </button>';

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }
    public function transaction_server_side()
    {

        $columns = array(
            0 => 'invoice_id',
            1 => 'license_plate',
            2 => 'state_code',
            3 => 'state_code',
            4 => 'agency_name',
            5 => 'exit_date_time',
            6 => 'exit_lane',
            7 => 'exit_location',
            8 => 'exit_name',
            9 => 'toll',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $member = $this->ion_auth->user()->row()->id;
        $org = $this->input->post('org');
        $dept = ($this->input->post('client_dept')) ? $this->input->post('client_dept') : (($this->input->post('member_dept')) ?? 0);

        $client_id = $this->db->select('client_id')->from('clients')->where('organization', $this->input->post('org'))->get()->row()->client_id;
        $overview_dept = $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client_id)->get('departments')->row()->dept_name;
        $sub_dept = ($this->input->post('member_sub_dept')) ?? 0;
        $group_id = 0;

        $from = ($this->input->post('from')) ? date('Y-m-d', strtotime($this->input->post('from'))) : 0;
        $to = ($this->input->post('to')) ? date('Y-m-d', strtotime($this->input->post('to'))) : 0;
        $post_month = ($this->input->post('post_month')) ? date('Y-m', strtotime($this->input->post('post_month'))) : 0;
        $date_type = $this->input->post('date_type');

        $totalData = $this->member_model->t_allposts_count($org, $dept, $sub_dept, $group_id, $from, $to, $post_month, $date_type);

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->member_model->t_allposts($limit, $start, $order, $dir, $org, $dept, $sub_dept, $group_id, $from, $to, $post_month, $date_type);
            $totalSum = $this->member_model->t_allposts_sum($org, $dept, $sub_dept, $group_id, $from, $to, $post_month, $date_type);
        } else {
            $search = $this->input->post('search')['value'];

            $posts =  $this->member_model->t_posts_search($limit, $start, $search, $order, $dir, $org, $dept, $sub_dept, $group_id, $from, $to, $post_month, $date_type);
            $totalSum = $this->member_model->t_searchedposts_sum($search, $org, $dept, $sub_dept, $group_id, $from, $to, $post_month, $date_type);

            $totalFiltered = $this->member_model->t_posts_search_count($search, $org, $dept, $sub_dept, $group_id, $from, $to, $post_month, $date_type);
        }

        $data = array();
        if (!empty($posts)) {
            if ($org == 'caliber_auto_glass' || $org == 'protech_as') {
                foreach ($posts as $post) {
                    $nestedData['license_plate'] = $post->license_plate;
                    $nestedData['state_code'] = $post->state_code;
                    $nestedData['dept'] = ($post->dept_name !== $overview_dept) ? $post->dept_name : "Overview";
                    $nestedData['unit'] = ($post->unit !== null) ? $post->unit : "<center>-</center>";
                    $nestedData['agency_name'] = $post->agency_name;
                    $nestedData['exit_date_time'] = date('m/d/Y', strtotime($post->exit_date_time));
                    ($org !== 'amazon') ? $nestedData['exit_lane'] = $post->exit_lane : $nestedData['exit_lane'] = "<center>-</center>";
                    ($org !== 'amazon') ? $nestedData['exit_location'] = $post->exit_location : $nestedData['exit_location'] =  $post->exit_name;
                    $nestedData['toll'] = '$ ' . number_format($post->toll, 2);
                    $nestedData['action'] = '<button type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Dispute transaction" onclick="dispute_status(' . $post->invoice_id . ', \'' . $org . '\')"><i class="fa fa-exclamation-circle"></i></button>';

                    $data[] = $nestedData;
                }
            } else {
                foreach ($posts as $post) {
                    $nestedData['license_plate'] = $post->license_plate;
                    $nestedData['state_code'] = $post->state_code;
                    $nestedData['dept'] = ($post->dept_name !== $overview_dept) ? $post->dept_name : "Overview";
                    $nestedData['agency_name'] = $post->agency_name;
                    $nestedData['exit_date_time'] = date('m/d/Y', strtotime($post->exit_date_time));
                    ($org !== 'amazon') ? $nestedData['exit_lane'] = $post->exit_lane : $nestedData['exit_lane'] = "<center>-</center>";
                    ($org !== 'amazon') ? $nestedData['exit_location'] = $post->exit_location : $nestedData['exit_location'] =  $post->exit_name;
                    $nestedData['toll'] = '$ ' . number_format($post->toll, 2);
                    $nestedData['action'] = '<button type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Dispute transaction" onclick="dispute_status(' . $post->invoice_id . ', \'' . $org . '\')"><i class="fa fa-exclamation-circle"></i></button>';

                    $data[] = $nestedData;
                }
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "from"              => $from,
            "to"              => $to,
            "post_month"      => $post_month,
            "sum"              => is_null($totalSum->toll_sum) ? 0 :  $totalSum->toll_sum
        );
        echo json_encode($json_data);
    }

    public function dept_sub_departments($dept_id)
    {
        echo json_encode($this->member_model->get_sub_departments($dept_id));
    }
}

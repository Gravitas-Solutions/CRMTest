<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Auth_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('admin_model');
        $this->load->helper('date');

        if (!$this->ion_auth->is_admin()) {
            redirect('/');
        }
    }

    public function dashboard()
    {
        $this->data['title'] = "Dashboard";

        $this->data['vehicles'] = $this->db->count_all('vehicles');
        $this->data['states'] = $this->db->count_all('states');
        $this->data['tags'] = $this->db->count_all('tagtypes');
        $this->data['agencies'] = $this->db->count_all('agencies');
        $this->data['exitlocations'] = $this->db->count_all('exitlocations');
        $this->data['members'] = $this->ion_auth->users()->result();
        $this->data['roi_states'] = $this->admin_model->get_states();
        $this->data['uri'] = $this->uri;
        $this->render('backend/dashboard');
    }

    public function client_management()
    {
        $this->data['title'] = "Admin &raquo; Member &raquo;";
        $total_row = $this->db->from('clients')->get()->num_rows();

        /*Pagination*/
        $config = array();
        $config["base_url"] = base_url() . "backend/admin/client_management/";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 6;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['page_query_string'] = FALSE;

        $config['query_string_segment'] = '';
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul><!--pagination-->';

        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        if ($this->uri->segment(4)) {
            $page = ($this->uri->segment(4));
        } else {
            $page = 1;
        }
        $this->data['members'] = $this->admin_model->fetch_clients($config["per_page"], ($page - 1) * $config["per_page"], 0);
        $this->render('backend/client_management');
    }

    public function client_toll_spending()
    {
        $this->data['title'] = "Admin &raquo; Member &raquo;";
        $total_row = $this->db->from('clients')->get()->num_rows();

        /*Pagination*/
        $config = array();
        $config["base_url"] = base_url() . "backend/admin/client_toll_spending/";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 6;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['page_query_string'] = FALSE;

        $config['query_string_segment'] = '';
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul><!--pagination-->';

        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        if ($this->uri->segment(4)) {
            $page = ($this->uri->segment(4));
        } else {
            $page = 1;
        }
        $this->data['members'] = $this->admin_model->fetch_clients($config["per_page"], ($page - 1) * $config["per_page"], 1);
        $this->render('backend/client_toll_spending');
    }

    public function create_client()
    {
        $this->data['title'] = "Admin &raquo; New Client";
        $this->data['categories'] = $this->db->from('client_categories')->get()->result();
        if ($this->_client_validate() === FALSE) {
            $this->render('auth/client_register');
        } else {
            $username = strtolower(url_title(trim($this->input->post('company')), 'underscore'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user_id = $this->ion_auth->register($username, $password, $email);
            if (!$user_id) {
                $this->session->set_flashdata('message', '<p class="text-danger text-danger"><strong>Error creating client account</strong></p>');
                $this->session->set_flashdata('message', '<p class="text-danger text-danger"><strong>Error creating client account</strong>' . $this->ion_auth->errors('<p>', '</p>') . '</p>');
                $this->render('auth/client_register');
            } else {
                $logo = '';
                if ($_FILES['logo']["name"] != '') {
                    if ($this->_upload("logo", "./assets/images/client_logo/") === FALSE) {
                        $this->session->set_flashdata('message', $this->upload->display_errors('', ''));
                        $this->render('auth/client_register');
                    } else {
                        $data = $this->upload->data();
                        $logo .= $data["file_name"];
                    }
                } else {
                    $logo .= 'no_logo.png';
                }
                $organization = strtolower(url_title(trim($this->input->post('company')), 'underscore'));
                $client_data = array(
                    'address' => $this->input->post('address'),
                    'phone' => $this->input->post('company_phone'),
                    'organization' => $organization,
                    'category_id' => $this->input->post('category'),
                    'org_email' => $this->input->post('org_email'),
                    'demo_acc' => $this->input->post('demo_acc'),
                    'logo'         => $logo
                );
                $client_id = $this->admin_model->save_client($client_data);
                $overview_department_id = $this->admin_model->save_dept(['dept_name' => '[overview ' . $organization . ']', 'client_id' => $client_id]);
                $contact_data = array(
                    'first_name' => $this->input->post('firstname'),
                    'last_name' => $this->input->post('lastname'),
                    'phone' => $this->input->post('phone'),
                    'title' => $this->input->post('title'),
                    'user_id' => $user_id,
                    'client_id' => $client_id,
                    'department_id' => $overview_department_id,
                    'modules' => 'vehicles,transactions,invoices,citations',
                    'default_user' => 1,
                    'can_update' => 1
                );
                $this->admin_model->save_client_contact($contact_data);

                $this->create_client_tbl($organization);
                $update_date = date('Y-m-d h:i:s');
                $account_balance = array(
                    'dept_id' => $overview_department_id,
                    'balance' => 0
                );
                $this->admin_model->add_client_balance($account_balance);

                $this->session->set_flashdata('message', '<p class="text-success text-center">Client<strong><em> ' . ucwords(str_replace('_', ' ', $this->input->post('company'))) . '</em></strong> account details saved successfully</p>');
                redirect('add-client', 'refresh');
            }
        }
    }

    private function create_client_tbl($client)
    {
        $this->load->dbforge();
        $fields = array(
            'invoice_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),

            'license_plate' => array(
                'type' => 'VARCHAR',
                'constraint' => '15',
            ),
            'state_code' => array(
                'type' => 'CHAR',
                'constraint' => '5',
            ),
            'agency_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'exit_location' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'exit_date_time' => array(
                'type' => 'DATETIME',
            ),
            'exit_lane' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'toll' => array(
                'type' => 'DOUBLE',
                'constraint' => '5,2',
            ),
            'dump_date' => array(
                'type' => 'DATETIME',
            ),
            'date_for' => array(
                'type' => 'DATETIME',
            ),
            'dispute' => array(
                'type' => 'TINYINT',
                'default' => '0',
            ),
            'dept_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),

            'sub_dept_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null'  => TRUE
            ),

             'unit' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null'  => TRUE
            ),

             'reason' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
                'null'  => TRUE
            ),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('invoice_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table($client . '_invoice', TRUE, $attributes);
    }

    private function _dept_validate()
    {
        /*User details*/
        $this->form_validation->set_rules('email', 'Email Address ', 'trim|required|valid_email|is_unique[users.email]', array('is_unique' => 'Email address already registered', 'required' => 'Provide contact email', 'valid_email' => 'Provide a valid email'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required', array('required' => 'Choose password'));
        $this->form_validation->set_rules('conf_password', 'Password confirmation', 'trim|required|matches[conf_password]', array('required' => 'Re-type chosen password', 'matches' => 'Password mismatch'));

        /*Company details*/
        $this->form_validation->set_rules('company', 'department Name', 'trim|required', array('required' => 'Provide department name'));
        $this->form_validation->set_rules('address', 'Address', 'trim|required', array('required' => 'Provide department address'));
        $this->form_validation->set_rules('company_phone', 'Company phone', 'trim|required|is_unique[clients.phone]', array('is_unique' => 'Phone number already used', 'required' => 'Provide department phone number'));
        $this->form_validation->set_rules('org_email', 'Email Address ', 'trim|required|valid_email|is_unique[clients.org_email]', array('is_unique' => 'Email address already registered', 'required' => 'Provide department email', 'valid_email' => 'Provide a valid email'));
        $this->form_validation->set_rules('category', '', 'trim|required', array('required' => 'Select category'));
        //$this->form_validation->set_rules('demo_acc', '','trim|required', array('required' => 'Select account type'));

        /*Contact person*/
        $this->form_validation->set_rules('firstname', 'First Name ', 'trim|required', array('required' => 'Provide contact\'s first name'));
        $this->form_validation->set_rules('lastname', 'Last Name ', 'trim|required', array('required' => 'Provide contact\'s last name'));
        $this->form_validation->set_rules('phone', 'Contact Phone ', 'trim|required|is_unique[contacts.phone]', array('is_unique' => 'Phone number already used', 'required' => 'Provide contact\'s phone number'));
        $this->form_validation->set_rules('title', 'Job Title', 'trim|required', array('required' => 'Provide contact\'s job designation'));

        if ($this->form_validation->run() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function _client_validate()
    {
        /*User details*/
        $this->form_validation->set_rules('email', 'Email Address ', 'trim|required|valid_email|is_unique[users.email]', array('is_unique' => 'Email address already registered', 'required' => 'Provide company email address', 'valid_email' => 'Provide a valid email address'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required', array('required' => 'Choose password'));
        $this->form_validation->set_rules('conf_password', 'Password confirmation', 'trim|required|matches[conf_password]', array('required' => 'Re-type chosen password', 'matches' => 'Password mismatch'));

        /*Company details*/
        $this->form_validation->set_rules('company', 'Company Name', 'trim|required', array('required' => 'Provide company name'));
        $this->form_validation->set_rules('address', 'Address', 'trim|required', array('required' => 'Provide company address'));
        $this->form_validation->set_rules('company_phone', 'Company phone', 'trim|required|is_unique[clients.phone]', array('is_unique' => 'Phone number already used', 'required' => 'Provide company phone number'));
        $this->form_validation->set_rules('org_email', 'Email Address ', 'trim|required|valid_email|is_unique[clients.org_email]', array('is_unique' => 'Email address already registered', 'required' => 'Provide company email address', 'valid_email' => 'Provide a valid email address'));
        $this->form_validation->set_rules('category', 'Category Address ', 'trim|required', array('required' => 'Select category'));

        /*Contact person*/
        $this->form_validation->set_rules('firstname', 'First Name ', 'trim|required', array('required' => 'Provide contact\'s first name'));
        $this->form_validation->set_rules('lastname', 'Last Name ', 'trim|required', array('required' => 'Provide contact\'s last name'));
        $this->form_validation->set_rules('phone', 'Contact Phone ', 'trim|required|is_unique[contacts.phone]', array('is_unique' => 'Phone number already used', 'required' => 'Provide contact\'s phone number'));
        $this->form_validation->set_rules('title', 'Job Title', 'trim|required', array('required' => 'Provide contact\'s job designation'));

        if ($this->form_validation->run() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function _upload($file, $path)
    {
        if (isset($_FILES[$file]["name"])) {
            $config["upload_path"] = $path;
            $config["allowed_types"] = 'jpg|jpeg|png';
            $config['max_size']     = '1024';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function client_records($client)
    {
        $this->data['title'] = "Admin &raquo; Client Records";
        $this->data['org'] = $this->db->select('organization')->from('clients')->where('client_id', $client)->get()->row()->organization;
        $this->render('backend/client_records');
    }

    public function client_profile($id)
    {
        $this->data['title'] = "Admin &raquo; Client Profile";
        $this->data['client'] = $this->admin_model->client_profile($id);
        $this->render('backend/member_profile');
    }

    public function dept_profile($dept)
    {
        $this->data['title'] = "Admin &raquo; Dept Profile";
        $this->data['categories'] = $this->db->from('client_categories')->get()->result();
        $has_contact = ($this->db->get_where('contacts', ['department_id' => $dept])->row()) ? true : false;
        $has_category = ($this->db->get_where('departments', ['dept_id' => $dept])->row()->category_id) ? true : false;
        $this->data['client'] = $this->admin_model->get_client_departments($dept, $has_contact, $has_category);
        $this->render('backend/dept_profile');
    }

    public function client_road_tolls($org, $date)
    {
        $this->data['title'] = "Admin &raquo; Client Profile";
        $this->data['client'] = $org;
        $this->data['states'] = $this->db->select('distinct(state_code)')->from($org . '_invoice')->get()->result();

        $month = date('Y-m');
        $this->data['monthly_road_tolls'] = $this->admin_model->get_roads_by_client($org, $month);
        $this->data['breadcrumb'] = date('F, Y', strtotime($month));
        $client_id = $this->admin_model->get_clients_id($org)->client_id;
        $this->data['client_depts'] = $this->admin_model->get_departments($client_id);
        $this->render('backend/client_daily_road_tolls');
    }

    public function edit_client($client)
    {
        $this->data['title'] = "Admin &raquo; Update Client Profile";
        $this->data['categories'] = $this->db->from('client_categories')->get()->result();
        $this->data['client'] = $this->admin_model->client_profile($client);
        $this->render('auth/client_edit');
    }
    public function edit_client_dept($dept_id)
    {
        $client_dept = $this->db->get_where('departments', ['dept_id' => $dept_id])->row();
        if (!$client_dept) {
            echo json_encode(['status' => false, 'msg' => 'Error retrieving client department']);
        } else {
            echo json_encode(['status' => true, 'msg' => $client_dept]);
        }
    }

    public function update_client_dept()
    {
        $dept_id = $this->input->post('id');
        $this->form_validation->set_rules('company', 'department name', 'required|trim');
        $this->form_validation->set_rules('address', 'department address', 'required|trim');
        $this->form_validation->set_rules('phone', 'department phone', 'required|trim');
        $this->form_validation->set_rules('email', 'department email', 'required|trim|valid_email');
        $this->form_validation->set_rules('category', '', 'required|trim', ['required' => 'Select category']);
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['status' => false, 'msg' => validation_errors()]);
        } else {
            $dept_data = array(
                'dept_address' => $this->input->post('address'),
                'dept_phone' => $this->input->post('phone'),
                'dept_name' => $this->input->post('company'),
                'category_id' => $this->input->post('category'),
                'dept_email' => $this->input->post('email')
            );
            $dept_logo = array();
            if ($_FILES['logo']["name"] != '') {
                if ($this->_upload("logo", "./assets/images/client_logo/") === FALSE) {
                    echo json_encode(['status' => false, 'msg' => $this->upload->display_errors()]);
                } else {
                    $data = $this->upload->data();
                    $dept_logo = array('logo' => $data["file_name"]);
                }
            }
            $updates = array_merge($dept_data, $dept_logo);
            if (!$this->admin_model->update_dept(array('dept_id' => $dept_id), $updates)) {
                echo json_encode(['status' => false, 'msg' => 'Nothing to update']);
            } else {
                echo json_encode(['status' => true, 'msg' => 'Department details updated successfully']);
            }
        }
    }

    public function update_client()
    {
        $client = $this->input->post('id');
        $client_data = array(
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('company_phone'),
            'org_email' => $this->input->post('email'),
            'category_id' => $this->input->post('category'),
            'demo_acc' => $this->input->post('demo_acc')
        );
        $client_logo = array();
        if ($_FILES['logo']["name"] != '') {
            if ($this->_upload("logo", "./assets/images/client_logo/") === FALSE) {
                $this->session->set_flashdata('message', $this->upload->display_errors('<p class="text-danger text-center">', '</p>'));
                redirect('backend/admin/edit_client/' . $client, 'refresh');
            } else {
                $data = $this->upload->data();
                $client_logo = array('logo' => $data["file_name"]);
            }
        }
        $updates = array_merge($client_data, $client_logo);
        if (!$this->admin_model->update_client(array('client_id' => $client), $updates)) {
            $this->session->set_flashdata('message', '<p class="text-info text-center">Nothing to update</p>');
            redirect('backend/admin/edit_client/' . $client, 'refresh');
        } else {
            $this->session->set_flashdata('message', '<p class="text-success text-center"><strong><em>' . ucwords(str_replace('_', ' ', $this->input->post('company'))) . '\'s</em></strong> details updated successfully</p>');
            redirect('backend/admin/edit_client/' . $client, 'refresh');
        }
    }

    public function update_password()
    {
        $this->form_validation->set_rules('pass', 'Password', 'trim|required|matches[conf_pass]');
        $this->form_validation->set_rules('conf_pass', 'Password confirmation', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors()));
        } else {
            $user_email = $this->ion_auth->user($this->input->post('id'))->row()->email;
            $pass_change = array('password' => $this->input->post('pass'));
            $this->ion_auth->reset_password($user_email, $this->input->post('pass'));
            echo json_encode(array('status' => TRUE, 'msg' => 'Password change success'));
        }
    }

    public function update_client_account($id)
    {
        $active = $this->db->get_where('clients', ['client_id' => $id])->row()->client_status;
        $activation = array('client_status' => ($active == 1) ? 0 : 1);
        if (!$this->db->update('clients', $activation, ['client_id' => $id])) {
            echo json_encode(array('status' => FALSE, 'msg' => 'Error updating client account'));
        } else {
            $client_users = $this->db->select('user_id')->get_where('contacts', ['client_id' => $id])->result();
            $user_activation = array('active' => ($active == 1) ? 0 : 1);
            foreach ($client_users as $c) {
                $this->ion_auth->update($c->user_id, $user_activation);
            }
            echo json_encode(array('status' => TRUE, 'msg' => 'Client and its user accounts updated as requested'));
        }
    }

    public function update_dept_status($id)
    {
        $active = $this->db->get_where('departments', ['dept_id' => $id])->row()->dept_status;
        $activation = array('dept_status' => ($active == 1) ? 0 : 1);
        if (!$this->db->update('departments', $activation, ['dept_id' => $id])) {
            echo json_encode(array('status' => FALSE, 'msg' => 'Error updating department status'));
        } else {
            $dept_users = $this->db->select('user_id')->get_where('contacts', ['department_id' => $id])->result();
            if ($dept_users) {
                $user_activation = array('active' => ($active == 1) ? 0 : 1);
                foreach ($client_users as $c) {
                    $this->ion_auth->update($c->user_id, $user_activation);
                }
            }
            echo json_encode(array('status' => TRUE, 'msg' => 'Department and its system user accounts updated as requested'));
        }
    }



    public function admin_update_password()
    {
        $this->form_validation->set_rules('pass', 'Password', 'trim|required|matches[conf_pass]');
        $this->form_validation->set_rules('conf_pass', 'Password confirmation', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors()));
        } else {
            $user_email = $this->ion_auth->user()->row()->email;
            $pass_change = array('password' => $this->input->post('pass'));
            $this->ion_auth->reset_password($user_email, $this->input->post('pass'));
            echo json_encode(array('status' => TRUE, 'msg' => 'Password change success'));
        }
    }

    public function activate_client($id)
    {
        $active = $this->ion_auth->user($id)->row()->active;
        $activation = array('active' => ($active == 1) ? 0 : 1);
        $this->ion_auth->update($id, $activation);
        echo json_encode(array('status' => TRUE, 'msg' => 'User status changed'));
    }

    public function admin_duties()
    {
        $this->data['title'] = "Executive Dashboard";
        $this->data['vehicles'] = $this->db->count_all('vehicles');
        $this->data['states'] = $this->db->count_all('states');
        $this->data['tag'] = $this->db->count_all('tagtypes');
        $this->data['agency'] = $this->db->count_all('agencies');
        $this->data['exitlocations'] = $this->db->count_all('exitlocations');
        $this->data['value'] = $this->db->select('SUM(toll_amount) AS value')->from('invoices')->get()->row()->value;
        $this->render('backend/sub_dashboard');
    }

    public function create_dept($id)
    {
        $this->data['title'] = "Admin &raquo; New Department";
        $this->data['categories'] = $this->db->from('client_categories')->get()->result();
        if ($this->_dept_validate() === FALSE) {
            $this->render('auth/dept_register');
        } else {
            $username = strtolower(url_title(trim($this->input->post('company')), 'underscore'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user_id = $this->ion_auth->register($username, $password, $email);
            if (!$user_id) {
                $this->session->set_flashdata('message', $this->ion_auth->errors('<p>', '</p>'));
                $this->render('auth/dept_register');
            } else {
                if ($_FILES['logo']["name"] != '') {
                    if ($this->_upload("logo", "./assets/images/client_logo/") === FALSE) {
                        $this->session->set_flashdata('message', $this->upload->display_errors('', ''));
                        $this->render('auth/dept_register');
                    } else {
                        $data = $this->upload->data();
                        $logo = $data["file_name"];
                    }
                }
                $organization = strtolower(url_title(trim($this->input->post('company')), 'underscore'));
                $dept_data = array(
                    'dept_address' => $this->input->post('address'),
                    'dept_phone' => $this->input->post('company_phone'),
                    'dept_name' => ucwords($this->input->post('company')),
                    'category_id' => $this->input->post('category'),
                    'dept_email' => $this->input->post('org_email'),
                    'client_id' => $id,
                    'logo'         => ($logo) ?? NULL
                );

                $dept_id = $this->admin_model->save_dept($dept_data);
                $overview_sub_department_id = $this->db->insert('sub_departments', ['sub_dept_name' => '[overview ' . $organization . ']', 'dept_id' => $dept_id]);

                $contact_data = array(
                    'first_name' => $this->input->post('firstname'),
                    'last_name' => $this->input->post('lastname'),
                    'phone' => $this->input->post('phone'),
                    'title' => $this->input->post('title'),
                    'user_id' => $user_id,
                    'client_id' => $id,
                    'department_id' => $dept_id,
                    'sub_dept_id' => $overview_sub_department_id,
                    'modules' => 'vehicles,transactions,invoices,citations',
                    'default_user' => 1,
                    'can_update' => $this->input->post('vehicle_updater') ?? 0
                );
                $this->admin_model->save_client_contact($contact_data);

                $account_balance = array(
                    'dept_id' => $dept_id,
                    'balance' => 0
                );
                $this->admin_model->add_client_balance($account_balance);

                $this->session->set_flashdata('message', '<p class="text-success text-center">Department<strong><em> ' . ucwords(str_replace('_', ' ', $this->input->post('company'))) . '</em></strong> account details saved successfully</p>');
                redirect('backend/admin/create_dept/' . $this->uri->segment(4), 'refresh');
            }
        }
    }


    public function client_departments($id)
    {
        $this->data['title'] = "Admin &raquo; Department";
        $this->data['client'] = $client = $this->db->select('organization')->from('clients')->where('client_id', $id)->get()->row();
        $client = $this->db->select('sub_dept_exist')->from('clients')->where('client_id', $id)->get()->row();
        if ($client->sub_dept_exist) {
            $total_row = $this->db->where('client_id', $id)->get('departments')->num_rows();
            /*Pagination*/
            $config = array();
            $config["base_url"] = base_url() . "backend/admin/client_departments/" . $id;
            $config["total_rows"] = $total_row;
            $config["per_page"] = 6;
            $config['use_page_numbers'] = TRUE;
            $config['num_links'] = $total_row;
            $config['page_query_string'] = FALSE;

            $config['query_string_segment'] = '';
            $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
            $config['full_tag_close'] = '</ul><!--pagination-->';

            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';

            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';

            $config['next_link'] = 'Next &rarr;';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';

            $config['prev_link'] = '&larr; Previous';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';

            $this->load->library('pagination');
            $this->pagination->initialize($config);
            if ($this->uri->segment(5)) {
                $page = ($this->uri->segment(5));
            } else {
                $page = 1;
            }
            $this->data['departments'] = $this->admin_model->get_departments_paginator($config["per_page"], ($page - 1) * $config["per_page"], $id);
            $this->render('backend/dept_management');
        } else {

            $this->data['client_id'] = $id;
            $this->data['departments'] = $this->admin_model->get_departments($id);
            $this->render('backend/client_departments');
        }
    }


    public function org_departments($org)
    {
        $id = $this->db->from('clients')->where('organization', $org)->get()->row()->client_id;
        echo json_encode($this->admin_model->departments($id));
    }

    public function get_client_departments($org)
    {
        $id = $this->db->from('clients')->where('client_id', $org)->get()->row()->client_id;
        echo json_encode($this->admin_model->departments($id));
    }
    public function vehicle_departments($id)
    {
        echo json_encode($this->admin_model->departments($id));
    }

    public function add_dept()
    {
        $client_id = $this->input->post('client_id');
        $this->form_validation->set_rules(
            'department[]',
            'Department Name',
            'trim|required',
            array('required' => 'All fields are required')
        );
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $dept = $this->input->post('department[]');
            $department_data = array();
            for ($i = 0; $i < count($dept); $i++) {
                $department_data[] = array('dept_name' => $dept[$i], 'client_id' => $client_id);
            }
            $this->db->insert_batch('departments', $department_data);
            echo json_encode(array('status' => TRUE, 'msg' => 'Department(s) saved successfully'));
        }
    }

    public function edit_dept($dept_id)
    {
        $dept = $this->admin_model->dept_by_id($dept_id);
        if ($dept) {
            echo json_encode(array('status' => TRUE, 'dept' => $dept));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting department'));
        }
    }

    public function update_dept()
    {
        $this->form_validation->set_rules('dept_name', 'Department Name', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {

            if ($_FILES['logo']["name"] != '') {
                if ($this->_upload("logo", "./assets/images/client_logo/") === FALSE) {
                    echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
                } else {
                    $data = $this->upload->data();
                    $logo = $data["file_name"];
                    $update = array('dept_name' => $this->input->post('dept_name'), 'logo' => $logo);
                    $this->admin_model->update_dept(array('dept_id' => $this->input->post('dept_id')), $update);
                    echo json_encode(array('status' => TRUE, 'msg' => 'Department updated successfully'));
                }
            } else {
                $update = array('dept_name' => $this->input->post('dept_name'));
                $this->admin_model->update_dept(array('dept_id' => $this->input->post('dept_id')), $update);
                echo json_encode(array('status' => TRUE, 'msg' => 'Department name updated'));
            }
        }
    }

    public function delete_dept($id)
    {
        $client = $this->db->get_where('departments', ['dept_id' => $id])->row()->client_id;
        $org = $this->db->select('organization')->from('clients')->join('departments', 'clients.client_id = departments.client_id')->where('clients.client_id', $client)->get()->row()->organization;
        /*Department linkup*/
        $contacts_linked = ($this->db->get_where('contacts', ['department_id' => $id])->row()) ? true : false;
        $vehicles_linked = ($this->db->get_where('vehicles', ['dept_id' => $id])->row()) ? true : false;
        $org_invoice_linked = ($this->db->get_where($org . '_invoice', ['dept_id' => $id])->row()) ? true : false;
        //$excel_dump_linked = ($this->db->get_where('excel_dump', ['dept_id' => $id])->row()) ? true : false;
        $invoice_month_linked = ($this->db->get_where('invoice_month', ['dept_id' => $id])->row()) ? true : false;
        //$vehicle_excel_dump_linked = ($this->db->get_where('vehicle_excel_dump', ['dept_id' => $id])->row()) ? true : false;
        if (!$contacts_linked && !$vehicles_linked && !$org_invoice_linked /*&& !$excel_dump_linked */ && !$invoice_month_linked /*&& !$vehicle_excel_dump_linked*/) {
            if (!$this->admin_model->delete_dept($id)) {
                echo json_encode(array("status" => FALSE, 'msg' => 'Error deleting department'));
            } else {
                echo json_encode(array("status" => TRUE, 'msg' => 'Department deleted successfully'));
            }
        } else {
            echo json_encode(array("status" => FALSE, 'msg' => 'Can\'t delete - data attached to this department'));
        }
    }

    public function client_dept_grouping($id)
    {
        $this->data['client'] = $this->db->select('organization, client_id')->from('clients')->where('client_id', $id)->get()->row();
        $this->data['title'] = "Admin &raquo; Manage Department Grouping";
        $this->data['groups'] = $this->admin_model->get_dept_grouping($id);
        $this->data['client_departments'] = $this->admin_model->departments($id);
        $this->render('backend/department_grouping');
    }

    public function add_group()
    {
        $client_id = $this->input->post('client_id');
        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required', array('required' => 'Group Name is required'));
        $this->form_validation->set_rules('group[]', '', 'trim|required', ['required' => 'Select at least one Department']);
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {

            $data = array(
                'group_name' => strtolower($this->input->post('group_name')),
                'client_id' => $client_id
            );
            if ($group_id = $this->admin_model->add_group($data)) {
                foreach ($this->input->post('group[]') as $g) {
                    $this->admin_model->select_dept_group(['group_id' => $group_id], $g);
                }
                echo json_encode(array('status' => TRUE, 'msg' => 'Group saved successfully'));
            } else {
                echo json_encode(array('status' => FALSE, 'msg' => 'Error Adding Group'));
            }
        }
    }


    public function edit_group($group_id)
    {
        $group_depts = $this->admin_model->dept_by_group_id($group_id);
        if ($group_depts) {
            echo json_encode(array('status' => TRUE, 'dept' => $group_depts));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting group departments'));
        }
    }

    public function update_group()
    {
        $client_id = $this->input->post('client_id');
        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required', array('required' => 'Group Name is required'));
        $this->form_validation->set_rules('group[]', '', 'trim|required', ['required' => 'Select at least one Department']);
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {

            $group_id = $this->input->post('group_id');
            $data = array(
                'group_name' => strtolower($this->input->post('group_name'))
            );
            //updating new group imfo
            $this->admin_model->update_group($data, $group_id);
            //removing previous department group id                    
            if ($this->admin_model->update_dept_group(['group_id' => null], $group_id)) {
                foreach ($this->input->post('group[]') as $g) {
                    $this->admin_model->select_dept_group(['group_id' => $group_id], $g);
                }

                echo json_encode(array('status' => TRUE, 'msg' => 'Group updated successfully'));
            } else {
                echo json_encode(array('status' => FALSE, 'msg' => 'Error updating group'));
            }
        }
    }

    public function delete_group($id)
    {
        $client = $this->db->get_where('department_grouping', ['group_id' => $id])->row()->client_id;
        $linked_department = ($this->db->delete('departments', 'group_id', ['group_id' => $this->input->post('group_id')])) ? true : false;

        if (!$linked_department) {
            echo json_encode(array("status" => FALSE, 'msg' => 'Error detaching attached department'));
        } else {
            if (!$this->admin_model->delete_group($id)) {
                echo json_encode(array("status" => FALSE, 'msg' => 'Error deleting group'));
            }
            echo json_encode(array("status" => TRUE, 'msg' => 'Group deleted and department attached sucessfuly detached'));
        }
    }

    /*Sub-departs section*/
    public function sub_depts($dept_id)
    {
        $this->data['title'] = "Admin &raquo; Sub Department";
        $client_id = $this->db->get_where('departments', ['dept_id' => $dept_id])->row()->client_id;
        $this->data['client'] = $this->db->select('organization')->from('clients')->where('client_id', $client_id)->get()->row();
        $this->data['dept_id'] = $dept_id;
        $this->data['dept_name'] = $this->db->get_where('departments', ['dept_id' => $dept_id])->row()->dept_name;
        $this->data['sub_departments'] = $this->admin_model->get_sub_departments($dept_id);
        $this->render('backend/client_sub_departments');
    }

    public function dept_sub_departments($dept_id)
    {
        echo json_encode($this->admin_model->get_sub_departments($dept_id));
    }

    public function add_sub_dept()
    {
        $dept_id = $this->input->post('dept_id');

        $this->form_validation->set_rules('sub_department[]', '', 'trim|required', array('required' => 'Provide sub-department name'));
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $sub_dept = $this->input->post('sub_department[]');
            $sub_department_data = array();
            for ($i = 0; $i < count($sub_dept); $i++) {
                $sub_department_data[] = array('sub_dept_name' => $sub_dept[$i], 'dept_id' => $dept_id);
            }
            if (!$this->db->insert_batch('sub_departments', $sub_department_data)) {
                echo json_encode(array('status' => FALSE, 'msg' => 'Sub-department(s) save failed'));
            } else {
                $client_id = $this->db->get_where('departments', ['dept_id' => $dept_id])->row()->client_id;
                $this->db->update('clients', ['sub_dept_exist' => 1], ['client_id' => $client_id]);
                echo json_encode(array('status' => TRUE, 'msg' => 'Sub-department(s) saved successfully'));
            }
        }
    }

    public function edit_sub_dept($sub_dept_id)
    {
        $sub_dept = $this->admin_model->get_sub_dept($sub_dept_id);
        if ($sub_dept) {
            echo json_encode(array('status' => TRUE, 'sub_dept' => $sub_dept));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting sub-department'));
        }
    }

    public function update_sub_dept()
    {
        $this->form_validation->set_rules('sub_dept_name', 'Sub-department Name', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $update = array('sub_dept_name' => $this->input->post('sub_dept_name'));
            $updated = $this->admin_model->update_sub_dept(array('sub_dept_id' => $this->input->post('sub_dept_id')), $update);
            if (!$updated) {
                echo json_encode(array('status' => FALSE, 'msg' => 'Failed saving sub-department'));
            } else {
                echo json_encode(array('status' => TRUE, 'msg' => 'Sub-department updated successfully'));
            }
        }
    }

    public function delete_sub_dept($sub_dept_id)
    {
        $dept_id = $this->db->get_where('sub_departments', ['sub_dept_id' => $sub_dept_id])->row()->dept_id;
        $client = $this->db->get_where('departments', ['dept_id' => $dept_id])->row()->client_id;
        $org = $this->db->select('organization')->from('clients')->join('departments', 'clients.client_id = departments.client_id')->where('clients.client_id', $client)->get()->row()->organization;
        /*Department linkup*/
        $contacts_linked = ($this->db->get_where('contacts', ['sub_dept_id' => $sub_dept_id])->row()) ? true : false;
        $vehicles_linked = ($this->db->get_where('vehicles', ['sub_dept_id' => $sub_dept_id])->row()) ? true : false;
        $org_invoice_linked = ($this->db->get_where($org . '_invoice', ['sub_dept_id' => $sub_dept_id])->row()) ? true : false;
        //$excel_dump_linked = ($this->db->get_where('excel_dump', ['dept_id' => $id])->row()) ? true : false;
        //$invoice_month_linked = ($this->db->get_where('invoice_month', ['sub_dept_id' => $sub_dept_id])->row()) ? true : false;
        //$vehicle_excel_dump_linked = ($this->db->get_where('vehicle_excel_dump', ['dept_id' => $id])->row()) ? true : false;
        if (!$contacts_linked && !$vehicles_linked && !$org_invoice_linked /*&& !$excel_dump_linked */ /*&& !$vehicle_excel_dump_linked*/) {
            if (!$this->admin_model->delete_sub_dept($sub_dept_id)) {
                echo json_encode(array("status" => FALSE, 'msg' => 'Error deleting sub-department'));
            } else {
                echo json_encode(array("status" => TRUE, 'msg' => 'Sub-Department deleted successfully'));
            }
        } else {
            echo json_encode(array("status" => FALSE, 'msg' => 'Can\'t delete - data attached to this sub-department'));
        }
    }

    /*Values section*/
    public function values($date = NULL)
    {
        $date = (!$this->input->post('value_day')) ? date('Y-m-d', NOW()) : date('Y-m-d', strtotime($this->input->post('value_day')));
        //$date = ;
        $this->data['title'] = "Admin &raquo; Values";
        $this->data['breadcrumb'] = 'Date: ' . $date;
        $this->data['values'] = $this->admin_model->get_values($date);
        $this->render('backend/values');
    }

    public function values_by_member_vehicles($member)
    {
        $date = date('Y-m-d', NOW());
        $this->data['title'] = 'Admin &raquo; Member Vehicles';
        $this->data['member_mail'] = $this->ion_auth->user($member)->row()->email;
        $this->data['breadcrumb'] = 'Today: ' . $date;
        $this->data['vehicles'] = $this->admin_model->get_member_values_by_vehicle($member, $date);
        $this->render('backend/member_vehicle_tolls');
    }

    /*Vehicles*/
    public function vehicles()
    {
        $this->data['title'] = 'Admin &raquo; Vehicles';
        /*$this->data['vehicles'] = $this->admin_model->client_vehicles(0, 0);*/
        $this->data['agencies'] = $this->admin_model->get_agencies();
        $this->data['tags'] = $this->admin_model->get_tags();
        $this->data['states'] = $this->admin_model->get_states();
        $this->data['breadcrumb'] = 'All statuses & members';
        $this->data['topbar'] = 1;
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->render('backend/vehicles');
    }

    public function client_vehicles($client = 0)
    {
        $this->data['title'] = 'Admin &raquo; Client Vehicles';
        $id = (isset($client) && $client != 0) ? $client : $this->input->post('member');
        $dept = ($this->input->post('department')) ? $this->input->post('department') : 0;
        $this->data['vehicles'] = $this->admin_model->client_vehicles($id, $dept);
        $this->data['clients'] = $this->admin_model->get_clients_list();;
        $this->data['agencies'] = $this->admin_model->get_agencies();
        $this->data['tags'] = $this->admin_model->get_tags();
        $this->data['breadcrumb'] = ($this->input->post('member') != 0) ? ($this->input->post('department') ?  ucwords($this->db->get_where('clients', ['client_id' => $id])->row()->organization) . '-' .  $this->db->get_where('departments', ['dept_id' => $dept])->row()->dept_name : $this->db->get_where('clients', ['client_id' => $id])->row()->org_email) : 'All vehicles';
        $this->data['topbar'] = 1;
        $this->data['accessId'] = 0;
        $this->render('backend/vehicles');
    }

    public function vehicles_by_status()
    {
        $this->data['title'] = 'Admin &raquo; Vehicles&raquo;Status';
        $this->data['vehicles'] = $this->admin_model->vehicles_by_status($this->input->post('status'));
        $this->data['states'] = $this->admin_model->get_states();
        $this->data['tags'] = $this->admin_model->get_tags();
        $this->data['breadcrumb'] = ($this->input->post('status') == 1) ? 'Active status' : 'Inactive status';
        $this->data['topbar'] = 1;
        $this->data['accessId'] = 0;
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->render('backend/vehicles');
    }

    public function add_vehicle()
    {
        if ($this->_vehicle_validate() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<span>', ' | </span>')));
            exit;
        } else {
            $vehicle = array(
                'license_plate' => trim($this->input->post('license_plate')),
                'model' => $this->input->post('model'),
                'make' => $this->input->post('make'),
                'color' => $this->input->post('color'),
                'axles' => $this->input->post('axles'),
                'start_date' => ($this->input->post('start_date')) ? date('Y-m-d', strtotime($this->input->post('start_date'))) : date('Y-m-d'),
                'end_date' => ($this->input->post('end_date')) ? date('Y-m-d', strtotime($this->input->post('end_date'))) : '0000-00-00',
                'tag_id' => $this->input->post('tagtype'),
                'store' => $this->input->post('store'),
                'location' => $this->input->post('location'),
                'unit' => $this->input->post('unit'),
                'year' => $this->input->post('year'),
                'client_id' => $this->input->post('client'),
                'dept_id' => $this->input->post('dept'),
                'sub_dept_id' => ($this->input->post('sub_dept')) ?? NULL,
                'tolltag' => $this->input->post('tolltag'),
                'vin_no' => ($this->input->post('vin_no')) ?? NULL,
                'dump_date' => date('Y-m-d H:i:s'),
                'vehicle_status' => $this->input->post('status', TRUE)
            );
            if (!$this->admin_model->save_vehicle($vehicle)) {
                echo json_encode(array('status' => FALSE, 'msg' => 'Vehicle save failed!'));
            } else {
                echo json_encode(array('status' => TRUE, 'msg' => 'Vehicle saved'));
            }
        }
    }

    public function delete_vehicle($id)
    {
        $this->admin_model->delete_vehicle($id);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_vehicle($id)
    {
        $vehicle = $this->admin_model->vehicle_by_id($id);
        if ($vehicle) {
            echo json_encode(array('status' => TRUE, 'vehicle' => $vehicle));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    public function update_vehicle()
    {
        if ($this->_vehicle_validate() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
            exit;
        } else {
            $update = array(
                'license_plate' => trim($this->input->post('license_plate')),
                'model' => $this->input->post('model'),
                'make' => $this->input->post('make'),
                'color' => $this->input->post('color'),
                'axles' => $this->input->post('axles'),
                'start_date' => ($this->input->post('start_date')) ? date('Y-m-d', strtotime($this->input->post('start_date'))) : date('Y-m-d'),
                'end_date' => ($this->input->post('end_date')) ? date('Y-m-d', strtotime($this->input->post('end_date'))) : '0000-00-00',
                'tag_id' => $this->input->post('tagtype'),
                'store' => $this->input->post('store'),
                'location' => $this->input->post('location'),
                'unit' => $this->input->post('unit'),
                'year' => $this->input->post('year'),
                'client_id' => $this->input->post('client') /*$this->db->get_where('clients', ['organization' => $this->input->post('client')])->row()->client_id*/,
                'dept_id' => $this->input->post('dept'),
                'sub_dept_id' => ($this->input->post('sub_dept')) ?? NULL,
                'tolltag' => $this->input->post('tolltag'),
                'vin_no' => ($this->input->post('vin_no')) ?? NULL,
                'vehicle_status' => $this->input->post('status', TRUE)
            );
            $this->admin_model->update_vehicle(array('vehicle_id' => $this->input->post('id')), $update);
            echo json_encode(array('status' => TRUE, 'msg' => 'Vehicle updated'));
        }
    }

    public function activate_vehicle($id)
    {
        $active = ($this->admin_model->vehicle_by_id($id)->vehicle_status) ? 0 : 1;
        $activation = array('vehicle_status' => $active);
        if (!$this->admin_model->update_vehicle(array('vehicle_id' => $id), $activation)) {
            echo json_encode(array('status' => false, 'msg' => 'Error updating vehicle status'));
        } else {
            echo json_encode(array('status' => true, 'msg' => 'Vehicle status updated successfully'));
        }
    }

    private function _vehicle_validate()
    {
        $this->form_validation->set_rules('license_plate', 'License plate', 'trim|required', array('required' => 'Provide license plate'));
        $this->form_validation->set_rules('model', 'Vehicle model', 'trim|required', array('required' => 'Provide model'));
        $this->form_validation->set_rules('make', 'Vehicle make', 'trim|required', array('required' => 'Provide make'));
        $this->form_validation->set_rules('color', 'Color', 'trim|required', array('required' => 'Provide color'));
        $this->form_validation->set_rules('axles', 'Number of axles', 'trim|required', array('required' => 'Provide number of axles'));
        $this->form_validation->set_rules('start_date', 'Start date', 'trim|required', array('required' => 'Provide start date'));
        if (!empty($this->input->post('end_date'))) {
            $this->form_validation->set_rules('end_date', 'End date', 'callback_check_dates[' . $this->input->post('end_date') . ', ' . $this->input->post('start_date') . ']');
        }
        $this->form_validation->set_rules('tagtype', '', 'trim|required', array('required' => 'Select tag type'));
        $this->form_validation->set_rules('store', 'Store', 'trim|required', array('required' => 'Provide store'));
        $this->form_validation->set_rules('location', 'Location', 'trim|required', array('required' => 'Provide store location'));
        $this->form_validation->set_rules('year', 'Vehicle start year', 'trim|required', array('required' => 'Provide start year'));
        /*$this->form_validation->set_rules('unit', 'Unit','trim|required', array('required' => 'Provide unit number'));*/
        $this->form_validation->set_rules('client', 'Vehicle owner', 'trim|required', array('required' => 'Select vehicle owner'));

        if ($this->form_validation->run() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_dates($end_date, $start_date)
    {
        if (strtotime($end_date) < strtotime($start_date)) {
            $this->form_validation->set_message('check_dates', 'The %s field cannot be less than start date');
            return false;
        } else {
            return true;
        }
    }

    public function client_vehicle_uploads()
    {
        $this->data['title'] = 'Admin &raquo; Client Uploads';
        $iterator = new FilesystemIterator('./uploads/client_uploads');
        $filelist = array();
        foreach ($iterator as $i) {
            if (strpos($i->getFilename(), 'index') === 0) {
                continue;
            }
            $splitter = explode('@', $i->getFilename());
            $filelist[] = [
                'time' => $splitter[0],
                'uploader' => $splitter[1],
                'client' => $splitter[2],
                'dept' => explode('.', $splitter[3])[0],
                'filename' => $i->getFilename()
            ];
        }
        $this->data['uploads'] = $filelist;
        $this->render('backend/client_uploads');
    }

    public function delete_uploaded_file($time)
    {
        foreach (glob('./uploads/client_uploads/' . $time . '*') as $file) {
            if (unlink($file)) {
                echo json_encode(array('status' => true, 'msg' => 'File deleted'));
            } else {
                echo json_encode(array('status' => false, 'msg' => 'File couldn\'t be deleted'));
            }
        }
    }

    /*Roads*/
    public function client_roads()
    {
        $date = date('Y-m-d', NOW());
        $this->data['title'] = 'Admin &raquo; Client Roads';
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->render('backend/client_roads');
    }

    public function roads()
    {
        $date = date('Y-m-d', NOW());
        $this->data['title'] = 'Admin &raquo; Roads';
        $this->data['breadcrumb'] = 'Today: ' . $date;

        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', '<p><strong>' . validation_errors('<p class="text-danger text-center">', '</p>') . '</strong></p>');
            redirect('backend/client_roads');
        } else {
            $this->data['client'] = $this->input->post('client');
            $this->data['roads'] = $this->admin_model->get_roads_by_client($this->input->post('client'), 0);
            $this->render('backend/roads');
        }
    }

    public function roads_by_date()
    {
        $this->data['title'] = 'Admin &raquo; Road Toll by Date';
        $date = nice_date($this->input->post('road_day'), 'Y-m');
        $org = $this->input->post('client');
        $this->data['breadcrumb'] = date('F, Y', strtotime($date));
        $this->data['client'] = $this->input->post('client');
        $this->data['monthly_road_tolls'] = $this->admin_model->get_roads_by_client($org, $date);
        $this->render('backend/client_daily_road_tolls');
    }

    public function roads_by_dept()
    {
        $this->data['title'] = 'Admin &raquo; Road Toll by Dept';
        $dept = $this->input->post('client_dept');
        $this->data['client'] = $org = $this->input->post('client');
        $this->data['breadcrumb'] = $this->db->get_where('departments', ['dept_id' => $dept])->row()->dept_name;
        $client_id = $this->admin_model->get_clients_id($org)->client_id;
        $this->data['client_depts'] = $this->admin_model->get_departments($client_id);
        $this->data['monthly_road_tolls'] = $this->admin_model->get_roads_by_client_dept($org, $dept);
        $this->render('backend/client_daily_road_tolls');
    }

    /*States*/
    public function client_agency_tolls($org, $date)
    {
        $this->data['title'] = 'Admin &raquo; Road Toll by Agency';
        $d = ($date == 'all') ? date('Y-m') : $date;
        $this->data['breadcrumb'] = date('F, Y', strtotime($d));
        $this->data['client'] = $org;
        $client_id = $this->admin_model->get_clients_id($org)->client_id;
        $this->data['client_depts'] = $this->admin_model->get_departments($client_id);
        $this->data['monthly_road_tolls'] = $this->admin_model->get_tolls_by_agency($org, $d);
        $this->render('backend/client_agency_tolls');
    }

    public function client_agency_tolls_by_date()
    {
        $this->data['title'] = 'Admin &raquo; Road Toll by Agency & Date';
        $d = ($this->input->post('road_agency')) ? date('Y-m', strtotime($this->input->post('road_agency'))) : date('Y-m');
        $org = $this->input->post('client');
        $this->data['breadcrumb'] = date('F, Y', strtotime($d));
        $client_id = $this->admin_model->get_clients_id($org)->client_id;
        $this->data['client_depts'] = $this->admin_model->get_departments($client_id);
        $this->data['client'] = $this->input->post('client');
        $this->data['monthly_road_tolls'] = $this->admin_model->get_tolls_by_agency($org, $d);
        $this->render('backend/client_agency_tolls');
    }

    public function client_agency_tolls_by_dept()
    {
        $this->data['title'] = 'Admin &raquo; Road Toll by Agency & Dept';
        $dept = $this->input->post('client_dept');
        $this->data['client'] = $org = $this->input->post('client');
        $this->data['breadcrumb'] = $this->db->get_where('departments', ['dept_id' => $dept])->row()->dept_name;
        $client_id = $this->admin_model->get_clients_id($org)->client_id;
        $this->data['client_depts'] = $this->admin_model->get_departments($client_id);
        $this->data['monthly_road_tolls'] = $this->admin_model->get_tolls_by_agency_dept($org, $dept);
        $this->render('backend/client_agency_tolls');
    }



    public function departments($client)
    {
        $data = $this->admin_model->departments($client);
        echo json_encode($data);
    }

    public function states()
    {
        $this->data['title'] = "Admin &raquo; States";
        $this->data['states'] = $this->admin_model->get_states();
        $this->render('backend/states');
    }

    public function add_state()
    {
        $this->form_validation->set_rules('stateName', 'State Name', 'trim|required');
        $this->form_validation->set_rules('stateCode', 'State Code', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $state = array('state_name' => $this->input->post('stateName'), 'state_code' => $this->input->post('stateCode'));
            $this->admin_model->save_state($state);
            echo json_encode(array('status' => TRUE, 'msg' => 'State saved'));
        }
    }

    public function edit_state($id)
    {
        $state = $this->admin_model->state_by_id($id);
        if ($state) {
            echo json_encode(array('status' => TRUE, 'state' => $state));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    public function update_state()
    {
        $this->form_validation->set_rules('stateName', 'State Name', 'trim|required');
        $this->form_validation->set_rules('stateCode', 'State Code', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $update = array('state_name' => $this->input->post('stateName'), 'state_code' => $this->input->post('stateCode'));
            $this->admin_model->update_state(array('state_id' => $this->input->post('id')), $update);
            echo json_encode(array('status' => TRUE, 'msg' => 'State updated'));
        }
    }

    public function delete_state($id)
    {
        $state_code = $this->db->get_where('states', ['state_id' => $id])->row()->state_code;
        //State linkup
        $agencies_linked = ($this->db->get_where('agencies', ['state_id' => $id])->row()) ? true : false;
        $citations_linked = ($this->db->where('license_plate_state', $state_code)->or_where('violation_state', $state_code)->get('citations')->row()) ? true : false;
        $invoice_tbls = $this->db->query("SELECT TABLE_NAME as tbl FROM information_schema.tables WHERE TABLE_NAME LIKE '%_invoice%' AND table_schema='innovativetoll'")->result();
        $total_occurrences = 0;
        foreach ($invoice_tbls as $t) {
            $column = ($t->tbl == 'client_invoice') ? 'dept' : 'dept_id';
            $total_occurrences += ($this->db->get_where($t->tbl, [$column => $id])->row() !== null) ? 1 : 0;
        }
        if (!$agencies_linked && !$citations_linked && $total_occurrences < 1) {
            if (!$this->admin_model->delete_state($id)) {
                echo json_encode(array("status" => FALSE, 'msg' => 'Error deleting state'));
            } else {
                echo json_encode(array("status" => TRUE, 'msg' => 'State deleted successfully'));
            }
        } else {
            echo json_encode(array("status" => FALSE, 'msg' => 'Can\'t delete - data attached to this state'));
        }
    }

    /*Agencies*/
    public function agency_tolls()
    {
        $this->data['title'] = "Admin &raquo; Agencies";
        $this->data['agency_tolls'] = $this->admin_model->get_agency_tolls();
        $this->render('backend/agency_tolls');
    }

    public function agencies()
    {
        $this->data['title'] = "Admin &raquo; Agencies";
        $this->data['agencies'] = $this->admin_model->get_agencies();
        $this->data['states'] = $this->admin_model->get_states();
        $this->data['dashboard_id'] = 1;
        $this->render('backend/agencies');
    }

    public function add_agency()
    {
        $this->form_validation->set_rules('agencyName', 'Agency', 'trim|required');
        $this->form_validation->set_rules('stateName', 'State', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $agency = array('agency_name' => $this->input->post('agencyName'), 'state_id' => $this->input->post('stateName'));
            $this->admin_model->save_agency($agency);
            echo json_encode(array('status' => TRUE, 'msg' => 'Agency saved'));
        }
    }

    public function edit_agency($id)
    {
        $agency = $this->admin_model->agency_by_id($id);
        if ($agency) {
            echo json_encode(array('status' => TRUE, 'agency' => $agency));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    public function update_agency()
    {
        $this->form_validation->set_rules('agencyName', 'Agency', 'trim|required');
        $this->form_validation->set_rules('stateName', 'State', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $update = array('agency_name' => $this->input->post('agencyName'), 'state_id' => $this->input->post('stateName'));
            $this->admin_model->update_agency(array('agency_id' => $this->input->post('id')), $update);
            echo json_encode(array('status' => TRUE, 'msg' => 'Agency updated'));
        }
    }

    public function delete_agency($id)
    {
        $this->admin_model->delete_agency($id);
        echo json_encode(array("status" => TRUE));
    }

    /*Tag types*/
    public function tags()
    {
        $this->data['title'] = "Admin &raquo; Tags";
        $this->data['tags'] = $this->admin_model->get_tags();
        $this->render('backend/tags');
    }

    public function add_tag()
    {
        $this->form_validation->set_rules('tagName', 'Tag Type', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $tag = array('tag_type' => $this->input->post('tagName'));
            $this->admin_model->save_tag($tag);
            echo json_encode(array('status' => TRUE, 'msg' => 'Tag saved'));
        }
    }

    public function edit_tag($id)
    {
        $tag = $this->admin_model->tag_by_id($id);
        if ($tag) {
            echo json_encode(array('status' => TRUE, 'tag' => $tag));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    public function update_tag()
    {
        $this->form_validation->set_rules('tagName', 'Tag Type', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $update = array('tag_type' => $this->input->post('tagName'));
            $this->admin_model->update_tag(array('tag_id' => $this->input->post('id')), $update);
            echo json_encode(array('status' => TRUE, 'msg' => 'Tag updated'));
        }
    }

    public function delete_tag($id)
    {
        $vehicles_linked = $this->db->get_where('vehicles', ['tag_id' => $id])->row() ? true : false;
        if (!$vehicles_linked) {
            if (!$this->admin_model->delete_tag($id)) {
                echo json_encode(array("status" => FALSE, 'msg' => 'Error deleting tag type'));
            } else {
                echo json_encode(array("status" => TRUE, 'msg' => 'Tag type deleted successfully'));
            }
        } else {
            echo json_encode(array("status" => FALSE, 'msg' => 'Can\'t delete - data attached to this tag type'));
        }
    }

    public function signups()
    {
        $this->data['title'] = "Admin &raquo; Tests";
        $this->data['signups'] = $this->admin_model->get_signups();
        $this->render('backend/signups');
    }

    public function approve($id)
    {
        $approved = $this->admin_model->get_signup_id($id)->signup_status;
        $approval = array(
            'signup_status' => ($approved == 1) ? 0 : 1
        );
        $this->admin_model->update_signup(array('signup_id' => $id), $approval);
        echo json_encode(array('status' => TRUE, 'msg' => 'Signup status changed'));
    }

    public function db_dump()
    {
        $this->data['title'] = "Admin &raquo; DB Dump";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->render('backend/excel');
    }
    public function range_db_dump()
    {
        $this->data['title'] = "Admin &raquo; DB Dump";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->render('backend/multiple_date_upload');
    }

    public function has_sub_depts($org)
    {
        $has_sub_depts = ($this->db->get_where('clients', ['organization' => $org])->row()->sub_dept_exist) ? true : false;
        echo json_encode($has_sub_depts);
    }
    public function client_sub_depts($org)
    {
        $has_sub_depts = ($this->db->get_where('clients', ['client_id' => $org])->row()->sub_dept_exist) ? true : false;
        echo json_encode($has_sub_depts);
    }

    public function excel_listing()
    {
        $this->data['title'] = "Admin &raquo Excel Dumps";
        $this->data['excel_dumps'] = $this->admin_model->excel_dumps();
        $this->render('backend/excel_listings');
    }

    public function delete_excel($excel_dump_id)
    {
        $this->data['title'] = "Admin &raquo Excel Dumps";
        $this->data['excel_dumps'] = $this->admin_model->excel_dumps();

        $dump_data = $this->admin_model->get_excel_dump($excel_dump_id);
        $client_table = $dump_data->client_name . '_invoice';

        //get client id
        $results = $this->admin_model->get_clients_id($dump_data->client_name);
        //$client_id = $results->client_id;
        $acc_dept = ($dump_data->dept_id != NULL) ? $dump_data->dept_id : $this->db->like('dept_name', 'overview', 'both')->where('client_id', $results->client_id)->get('departments')->row()->dept_id;



        /*
            foreach ($no_of_dumps as $dump) {
                $ac_amount = $this->admin_model->get_data_account_id($dump->account_id)->amount;
                $client_id = $this->admin_model->get_clients_id($client)->client_id;
                $acc_dept = ($this->input->post('dept')) ?? $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client_id)->get('departments')->row()->dept_id ;
                $current_balance = $this->admin_model->get_data_account_balance_id($acc_dept)->balance;
                $new_balance = $ac_amount + $current_balance;
                $this->admin_model->account_id_delete($dump_data->account_id);
                $update_balance = array(
                        'balance' => $new_balance
                    );
                $this->admin_model->update_account_balance($update_balance, ['dept_id' => $acc_dept]);
                unlink('./uploads/agency_invoices/'.$dump->filename);
            }
            $previous_dump = $this->admin_model->undo_dumps($date_for, $client, $dept);
        */

        //deleting all records of the dump
        $this->admin_model->excel_dumps_delete($dump_data->last_id, $dump_data->first_id, $client_table);

        //get the details of the dump account details from accounts table
        $account_balance_details = $this->admin_model->get_data_account_id($dump_data->account_id);

        //get last updated balance amount from accounts table
        $amount = $account_balance_details->amount;

        //get the details of the current client balance
        $client_account_balance = $this->admin_model->get_data_account_balance_id($acc_dept)->balance;
        $new_balance = $client_account_balance + $amount;

        //delete the records from the accounts table
        $this->admin_model->account_id_delete($dump_data->account_id);

        $this->admin_model->update_account_balance(['balance' => $new_balance], ['dept_id' => $acc_dept]);

        //delete the excel listing
        $this->admin_model->excel_listing_delete($excel_dump_id);

        $path = FCPATH . '/uploads/agency_invoices/' . $dump_data->filename;
        if (unlink($path)) {
            $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>Undo of the Excel Dump Done successfully</strong></p>');
            redirect('backend/admin/excel_listing', 'refresh');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Undo of the Excel Dump Done successfully BUT File delete from sever failed</strong></p>');
            redirect('backend/admin/excel_listing', 'refresh');
        }
    }

    public function delete_vehicle_excel($vehicle_excel_dump_id)
    {
        $this->data['title'] = "Admin &raquo; vehicles Uploads";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['excel_dumps'] = $this->admin_model->vehicle_excel_dumps();

        $dump_data = $this->admin_model->get_vehicle_excel_dump($vehicle_excel_dump_id);

        $this->admin_model->vehicle_excel_dumps_delete($dump_data->last_id, $dump_data->first_id);
        $this->admin_model->vehicle_excel_listing_delete($vehicle_excel_dump_id);

        $path = FCPATH . '/uploads/vehicles/' . $dump_data->filename;
        if (unlink($path)) {
            $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>Undo of the vehicle dump done successfully</strong></p>');
            redirect('backend/admin/vehicle_dump', 'refresh');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Undo of the vehicle dump done successfully but file delete from sever failed</strong></p>');
            redirect('backend/admin/vehicle_dump', 'refresh');
        }
    }
    public function vehicle_dump()
    {
        $this->data['title'] = "Admin &raquo; vehicles Uploads";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['excel_dumps'] = $this->admin_model->vehicle_excel_dumps();
        $this->render('backend/bulk_vehicle_upload');
    }
    public function month_for_invoice()
    {
        $this->data['title'] = "Admin &raquo; Monthly Invoices";
        $this->data['invoice_details'] = $this->admin_model->get_invoices_month();
        $this->render('backend/invoice_month');
    }

    public function update_displayed_invoice_amount($id)
    {
        $invoice = $this->admin_model->get_invoice_month($id);
        $invoice_details = array(
            'invoice_month_id' => $invoice->invoice_month_id,
            'client_name' => $invoice->client_name,
            'month' => $invoice->month,
            'invoice_amount' => $invoice->invoice_amount
        );
        echo json_encode($invoice_details);
    }

    public function edit_month_invoice_amount()
    {
        $this->form_validation->set_rules('invoice_amount', 'Displayed Amount', 'trim|required');
        $this->form_validation->set_rules('month', 'Month for Invoice Amount', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors()));
        } else {

            $invoice_details = array(
                'invoice_amount' => $this->input->post('invoice_amount'),
                'month' => $this->input->post('month')
            );

            $where = array(
                'invoice_month_id' => $this->input->post('invoice_month_id')
            );

            $this->admin_model->update_mounth_invoice_amount($invoice_details, $where);

            echo json_encode(array('status' => TRUE, 'msg' => 'Month/Invoice Amount Edited sucessfully'));
        }
    }

    public function client_invoices()
    {
        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        $this->form_validation->set_rules('dept', 'Department', 'trim|required');
        $this->form_validation->set_rules('invoice_date', 'Invoice date', 'trim|required');
        $this->form_validation->set_rules('invoice_amount', 'Invoice amount', 'trim|required');
        $this->form_validation->set_rules('toll_amount', 'Toll amount', 'trim|required');
        $this->form_validation->set_rules('toll_fees', 'Toll fees', 'trim|required');
        $this->form_validation->set_rules('total_paid', 'Total paid', 'trim|required');
        $this->form_validation->set_rules('fee_type', 'Fees Type', 'trim|required');
        /*$this->form_validation->set_rules('invoice_status', 'Invoice status','trim|required'); */

        $this->data['title'] = "Admin &raquo; Client Invoice";
        $this->data['clients'] = $this->admin_model->get_clients_list();

        if ($this->form_validation->run() === FALSE) {
            $this->render('backend/client_invoice');
        } else {
            $config_excel = array(
                'upload_path'   => './uploads/client_invoices',
                'allowed_types' => 'xls|xlsx',
                'remove_spaces' => 'TRUE',
                'file_name'      => time() . '_' . $_FILES['client_excel']['name']
            );
            $this->load->library('upload', $config_excel);
            if (!$this->upload->do_upload('client_excel')) {
                $this->session->set_flashdata('message', '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '<hr /></p>');
                $this->render('backend/client_invoice');
            } else {
                $client = $this->input->post('client');
                $dept_id = $this->input->post('dept');
                $current_month_invoice = $this->admin_model->current_month_invoice($client, date('Y-m', NOW()));
                $month = date('M', strtotime($this->input->post('invoice_date')));
                if (empty($this->admin_model->client_invoice_month($client, $dept_id))) {

                    $invoice_details = array(
                        'client_name' => $client,
                        'month' => $month,
                        'dept_id' => $dept_id,
                        'invoice_amount' => $this->input->post('invoice_amount')
                    );
                    $this->admin_model->add_mounth_invoice_amount($invoice_details);
                } else {
                    $invoice_details = array(
                        'invoice_amount' => $this->input->post('invoice_amount'),
                        'month' => $month
                    );
                    $where = array(
                        'client_name' => $client,
                        'dept_id' => $dept_id
                    );
                    $this->admin_model->update_mounth_invoice_amount($invoice_details, $where);
                }

                $data_excel = $this->upload->data();
                $excel = $data_excel['file_name'];
                $client_dump = array(
                    'client_name' => $this->input->post('client'),
                    'dept' => $this->input->post('dept'),
                    'invoice_date' => $this->input->post('invoice_date'),
                    'pay_date' => $this->input->post('pay_date'),
                    'invoice_amount' => $this->input->post('invoice_amount'),
                    'toll_amount' => $this->input->post('toll_amount'),
                    'fee_type' => $this->input->post('fee_type'),
                    'toll_fee' => $this->input->post('toll_fees'),
                    'paid_amount' => $this->input->post('total_paid'),
                    'invoice_status' => (($this->input->post('pay_date')) === NULL ? 1 : 0),
                    'excel' => $excel
                );
                $this->pdf_invoice('client_pdf', $client_dump);
            }
        }
    }

    public function pdf_invoice($file, $dump)
    {
        $config_pdf = array(
            'upload_path'   => './uploads/client_invoices',
            'allowed_types' => 'pdf',
            'remove_spaces' => 'TRUE',
            'file_name'      => time() . '_' . $_FILES[$file]['name']
        );
        $this->load->library('upload', $config_pdf);
        $this->upload->initialize($config_pdf);
        if (!$this->upload->do_upload($file)) {
            $this->session->set_flashdata('message', '<p class="text-danger"><strong>PDF error:</strong> ' . $this->upload->display_errors('', '') . '<hr /></p>');
            $this->render('backend/client_invoice');
        }
        $data_pdf = $this->upload->data();
        $pdf = array('pdf' => $data_pdf['file_name']);
        $d = array_merge($dump, $pdf);
        $this->admin_model->client_invoice($d);
        $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>Client invoice saved successfully</strong><hr /></p>');
        redirect('backend/admin/client_invoices');
    }

    public function edit_client_invoice()
    {
        $this->data['title'] = "Admin &raquo; Client Invoice";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $invoice_id = $this->input->post('id');

        //some validations here
        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        $this->form_validation->set_rules('dept', 'Department', 'trim|required');
        $this->form_validation->set_rules('invoice_date', 'Invoice date', 'trim|required');
        $this->form_validation->set_rules('invoice_amount', 'Invoice amount', 'trim|required');
        $this->form_validation->set_rules('toll_amount', 'Toll amount', 'trim|required');
        $this->form_validation->set_rules('toll_fees', 'Toll fees', 'trim|required');
        $this->form_validation->set_rules('total_paid', 'Total paid', 'trim|required');
        $this->form_validation->set_rules('fee_type', 'Fees Type', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', validation_errors('<p class="text-danger text-center"><strong>', '</strong></p>'));
            redirect('backend/edit_invoice/' . $invoice_id, 'refresh');
        } else {
            $client = $this->input->post('client');
            $dept_id = $this->input->post('dept');
            $current_month_invoice = $this->admin_model->current_month_invoice($client, date('Y-m', NOW()));
            $month = date('M', strtotime($this->input->post('invoice_date')));
            $invoice_details = array(
                'invoice_amount' => $this->input->post('invoice_amount'),
                'month' => $month
            );
            $where = array(
                'client_name' => $client,
                'dept_id' => $dept_id
            );
            $this->admin_model->update_mounth_invoice_amount($invoice_details, $where);

            $client_invoice = array(
                'client_name' => $this->input->post('client'),
                'dept' => $this->input->post('dept'),
                'invoice_date' => $this->input->post('invoice_date'),
                'pay_date' => $this->input->post('pay_date'),
                'invoice_amount' => $this->input->post('invoice_amount'),
                'toll_amount' => $this->input->post('toll_amount'),
                'fee_type' => $this->input->post('fee_type'),
                'toll_fee' => $this->input->post('toll_fees'),
                'paid_amount' => $this->input->post('total_paid'),
                'invoice_status' => (($this->input->post('pay_date')) === NULL ? 1 : 0)
            );
            $pdf_excel = $this->db->select('excel, pdf')->get_where('client_invoice', ['invoice_id' => $invoice_id])->row();
            if ($_FILES['client_excel']["name"] != '') {
                $config_excel = array(
                    'upload_path'   => './uploads/client_invoices',
                    'allowed_types' => 'xls|xlsx',
                    'remove_spaces' => 'TRUE',
                    'file_name'      => time() . '_' . $_FILES['client_excel']['name']
                );
                $this->load->library('upload', $config_excel);
                if (!$this->upload->do_upload('client_excel')) {
                    $this->session->set_flashdata('message', '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '<hr /></p>');
                } else {
                    $data_excel = $this->upload->data();
                    $excel = array('excel' => $data_excel['file_name']);
                    $client_invoice = array_merge($client_invoice, $excel);
                    unlink('./uploads/client_invoices/' . $pdf_excel->excel);
                }
            }
            $this->update_pdf_invoice($pdf_excel->pdf, $invoice_id, 'client_pdf', $client_invoice);
        }
    }

    public function update_pdf_invoice($old_pdf, $invoice_id, $file, $dump)
    {
        $d = $dump;
        if ($_FILES[$file]["name"] != '') {
            $config_pdf = array(
                'upload_path'   => './uploads/client_invoices',
                'allowed_types' => 'pdf',
                'remove_spaces' => 'TRUE',
                'file_name'      => time() . '_' . $_FILES[$file]['name']
            );
            $this->load->library('upload', $config_pdf);
            $this->upload->initialize($config_pdf);
            if (!$this->upload->do_upload($file)) {
                $this->session->set_flashdata('message', '<p class="text-danger"><strong>PDF error:</strong> ' . $this->upload->display_errors('', '') . '<hr /></p>');
            } else {
                $data_pdf = $this->upload->data();
                $pdf = array('pdf' => $data_pdf['file_name']);
                $d = array_merge($d, $pdf);
                unlink('./uploads/client_invoices/' . $old_pdf);
            }
        }
        if (!$this->admin_model->update_client_invoice(['invoice_id' => $invoice_id], $d)) {
            $this->session->set_flashdata('message', '<p class="text-info text-center"><strong>Nothing to update</strong><hr /></p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>Client invoice updated successfully</strong><hr /></p>');
        }
        redirect('backend/edit_invoice/' . $invoice_id, 'refresh');
    }

    public function invoice_listing()
    {
        $this->data['title'] = "Admin &raquo Excel Dumps";
        $this->data['invoices'] = $this->admin_model->get_invoices();
        $this->render('backend/invoice_listings');
    }

    public function delete_invoice($id, $client, $dept)
    {
        if ($this->admin_model->delete_invoice($id)) {
            $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>Invoice deleated sucessfully</strong><hr /></p>');
            $current_month_invoice = $this->admin_model->current_month_invoice($client, date('Y-m', NOW()));
            $month = date('M', NOW());
            $invoice_details = array(
                'invoice_amount' => (!null($current_month_invoice)) ? $current_month_invoice : 0,
                'month' => $month
            );
            $where = array(
                'client_name' => $client,
                'dept_id' => $dept
            );
            $this->admin_model->update_mounth_invoice_amount($invoice_details, $where);
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Client invoice deleate failed</strong><hr /></p>');
        }
        redirect('backend/admin/invoice_listing');
    }

    public function edit_invoice($invoice_id)
    {
        $this->data['title'] = "Admin &raquo; Update Client Invoice";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['invoice'] = $invoice = $this->admin_model->get_invoice($invoice_id);
        $invoice_client = $this->db->get_where('departments', ['dept_id' => $invoice->dept])->row()->client_id;
        $this->data['client_depts'] = $this->db->select('dept_id, dept_name')->get_where('departments', ['client_id' => $invoice_client])->result();
        $this->render('backend/invoice_edit');
    }

    public function vehicle_import()
    {
        $client = $this->input->post('client');
        $results = $this->admin_model->get_clients_id($client);
        $client_id = $results->client_id;
        $dept = $this->input->post('dept');
        $draft = $this->input->post('draft');
        $sub_dept = ($this->input->post('sub_dept')) ?? NULL;
        $vehicle_status = 1;
        $dump_date = date('Y-m-d h:i:s');

        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Select Client to proceed with upload</strong></p>');
            redirect('backend/admin/vehicle_dump');
        } else {
            if (isset($_FILES["excel_data"]["name"])) {
                $this->load->library('excel');
                $path = $_FILES["excel_data"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                $data = array();
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $data[] = array(
                            'license_plate'  => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()),
                            'location'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'year'    => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                            'color'  => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                            'make'   => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                            'unit'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                            'model'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                            'tolltag'   => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                            'axles'   => str_replace(' ', '_', $worksheet->getCellByColumnAndRow(8, $row)->getValue()),
                            'tag_id'   => $this->admin_model->tag_by_name(trim(strtolower($worksheet->getCellByColumnAndRow(9, $row)->getValue()))),
                            'vin_no'   => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),
                            'start_date'   => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(11, $row)->getFormattedValue())),
                            'end_date'   => ($worksheet->getCellByColumnAndRow(12, $row)->getFormattedValue() !== '') ? date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(12, $row)->getFormattedValue())) : '',
                            'client_id' => $client_id,
                            'store' => $client . '_store',
                            'dump_date'   => $dump_date,
                            'dept_id'   => $dept,
                            'sub_dept_id'   => $sub_dept,
                            'draft'   => $draft
                        );
                    } //end foreach row
                } //end foreach object

                $count = count($data);
                $batch = $this->db->insert_batch('vehicles', $data);
                if ($batch) {
                    //echo json_encode($batch);
                    if (!empty($this->admin_model->get_last_inserted_vehicle($client_id))) {
                        $last_row = $this->admin_model->get_last_inserted_vehicle($client_id);
                        $last_id = $last_row->vehicle_id;
                    } else {
                        $last_id = 1;
                    }
                    $first_id = $last_id - ($count - 1);

                    //echo json_encode($first_id );

                    $config_excel = array(
                        'upload_path'   => './uploads/vehicles',
                        'allowed_types' => 'xls|xlsx',
                        'remove_spaces' => 'TRUE',
                        'file_name'      => time() . '_' . $_FILES['excel_data']['name']
                    );

                    $this->load->library('upload', $config_excel);
                    if (!$this->upload->do_upload('excel_data')) {
                        $this->session->set_flashdata('message', '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</p>');
                        redirect('backend/admin/vehicle_dump');
                    } else {
                        $data_excel = $this->upload->data();
                        $excel = $data_excel['file_name'];
                        $vehicle_dump = array(
                            'client_name' => $client,
                            'uploaded_date' => $dump_date,
                            'uploaded_by' => $this->ion_auth->user()->row()->id,
                            'total_row' => $count,
                            'last_id' => $last_id,
                            'first_id' => $first_id,
                            'filename' => $excel,
                            'vehicle_dept' => $dept,
                            'vehicle_sub_dept' => $sub_dept,
                            'draft' => $draft
                        );
                        $this->admin_model->vehicle_dump($vehicle_dump);
                        $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>' . ucwords(str_replace('_', ' ', $client)) . '\'s vehicle dump saved successfully</strong></p>');
                        redirect('backend/admin/vehicle_dump');
                    }
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>' . ucwords(str_replace('_', ' ', $client)) . '\'s vehicle dump failed</strong></p>');
                    redirect('backend/admin/vehicle_dump');
                } //else batch
            } //end if file is empty          

        } //end first else on client selected 
    }

    public function range_import()
    {
        $client = $this->input->post('client');
        $dump_date = date('Y-m-d h:i:s');
        $user = $this->ion_auth->user()->row()->id;

        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Select Client to proceed with upload</strong></p>');
            redirect('backend/admin/range_db_dump');
        } else {
            if ($_FILES["excel_data"]["name"] == '') {
                $this->session->set_flashdata('message', '<p class="text-info text-center"><strong>Select transactions file to upload</strong></p>');
                redirect('backend/admin/range_db_dump');
            } else {
                $matcher = $this->_lp_dept_matcher('excel_data', $client, $user);
                if (!$matcher['status']) {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>' . $matcher['msg'] . '</strong></p>');
                    redirect('backend/admin/range_db_dump');
                } else {
                    $this->_range_dump_transactions('excel_data', $client, $dump_date, $user);
                }
            }
        }
    }

    public function import_transactions()
    {
        $client = $this->input->post('client');
        $date_for = date('Y-m-d', strtotime($this->input->post('date_for')));
        $dump_date = date('Y-m-d h:i:s');
        $user = $this->ion_auth->user()->row()->id;

        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        $this->form_validation->set_rules('date_for', 'Date For', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Select Client & transactions date to proceed with upload</strong></p>');
            redirect('backend/admin/db_dump');
        } else {
            if ($_FILES["excel_data"]["name"] == '') {
                $this->session->set_flashdata('message', '<p class="text-info text-center"><strong>Select transactions file to upload</strong></p>');
                redirect('backend/admin/db_dump');
            } else {
                $matcher = $this->_lp_dept_matcher('excel_data', $client, $user);
                if (!$matcher['status']) {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>' . $matcher['msg'] . '</strong></p>');
                    redirect('backend/admin/db_dump');
                } else {
                    $this->_dump_transactions('excel_data', $client, $dump_date, $date_for, $user);
                }
            }
        }
    }


    private function _lp_dept_matcher($transactions_file, $client, $user)
    {
        $client_id = $this->admin_model->get_clients_id($client)->client_id;
        $this->load->library('excel');
        $path = $_FILES[$transactions_file]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        $data = array();
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($client == 'amazon' || $client == 'fleet_serv_pro') {
                    $data[] = array(
                        'license_plate'  => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()),
                        'state_code'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'agency_name'    => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                        'exit_date_time'  => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(2, $row)->getFormattedValue())),
                        'exit_name'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'class'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'toll'   => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                        'dept_id' => ($this->input->post('dept')) ? (($this->db->where('client_id', $client_id)->where('license_plate', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? (($this->db->where('client_id', $client_id)->where('tolltag', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? '-1')) : (($this->db->where('client_id', $client_id)->where('license_plate', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? (($this->db->where('client_id', $client_id)->where('tolltag', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? '-1')),
                        'uploader' => $user
                    );
                } else {
                    $data[] = array(
                        'license_plate'  => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()),
                        'state_code'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'agency_name'    => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                        'exit_date_time'  => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(3, $row)->getFormattedValue())),
                        'exit_lane'   => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                        'exit_location'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'toll'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'dept_id' => ($this->input->post('dept')) ? (($this->db->where('client_id', $client_id)->where('license_plate', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? (($this->db->where('client_id', $client_id)->where('tolltag', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? '-1')) : (($this->db->where('client_id', $client_id)->where('license_plate', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? (($this->db->where('client_id', $client_id)->where('tolltag', trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()))->get('vehicles')->row()->dept_id) ?? '-1')),
                        'uploader' => $user
                    );
                }
            } //end foreach row
        } //end foreach object
        $table = ($client == 'amazon' || $client == 'fleet_serv_pro') ? 'lp_department_matcher_special' : 'lp_department_matcher';
        $this->db->delete($table, ['uploader' => $user]);

        if (count($data) == 0) {
            $result = ['status' => true, 'msg' => ''];
        } else {
            $imports = $this->db->insert_batch($table, $data);
            if ($imports) {
                $dumped = $this->db->select('distinct(license_plate) as lp, dept_id')->get_where($table, ['uploader' => $user])->result();
                $duplicates_count = 0;
                $lps = '';
                foreach ($dumped as $dump) {
                    $duplicates_count += ($dump->dept_id == '-1') ? 1 : 0;
                    $lps .= ($dump->dept_id == '-1') ? '[' . $dump->lp . '] ' : '';
                }
                if ($duplicates_count > 0) {
                    $result = ['status' => false, 'msg' => $duplicates_count . ' LP(s) ' . $lps . ' not found in the system'];
                } else {
                    $result = ['status' => true, 'msg' => ''];
                }
            } else {
                $result = ['status' => false, 'msg' => 'Error matching LPs to departments'];
            }
        }
        return $result;
    }


    private function _dump_transactions($file, $client, $dump_date, $date_for, $user)
    {
        $this->load->library('excel');
        $path = $_FILES[$file]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        $data = array();
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($client == 'amazon' || $client == 'fleet_serv_pro') {
                    $data[] = array(
                        'license_plate'  =>($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->vehicle_id) ? trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) : ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->license_plate),
                        'state_code'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'agency_name'    => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                        'exit_date_time'  => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(2, $row)->getFormattedValue())),
                        'exit_name'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'class'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'toll'   => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                        'dept_id' => ($this->input->post('dept')) ? ($this->input->post('dept')) : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id)),
                        'sub_dept_id' => (!$this->input->post('dept')) ? NULL : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id)),
                        'unit' => ($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? (($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? NULL),
                        'dump_date'   => $dump_date,
                        'date_for'   => $date_for
                    );
                } else {
                    $data[] = array(
                       'license_plate'  =>($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->vehicle_id) ? trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) : ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->license_plate),
                        'state_code'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'agency_name'    => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                        'exit_date_time'  => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(3, $row)->getFormattedValue())),
                        'exit_lane'   => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                        'exit_location'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'toll'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'dept_id' => ($this->input->post('dept')) ? ($this->input->post('dept')) : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id)),
                        'sub_dept_id' => (!$this->input->post('dept')) ? NULL : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id)),
                        'unit' => ($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? (($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? NULL),
                        'dump_date'   => $dump_date,
                        'date_for'   => $date_for
                    );
                }
            } //end foreach row
        } //end foreach object

        //Delete previous dumps for date_for [date]
        $dept = ($this->input->post('dept')) ?? 0;
        $client_id = $this->admin_model->get_clients_id($client)->client_id;
        $acc_dept = ($this->input->post('dept')) ?? $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client_id)->get('departments')->row()->dept_id;
        $no_of_dumps = $this->admin_model->check_dumper($date_for, $client, $dept);
        if ($no_of_dumps) {
            $delete_previous_transactions = $this->admin_model->delete_previous_transactions($date_for, $client, $dept);
            foreach ($no_of_dumps as $dump) {
                $ac_amount = $this->admin_model->get_data_account_id($dump->account_id)->amount;
                $current_balance = $this->admin_model->get_data_account_balance_id($acc_dept)->balance;
                $new_balance = $ac_amount + $current_balance;
                $this->admin_model->account_id_delete($dump_data->account_id);
                $update_balance = array(
                    'balance' => $new_balance
                );
                $this->admin_model->update_account_balance($update_balance, ['dept_id' => $acc_dept]);
                //unlink('./uploads/agency_invoices/'.$dump->filename);
            }
            $previous_dump = $this->admin_model->undo_dumps($date_for, $client, $dept, ['is_deleted' => 1]);
        }

        $count = count($data);
        if ($count == 0) {
            $client_dump = array(
                'client_name' => $client,
                'uploaded_date' => $dump_date,
                'date_for' => $date_for,
                'uploaded_by' => $this->ion_auth->user()->row()->id,
                'total_row' => $count,
                'last_id' => -1,
                'first_id' => -1,
                'filename' => 'Empty',
                'account_id' => -1,
                'dept_id'   => ($this->input->get_post('dept')) ?? NULL,
                'is_deleted' => 1
            );
            if ($this->admin_model->client_dump($client_dump)) {
                $this->session->set_flashdata('message', '<p class="text-info text-center"><strong>Empty excel upload noted</strong></p>');
                redirect('backend/admin/db_dump', 'refresh');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center"><strong>Empty excel upload Failed</strong></p>');
                redirect('backend/admin/db_dump', 'refresh');
            }
        } else {
            $batch = $this->db->insert_batch($client . '_invoice', $data);
            if ($batch) {
                //echo json_encode($batch);
                $last_row = $this->admin_model->get_last_inserted_row($client . '_invoice');
                $last_id = $last_row->invoice_id;
                $first_id = $last_id - ($count - 1);

                //update account balance in accounts table
                $client_id = $this->db->select('client_id')->from('clients')->where('organization', $client)->get()->row()->client_id;
                // get the total amount of the last dump
                $dump_sum = $this->admin_model->get_sum_last_dump($client, $first_id, $last_id);

                //get the current account balance
                $client_account = $this->admin_model->get_data_account_balance_id($acc_dept);
                $client_account_balance = $client_account->balance;

                //get new balance
                $new_account_balance = $client_account_balance - $dump_sum;
                $account_balance = array(
                    'dept_id' => $acc_dept,
                    'amount' => $dump_sum,
                    'transaction_date' => date('Y-m-d h:i:s'),
                    'source' => 'Dump'

                );
                $this->admin_model->add_balance($account_balance);
                // update the existing new account with records after the dump
                $this->admin_model->update_account_balance(['balance' => $new_account_balance], ['dept_id' => $acc_dept]);

                //get the new transaction account number to add to excel listing records
                $account_id = $this->admin_model->get_client_account_detail($acc_dept)->account_id;
                //end update_account Balance

                $config_excel = array(
                    'upload_path'   => './uploads/agency_invoices',
                    'allowed_types' => 'xls|xlsx',
                    'remove_spaces' => 'TRUE',
                    'file_name'      => time() . '_' . $_FILES['excel_data']['name']
                );
                $this->load->library('upload', $config_excel);
                if (!$this->upload->do_upload('excel_data')) {
                    $this->session->set_flashdata('message', '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</p>');
                    redirect('backend/admin/db_dump', 'refresh');
                } else {
                    $data_excel = $this->upload->data();
                    $excel = $data_excel['file_name'];
                    $client_dump = array(
                        'client_name' => $client,
                        'uploaded_date' => $dump_date,
                        'date_for' => $date_for,
                        'uploaded_by' => $this->ion_auth->user()->row()->id,
                        'total_row' => $count,
                        'last_id' => $last_id,
                        'first_id' => $first_id,
                        'filename' => $excel,
                        'account_id' => $account_id,
                        'dept_id'   => ($this->input->get_post('dept')) ?? NULL
                    );
                    $this->admin_model->client_dump($client_dump);
                    $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>' . ucwords(str_replace('_', ' ', $client)) . '\'s transaction saved successfully</strong></p>');
                    redirect('backend/admin/db_dump', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger"><strong>' . ucwords(str_replace('_', ' ', $client)) . '\'s invoice dump failed</strong></p>');
                redirect('backend/admin/db_dump', 'refresh');
            }
        }
    }

    private function _range_dump_transactions($file, $client, $dump_date, $user)
    {
        $this->load->library('excel');
        $path = $_FILES[$file]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        $data = array();
        $post_date = array();
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($client == 'amazon' || $client == 'fleet_serv_pro') {
                    $data[] = array(
                        'license_plate'  =>($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->vehicle_id) ? trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) : ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->license_plate),
                        'state_code'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'agency_name'    => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                        'exit_date_time'  => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(2, $row)->getFormattedValue())),
                        'exit_name'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'class'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'toll'   => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                        'dept_id' => ($this->input->post('dept')) ? ($this->input->post('dept')) : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id)),
                        'sub_dept_id' => (!$this->input->post('dept')) ? NULL : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id)),
                        'unit' => ($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? (($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? NULL),
                        'dump_date'   => $dump_date,
                        'date_for'   => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(8, $row)->getFormattedValue()))
                    );
                    $post_date[] =  date('Y-m-d', strtotime($worksheet->getCellByColumnAndRow(8, $row)->getFormattedValue()));
                } else {
                    $data[] = array(
                        'license_plate'  =>($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->vehicle_id) ? trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) : ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->license_plate),
                        'state_code'   => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'agency_name'    => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                        'exit_date_time'  => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(3, $row)->getFormattedValue())),
                        'exit_lane'   => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                        'exit_location'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'toll'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'dept_id' => ($this->input->post('dept')) ? ($this->input->post('dept')) : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->dept_id)),
                        'sub_dept_id' => (!$this->input->post('dept')) ? NULL : (($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id) ?? ($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->sub_dept_id)),
                        'unit' => ($this->db->get_where('vehicles', ['license_plate' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? (($this->db->get_where('vehicles', ['tolltag' => trim($worksheet->getCellByColumnAndRow(0, $row)->getValue())])->row()->unit) ?? NULL),
                        'dump_date'   => $dump_date,
                        'date_for'   => date('Y-m-d H:i:s', strtotime($worksheet->getCellByColumnAndRow(7, $row)->getFormattedValue()))
                    );
                    $post_date[] = date('Y-m-d', strtotime($worksheet->getCellByColumnAndRow(7, $row)->getFormattedValue()));
                }
            } //end foreach row
        } //end foreach object

        //Delete previous dumps for date_for [date]
        $dept = ($this->input->post('dept')) ?? 0;
        $client_id = $this->admin_model->get_clients_id($client)->client_id;
        $acc_dept = ($this->input->post('dept')) ?? $this->db->like('dept_name', 'overview', 'both')->where('client_id', $client_id)->get('departments')->row()->dept_id;

        /*//end of get max and min date for datefor
        usort($post_date, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return ($dateTimestamp1 < $dateTimestamp2) ? -1: 1;
        });*/

        $date_from =  min($post_date);
        $date_to =  max($post_date);
        //end of get max and min date for datefor

        $no_of_dumps = $this->admin_model->range_check_dumper($date_from, $date_to, $client, $dept);
        if ($no_of_dumps) {
            /* $delete_previous_transactions = $this->admin_model->delete_previous_transactions($date_for, $client, $dept);*/
            foreach ($no_of_dumps as $dump) {
                //delete dump from transaction table
                $client_table = $dump->client_name . '_invoice';
                $this->admin_model->excel_dumps_delete($dump->last_id, $dump->first_id, $client_table);
                //update account balance
                $ac_amount = $this->admin_model->get_data_account_id($dump->account_id)->amount;
                $current_balance = $this->admin_model->get_data_account_balance_id($acc_dept)->balance;
                $new_balance = $ac_amount + $current_balance;
                $this->admin_model->account_id_delete($dump_data->account_id);
                $update_balance = array(
                    'balance' => $new_balance
                );
                $this->admin_model->update_account_balance($update_balance, ['dept_id' => $acc_dept]);
                //delete the excel listing
                $this->admin_model->excel_listing_delete($dump->excel_dump_id);

                $path = FCPATH . '/uploads/agency_invoices/' . $dump->filename;
                unlink($path);
            }
            // $previous_dump = $this->admin_model->undo_dumps($date_for, $client, $dept, ['is_deleted' => 1]);
        }
        $batch = $this->db->insert_batch($client . '_invoice', $data);
        if ($batch) {
            //echo json_encode($batch);
            $count = count($data);
            $last_row = $this->admin_model->get_last_inserted_row($client . '_invoice');
            $last_id = $last_row->invoice_id;
            $first_id = $last_id - ($count - 1);

            //update account balance in accounts table
            $client_id = $this->db->select('client_id')->from('clients')->where('organization', $client)->get()->row()->client_id;
            // get the total amount of the last dump
            $dump_sum = $this->admin_model->get_sum_last_dump($client, $first_id, $last_id);

            //get the current account balance
            $client_account = $this->admin_model->get_data_account_balance_id($acc_dept);
            $client_account_balance = $client_account->balance;

            //get new balance
            $new_account_balance = $client_account_balance - $dump_sum;
            $account_balance = array(
                'dept_id' => $acc_dept,
                'amount' => $dump_sum,
                'transaction_date' => date('Y-m-d h:i:s'),
                'source' => 'Dump'

            );
            $this->admin_model->add_balance($account_balance);
            // update the existing new account with records after the dump
            $this->admin_model->update_account_balance(['balance' => $new_account_balance], ['dept_id' => $acc_dept]);

            //get the new transaction account number to add to excel listing records
            $account_id = $this->admin_model->get_client_account_detail($acc_dept)->account_id;
            //end update_account Balance

            $config_excel = array(
                'upload_path'   => './uploads/agency_invoices',
                'allowed_types' => 'xls|xlsx',
                'remove_spaces' => 'TRUE',
                'file_name'      => time() . '_' . $_FILES['excel_data']['name']
            );
            $this->load->library('upload', $config_excel);
            if (!$this->upload->do_upload('excel_data')) {
                $this->session->set_flashdata('message', '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</p>');
                redirect('backend/admin/range_db_dump', 'refresh');
            } else {
                $data_excel = $this->upload->data();
                $excel = $data_excel['file_name'];
                $client_dump = array(
                    'client_name' => $client,
                    'uploaded_date' => $dump_date,
                    'date_for' => NULL,
                    'uploaded_by' => $this->ion_auth->user()->row()->id,
                    'total_row' => $count,
                    'last_id' => $last_id,
                    'first_id' => $first_id,
                    'filename' => $excel,
                    'account_id' => $account_id,
                    'dept_id'   => ($this->input->get_post('dept')) ?? NULL
                );
                $this->admin_model->client_dump($client_dump);
                $this->session->set_flashdata('message', '<p class="text-success text-center"><strong>' . ucwords(str_replace('_', ' ', $client)) . '\'s transaction saved successfully</strong></p>');
                redirect('backend/admin/range_db_dump', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger"><strong>' . ucwords(str_replace('_', ' ', $client)) . '\'s invoice dump failed</strong></p>');
            redirect('backend/admin/range_db_dump', 'refresh');
        }
    }

    public function dept_citation_vehicles($dept)
    {
        $data = $this->admin_model->dept_vehicles($dept);
        echo json_encode($data);
    }

    public function citations()
    {
        $this->data['title'] = "Admin &raquo; Citations";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['states'] = $this->admin_model->get_states();
        $this->data['citations'] = $this->admin_model->get_citations();
        $this->render('backend/citation_listing');
    }
    public function accounts()
    {
        $this->data['title'] = "Admin &raquo; Account Management";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['account_balance'] = $this->admin_model->get_account_balance();
        $this->render('backend/accounts');
    }

    public function view_accounts_details($id)
    {
        $month = date('Y-m');
        $monthly_account_details = $this->admin_model->view_accounts_details($month, $id);
        echo json_encode($monthly_account_details);
    }

    public function get_client_balance($id)
    {
        $account_detail = $this->admin_model->get_account_detail($id);
        echo json_encode($account_detail);
    }

    public function add_balance()
    {
        $this->form_validation->set_rules('amount', 'Amount to Add', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors()));
        } else {
            $account_balance_amount = ($this->input->post('acc_balance')) + ($this->input->post('amount'));
            $dept_id = $this->input->post('dept_id');
            $account_balance = array(
                'dept_id' => $dept_id,
                'amount' => $this->input->post('amount'),
                'transaction_date' => date('Y-m-d h:i:s'),
                'source' => 'Added Amount'
            );
            $this->admin_model->add_balance($account_balance);
            $this->admin_model->update_account_balance(['balance' => $account_balance_amount], ['dept_id' => $dept_id]);
            echo json_encode(array('status' => TRUE, 'msg' => 'Amount Added to Balance sucessfully'));
        }
    }

    public function edit_balance()
    {
        $this->form_validation->set_rules('new_balance', 'New Balance', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors()));
        } else {
            $account_balance_amount = $this->input->post('new_balance');
            $difference = $this->input->post('balance') - $this->input->post('new_balance');
            $dept_id = $this->input->post('edit_dept_id');

            $account_balance = array(
                'dept_id' => $dept_id,
                'amount' => $difference,
                'transaction_date' => date('Y-m-d h:i:s'),
                'source' => 'Updated amount'
            );

            $this->admin_model->add_balance($account_balance);
            $this->admin_model->update_account_balance(['balance' => $account_balance_amount], ['dept_id' => $dept_id]);
            echo json_encode(array('status' => TRUE, 'msg' => 'Balance edited sucessfully'));
        }
    }

    public function new_citation()
    {
        $this->data['title'] = "Admin &raquo; Add Citation";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['agencies'] = $this->admin_model->get_agencies();
        $this->render('backend/citations');
    }

    public function edit_citation($id)
    {
        $citation = $this->admin_model->get_citation($id);
        if (!$citation) {
            echo json_encode(['status' => false, 'msg' => 'No citation found with specified ID']);
        } else {
            echo json_encode(['status' => true, 'msg' => $this->admin_model->get_citation($id)]);
        }
    }
    public function import_citations()
    {
        $this->data['title'] = "Admin &raquo; Import Citations";
        $ext = (new SplFileInfo($_FILES["citations_file"]["name"]))->getExtension();
        if ($_FILES["citations_file"]["name"] != '' && ($ext == 'xls' || $ext == 'xlsx')) {
            $this->load->library('excel');
            $path = $_FILES["citations_file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            $data = array();
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $data[] = array(
                        'organization'  => $this->input->get_post('client'),
                        'license_plate'  => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                        'license_plate_state'  => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'citation_type'   => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                        'violation_no'    => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                        'violation_date'  => date('Y-m-d', strtotime($worksheet->getCellByColumnAndRow(4, $row)->getFormattedValue())),
                        'violation_state'   => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'citation_amount'   => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                        'payable_to'   => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                        'paid_date'   => date('Y-m-d', strtotime($worksheet->getCellByColumnAndRow(8, $row)->getFormattedValue())),
                        'citation_fee'   => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),
                        'paid_amount'   => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),
                        'citation_status'   => $worksheet->getCellByColumnAndRow(11, $row)->getValue()
                    );
                }
            }
            $dump = $this->db->insert_batch('citations', $data);
            if ($dump) {
                echo json_encode(array('status' => true, 'msg' => '<i class="fa fa-check-square"></i> Dumped! Upload xls|xlsx to server...'));
            } else {
                echo json_encode(array('status' => false, 'msg' => '<i class="fa fa-exclamation-triangle"></i> Error importing citations'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => '<i class="fa fa-exclamation-triangle"></i> Choose a .xls or .xlsx file to upload'));
        }
    }

    public function save_citation()
    {
        if ($this->_citation_validate() === FALSE) {
            echo json_encode(array('status' => false, 'msg' => validation_errors('<li class="list-inline"><i class="fa fa-exclamation-circle"></i> ', '</li>')));
        } else {
            $citation = array(
                'license_plate' => $this->input->post('vehicle'),
                'license_plate_state' => $this->input->post('license_plate_state'),
                'violation_no' => $this->input->post('violation_no'),
                'organization' => $this->input->post('client'),
                'dept_id' => ($this->input->post('dept')) ?? NULL,
                'violation_date' => $this->input->post('violation_date'),
                'paid_date' => $this->input->post('paid_date'),
                'payable_to' => $this->input->post('payable_to'),
                'citation_fee' => $this->input->post('fees_amount'),
                'citation_amount' => $this->input->post('citation_amount'),
                'paid_amount' => $this->input->post('paid_amount'),
                'citation_type' => $this->input->post('type'),
                'citation_status' => $this->input->post('citation_status'),
                'violation_state' => $this->input->post('violation_state')
            );
            if ($this->admin_model->save_citation($citation)) {
                echo json_encode(array('status' => true, 'msg' => '<p class="text-success text-center"><strong>Citation saved successfully</strong></p>'));
            } else {
                echo json_encode(array('status' => false, 'msg' => '<p class="text-danger text-center"><strong>Error saving citation</strong></p>'));
            }
        }
    }

    public function update_citation()
    {
        if ($this->_citation_validate() === FALSE) {
            echo json_encode(array('status' => false, 'msg' => validation_errors('<li class="list-inline"><i class="fa fa-exclamation-circle"></i> ', '</li>')));
        } else {
            $citation_update = array(
                'license_plate' => $this->input->post('vehicle'),
                'license_plate_state' => $this->input->post('license_plate_state'),
                'violation_no' => $this->input->post('violation_no'),
                'organization' => $this->input->post('client'),
                'dept_id' => ($this->input->post('dept')) ?? NULL,
                'violation_date' => $this->input->post('violation_date'),
                'paid_date' => $this->input->post('paid_date'),
                'payable_to' => $this->input->post('payable_to'),
                'citation_fee' => $this->input->post('fees_amount'),
                'citation_amount' => $this->input->post('citation_amount'),
                'paid_amount' => $this->input->post('paid_amount'),
                'citation_type' => $this->input->post('type'),
                'citation_status' => $this->input->post('citation_status'),
                'violation_state' => $this->input->post('violation_state')
            );
            if ($this->admin_model->update_citation($this->input->post('citation_id'), $citation_update)) {
                echo json_encode(array('status' => true, 'msg' => '<p class="text-success text-center"><strong>Citation updated successfully</strong></p>'));
            } else {
                echo json_encode(array('status' => false, 'msg' => '<p class="text-danger text-center"><strong>Error updating citation</strong></p>'));
            }
        }
    }

    private function _citation_validate()
    {
        $this->form_validation->set_rules('client', 'Client ', 'trim|required', array('required' => 'Select Client'));
        $this->form_validation->set_rules('vehicle', 'Vehicle ', 'trim|required', array('required' => 'Select Vehicle LP'));
        $this->form_validation->set_rules('license_plate_state', 'LP State ', 'trim|required');
        $this->form_validation->set_rules('violation_no', 'Violation no.', 'trim|required');
        $this->form_validation->set_rules('violation_date', 'Violation date', 'trim|required');
        $this->form_validation->set_rules('fees_amount', 'Fees Amount', 'trim|required', array('required' => 'Enter citation fee'));
        $this->form_validation->set_rules('citation_amount', 'Citation Amount', 'trim|required', array('required' => 'Enter citation amount'));
        $this->form_validation->set_rules('type', 'Citation type', 'trim|required');
        $this->form_validation->set_rules('citation_status', 'Citation status', 'trim|required');
        $this->form_validation->set_rules('payable_to', 'Payable to', 'trim|required');
        $this->form_validation->set_rules('violation_state', 'Violation state', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function resolve_citation($id)
    {
        $resolve = $this->db->select('citation_status')->from('citations')->where('citation_id', $id)->get()->row()->citation_status;
        $resolution = array(
            'citation_status' => ($resolve == 1) ? 0 : 1
        );
        $this->db->where('citation_id', $id)->update('citations', $resolution);
        echo json_encode(array('status' => TRUE, 'msg' => 'Citation status changed'));
    }

    /*Client users management*/
    function client_users($client)
    {
        $this->data['title'] = "Admin &raquo; Client Users";
        $this->data['clients'] = $this->db->select('id, email, organization')->from('users')->join('contacts', 'contacts.user_id = users.id')->join('clients', 'clients.client_id = contacts.client_id')->where('client_status', 1)->get()->result();
        $this->data['has_sub_depts'] = $this->db->get_where('clients', ['client_id' => $client])->row()->sub_dept_exist;
        $this->data['organization'] = $org = $this->db->where('client_id', $client)->get('clients')->row()->organization;
        $this->data['client_users'] = $this->admin_model->get_client_users($client);
        $this->data['client_groups'] = $this->admin_model->get_client_groups($client);
        $this->data['client_departments'] = $this->admin_model->departments($client);
        $this->render('backend/client_users');
    }

    function edit_client_user($id)
    {
        $client_user = $this->admin_model->get_client_user($id);
        if ($client_user) {
            echo json_encode(['status' => true, 'msg' => $client_user]);
        } else {
            echo json_encode(['status' => false, 'msg' => 'User not found... contact system administrator']);
        }
    }

    function add_client_user()
    {
        if (!$this->_validate_client_user()) {
            echo json_encode(['status' => false, 'msg' => validation_errors()]);
        } else {
            $modules = '';
            foreach ($this->input->post('module[]') as $m) {
                $modules .= $m . ',';
            }
            $username = $this->db->where('client_id', $this->input->post('org'))->get('clients')->row()->organization;
            $email = $this->input->post('user_email');
            $password = 'password';

            $user_id = $this->ion_auth->register($username, $password, $email);
            if (!$user_id) {
                echo json_encode(['status' => false, 'msg' => 'Error creating client user account']);
            } else {
                $client_id = $this->input->post('org');
                $contact_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('user_phone'),
                    'title' => $this->input->post('designation'),
                    'department_id' => $this->input->post('department') ?? $this->db->get_where('departments', ['dept_name LIKE' => '%overview%', 'client_id' => $client_id])->row()->dept_id,
                    'modules' => rtrim($modules, ','),
                    'user_id' => $user_id,
                    'client_id' => $client_id,
                    'default_user' => $this->input->post('entity') == 'overview' ? 1 : 0,
                    'can_update' => $this->input->post('vehicle_updater'),
                    'group_id' => ($this->input->post('group')) ?? null
                );
                if (!$this->admin_model->save_client_contact($contact_data)) {
                    echo json_encode(['status' => false, 'msg' => 'Error saving user\'s contact information']);
                } else {
                    echo json_encode(['status' => true, 'msg' => 'User account created successfully']);
                }
            }
        }
    }

    function update_client_user()
    {
        if (!$this->_validate_client_user()) {
            echo json_encode(['status' => false, 'msg' => validation_errors()]);
        } else {
            $account_updates = array(
                'email' => $this->input->post('user_email')
            );

            $email_update = $this->ion_auth->update($this->input->post('user_id'), $account_updates);
            if (!$email_update) {
                echo json_encode(['status' => false, 'msg' => 'Error creating client user account']);
            } else {
                $modules = '';
                foreach ($this->input->post('module[]') as $m) {
                    $modules .= $m . ',';
                }
                $client_id = $this->input->post('org');
                $contact_updates = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('user_phone'),
                    'title' => $this->input->post('designation'),
                    'department_id' => $this->input->post('department') ?? $this->db->get_where('departments', ['dept_name LIKE' => '%overview%', 'client_id' => $client_id])->row()->dept_id,
                    'modules' => rtrim($modules, ','),
                    'can_update' => $this->input->post('vehicle_updater'),
                    'group_id' => ($this->input->post('group')) ?? null
                );
                $this->admin_model->update_client_contact(['user_id' => $this->input->post('user_id')], $contact_updates);
                echo json_encode(['status' => true, 'msg' => 'User account updated successfully']);
            }
        }
    }

    function _validate_client_user()
    {
        $this->form_validation->set_rules('first_name', 'First name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
        $this->form_validation->set_rules('user_phone', 'Phone number', 'trim|required');
        $this->form_validation->set_rules('user_email', 'Email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('entity', 'Entity', 'trim|required');
        if ($this->input->post('entity') == 'group') {
            $this->form_validation->set_rules('group', '', 'trim|required', ['required' => 'Select Group']);
        }
        if ($this->input->post('entity') == 'dept') {
            $this->form_validation->set_rules('department', '', 'trim|required', ['required' => 'Select department']);
        }
        $this->form_validation->set_rules('module[]', '', 'trim|required', ['required' => 'Select at least a module']);

        return ($this->form_validation->run()) ? true : false;
    }

    public function system_users()
    {
        $this->data['title'] = 'System Users';
        $this->data['users'] = $this->ion_auth->users('admin')->result();
        $this->render('backend/system_users');
    }

    public function system_user($user_id)
    {
        $user = $this->ion_auth->user($user_id)->row();
        if (!$user) {
            echo json_encode(['status' => false, 'msg' => 'Error retrieving user account details']);
        } else {
            echo json_encode(['status' => true, 'msg' => $user]);
        }
    }

    public function add_system_user()
    {
        $this->form_validation->set_rules('system_user_email', '', 'required|trim|valid_email', ['required' => 'Provide user email', 'valid_email' => 'Invalid email address']);
        $this->form_validation->set_rules('user_password', '', 'required|trim|min_length[8]', ['required' => 'Provide a password of choice', 'min_length' => 'Password must be at least 8 characters long']);
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['status' => false, 'msg' => validation_errors('<span class="text-danger"><i class="fa fa-exclamation-circle"></i>  ', '</span>')]);
        } else {
            $username = $this->input->post('system_user_email');
            $password = $this->input->post('user_password');
            $email = $this->input->post('system_user_email');
            if (!$this->ion_auth->email_check($email)) {
                $group_name = 'admin';
                $user_id = $this->ion_auth->register($username, $password, $email, array(), array('1'));
                if (!$user_id) {
                    echo json_encode(['status' => false, 'msg' => '<p class="text-danger"><strong>System user account creation failed</strong></p>']);
                } else {
                    echo json_encode(['status' => true, 'msg' => 'System user account created successfully']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => '<p class="text-info"><i class="fa fa-exclamation-circle"></i> Email address already registered with another account</p>']);
            }
        }
    }

    public function update_system_user()
    {
        $this->form_validation->set_rules('system_user_email', '', 'required|trim|valid_email', ['required' => 'Provide user email', 'valid_email' => 'Invalid email address']);
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['status' => false, 'msg' => validation_errors('<span class="text-danger"><i class="fa fa-exclamation-circle"></i>  ', '</span>')]);
        } else {
            $updates = array('email' => $this->input->post('system_user_email', TRUE));
            $update_account = $this->ion_auth->update($this->input->post('system_user_id', TRUE), $updates);
            if (!$update_account) {
                echo json_encode(array('status' => FALSE, 'msg' => 'Account update failed'));
            } else {
                echo json_encode(array('status' => TRUE, 'msg' => 'Account update successful'));
            }
        }
    }

    function dept_users($dept)
    {
        $this->data['title'] = "Admin &raquo; Client Users";
        $department = $this->db->where('dept_id', $dept)->get('departments')->row();
        $this->data['organization'] = $department->dept_name;
        //$this->data['has_sub_depts'] = $this->db->get_where('clients', ['client_id' => $department->client_id])->row()->sub_dept_exist;
        $this->data['has_sub_depts'] = $this->db->get_where('sub_departments', ['dept_id' => $dept])->result();
        $this->data['dept_users'] = $this->admin_model->get_dept_users($dept);
        $this->data['client_sub_departments'] = $this->admin_model->client_sub_departments($dept);
        $this->render('backend/dept_users');
    }


    function add_dept_user()
    {
        if (!$this->_validate_client_user()) {
            echo json_encode(['status' => false, 'msg' => validation_errors()]);
        } else {
            $modules = '';
            foreach ($this->input->post('module[]') as $m) {
                $modules .= $m . ',';
            }
            $username = $this->db->where('dept_id', $this->input->post('org'))->get('departments')->row()->dept_name;
            $email = $this->input->post('user_email');
            $password = 'password';
            $client_id = $this->db->get_where('departments', ['dept_id' => $this->input->post('org')])->row()->client_id;
            $user_id = $this->ion_auth->register($username, $password, $email);
            if (!$user_id) {
                echo json_encode(['status' => false, 'msg' => 'Error creating client user account']);
            } else {
                $dept_has_default_user = $this->db->where('department_id', $this->input->post('org'))->where('default_user', 1)->get('contacts')->row();
                if ($dept_has_default_user) {
                    $contact_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'phone' => $this->input->post('user_phone'),
                        'title' => $this->input->post('designation'),
                        'department_id' => $this->input->post('org'),
                        'modules' => rtrim($modules, ','),
                        'user_id' => $user_id,
                        'client_id' => $client_id,
                        'sub_dept_id' => $this->input->post('department'),
                        'default_user' => 0,
                        'can_update' => $this->input->post('vehicle_updater')
                    );
                } else {

                    $organization = $this->db->get_where('departments', ['dept_id' => $this->input->post('org')])->row()->dept_name;
                    $overview_sub_department_id = $this->admin_model->save_sub_dept(['sub_dept_name' => 'overview [' . $organization . ']', 'dept_id' => $this->input->post('org')]);
                    $contact_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'phone' => $this->input->post('user_phone'),
                        'title' => $this->input->post('designation'),
                        'department_id' => $this->input->post('org'),
                        'modules' => rtrim($modules, ','),
                        'user_id' => $user_id,
                        'client_id' => $client_id,
                        'sub_dept_id' => $overview_sub_department_id,
                        'default_user' => 1,
                        'can_update' => $this->input->post('vehicle_updater')
                    );
                }
                //$default_sub_dept = $this->db->get_where('sub_departments', ['dept_id' => $]);

                if (!$this->admin_model->save_client_contact($contact_data)) {
                    echo json_encode(['status' => false, 'msg' => 'Error saving user\'s contact information']);
                } else {
                    echo json_encode(['status' => true, 'msg' => 'User account created successfully']);
                }
            }
        }
    }

    function update_dept_user()
    {
        if (!$this->_validate_client_user()) {
            echo json_encode(['status' => false, 'msg' => validation_errors()]);
        } else {
            $account_updates = array(
                'email' => $this->input->post('user_email')
            );

            $email_update = $this->ion_auth->update($this->input->post('user_id'), $account_updates);
            if (!$email_update) {
                echo json_encode(['status' => false, 'msg' => 'Error creating client user account']);
            } else {
                $modules = '';
                foreach ($this->input->post('module[]') as $m) {
                    $modules .= $m . ',';
                }
                $contact_updates = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('user_phone'),
                    'title' => $this->input->post('designation'),
                    'sub_dept_id' => $this->input->post('department'),
                    'modules' => rtrim($modules, ','),
                    'can_update' => $this->input->post('vehicle_updater')
                );
                $this->admin_model->update_client_contact(['user_id' => $this->input->post('user_id')], $contact_updates);
                echo json_encode(['status' => true, 'msg' => 'User account updated successfully']);
            }
        }
    }

    public function dump_stats()
    {
        $this->data['title'] = "Admin &raquo; Dump Stats";
        $this->data['dump_stats'] = $this->admin_model->get_dump_stats();
        $dump_stats_totals = $this->admin_model->get_dump_stats_totals();
        $this->data['dump_stats_totals'] = '';
        $this->load->helper('text');
        foreach ($dump_stats_totals as $dst) {
            $name = explode('@', $dst->email);
            $this->data['dump_stats_totals'] .= "{ agent:'" . ucfirst(substr($name[0], 0, 6)) . "', total:" . $dst->total . "}, ";
        }
        $this->render('backend/dump_stats');
    }
    public function fulfilment()
    {
        $this->data['title'] = "Admin &raquo; fulfilment";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['states'] = $this->admin_model->get_states();
        $this->data['orders'] = $this->admin_model->get_fulfilment();
        $this->render('backend/fulfilment');
    }
    public function fulfilment_status($id, $status)
    {
        $processor = $this->ion_auth->user()->row()->id;
        if (!$this->admin_model->update_fulfilment_status($id, $status, $processor)) {
            echo json_encode(['status' => false, 'msg' => 'Error in updating transaction']);
        } else {
            echo json_encode(['status' => true, 'msg' => 'Transaction filed as disputed']);
        }
    }
    public function posts()
    {

        $columns = array(
            0 => 'vehicle_id',
            1 => 'license_plate',
            2 => 'color',
            3 => 'make',
            4 => 'model',
            5 => 'unit',
            6 => 'tolltag',
            7 => 'vin_no',
            8 => 'store',
            9 => 'location',
            10 => 'start_date',
            11 => 'end_date',
            12 => 'organization',
            13 => 'dept_name',
            14 => 'status',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $status = (empty($this->input->post('status'))) ? '-2' : $this->input->post('status');
        $client = (empty($this->input->post('member'))) ? 0 : $this->input->post('member');
        $dept = (empty($this->input->post('department'))) ? 0 : $this->input->post('department');


        $totalData = $this->admin_model->allposts_count($status, $client, $dept);

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->admin_model->allposts($limit, $start, $order, $dir, $status, $client, $dept);
        } else {
            $search = $this->input->post('search')['value'];

            $posts =  $this->admin_model->posts_search($limit, $start, $search, $order, $dir, $status, $client, $dept);

            $totalFiltered = $this->admin_model->posts_search_count($search, $status, $client, $dept);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['vehicle_id'] = $post->vehicle_id;
                $nestedData['license_plate'] = ($post->license_plate !== null) ? $post->license_plate : "<center>-</center>";
                $nestedData['color'] = $post->color;
                $nestedData['make'] = ($post->make !== null) ? $post->make : "<center>-</center>";
                $nestedData['model'] = ($post->model !== null) ? $post->model : "<center>-</center>";
                $nestedData['unit'] = ($post->unit !== null) ? $post->unit : "<center>-</center>";
                $nestedData['tolltag'] = ($post->tolltag !== null) ? $post->tolltag : "<center>-</center>";
                $nestedData['vin_no'] = ($post->vin_no !== null) ? $post->vin_no : "<center>-</center>";
                $nestedData['store'] = ($post->store !== null) ? $post->store : "<center>-</center>";
                $nestedData['location'] = ($post->location !== null) ? $post->location : "<center>-</center>";
                $nestedData['year'] = ($post->year !== null) ? $post->year : "<center>-</center>";
                $nestedData['start_date'] = date('m/d/Y', strtotime($post->start_date));
                $nestedData['end_date'] = ($post->end_date !== '0000-00-00') ? date('j M Y h:i a', strtotime($post->end_date)) : "<center>-</center>";
                $nestedData['organization'] = ucwords(str_replace('_', ' ', $post->organization));
                $nestedData['dept_name'] = ucwords($post->dept_name);
                if ($post->vehicle_status == 0) {
                    $nestedData['status'] = "Maintenance";
                }
                if ($post->vehicle_status == 1) {
                    $nestedData['status'] = "Active";
                }
                if ($post->vehicle_status == 2) {
                    $nestedData['status'] = "Start";
                }
                if ($post->vehicle_status == 3) {
                    $nestedData['status'] = "End";
                }
                if ($post->vehicle_status == -1) {
                    $nestedData['status'] = "Inactive";
                }
                /*'switch ('.$post->vehicle_status.') {
                                  case "0" : "Maintenance"; break;
                                  case "1" : "Active"; break;
                                  case "2" : "Start"; break;
                                  case "3" : "End"; break;
                                  case "-1" : "Inactive"; break;}';*/
                $nestedData['action'] = '
                    <button type="button" onclick="edit_vehicle(' . $post->vehicle_id . ')" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit Vehicle"><i class="flaticon-pen"></i>
                                                                        </button> | <button class="btn btn-danger btn-xs" onclick="delete_vehicle(' . $post->vehicle_id . ')" data-toggle="tooltip" data-placement="bottom" title="Delete Vehicle"><span><i class="fa fa-trash"></i></span></button>';

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
    //manage toll tags
    public function toll_tag()
    {
        $this->data['title'] = "Admin &raquo; Tolltags";
        $this->data['tags'] = $this->admin_model->get_toll_tags();
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->render('backend/toll_tag');
    }

    public function add_toll_tag()
    {
        //$this->form_validation->set_rules('previx', 'Previx','trim|required');
        $this->form_validation->set_rules('toll_tag', 'Toll Tag', 'trim|required');
        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $toll_tag = array('previx' => $this->input->post('previx'), 'client_id' => $this->input->post('client'), 'tag' => $this->input->post('toll_tag'));
            $this->admin_model->save_toll_tag($toll_tag);
            echo json_encode(array('status' => TRUE, 'msg' => 'Toll Tag saved'));
        }
    }
    public function edit_toll_tag($id)
    {
        $tag_data = $this->admin_model->toll_tag_by_id($id);
        if ($tag_data) {
            echo json_encode(array('status' => TRUE, 'tag_data' => $tag_data));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    public function update_toll_tag()
    {
        //$this->form_validation->set_rules('previx', 'Previx','trim|required');
        $this->form_validation->set_rules('toll_tag', 'Toll Tag', 'trim|required');
        $this->form_validation->set_rules('client', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $update = array('previx' => $this->input->post('previx'), 'client_id' => $this->input->post('client'), 'tag' => $this->input->post('toll_tag'));
            $this->admin_model->update_toll_tag(array('tag_id' => $this->input->post('id')), $update);
            echo json_encode(array('status' => TRUE, 'msg' => 'Toll Tag updated'));
        }
    }

    public function delete_toll_tag($id)
    {
        $this->admin_model->delete_toll_tag($id);
        echo json_encode(array("status" => TRUE));
    }

    //manage credit card Information
    public function card_info()
    {
        $this->data['title'] = "Admin &raquo; Payment Card Info";
        $this->data['cards'] = $this->admin_model->get_cards();
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['card_types'] = $this->admin_model->get_card_type_list();
        $this->render('backend/card_info');
    }

    public function edit_card_info($id)
    {
        $card_data = $this->admin_model->card_by_id($id);
        if ($card_data) {
            echo json_encode(array('status' => TRUE, 'card_data' => $card_data));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

    public function delete_card_info($id)
    {
        $this->admin_model->delete_card_info($id);
        echo json_encode(array("status" => TRUE));
    } 

    public function add_card_info()
    {
        $this->form_validation->set_rules('holder_name', 'Card Holder Name', 'trim|required');
        $this->form_validation->set_rules('card_type', 'Card Type', 'trim|required');
        $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required');
        $this->form_validation->set_rules('cvc', 'CVC', 'trim|required');
        $this->form_validation->set_rules('postal_code', 'Postal  Code', 'trim|required');
        $this->form_validation->set_rules('expiration_date', 'Expiration Date', 'trim|required');
        $this->form_validation->set_rules('client_id', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
           $card_info = array('holder_name' => $this->input->post('holder_name'),
                        'card_type' => $this->input->post('card_type'),
                        'card_number' => $this->input->post('card_number'),
                        'cvc' => $this->input->post('cvc'),
                        'postal_code' => $this->input->post('postal_code'),
                        'expiration_date' => date('Y-m-d', strtotime($this->input->post('expiration_date'))),
                        'client_id' => $this->input->post('client_id'));
            $this->admin_model->save_card_info($card_info);
            echo json_encode(array('status' => TRUE, 'msg' => 'Card Info saved'));
        }
    }

     public function update_card_info()
    {
        $this->form_validation->set_rules('holder_name', 'Card Holder Name', 'trim|required');
        $this->form_validation->set_rules('card_type', 'Card Type', 'trim|required');
        $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required');
        $this->form_validation->set_rules('cvc', 'CVC', 'trim|required');
        $this->form_validation->set_rules('postal_code', 'Postal  Code', 'trim|required');
        $this->form_validation->set_rules('expiration_date', 'Expiration Date', 'trim|required');
        $this->form_validation->set_rules('client_id', 'Client', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
        } else {
            $card_info = array('holder_name' => $this->input->post('holder_name'),
                        'card_type' => $this->input->post('card_type'),
                        'card_number' => $this->input->post('card_number'),
                        'cvc' => $this->input->post('cvc'),
                        'postal_code' => $this->input->post('postal_code'),
                        'expiration_date' => date('Y-m-d', strtotime($this->input->post('expiration_date'))),
                        'client_id' => $this->input->post('client_id'));
            $this->admin_model->update_card_info(array('card_info_id' => $this->input->post('id')), $card_info);
            echo json_encode(array('status' => TRUE, 'msg' => 'Card Info updated'));
        }
    }

    public function weekly_report()
    {
        $this->data['title'] = "Admin &raquo; Weekly Reports";
        $this->data['clients'] = $this->admin_model->get_clients_list();
        $this->data['types'] = $this->admin_model->get_report_type_list();
        $this->data['weeks'] = $this->admin_model->get_week_list();
        $this->data['reports'] = $this->admin_model->get_reports();
        $this->render('backend/weekly_report');
    }

     function add_weekly_report(){
        if ($_FILES["report_file"]["name"] == '') {
            echo json_encode(['status' => false, 'msg' => 'Select a report to upload']);
        } else {
            $ext = (new SplFileInfo($_FILES["report_file"]["name"]))->getExtension();
            if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv'){
                if ($this->_report_validate() === FALSE) {
                   echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<span>', ' | </span>')));
                } else {

                    $config = array(
                        'upload_path'   => './uploads/weekly_report',
                        'allowed_types' => 'xls|xlsx|csv',
                        'remove_spaces' => 'TRUE',
                        'file_name'      => time() . '_' . $_FILES['report_file']['name']
                    );
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('report_file')) {

                        echo json_encode(['status' => true, 'msg' => '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</p>']);
                    } else {
                        $data_excel = $this->upload->data();
                        $report_file = $data_excel['file_name'];

                     $weekly_report = array(
                        'records' => $this->input->post('records'),
                        'week_id' => $this->input->post('week'),
                        'amount' => $this->input->post('amount'),
                        'report_type_id' => $this->input->post('report_type'),
                        'end_week_date' => date('Y-m-d', strtotime($this->input->post('end_week_date'))),
                        'uploader' => $this->ion_auth->user()->row()->id,
                        'file_name' => $report_file,
                        'client_id' => $this->input->post('client_id'));
                        $this->admin_model->save_weekly_report($weekly_report);
                        echo json_encode(array('status' => TRUE, 'msg' => 'Weekly report uploaded sucessfully'));
                    }

                }
            }else{
                echo json_encode(['status' => false, 'msg' => 'Only excel files with extension .xls or .xlsx are allowed']);
            }
        }
    }    
    function update_weekly_report(){
        if ($this->_report_validate() === FALSE) {
            echo json_encode(array('status' => FALSE, 'msg' => validation_errors('<p>', '</p>')));
            exit;
        } else {
                $weekly_report = array(
                    'records' => $this->input->post('records'),
                    'week_id' => $this->input->post('week'),
                    'amount' => $this->input->post('amount'),
                    'report_type_id' => $this->input->post('report_type'),
                    'end_week_date' => date('Y-m-d', strtotime($this->input->post('end_week_date'))),
                    'uploader' => $this->ion_auth->user()->row()->id,
                    'client_id' => $this->input->post('client_id'));

                if ($_FILES["report_file"]["name"] !== '') {

                    $ext = (new SplFileInfo($_FILES["report_file"]["name"]))->getExtension();
                    if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv'){
                         $config = array(
                        'upload_path'   => './uploads/weekly_report',
                        'allowed_types' => 'xls|xlsx|csv',
                        'remove_spaces' => 'TRUE',
                        'file_name'      => time() . '_' . $_FILES['report_file']['name']
                        );
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('report_file')) {

                            echo json_encode(['status' => true, 'msg' => '<p class="text-danger"><strong>Excel error:</strong> ' . $this->upload->display_errors('', '') . '</p>']);
                        } else {
                            $data_excel = $this->upload->data();
                            $report_file = $data_excel['file_name'];

                            $previous_file = $this->db->select('file_name')->get_where('weekly_report', ['weekly_report_id' => $this->input->post('id')])->row();
                            $report_file_name = array('file_name' => $report_file);
                            $weekly_report = array_merge($weekly_report, $report_file_name);
                            unlink('./uploads/weekly_report/' . $previous_file->file_name);

                            if (!$this->admin_model->update_weekly_report(['weekly_report_id' => $this->input->post('id')], $weekly_report)) {
                                echo json_encode(['status' => false, 'msg' => 'Error Updating weekly report']);
                            } else {
                                 echo json_encode(array('status' => TRUE, 'msg' => 'Weekly report updated sucessfully'));
                            }
                        }
                   
                    }else{
                        echo json_encode(['status' => false, 'msg' => 'Only excel files with extension .xls or .xlsx are allowed']);
                    }

                }else{
                    if (!$this->admin_model->update_weekly_report(['weekly_report_id' => $this->input->post('id')], $weekly_report)) {
                            echo json_encode(['status' => false, 'msg' => 'Error Updating weekly report']);
                        } else {
                             echo json_encode(array('status' => TRUE, 'msg' => 'Weekly report updated sucessfully'));
                        }
                }


        }
    }

    private function _report_validate()
    {
      
        $this->form_validation->set_rules('end_week_date', 'Post/End Week Date', 'trim|required', array('required' => 'Specify Post/End Week Date'));
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required', array('required' => 'Provide report total amount'));
        $this->form_validation->set_rules('client_id', 'Client', 'trim|required', array('required' => 'Select Client'));
        $this->form_validation->set_rules('week', 'Week', 'trim|required', array('required' => 'Select report Week'));
        $this->form_validation->set_rules('records', 'Number of records', 'trim|required', array('required' => 'Provie number of records'));
        $this->form_validation->set_rules('report_type', 'Report type', 'trim|required', array('required' => 'Specify report type'));

        if ($this->form_validation->run() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function delete_weekly_report($id)
    {
        $this->admin_model->delete_weekly_report($id);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_weekly_report($id)
    {
        $report_data = $this->admin_model->weekly_report_by_id($id);
        if ($report_data) {
            echo json_encode(array('status' => TRUE, 'report_data' => $report_data));
        } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Failed getting data'));
        }
    }

}

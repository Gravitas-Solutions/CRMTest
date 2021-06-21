<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = array();
    function __construct(){
        parent::__construct();
    }

    protected function render($the_view = NULL, $template = 'public'){
        if($template == 'json' || $this->input->is_ajax_request()){
            header('Content-Type: application/json');
            echo json_encode($this->data);
        }elseif(is_null($template)){
            $this->load->view($the_view,$this->data);
        }else{
            $this->data['content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->load->view('templates/' . $template, $this->data);
        }
    }
}

class Auth_Controller extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        if($this->ion_auth->logged_in()===FALSE){
            redirect('auth');
        }
    }
    
    protected function render($the_view = NULL, $template = 'auth'){
        parent::render($the_view, $template);
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
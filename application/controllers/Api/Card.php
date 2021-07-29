<?php  

require APPPATH . 'libraries/REST_Controller.php';     

class Card extends REST_Controller {

    public function __construct() {

       parent::__construct();

        $this->load->model('card_model');
    }

       
    function index_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }
 
        $card = $this->card_model->card_by_id($this->get('id'));
         
        if($card)
        {
            $this->response($card, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(NULL, 404);
        }
    }

   

}
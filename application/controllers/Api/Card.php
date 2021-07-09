<?php  

require APPPATH . 'libraries/REST_Controller.php';     

class Card extends REST_Controller {
    /**
     * Get All Data from this method.
    *

     * @return Response

    */

    public function __construct() {

       parent::__construct();

        $this->load->model('card_model');
    }

       
    function card_get()
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

    // public function index_get($id = 0)
    // {
    //   if(!empty($id)){
    //         $data = $this->db->get_where("items", ['id' => $id])->row_array();
    //     }else{
    //         $data = $this->db->get("items")->result();
    //     }
    //     $this->response($data, REST_Controller::HTTP_OK);

    // }

    // public function index_post()
    // {
    //     $input = $this->input->post();
    //     $this->db->insert('items',$input);

    //     $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);

    // }

    // public function index_put($id)
    // {
    //     $input = $this->put();

    //     $this->db->update('items', $input, array('id'=>$id));
    //     $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);

    // }

    // public function index_delete($id)
    // {
    //     $this->db->delete('items', array('id'=>$id));
    //     $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);

    // }

}
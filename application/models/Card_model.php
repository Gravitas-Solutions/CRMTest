<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function card_by_id($id)
    {
        $this->db->from('card_info');
        $this->db->where('card_info_id', $id);
        $query = $this->db->get();
        return $query->row();
    }
}
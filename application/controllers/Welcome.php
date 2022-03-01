<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url');
    }

    public function index()
	{
		$this->load->view('users');
	}

	public function load_data()
	{
        $users = $this->user_model->get_users();
        echo json_encode($users);
        exit;
	}
}

<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class User extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            $this->load->helper('url');
        }

        public function index()
        {
            $users = $this->user_model->get_users();
            $rows = $users['result'];
            $count_all = $users['count_all'];
            $data = [];
            foreach ($rows as $row)
            {
                $sub_array = [];
                $sub_array[] = $row["id"];
                $sub_array[] = $row["first_name"];
                $sub_array[] = $row["last_name"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["id"] . '" class="btn btn-primary btn-xs update">Update</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["id"] . '" class="btn btn-danger btn-xs delete">Delete</button>';
                $data[] = $sub_array;
            }

            $output = [
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => count($rows),
                "recordsFiltered" => $count_all,
                "data" => $data
            ];
            return $this->json($output);
        }

        public function create_user()
        {
            $res = $this->user_model->create_user();
            $this->json($res);
        }

        public function update_user()
        {
            $res = $this->user_model->update_user();
            $this->json($res);
        }

        public function delete_one($id)
        {
            $res = $this->user_model->delete_one($id);
            $this->json($res);
        }

        public function get_one($id)
        {
            $data = $this->user_model->get_one($id);
            $this->json($data);
        }

        public function json($data, $http_code = 200)
        {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header($http_code)
                ->set_output(json_encode($data));
        }
    }

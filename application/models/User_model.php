<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class User_model extends CI_Model
    {
        public  $first_name;
        public  $last_name;
        private $table = 'users';

        public function create_user()
        {
            $posted = $_POST;
            if (!empty($posted))
            {
                $this->first_name = $posted["first_name"];
                $this->last_name = $posted["last_name"];
                $this->db->insert($this->table, $this);
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'error' => 'Request can not be empty'
            ];
        }

        public function get_users()
        {
            $query = '';
            $output = [];
            $query .= "SELECT * FROM users ";
            if (isset($_POST["search"]["value"]))
            {
                $query .= 'WHERE first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
                $query .= 'OR last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            }
            if (isset($_POST["order"]))
            {
                $column_index = $_POST['order']['0']['column'];
                $column = 'id';
                if ($column_index == 1)
                {
                    $column = 'first_name';
                }
                elseif ($column_index == 2)
                {
                    $column = 'last_name';
                }
                $query .= 'ORDER BY ' .  $column . ' ' . $_POST['order']['0']['dir'] . ' ';
            }
            else
            {
                $query .= 'ORDER BY id DESC ';
            }
            if ($_POST["length"] != -1)
            {
                $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }

            $query = $this->db->query($query);
            return [
                'result' => $query->result_array(),
                'count_all' => $this->db->count_all('users')
            ];
        }

        public function get_one($id)
        {
            $query = $this->db->from($this->table)->where('id', $id)->get();
            return $query->row_array();
        }

        public function update_user()
        {
            $posted = $_POST;
            if (!empty($posted) && isset($posted['user_id']))
            {
                $this->first_name = $posted["first_name"];
                $this->last_name = $posted["last_name"];
                $this->db->update($this->table, $this, ['id' => $posted['user_id']]);
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'error' => 'Request can not be empty'
            ];
        }

        public function delete_one($id)
        {
            $one = $this->get_one($id);
            if (!empty($one))
            {
                $this->db->delete($this->table, ['id' => $id]);
                return [
                    'success' => true
                ];
            }
            return [
                'success' => false,
                'error' => 'User not found'
            ];
        }

    }
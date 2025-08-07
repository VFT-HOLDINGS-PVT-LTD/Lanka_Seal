<?php

/* -------ASHAN RATHSARA---------
 * 
 * Database model
 */

class db_model extends CI_Model {
    /*
     * Insert data
     */

    public function insertData($table, $data) {

        try {
            $this->db->trans_start();
            $result = $this->db->insert($table, $data);
            $this->db->trans_complete();
            return $result;
        } catch (Exception $e) {
            $error = $e->getMessage();
            return $error;
        }
    }

    /*
     * Update Data
     */

    public function updateData($tableName, $dataArray, $whereArray) {

        $this->db->where($whereArray);
        $result = $this->db->update($tableName, $dataArray);
        return $result;
    }

    /*
     * Get Data
     */

    public function getData($fieldset, $tableName) {

        $this->db->select($fieldset)->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * Get Data Advance
     */

    function getData2($tablename = '', $columns_arr = array(), $where_arr = array(), $limit = 0, $offset = 0, $orderby = array()) {
        $limit = ($limit == 0) ? Null : $limit;

        if (!empty($columns_arr)) {
            $this->db->select(implode(',', $columns_arr), FALSE);
        }

        if ($tablename == '') {
            return array();
        } else {
            $this->db->from($tablename);

            if (!empty($where_arr)) {
                $this->db->where($where_arr);
            }

            if ($limit > 0 AND $offset > 0) {
                $this->db->limit($limit, $offset);
            } elseif ($limit > 0 AND $offset == 0) {
                $this->db->limit($limit);
            }

            if (count($orderby) > 0) {
                $orderbyString = '';

                foreach ($orderby as $orderclause) {

                    $orderbyString .= $orderclause["field"] . ' ' . $orderclause["order"] . ', ';
                }
                if (strlen($orderbyString) > 2) {
                    $orderbyString = substr($orderbyString, 0, strlen($orderbyString) - 2);
                }
                $this->db->order_by($orderbyString);
            }

            $query = $this->db->get();


            return $query->result();
        }
    }

    /*
     * Get Number of Rows
     */

    public function get_num_rows($strSQL) {

        $query = $this->db->query($strSQL);
        return $query->num_rows();
    }

    /*
     * Get SQL Quary Filter Data
     */

    public function getfilteredData($strSQL) {

        $query = $this->db->query($strSQL);
        return $query->result();
    }

    /*
     * Get SQL Quary Delete
     */

    public function getfilteredDelete($strSQL) {

        $query = $this->db->query($strSQL);
    }

    /*
     * Delete By
     */

    public function delete_by_id($id, $where, $table) {

        $this->db->where($where, $id);
        $this->db->delete($table);
    }

    public function setWhere($whereArray) {

        $this->db->where($whereArray);
    }

    public function get_dropdown() {

        $query = "select EmpNo,Emp_Full_Name from tbl_empmaster where status =1";
        $city_info = $this->db->query($query);
        return $city_info;
    }

    public function get_dropdown_dep() {

        $query = "select Dep_ID,Dep_Name from tbl_departments";
        $city_info = $this->db->query($query);
        return $city_info;
    }

    public function get_dropdown_des() {

        $query = "select Des_ID,Desig_Name from tbl_designations";
        $city_info = $this->db->query($query);
        return $city_info;
    }

    public function get_dropdown_group() {

        $query = "select Grp_ID,EmpGroupName from tbl_emp_group";
        $city_info = $this->db->query($query);
        return $city_info;
    }

    public function get_dropdown_comp() {

        $query = "select Cmp_ID,Company_Name from tbl_companyprofile";
        $city_info = $this->db->query($query);
        return $city_info;
    }

    public function verification($fieldset, $tableName, $where = '') {
        /*
         * Get Date time
         */
        date_default_timezone_set('Asia/Colombo');
        $date = date('Y-M-d   h:i:s a', time());

        $username = $where['username'];
        $password = $where['password'];

        /*
         * Select Table Data
         */
        $this->db->select($fieldset)->from($tableName)->where($where);
        $data = $this->db->get();



        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {

                /*
                 * Set data to Session
                 */
//                $data = $this->getfilteredData("select * from tbl_empmaster
//                Where username='$username' and password='$password' and Is_allow_login=1");


                $query = ("SELECT 
                                    *
                                FROM
                                    tbl_user_permisions as tbl_user_permisions
                                        inner JOIN
                                    tbl_empmaster ON tbl_user_permisions.user_p_id = tbl_empmaster.user_p_id
                                    where username='$username' and password='$password' and Is_allow_login=1
                                ");

                $data = $this->getfilteredData($query);

                $this->session->set_userdata('login_user', $data);

                return "success";
            }
        } else {
            return "invalid";
        }
    }

    function get_auto_cus_name($q) {
        $this->db->select('*');
        $this->db->like('Emp_Full_Name', $q);
        $query = $this->db->get('tbl_empmaster');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Emp_Full_Name']));
                $new_row['aa'] = htmlentities(stripslashes($row['Emp_Full_Name']));
                $new_row['value'] = htmlentities(stripslashes($row['EmpNo']));
                $row_set[] = $new_row; //build an array
            }
//            var_dump($row_set);die;
            echo json_encode($row_set); //format the array into json data
        }
    }

    function get_auto_emp_name($q) {
        $this->db->select('*');
        $this->db->like('Emp_Full_Name', $q);
        $query = $this->db->get('tbl_empmaster');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Emp_Full_Name']));
                $new_row['value'] = htmlentities(stripslashes($row['Emp_Full_Name']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
    }

    function get_auto_emp_no($q) {
        $this->db->select('*');
        $this->db->like('EmpNo', $q);
        $query = $this->db->get('tbl_empmaster');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['EmpNo']));
                $new_row['value'] = htmlentities(stripslashes($row['EmpNo']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
    }

    public function get_emp_info() {
        $name = $this->input->post("txt_emp_name");
        $query = "select EmpNo from tbl_empmaster where Emp_Full_Name ='$name' ";
        $info = $this->db->query($query);
        return $info;
    }

    public function get_bank_info() {
        $cmb_bank_id = $this->input->post("cmb_bank");
        $query = "select distinct Acc_no from tbl_accounts where id ='$cmb_bank_id' ";
        $bank_info = $this->db->query($query);
        return $bank_info;
    }

    public function get_chqno_info() {
        $cmb_acc_id = $this->input->post("cmb_acc_no");
        $query = "select distinct lc_no from tbl_cheque_no where id ='$cmb_acc_id' ";
        $bank_info = $this->db->query($query);
        return $bank_info;
    }

}

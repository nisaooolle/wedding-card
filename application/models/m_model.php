<?php
class M_model extends CI_Model
{
    function get_data($table)
    {
        return $this->db->get($table);
    }
    function getWhere($table, $data)
    {
        return $this->db->get_where($table, $data);
    }
    public function register($data)
    {
        $this->db->insert('akun', $data);
    }
    public function get_by_id($table, $id_colomn, $id)
    {
        $data = $this->db->where($id_colomn, $id)->get($table);
        return $data;
    }
    public function ubah_data($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
    public function tambah_data($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    public function delete($table, $field, $id)
    {
        $data = $this->db->delete($table, array($field => $id));
        return $data;
    }
    public function EmailSudahAda($email)
    {
        $this->db->where('email', $email);    // Menggunakan CodeIgniter Query Builder, kita menentukan kondisi pencarian berdasarkan kolom 'email'.
        $query = $this->db->get('akun');    // Melakukan query ke tabel 'user' dengan kondisi di atas.
        return $query->num_rows() > 0; //Memeriksa jumlah baris hasil query Jika jumlah baris (rows) lebih dari 0, berarti email sudah ada
    }
    public function usernameSudahAda($username)
    {
        $this->db->where('username', $username);    // Menggunakan CodeIgniter Query Builder, kita menentukan kondisi pencarian berdasarkan kolom 'username'.
        $query = $this->db->get('akun');    // Melakukan query ke tabel 'user' dengan kondisi di atas.
        return $query->num_rows() > 0; //Memeriksa jumlah baris hasil query Jika jumlah baris (rows) lebih dari 0, berarti username sudah ada
    }

}
?>
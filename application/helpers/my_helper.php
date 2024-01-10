<?php 
function tampil_karyawan($id) {
    $ci = &get_instance();
    $ci->load->database();
    $result = $ci->db->where('id_karyawan', $id)->get('absensi');
    foreach ($result->result() as $key) {
        $stmt = $key->nama_depan . ' ' . $key->nama_belakang;
        return $stmt;
    }
}
?>
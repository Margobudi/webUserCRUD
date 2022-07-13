//user ada!
if ($register) {
//user aktif!
if ($register['is_active'] == 1) {
} else {
$this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">
    Akun Belum Di Verifikasi!
</div>');
}
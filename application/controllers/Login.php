<?php

class Login extends CI_Controller
{

  public function __contruct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index()
  {
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
      'required' => 'Tidak Boleh Kosong!'
    ]);
    $this->form_validation->set_rules('password', 'Password', 'trim|required', [
      'required' => 'Tidak Boleh Kosong!'
    ]);
    if ($this->form_validation->run() == false) {
      $data['title'] = 'Page|login';
      $this->load->view('auth/templet/header', $data);
      $this->load->view('auth/login');
      $this->load->view('auth/templet/footer');
    } else {
      //validasi success
      $this->_login();
    }
  }

  private function _login()
  {
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    $register = $this->db->get_where('register', ['email' => $email])->row_array();
    //Jika usernya ada
    if ($register) {
      //Jika user aktif
      echo 'success';
      if ($register['is_active'] == 1) {
        # cekk passwooorrdddd!!!!!
        if (password_verify($password, $register['password'])) {
          $data = [
            'email' => $register['email'],
            'role_id' => $register['role_id'],
          ];
          $this->session->set_userdata($data);
          redirect('User');
        } else {
          $this->session->set_flashdata(
            'massege',
            '<div class="alert alert-danger" role="alert">
                Password Anda Salah!
              </div>'
          );
          redirect('login');
        }
      } else {
        $this->session->set_flashdata(
          'massege',
          '<div class="alert alert-danger" role="alert">
              Email Anda Belum Di Verifikasi!
            </div>'
        );
        redirect('login');
      }
      //
    } else {
      $this->session->set_flashdata(
        'massege',
        '<div class="alert alert-danger" role="alert">
          Email Anda Belum Terdaftar!
        </div>'
      );
      redirect('login');
    }
  }

  public function register()
  {
    $this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[register.nama]', [
      'is_unique' => 'Nama Sudah Ada!'
    ]);
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[register.email]', [
      'is_unique' => 'Email Sudah Terdaftar!'
    ]);
    $this->form_validation->set_rules('password1', 'Password', 'required');
    $this->form_validation->set_rules('password2', 'Password', 'trim|required|matches[password1]', [
      'matches' => 'Pasword Harus Sama!'
    ]);

    if ($this->form_validation->run() == false) {
      $data['title'] = 'Page|Register';
      $this->load->view('auth/templet/header', $data);
      $this->load->view('auth/register');
      $this->load->view('auth/templet/footer');
    } else {
      $data = [
        'nama' => htmlspecialchars($this->input->post('name', true)),
        'email' => htmlspecialchars($this->input->post('email', true)),
        'image' => 'deaufult.jpg',
        'password' => password_hash(
          $this->input->post('password1'),
          PASSWORD_DEFAULT
        ),
        'role_id' => 2,
        'is_active' => 1,
        'data_created' => time()
      ];
      $this->db->insert('register', $data);

      $this->session->set_flashdata(
        'massege',
        '<div class="alert alert-success" role="alert">
          Akun Telah Terdaftar Silahkan Login!
        </div>'
      );
      redirect('login');
    }
  }
}

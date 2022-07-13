<?php
class Valid extends CI_Controller
{
    public function add()
    {
        //Validation Rules
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]');

        $data['groups'] = $this->User_model->get_groups();

        if ($this->form_validation->run() == FALSE) {
            //Views
            $data['main_content'] = 'admin/users/add';
            $this->load->view('coba', $data);
        } else {
            //Create Data Array
            $data = array(
                'first_name'    => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'username'      => $this->input->post('username'),
                'password'      => md5($this->input->post('password')),
                'group_id'      => $this->input->post('group'),
                'email'         => $this->input->post('email')
            );

            //Table Insert
            $this->User_model->insert($data);

            //Create Message
            $this->session->set_flashdata('user_saved', 'User has been saved');

            //Redirect to pages
            redirect('login');
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{


	public function index()
	{

		//  echo password_hash('admin',PASSWORD_DEFAULT);

		$this->load->view('admin/login');
	}


	public function authenticate()
	{


		$this->form_validation->set_rules('username', 'Usernaame', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == true) {
			// success
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$admin = $this->Admin_model->getByUsername($username);

			// check admin crendtials
			if (!empty($admin)) {

				if (password_verify($password, $admin['password']) == true) {
					// stores admin data
					$adminArray['admin_id'] = $admin['id'];
					$adminArray['username'] = $admin['username'];
					$this->session->set_userdata('admin', $adminArray);
					redirect(base_url() . 'admin/Home/index');

				} else {
					// incorrect password
					$this->session->set_flashdata('msg', 'Either username or password is incorrect');
					redirect(base_url() . 'admin/Login/index');
				}
			} else {
				$this->session->set_flashdata('msg', 'Either username or password is incorrect');
				redirect(base_url() . 'admin/Login/index');
			}
		} else {
			// form errors
			$this->load->view('admin/login');
		}
	}

	public function logout(){
		$this->session->unset_userdata('admin');
		redirect(base_url() . 'admin/Login/index','refresh');
	}


}

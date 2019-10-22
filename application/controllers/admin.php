<?php
defined('BASEPATH') or exit('No direct script access allowed');

class admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index(){

        $data['title'] = 'Dasboard';
        $data['user'] = $this->db->get_where('user', array('email' =>
        $this->session->userdata('email')))->row_array();
        
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('admin/index',$data);
        $this->load->view('templates/footer');
    }

    public function role(){

        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', array('email' =>
        $this->session->userdata('email')))->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();
        
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('admin/role',$data);
        $this->load->view('templates/footer');
    }    

    public function roleAccess($role_id){

        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('user', array('email' =>
        $this->session->userdata('email')))->row_array();

        $data['role'] = $this->db->get_where('user_role',
        array('id' => $role_id))->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();
        
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('admin/role-access',$data);
        $this->load->view('templates/footer');
    }    

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = array(
            'role_id' => $role_id,
            'menu_id' => $menu_id
        );

        $result = $this->db->get_Where('user_access_menu', $data);

        if($result->num_rows() < 1){
            $this->db->insert('user_access_menu', $data);
        }else{
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', 
        '<div class="alert alert-success" 
        role="alert"> Access Changed!</div>');
    }

    //editRole
    public function editRole()
    {
        $this->form_validation->set_rules('role', 'Role', 'required|trim');
        if($this->form_validation->run() == false){
            $this->session->set_flashdata('message', '<div class="alert alert-danger" 
            role="alert">Data gagal diedit!</div>'); 
            redirect('admin/role');
        }else{
            $this->db->set('role', $this->input->post('role'));
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('user_role');
            $this->session->set_flashdata('message', '<div class="alert alert-success" 
            role="alert">Data berhasil diedit!</div>'); 
            redirect('admin/role');
        }
    }

    public function delete($id)
    {
        $this->db->delete('user_role', array('id' => $id));
        $this->session->set_flashdata('message', '<div class="alert alert-success" 
                role="alert">Data berhasil hapus!</div>'); 
        redirect('admin/role');
    }
    
}
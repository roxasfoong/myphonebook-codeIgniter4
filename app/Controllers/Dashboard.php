<?php

namespace App\Controllers;
use App\Models\Contact_model;
use CodeIgniter\Controller;


class Dashboard extends BaseController
{
        protected $contact_model;
        protected $session;
        protected $validation;
        protected $request;

        public function __construct()
        {
                $this->contact_model = new Contact_model();
                $this->validation = \Config\Services::validation();
                $this->session = \Config\Services::session();
                $this->request = \Config\Services::request();
        }

        public function index()
        {

                if (!session()->has('user_id')) {
                        $this->session->setFlashdata('error', 'Session Expired: Please Login Again.');
                        return redirect()->to('login')->withInput();
                }
                $data['title'] = 'Dashboard Page';
                $data['contacts_view'] = 'contacts_view';
                $data['header_view'] = 'header_view';
                $data['recently_added_view'] = 'recently_added_view';
                //$data['utility_view'] = 'utility_view';
                if ($this->contact_model->load_database()) {
                        $data['recently_added_data'] = $this->contact_model->get_recently_added_contact(session('user_id'));
                        $data['total_number_of_contact'] = $this->contact_model->get_all_contacts(session('user_id'));
                        $data['all_contact_data'] = $this->contact_model->get_all_contacts_with_offset(session('user_id'),1);
                        $data['total_number_of_page'] = $this->contact_model->get_total_number_of_page(session('user_id'));
                        $data['pagination_data'] = $this->contact_model->generatePagination($this->contact_model->count_max_page(session('user_id')),1);
                        if (!$data['recently_added_data']) {
                                $data['recently_added_data'] = array(
                                        'name' => 1,
                                        'error' => 'You Have Not Added Any Contact',
                                );
                        }

                        if ($data['all_contact_data'] == null ) {

                                $data['all_contact_data'] = array(
                                        'name' => 1,
                                        'error' => 'You Have Not Added Any Contact',
                                );

                        } elseif (!is_array($data['all_contact_data'])) {

                                $data['all_contact_data'] = array(
                                        'name' => 1,
                                        'error' => 'Something is wrong...',
                                );
                        }
                } else {
                        $data['recently_added_data'] = array(
                                'name' => 1,
                                'error' => 'Unable to Communicate with Database...',
                        );
                }

                return view('/layouts/dashboard_layout', $data);
        }
}

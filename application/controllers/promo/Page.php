<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $empId = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function menu($menu = 'dashboard', $file = 'dashboard', $emp_id = '')
    {

        if (!file_exists(APPPATH . "views/promo/page/$menu/$file.php")) {
            show_404();
        }

        if ($menu == 'dashboard' && $file == 'dashboard') {
            $i      = 0;
            $stores = '';
            $num    = '';
            $md     = date('m-d');
            $new    = $this->promo_model->newPromo();
            $total  = $this->promo_model->activeTotal();
            $eoc    = $this->promo_model->eocToday();
            $bUs    = $this->promo_model->locate_promo_bu('asc');
            $failed = $this->promo_model->failedEpas();
            $bday   = $this->promo_model->birthdayToday($md);
            $color  = [];
            function random_color_part()
            {
                return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            }

            function random_color()
            {
                return random_color_part() . random_color_part() . random_color_part();
            }
            foreach ($bUs as $value) {
                $i++;
                $stores = ($i == 1) ? "'" . $value['bunit_name'] . "'" : $stores . ", '" . $value['bunit_name'] . "'";
                $count  = $this->promo_model->active_promoStore($value['bunit_field']);
                $num    = ($i == 1) ? $count['num'] : $num . ', ' . $count['num'];
                $color[] = random_color();
            }

            $data['color']      = $color;
            $data['stores']     = $stores;
            $data['num']        = $num;
            $data['newPromo']   = $new;
            $data['failedEpas'] = $failed;
            $data['bday']       = $bday;
            $data['total']      = $total;
            $data['eoc']        = $eoc;
        } else if ($menu == 'contract' && $file == 'renewContract') {
            $renewID = $this->session->userdata('renewID');
            if (isset($renewID) && !empty($renewID)) {
                $this->session->unset_userdata('renewID');
            } else {
                header('Location: http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/hrmsv2/promo/page/contract/renewal');
                exit;
            }
        }

        $data['emp_id'] = $emp_id;
        $user_id        = $this->session->userdata('emp_id');
        $data['user']   = $this->promo_model->user_info($user_id);
        $data['menu']   = $menu;
        $data['file']   = $file;
        $this->load->view('promo/template/header', $data);
        $this->load->view('promo/template/menu', $data);
        $this->load->view('promo/page/' . $menu . '/' . $file, $data);
        $this->load->view('promo/template/script');
        $this->load->view('promo/page/' . $menu . '/' . $menu . '_js', $data);
    }

    public function searchPromo()
    {
        $searchValue = $this->input->post('str', TRUE);
        $this->session->set_userdata('searchPromo', $searchValue);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
    }
}

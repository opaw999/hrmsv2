<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"> Promo</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <i class="lni lni-users"></i>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Search Result</li>

                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
                    <?php
                    $searchPromo = $this->session->userdata('searchPromo');
                    if (isset($searchPromo)) {

                        $list = $this->promo_model->searchPromo($searchPromo);

                        if (count($list) > 0) {

                            foreach ($list as $key => $value) {
                                if ($value['current_status'] == 'Active') {

                                    $bgColor = 'bg-success';
                                } else if ($value['current_status'] == 'blacklisted') {

                                    $bgColor = 'bg-danger';
                                } else {

                                    $bgColor = 'bg-warning';
                                }

                                if ($value['photo'] != '') {

                                    $url        = "http://$_SERVER[SERVER_ADDR]:$_SERVER[SERVER_PORT]/hrms/promoV2/{$value['photo']}";
                                    $response   = @file_get_contents($url);
                                    $photo      = ($response !== false) ? $value['photo'] : 'assets/images/promologo.png';
                                } else {

                                    $photo = 'assets/images/promologo.png';
                                }

                                $photoLink = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promoV2/' . $photo;

                                echo    '<a href="' . base_url('promo/page/promo/profile/' . $value['emp_id']) . '">
                                            <div class="col">
                                                <div class="card radius-10 ' . $bgColor . '">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <p class="mb-0 text-white"><b>' . $value['emp_id'] . '</b></p>
                                                                <p class="mb-0 text-white"><b>' . ucwords(strtolower($value['name'])) . '</b></p>                                                           
                                                                <p class="mb-0 font-13 text-white">' . $value['promo_company'] . '</p>
                                                                <p class="mb-0 font-13 text-white">' . $value['position'] . '</p>
                                                                <p class="mb-0 font-13 text-white">' . $value['current_status'] . ' (' . $value['sub_status'] . ')</p>
                                                            </div>
                                                            <div class="widgets-icons-promo bg-white text-success ms-auto">
                                                                <img class="" src="' . $photoLink . '" alt="" style="width: 100%; height: 100%;border-radius:5px;" class="rounded-circle p-1 bg-primary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>';
                            }
                        } else {
                            echo '<h5 class="mb-0">No Records Found!</h5>';
                        }
                    }
                    if (isset($searchPromo) && !empty($searchPromo)) {
                        $this->session->unset_userdata('searchPromo');
                    } else {
                        header('Location: http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/hrmsv2/promo');
                        exit;
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="<?= base_url('assets/promo_assets/images/promologo.png') ?>" class="logo-icon" alt="logo icon" />
            <!-- <i class='bx bx-purchase-tag'></i> -->
        </div>
        <div>
            <a href="<?= base_url('promo'); ?>">
                <h3 class="logo-text"><b>Promo</b> V2</h3>
            </a>
        </div>
        <div class="toggle-icon ms-auto">
            <i class='bx bx-menu'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu" style="margin-top: 40px;">
        <li class="menu-label" style="margin-left: 15px;">Main Navigation </li>
        <li>
            <a href="<?= base_url('promo'); ?>">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard </div>
            </a>
        </li>
        <?php
        $main_menu = $this->promo_model->promo_menu();

        foreach ($main_menu as $key => $menuVal) {
            echo    '<li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="' . $menuVal['icon'] . '"></i></div>
                            <div class="menu-title">' . $menuVal['menu_name'] . ' </div>
                        </a>';

            $sub_menu = $this->promo_model->promo_submenu($menuVal['menu_id']);

            echo        '<ul>';
            foreach ($sub_menu as $key => $value) {

                echo        '<li>
                                <a href="' . base_url('promo/page/' . $menuVal['routeFolder']) . '/' . $value['routeFile'] . '">
                                    <i class="bx bx-radio-circle"></i>' . $value['submenu_name'] . '
                                </a>
                            </li>';
            }
            echo        '</ul>
                    </li>';
        } ?>

        <li>
            <a href="<?= base_url('promo/page/dashboard/aboutUs'); ?>">
                <div class="parent-icon"><i class="lni lni-information"></i>
                </div>
                <div class="menu-title">About Us </div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('assets/promo_assets/images/userguide.pdf'); ?>" target="_blank">
                <div class="parent-icon"><i class="bx bx-map-alt"></i>
                </div>
                <div class="menu-title">User Guide </div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
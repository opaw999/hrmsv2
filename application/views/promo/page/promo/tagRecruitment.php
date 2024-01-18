<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    ul.list-group a:hover {
        background-color: #008cff;
        color: #fff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .tagtoRecruitment {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    ul.list-group a:hover {
        color: #fff;
    }

    html.dark-theme a.list-group-item:hover {
        background-color: #008cff;
    }

    html.light-theme a.list-group-item:hover {
        background-color: #008cff;
    }

    .tagtoRecruitmentForm {
        display: none;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Tag to Recruitment</h5>
                </div>
                <hr>
                <form id="tagtoRecruitmentForm" autocomplete="off">
                    <input type="hidden" name="process" value="tagtoRecruitment">
                    <div class="row">
                        <div class="col-md-6 mb-2 searchContainer">
                            <label class="form-label text-secondary">Search Applicant</label>
                            <div class="input-group font-22">
                                <i class="input-group-text fadeIn animated lni lni-users"></i>
                                <input type="text" class="form-control" name="tagtoRecruitment" placeholder=" Search here..." autocomplete="off" onkeyup="searhPromo(this.value, this.name)">
                            </div>
                            <div class="dropdown-list tagtoRecruitment"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-secondary">Status</label>
                            <input type="text" class="form-control" name="status" disabled>
                            <input type="hidden" class="form-control" name="current_status">
                            <input type="hidden" class="form-control" name="emp_id">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-secondary">Recruitment Process</label>
                            <select name="recProcess" class="form-select" disabled>
                                <option value="">Select Process</option>
                                <option value="initial_completion"> Initial Completion </option>
                                <option value="exam"> For Examination </option>
                                <option value="interview"> For Interview </option>
                                <option value="training"> For Training </option>
                                <option value="final_completion"> For Final Completion </option>
                                <option value="orientation"> For Orientation </option>
                                <option value="hiring"> For Hiring </option>
                                <option value="deployment"> For Deployment </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-secondary">Position</label>
                            <select name="position" class="form-select select2" data-placeholder="Select Position" disabled>
                                <option value="">Select Position</option>
                                <?php
                                $c = "tag = 'active' OR tag = ''";
                                $position = $this->promo_model->selectAll_tcA('positions', $c);
                                foreach ($position as $value) {
                                    echo '<option value="' . $value['position'] . '">' . $value['position'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <div class="pull-right"> -->
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <!-- </div> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
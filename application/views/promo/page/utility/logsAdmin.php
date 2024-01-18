<style>
    #logsAdmin {

        font-size: 12px;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap mt-0">
                    <h5 class="card-title text-primary">Logs (Admin)</h5>
                    <span>
                        <div class="input-group input-group-sm">
                            <input type="text" name="day" class="form-control form-control-sm datepicker" value="<?= date('Y-m-d') ?>">
                            <button class="btn btn-primary" type="button" onclick=" logsAdmin()">Filter Date</button>
                        </div>
                    </span>
                </div>
                <hr style="margin-top: 8px;">
                <div class="row">
                    <div class="row logsAdmin_list"></div>
                    <div class="col logsAdmin">
                        <table id="logsAdmin" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>LogNo</th>
                                    <th>Activity</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Username</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
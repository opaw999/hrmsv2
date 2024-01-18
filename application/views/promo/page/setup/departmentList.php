<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Department List</h5>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary px-2" onclick="addForm()">Add Department</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="departmentListTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Business Unit</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Action</th>
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


<div class="modal fade" id="addForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Department for Store</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="process" value="add">
                <div class="form-group mb-2">
                    <label>Business Unit</label>
                    <select name="bunit_id" class="form-select">
                        <option value="">Select Business Unit</option>
                        <?php
                        $c = array('status' => 'active', 'hrd_location' => 'asc');
                        $bus = $this->promo_model->selectAll_tcA('locate_promo_business_unit', $c);
                        foreach ($bus as $bu) {
                            echo '<option value="' . $bu['bunit_id'] . '">' . $bu['bunit_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="dept_name" class="form-select">
                        <option value="">Select Department</option>
                        <?php
                        $c = array('dept_name !=' => '');
                        $dept = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $c);
                        foreach ($dept as $row) {
                            echo '<option value="' . $row['dept_name'] . '">' . $row['dept_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveDepartment()">Submit</button>
            </div>
        </div>
    </div>
</div>
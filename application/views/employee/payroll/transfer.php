<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">
                <div class="row">
                    <div class="col-lg-11">
                        <h6 class="card-body-title" style='font-size:20px'> JOB TRANSFER </h6>
                    </div>
                    <div class="col-lg-1 text-right">
                        <form>
                        <button
                            type="button"
                            class="btn btn-success active"
                            modal-size=""
                            modal-route=""
                            modal-form="employee/payroll/formfilter"  
                            modal-redirect= "employee/payroll/filter_transfer/"                             
                            modal-skeleton="0"
                            modal-id=""
                            modal-atype="POST"
                            modal-title="Filter Employees"
                            onclick="modal(event)">
                            Filter
                        </button>
                        <br> 
                        </form> 
                     </div>
                </div> 
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-5p">  EMPID </th>
                                <th class="wd-20p">  NAME </th>
                                <th class="wd-10p">  EFFECTIVE </th>
                                <th class="wd-25p"> FROM </th>
                                <th class="wd-25p"> TO </th>
                                <th class="wd-10p"> STATUS </th>
                                <th class="wd-5p"> ACTION </th>
                            </tr>
                        </thead> 
                    </table>
                </div><!-- table-wrapper -->
            </div><!-- card -->          
        </div>
    </div>
</div>
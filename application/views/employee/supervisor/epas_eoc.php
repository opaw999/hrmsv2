<!-- 
Consider All Emptype in the Resignations
AE, NESCO, Promo
-->
<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">

            <div class="pd-5 mg-t-5 bg-gray-800">
                <ul class="nav nav-white-800 flex-column flex-md-row" role="tablist">
                    <li class="nav-item" id="tab-menu_ThisMonth" data-api="thismonth"><a class="nav-link active" data-toggle="tab"  href="#" role="tab"> <i class="icon ion-arrow-right-a"></i> EOC FOR THIS MONTH </a></li>
                    <li class="nav-item" id="tab-menu_NextMonth" data-api="nextmonth"><a class="nav-link" data-toggle="tab"  href="#" role="tab"> <i class="icon ion-arrow-right-a"></i> EOC FOR NEXT MONTH </a></li>
                    <li class="nav-item" id="tab-menu_OverDue"   data-api="overdue">  <a class="nav-link" data-toggle="tab"  href="#" role="tab"> <i class="icon ion-arrow-right-a"></i> OVERDUE EOC </a></li>
                </ul>
            </div>

            <div class="card pd-20 pd-sm-30">    
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-5p"> EMPID </th>
                                <th class="wd-20p"> NAME </th>
                                <th class="wd-20p"> POSITION</th>
                                <th class="wd-15p"> EMPTYPE </th>
                                <th class="wd-10p"> STATUS </th>
                                <th class="wd-20p"> DEPARTMENT </th>
                                <th class="wd-15p"> EOCDATE </th>
                                <th class="wd-15p"> RATING</th>
                                <th class="wd-10p"> ACTION </th>
                            </tr>
                        </thead> 
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>
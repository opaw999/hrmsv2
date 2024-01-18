<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">               
                <h6 class="card-body-title" style='font-size:20px'> APPLICANTS FOR INTERVIEW </h6>                    
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-5p"> APPID </th>
                                <th class="wd-20p"> NAME </th>
                                <th class="wd-15p"> APPLYING FOR </th>
                                <th class="wd-15p"> DATE APPLIED </th>
                                <th class="wd-15p"> INTERVIEW GRADE </th>
                                <th class="wd-15p"> ACTION </th>
                            </tr>
                        </thead> 
                        <tbody>
                            <?php  
                            if(@$interviews){
                                foreach($interviews as $row){
                                    //$numrate =  application_interview_totalrates
                                    echo "<tr>
                                        <td> $row[interviewee_id] </td>
                                        <td> $row[name] </td>
                                        <td> $row[position] </td>
                                        <td> $row[date_applied] </td>
                                        <td> </td>
                                        <td>                 
                                            <a href='".base_url()."supervisor/interview/grade/$row[interviewee_id]' title='Click button to Start Interview' class='btn btn-primary btn-sm'> Start Interview </a>
                                        </td>
                                    </tr>";                                    
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
            </div><!-- card -->          
        </div>
    </div>
</div>
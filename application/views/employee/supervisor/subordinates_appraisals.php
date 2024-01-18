
    <h6> EMPLOYEE NAME: <?= $name;?> </h6>
    <table class="table table-bordered table-striped"> 
        <thead>
            <tr> 
                <th> NO </th>
                <th> POSITION  </th>
                <th> STARTDATE  </th>
                <th> EOC DATE  </th>
                <th> RATING </th>
                <th> DESCRIPTION </th>
                <th> EVALUATED BY </th>
                <th> RATING DATE </th>
                <th> ACTION </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ctr = 0;
            foreach($emphistory as $row):
            
                $ctr++;
                $result   = @$this->dbmodel->get_row(
                    'appraisal_details',
                    '* , DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                    array( 'field1' => 'emp_id', 'field2' => 'record_no' ),
                    array( $row['emp_id'], $row['record_no'] )
                );

                if($result){
                    $rater  = @$this->dbmodel
                        ->get_row(
                            'employee3',
                            'name',
                            array( 'field1' => 'emp_id' ),
                            array( $result->rater )
                        )->name;
                }else{
                    $rater = "";
                }

                echo "
                <tr>
                    <td> $ctr </td>
                    <td> $row[position] </td>
                    <td> $row[startdate] </td>
                    <td> $row[eocdate] </td>
                    <td> ".@$result->numrate." </td>
                    <td> ".@$result->descrate." </td>
                    <td> ".@$rater." </td>
                    <td> ".@$result->ratingdate." </td>
                    <td>";
                        if($result){
                            echo "
                            <button
                                type='button'
                                class='btn btn-success btn-sm active'
                                modal-size='modal-sm'
                                modal-form='supervisor/subordinates/showanswers/".$result->details_id."' 
                                modal-skeleton='0'
                                modal-id='appraisalModal'
                                modal-title='Appraisal Details'
                                modal-button='false'
                                onclick='modals(event)'>
                                <i class='icon ion-search'></i> Show Details
                            </button>";
                        } echo "
                    </td>
                </tr>";  
            endforeach; ?>
        </tbody>
    </table>
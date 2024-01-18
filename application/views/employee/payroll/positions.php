<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">
            <h6 class="card-body-title" style='font-size:20px'> POSITION LEVELING </h6>
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered">
                        <thead>
                            <tr>
                                <th class="wd-5p"> LVLNO </th>
                                <th class="wd-5p"> LEVEL </th>
                                <th class="wd-40p"> POSITION </th>
                                <th class="wd-40p"> CATEGORY </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($result as $row):
                                echo "
                                <tr>
                                    <td> $row[lvlno] </td>
                                    <td> $row[level] </td>
                                    <td> $row[position_title] </td>
                                    <td> $row[category] </td>
                                </tr>";
                            endforeach; ?>           
                        </tbody>
                    </table>
                </div>
            </div>   
        </div>
    </div>
</div>
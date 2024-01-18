<?php

    function Head()
    {
        $cc = @$_GET['cc'];//$_SESSION['cc'];
        $bc = @$_GET['bc'];//$_SESSION['bc'];
        if($cc == "02" && $bc == "03") { //kung icm ang mag send
            // Logo
            //$this->Image('../images/icons/icmlogo.png',65,4,16); //center
            $this->Image('../images/icons/ICM-LOGO.png', 100, 15, 32);
        }

    }

$this->ppdf = new TCPDF();
// $this->ppdf->AliasNbPages();
$this->ppdf->setPrintHeader(false);
$this->ppdf->AddPage("P", "A5");
$this->ppdf->SetFont('Times', '', 12);
$this->ppdf->SetTitle('HRMS - NESCO TO AE');

$transno = $this->input->get('transno');
$rec 	= $this->input->get('rec');
$empId 	=$this->input->get('empid');
// var_dump($empId);
$date 	= $this->input->get('dates');

$result = $this->dbmodel
       ->get_row(
           'employee3',
           'name',
           array(
               'field1' => 'emp_id'
           ),
           array(
                $empId
           )
 );



$re 	= "Job Transfer";
if($date != '') {
    $date 	= date('F d, Y', strtotime($date));
} else {
    $date 	= date('F d, Y');
}

$query = $this->db->query("SELECT * FROM employee_transfer_details WHERE record_no='$rec' and emp_id='$empId' and transfer_no='$transno' ");
foreach ($query->result_array() as $r) {
   

    $to 	=  $result->name;//not necessarily promo, i just used the function to get the suffix 8/24/17
    $from 	= $r['assignedfrom'];
    $effective 	= date('F d, Y',strtotime($r['effectiveon']));
    $supervision = $r['supervision'];
    $pos_new 	= $r['position'];
    $oldpos		= $r['old_position'];
    $old_loc	= $r['old_location'];
    $new_loc	= $r['new_location'];
    $newemptype = $r['transfer_to_emptype'];

    //get cc
    $cc = explode('$', $r['carbon_copy']);
    $cc1     = @$cc[0];
    $cc2     = @$cc[1];
    $cc3     = @$cc[2];
    $cc4     = @$cc[3];
    $cc5     = @$cc[4];
    $cc6     = @$cc[5];
}

$emptype = $this->dbmodel->get_field('emp_type', 'employee3', "emp_id = '$empId' ")['emp_type'];

$ol = explode('-', $old_loc);
$nl = explode('-', $new_loc);

$olcc = @$ol[0];
$olbc = @$ol[1];
$oldc = @$ol[2];
$olsc = @$ol[3];
$olssc = @$ol[4];
$oluc = @$ol[5];
// print_r($ol);
//from
$fbunit 	= $this->dbmodel->get_field('acroname', 'locate_business_unit', "company_code = '$olcc' AND bunit_code = '$olbc'")['acroname']; //getting the business unit name\
 $fdept  	= $this->dbmodel->get_field('acroname', 'locate_department', "company_code = '$olcc' AND bunit_code = '$olbc' AND dept_code = '$oldc' ")['acroname'];
 $fsection	= $this->dbmodel->get_field('section_name', 'locate_section', "company_code = '$olcc' AND bunit_code = '$olbc' AND dept_code = '$oldc' AND section_code = '$olsc'")['section_name'];
$fsubsec 	= $this->dbmodel->get_field('sub_section_name', 'locate_sub_section', "company_code = '$olcc' AND bunit_code = '$olbc' AND dept_code = '$oldc' AND section_code = '$olsc' and sub_section_code = '$olssc'")['sub_section_name'];

if($fsubsec) {
    $oldlocation = $fbunit . " - " . $fdept . " - " . $fsection . " - " . $fsubsec;
} elseif($fsection) {
    $oldlocation = $fbunit . " - " . $fdept . " - " . $fsection;
} elseif($fdept) {
    $oldlocation = $fbunit . " - " . $fdept;
} elseif($fbunit) {
    $oldlocation = $fbunit;
}

$nlcc = @$nl[0];
$nlbc = @$nl[1];
$nldc = @$nl[2];
$nlsc = @$nl[3];
$nlssc = @$nl[4];
$nluc = @$nl[5];

//to
$tcom 	= $this->dbmodel->get_field('company', 'locate_company', "company_code = '$nlcc'")['company']; //
$tbunit 	= $this->dbmodel->get_field('acroname', 'locate_business_unit', "company_code = '$nlcc' AND bunit_code = '$nlbc'")['acroname']; //getting the business unit name\
$tdept  	= $this->dbmodel->get_field('acroname', 'locate_department', "company_code = '$nlcc' AND bunit_code = '$nlbc' AND dept_code = '$nldc' ")['acroname'];
$tsection	= $this->dbmodel->get_field('section_name', 'locate_section', "company_code = '$nlcc' AND bunit_code = '$nlbc' AND dept_code = '$nldc' AND section_code = '$nlsc'")['section_name'];
$tsubsec 	= $this->dbmodel->get_field('sub_section_name', 'locate_sub_section', "company_code = '$nlcc' AND bunit_code = '$nlbc' AND dept_code = '$nldc' AND section_code = '$nlsc' and sub_section_code = '$nlssc'")['sub_section_name'];

if($tsubsec) {
    $newlocation = $tcom . " - " . $tbunit . " - " . $tdept . " - " . $tsection . " - " . $tsubsec;
} elseif($tsection) {
    $newlocation = $tcom . " - " . $tbunit . " - " . $tdept . " - " . $tsection;
} elseif($tdept) {
    $newlocation = $tcom . " - " . $tbunit . " - " . $tdept;
} elseif($tbunit) {
    $newlocation = $tcom . " - " . $tbunit;
} elseif($tcom) {
    $newlocation = $tcom;
}
// var_dump($newlocation);

$randomno = $this->custom->random();

// var_dump($randomno); 

$to1  = htmlspecialchars($to . " ", ENT_QUOTES);
$this->ppdf->SetFont('Times', '', 11);
if($emptype == 'NESCO Probationary' || $emptype == 'NESCO' || $emptype == 'NESCO Contractual' || $emptype == 'NESCO-PTP' || $emptype == 'NESCO-PTA' || $emptype == 'NESCO Regular' || $emptype == 'NESCO Partimer' || $emptype == 'NESCO Regular Partimer') {
    $this->ppdf->Ln(20);
} else {
    $this->ppdf->Ln(13);
}

$this->ppdf->Cell(7);
$this->ppdf->Cell(16, 5, 'To');
$this->ppdf->Cell(2, 5, ": ");
$this->ppdf->Cell(72, 5, utf8_decode(strtoupper($to1)));
$this->ppdf->Ln();

$this->ppdf->Cell(7);
$this->ppdf->Cell(16, 5, 'From');
$this->ppdf->Cell(2, 5, ": ");
$this->ppdf->Cell(72, 5, $from);
$this->ppdf->Ln();

$this->ppdf->Cell(7);
$this->ppdf->Cell(16, 5, 'Date');
$this->ppdf->Cell(2, 5, ": ");
$this->ppdf->Cell(72, 5, $date);
$this->ppdf->Ln();

$this->ppdf->Cell(7);
$this->ppdf->Cell(153, 5, '_____________________________________________________');
$this->ppdf->Ln(8);
$this->ppdf->Cell(7);
$this->ppdf->SetX(17);

$this->ppdf->MultiCell(110, 5, "Please be informed that your employment with NESCO MULTI-PURPOSE COOPERATIVE will end effective $effective and of the same date you will be directly employed with $newlocation as $pos_new with a $newemptype status of employment. Further, your length of service will be continued from the date you started working at $tcom - $tbunit / or NESCO MULTI-PURPOSE COOPERATIVE.                  ");

$this->ppdf->Ln(4);
$this->ppdf->Cell(6);
$this->ppdf->SetX(17);
$this->ppdf->MultiCell(110, 5, 'Being part of our company, you are therefore responsible to the success of our business operations and we look forward for your continued support to all business endeavors.                     ');

$this->ppdf->Ln(3);
$this->ppdf->Cell(7);
$this->ppdf->Cell(16, 5, 'Good luck and Congratulations!');
$this->ppdf->Ln(15);
$this->ppdf->Cell(7);

//if($emptype == 'NESCO Probationary' || $emptype == 'NESCO'  || $emptype == 'NESCO Contractual' || $emptype == 'NESCO-PTP' || $emptype == 'NESCO-PTA' || $emptype == 'NESCO Regular' || $emptype == 'NESCO Partimer' || $emptype == 'NESCO Regular Partimer')
//{
//	$this->ppdf->Cell(16 ,7, 'MERCEDES NARCE');
//	$this->ppdf->Ln(5);
//	$this->ppdf->Cell(7);
//	$this->ppdf->Cell(16 ,7, 'NESCO MANAGER');
//}
//else{
$this->ppdf->Cell(16, 7, 'MARIA NORA A. PAHANG');
$this->ppdf->Ln(5);
$this->ppdf->Cell(7);
$this->ppdf->Cell(16, 7, 'Corporate HR Manager');
//	}


$this->ppdf->Ln(10);
$this->ppdf->Cell(7);
$this->ppdf->Cell(4, 5, 'Cc:');
$this->ppdf->Cell(5);
if($cc1 != "") {
    //$this->ppdf->Cell(16);
    $this->ppdf->Cell(20, 5, $cc1);
    $this->ppdf->Ln(5);
}
if($cc2 != "") {
    $this->ppdf->Cell(16);
    $this->ppdf->Cell(20, 5, $cc2);
    $this->ppdf->Ln(5);
}
if($cc3 != "") {
    //$cc3 = htmlspecialchars($cc3." ", ENT_QUOTES);
    $this->ppdf->Cell(16);
    $this->ppdf->Cell(20, 5, utf8_encode($cc3));// utf8_decode(strtoupper($cc3)));
    $this->ppdf->Ln(5);
}
if($cc4 != "") {
    //$cc4  = htmlspecialchars($cc4." ", ENT_QUOTES);
    $this->ppdf->Cell(16);
    $this->ppdf->Cell(20, 5, utf8_decode(ucwords(strtolower($cc4))));
    $this->ppdf->Ln(5);
}
if($cc5 != "") {
    //	$cc5  = htmlspecialchars($cc5." ", ENT_QUOTES);
    $this->ppdf->Cell(16);
    $this->ppdf->Cell(20, 5, utf8_decode(ucwords(strtolower($cc5))));
    $this->ppdf->Ln(5);
}
if($cc6 != "") {
    //$cc6  = htmlspecialchars($cc6." ", ENT_QUOTES);
    $this->ppdf->Cell(16);
    $this->ppdf->Cell(20, 5, utf8_decode(ucwords(strtolower($cc6))));
    //$this->ppdf->Ln(5);
}

//}
//********************************* for report logs	*********************************************//

$activity 		= "Generate Job Transfer Report for " . @$to;
$date 			= date("Y-m-d");
$time 			= date("H:i:s");
// $Sessionemp   =$this->session->userdata('emp_id');
// $username = $this->session->userdata('username');

$data = array(

    'activity'   =>  $activity,
    'date'            =>  $date ,
    'time'            =>  $time ,
    'user'         => $this->session->userdata('emp_id'),
    'username'     =>  $this->session->userdata('username')
);
$result = $this->dbmodel->add("logs", $data);

$filename = $empId . "_jobtransfer_" . date('mdy') . ".pdf";

/************************************************************************************************/
$path = "../document/jobtransfer/" . $filename;

$this->db->query("UPDATE employee_transfer_details set file = '$path' where record_no = '$rec' and emp_id = '$empId' and transfer_no = '$transno'");
/*$que = mysql_query("SELECT * FROM employee_transfer_details where record_no = '$rec' and emp_id = '$empid' and transfer_no = '$transno'  ORDER BY transfer_no desc limit 1  ");
$rrt = mysql_fetch_array($que);
$tno = $rrt['transfer_no'];*/

$this->ppdf->Output();
$this->ppdf->Output($path, 'F');
ob_clean();
///used when you back up the file just change the directory to the right one!
/*
?>

<script>
window.location = '../placement/preview_jobtrans.php?tno=<?php echo $tno;?>&header=yes';
</script>
*/
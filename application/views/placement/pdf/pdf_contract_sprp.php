<?php
$user	= $this->session->userdata('emp_id');
$usern	= $this->session->userdata('username');;
$date 	= date('Y-m-d');
$time 	= date('H:i:s');
		
// class PDF extends FPDF
// {
// 	function Header()
// 	{													
// 		$this->SetFont('Times','B',12);
// 		$this->Image('../images/icons/sprp.png',25,10,30,23);	
// 		$this->Ln(16);		
// 		$this->Cell(55);
// 		$this->Cell(120,5,'CONTRACT OF EMPLOYMENT FOR A DEFINITE PERIOD');		
// 		$this->Ln(10);					
// 		//$this->Ln();	
// 	}		
// }	
// Instanciation of inherited class
$pdf = new TCPDF();
$pdf->SetTopMargin(5);
$pdf->AliasNbPages();
$pdf->AddPage("P","Legal");
$pdf->SetFont('Times','',12);
$pdf->SetAutoPageBreak('on',5);
$pdf->SetTitle('CONTRACT');

//if reprint 
if(@$_GET['printtype'] == "Reprint"){ $printtype = "Reprint of";} else { $printtype = "";};

$rec = $_GET['rec'];
$query = $nq->selectTableWhere('employee3','record_no',$rec);

$ctc_or_sss = @$_GET['clear'];
if($ctc_or_sss == "SSS")
{	
	$ctc_sssno = @$_GET['ssstf'];
}
else
{
	$ctc_sssno = @$_GET['cleartf'];
}

$issuedon = @$_GET['issuedon'];
$issuedat = @$_GET['issuedat'];

//if reprint 
if(@$_GET['printtype'] == "Reprint" && @$_GET['contractno']!="")
{
	$printtype= "Reprint of";
	$randomno = "C".@$_GET['contractno'];
	$reprint  = "[".@$_GET['printtype']."]";
	
	$pr 	= mysql_query("SELECT * FROM reprint_logs where print_no = '".$randomno."' order by reprint_no desc");		
	$rr 	= mysql_fetch_array($pr);
	$times 	= $rr['reprint_times'];
	$notimes= ($times+1);		
	$rl 	= mysql_query("INSERT INTO reprint_logs VALUES('','Contract','$randomno','$notimes','$date','$time','$user','$usern') ");		
}
else{
	$printtype = "";
	$randomno = $nq->random()."-".date('mdy');
	$updatepermit = mysql_query("UPDATE employee3 set contract='$randomno' where record_no = '$rec' ");
}
/***************************************************************/

$code = $_GET['ccode'];
//get the contract header
$ch = mysql_query("SELECT * FROM `contract_header` where ccode_no = '$code' ");
while($rch = mysql_fetch_array($ch))
{
	$ccode 		= $rch['ccode_no'];
	$addresse 	= $rch['addresse'];
	$company 	= $rch['company'];
	$address  	= $rch['address'];
}
/***************************************************************/

//update the contract header to the employee_witness table
mysql_query("UPDATE employment_witness SET code = '@$ccode' where rec_no = '$rec' ");
/*****************************************************************************************************************************************/

date_default_timezone_set('Asia/Manila');
$cdate = $_GET['cdate'];
$d = explode('/',$cdate);
//$datetoday = date("Y-m-d H:i:s");

//$date = date("09/02/2014");
$day = date("jS",strtotime($cdate)); 

$month = date("m",strtotime($cdate)); // date($d[0]); //month
$year = date("Y",strtotime($cdate));//date($d[2]);	//year
$monthName = date("F", mktime(0, 0, 0, $month, 10));

if(@$_GET['w1'] != "" && @$_GET['w2'] !="" ){
	$witness1 = $_GET['w1'];
	$witness2 = $_GET['w2'];
	$witnesses = $nq->selectTableWhere('employment_witness','rec_no',$rec);
	if(mysql_num_rows($witnesses) > 0 ){
		$q = mysql_query("UPDATE employment_witness set witness1='$witness1', witness2 = '$witness2' where rec_no = '$rec'  ");		
	}else{		
		$q = mysql_query("INSERT INTO employment_witness ew_no,rec_no,witness1,witness2,contract_header_no,sss_ctc,sssno_ctcno,issuedat,issuedon,date_generated,generated_by
			VALUES (
				'',
				'".$rec."',
				'".@$witness1."',
				'".@$witness2."',
				'".$ccode."',
				'".$ctc_or_sss."',
				'".$ctc_sssno."',
				'".$issuedat."',
				'".$issuedon."',
				'".date('Y-m-d')."',
				'".$user."'
			) ");				
	}
}else{
	$witnesses = $nq->selectTableWhere('employment_witness','rec_no',$rec);
	while($row = mysql_fetch_array($witnesses)){ $witness1 = strtoupper($row['witness1']); $witness2 = strtoupper($row['witness2']);}
}

$date_g = date('Y-m-d');
/*updating the em,ployment witness table*/
$que  = mysql_query("Update employment_witness set
	contract_header_no = '$code',
	sss_ctc = '$ctc_or_sss',
	sssno_ctcno = '$ctc_sssno',
	issuedat = '$issuedat',
	issuedon = '$issuedon',
	date_generated = '$date_g',
	generated_by = '$user'
where rec_no = '$rec' "); 

$q = new newqueries(); //newqueries object	

while($r = mysql_fetch_array($query))
{		
	//names
	$getname= $nq->getFulName($r['emp_id']);
    $rrg  = mysql_fetch_array($getname);
    $mi   = substr($rrg['middlename'],0,1).".";  
    if($rrg['suffix'] != ""){
    	$names  = $rrg['firstname']." ".$mi." ".$rrg['lastname']." ".$rrg['suffix'];
    }
    else{
        $names  = $rrg['firstname']." ".$mi." ".$rrg['lastname'];
    }
    $pos  = strtoupper($r['position']);
	$etype= strtoupper($r['emp_type']);
	$cc   = $r['company_code'];
	$bc	  = $r['bunit_code'];
	$dc	  = $r['dept_code'];
	$sc	  = $r['section_code'];
	$ssc  = $r['sub_section_code'];
	$sdate= $q->changeDateFormat("F d, Y",$r['startdate']);
	$edate= $q->changeDateFormat("F d, Y",$r['eocdate']);	
	$com  = $r['comments'];
	$lodg = $r['lodging'];
	if($r['duration'] == 1){
		$duration  = @$r['duration']." month";
	}else{
		$duration  = @$r['duration']." months";
	}
	$sd = new DateTime($sdate);
	$ed = new DateTime($edate);	
		
	
	$bunit	= $q->getBusinessUnitName($bc,$cc);	
	$dep	= $q->getDepartmentName($dc,$bc,$cc);
	$sec	= $q->getSectionName($sc,$dc,$bc,$cc);
	$subse	= $q->getSubSectionName($ssc,$sc,$dc,$bc,$cc);
		
	if($ssc != ""){
		$newdept = $dep."-".$sec."-".$subse;
	}else if($sc != "" ){
		$newdept = $dep."-".$sec;
	}else if($dc !="") {
		$newdept = $dep;
	}
	else{
		$newdept = $bunit;
	}

	$pdf->Ln(5);
	$pdf->SetFont('Times','',11);
	$pdf->Cell(12);
	$pdf->Cell(20, 7, $addresse,0,0,'L');
	
	$pdf->Ln(5);
	$pdf->Cell(12);
	$pdf->Cell(32, 7, "PANGLAO BAY PREMIERE PARKS & RESORTS CORP.",0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(12);
	$pdf->Cell(32, 7, "Bolod, Panglao, Bohol",0,0,'L');
	
	$pdf->Ln(10);	
	$pdf->Cell(12);
	$pdf->Cell(32, 7, 'SIR:',0,0,'L');
	$pdf->Ln(10);	
	$pdf->Cell(28);
	//$pdf->Ce(25);  

	$pdf->Cell(175, 5, 'Anent to my employment with PANGLAO BAY PREMIERE PARKS & RESORTS CORP., I hereby ');
	$pdf->Ln(5);
	$pdf->SetX(22);
	$pdf->MultiCell(175, 5,'agree on the following terms and conditions therewith, to wit:');
	$pdf->Ln(5);

	$pdf->SetX(42);  
	$pdf->Cell(5, 5, '1.');
	$pdf->MultiCell(152, 5, 'To be hired as '.$pos.' in the '.$newdept.' Department of the establishment for a period of '.$duration.' effective '.$sdate.' until '.$edate.'.');

	$pdf->Ln(4);
	
	$pdf->SetX(42);  
	$pdf->Cell(5, 5, '2.');
	$pdf->MultiCell(152, 5, 'To abide by the established working hours and to strictly comply with the rules, regulations, and policies, as stipulated in the company handbook.');

	$pdf->Ln(4);
	
	$pdf->SetX(42);  
	$pdf->Cell(5, 5, '3.');
	$pdf->MultiCell(152, 5,'Can be terminated from service/employment in accordance with its rules and regulations and/or pursuant to law and may terminate this contract for cause pursuant to law and for any violations of the terms and conditions of this contract.');	

	$pdf->Ln(4);
	
	$pdf->SetX(42);   
	$pdf->Cell(5, 5, '4.');	
	$pdf->MultiCell(152, 5, 'All records and documents of my employer and all information pertaining to its business affairs are confidential and there shall be no unauthorized release, disclosure or reproduction of the same any time during or after my assignment.');	
	
	$pdf->Ln(4);
	
	$pdf->SetX(42);  
	$pdf->Cell(5, 5, '5.');
	$pdf->MultiCell(152, 5, "You shall not, during the term of your employment with South Palms Resort Panglao and for a period of one year (1) year after termination for whatever reason of your employment, accept employment from any person, firm, or competition in Panglao or any resort in Bohol, involve in any business or undertaking in competition or in conflict or in any way prejudicial to the business or interest of South Palms Resort Panglao.");

	$pdf->Ln(8);
	$pdf->Cell(28);
	$pdf->Cell(175, 5, "IN WITNESS WHEREOF, I have hereunto set my hand and affixed my signature this ".$day." day of");
	$pdf->Ln(5);
	$pdf->SetX(22);
	$pdf->MultiCell(175, 5,strtoupper($monthName).", ".$year." at Bolod, Panglao, Bohol Philippines.");
	$pdf->Ln(15);
	
	$pdf->SetFont('Times','U',11);
	$pdf->Cell(125);
	$pdf->Cell(60, 7, mb_convert_encoding(strtoupper($names), '', 'UTF-8'),0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(146);
	$pdf->SetFont('Times','',11);
	$pdf->Cell(20, 7, 'Employee',0,0,'L');
	$pdf->Ln(8);
	
	$pdf->Cell(125);
	if($ctc_or_sss == 'Cedula')
	{
		//$pdf->Cell(20, 7, 'CTC No. ______________________',0,0,'L');
		$pdf->Cell(15, 7, 'CTC No.');
		$pdf->SetFont('Times','U',11);		
		$pdf->Cell(45, 7, @$ctc_sssno);	

		$pdf->SetFont('Times','',11);
		$pdf->Ln(5);
		$pdf->Cell(125);
		$pdf->Cell(16, 7, 'issued on ');
		$pdf->SetFont('Times','U',11);
		$pdf->Cell(45, 7, @$issuedon);	
		$pdf->SetFont('Times','',11);

		$pdf->Ln(5);
		$pdf->Cell(125);
		$pdf->Cell(5, 7, 'at ');
		$pdf->SetFont('Times','U',11);
		$pdf->Cell(45, 7, @$issuedat);	
	}
	else
	{	
		$pdf->Cell(15, 7, 'SSS No.');	
		$pdf->SetFont('Times','U',11);	
		$pdf->Cell(45, 7, @$ctc_sssno);	
		$pdf->SetFont('Times','',11);
		$pdf->Ln(5);
		$pdf->Cell(125);
		$pdf->Cell(16, 7, 'issued at ',0,0,'L');
		$pdf->SetFont('Times','U',11);	
		$pdf->Cell(45, 7, @$issuedat);	
	}
	
	$pdf->Ln(10);
	$pdf->SetFont('Times','',11);	
	$pdf->Cell(11);	
	$pdf->Ln();
	$pdf->Cell(11);
	$pdf->Cell(20, 5, "PANGLAO BAY PREMIERE PARKS & RESORTS CORP.",0,0,'L');
	$pdf->Ln();
	$pdf->Cell(11);
	$pdf->Cell(20, 7, 'By:',0,0,'L');
	$pdf->Ln(5);

	$pdf->Cell(11);
	$pdf->Cell(20, 7, '_______________________________',0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(11);
	$pdf->Cell(20, 7, 'MISS MARIA NORA A. PAHANG',0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(11);
	$pdf->Cell(20, 7, 'HRD Manager',0,0,'L');
	$pdf->Ln(10);
	$pdf->Cell(11);
	$pdf->Cell(20, 7, 'SIGNED IN THE PRESENCE OF:',0,0,'L');
	$pdf->Ln(15);
	$pdf->SetFont('Times','U',11);
	$pdf->Cell(12);
	$pdf->Cell(60, 7, strtoupper(@$witness1),0,0,'C');
	$pdf->Cell(15);
	$pdf->SetFont('Times','',11);
	$pdf->Cell(20, 7, 'and',0,0,'C');
	$pdf->Cell(13);
	$pdf->SetFont('Times','U',11);
	$pdf->Cell(60, 7, strtoupper(@$witness2),0,0,'C');
	$pdf->Ln(5);
	$pdf->Cell(32);
	$pdf->SetFont('Times','',11);
	$pdf->Cell(20, 7, 'Witness',0,0,'L');	
	$pdf->Cell(93);
	$pdf->Cell(20, 7, 'Witness',0,0,'L');
	
	$pdf->Ln(10);
	$pdf->Cell(7);
	$pdf->SetX(21);    
	$pdf->MultiCell(178, 6, "SUBSCRIBE AND SWORN TO before me this ".$day." day of ".strtoupper($monthName).", ".$year." in Panglao, Bohol, Philippines.");

	$pdf->Ln(10);
	$pdf->Cell(130);
	$pdf->Cell(20, 7, '_______________________________',0,0,'L');
	$pdf->Ln();
	$pdf->Cell(137);
	$pdf->Cell(20, 7, 'ADMINISTERING OFFICER',0,0,'L');
	$pdf->Ln();
}		
//********************************* for report logs	*********************************************//

	$activity 		= "Generate the $printtype Contract of Employment of ".@$names." Record No.".$rec." Contract No.".$randomno;
	$date 			= date("Y-m-d");
	$time 			= date('H:i:s');
	$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);	

	$name 			= preg_replace('/  */', '_', @$names);	 	
	$filename 		= "contract_".$name."_random_".$randomno.".pdf";

/************************************************************************************************/
$path = "../document/contract/".$filename;
$pdf->Output();
/*
$pdf->Output($path,'F'); 

?>
<script>
window.location = 'preview_reprint.php?code=contract&filename=<?php echo $filename;?>';
</script>
<?php///used when you back up the file just change the directory to the right one!

?>
*/

?>
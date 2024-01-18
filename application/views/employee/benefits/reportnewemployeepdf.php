<?php

// class PDF extends FPDF
// {
//     public function Header()
//     {
   

// Instanciation of inherited class

$emp_typefilter = "and (emp_type = 'Regular' or emp_type = 'Contractual' or emp_type = 'PTA' or emp_type = 'PTP' or emp_type = 'Seasonal' or emp_type = 'OJT') ";

/********************************************************************************/

    $y2d		= date('Y');
    $m 			= date('m');


    if(!empty($permonth)){
        if(strlen($permonth) <2){
            $querymonth = $y2d."-0".$permonth; // 2015-08
        }else{
            $querymonth = $y2d."-".$permonth; // 2015-08
        }
        $forthemonth= "for the Month of ".strtoupper($this->custom->getmonthname($permonth));
    }else{
        $querymonth = $y2d."-".$m;
        $forthemonth= "for the Month of ".strtoupper($this->custom->getmonthname($m));
    }
    $monthquery = "and startdate like '%$querymonth%' ";

    $m_plus		= $m+1;
    $m_minus	= $m-1;
    $month_plus = $this->custom->getmonthname($m_plus);
    $month_m 	= $this->custom->getmonthname($m);
    $month_minus= $this->custom->getmonthname($m_minus);

    if($m == '1'){
        $y_minus	= $y2d-1;
        $month_minus= $this->custom->getmonthname('12')." ".$y_minus;
        $m_minus	= '12|'.$y_minus;
    }else if($m == '12'){
        $y_plus		= $y2d+1;
        $m_plus		= '1|'.$y_plus;
        $month_plus = $this->custom->getmonthname('1')." ".$y_plus;
    }
  
$fields = "employee3.emp_id, name, position, company_code, bunit_code, dept_code,section_code, sub_section_code,emp_type";


$code	= $this->input->get('code');
$ec	 	= explode(".", $code);
$cc	   	= @$ec[0];
$bc		= @$ec[1];
$dc		= @$ec[2];
$sc		= @$ec[3];
$ssc	= @$ec[4];
$uc		= @$ec[5];




// /********************************************************************************/
// //	echo "permonth=".$permonth." code=".$code;
$loc		= '';


if($cc != '') {
    if($sc !='') {
        @$loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";
    } elseif($dc !='') {
        @$loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";
    } elseif($bc !='') {
        @$loc = "and company_code = '$cc' and bunit_code = '$bc' ";
    } elseif($cc !='') {
        @$loc = "and company_code = '$cc'";
    }
}
//echo "SELECT $fields from employee3 where current_status = 'active' and tag_as = 'new' $loc $monthquery";

$query = $this->db->query("SELECT $fields from employee3 where tag_as = 'new' $loc $emp_typefilter and  current_status = 'active' $monthquery");
// echo "SELECT $fields from employee3 where tag_as = 'new' $loc $emp_typefilter and  current_status = 'active' $monthquery";
$ctr = 0;
foreach ($query->result_array() as $row) {

  

    $ben_q = $this->db->query("SELECT * FROM benefits where emp_id = '$row[emp_id]' ");
    $rb = $ben_q->row_array();
    
    $lastname = $this->dbmodel->get_field('lastname', 'applicant', "app_id = '".$row['emp_id']."'");
    $firstname = $this->dbmodel->get_field('firstname', 'applicant', "app_id = '".$row['emp_id']."'");

    
        $this->ppdf = new TCPDF();
        
        $this->ppdf->setPrintHeader(false);
        $this->ppdf->setPrintFooter(false);

        // $this->ppdf->AliasNbPages();
                
      
        $this->ppdf->AddPage("L", "Letter");
        $this->ppdf->SetFont('times', '', 12);
        $this->ppdf->SetFont('times', 'B', 12);
        $this->ppdf->Cell(115);
        $this->ppdf->Cell(30, 10, 'ALTURAS SUPERMARKET CORPORATION', 0, 0, 'C');
        $this->ppdf->Ln();
        $this->ppdf->SetFont('times', 'B', 10);
        $this->ppdf->Cell(96);
        $this->ppdf->Ln(10);
        //'T,B',0,'L'
        $this->ppdf->SetFont('times', 'B', 10);
        $this->ppdf->Cell(9, 7, 'NO', 1);
        $this->ppdf->Cell(35, 7, "LASTNAME", 1);
        $this->ppdf->Cell(35, 7, "FIRSTNAME", 1);
        $this->ppdf->Cell(46, 7, 'POSITION', 1);
        $this->ppdf->Cell(20, 7, 'EMP TYPE', 1);
        $this->ppdf->Cell(24, 7, 'SSS NO', 1);
        $this->ppdf->Cell(27, 7, 'PHILHEALTH', 1);
        $this->ppdf->Cell(33, 7, 'PAG-IBIG RTN', 1);
        $this->ppdf->Cell(33, 7, 'PAG-IBIG MID NO', 1);
        $this->ppdf->Ln();
        
        $this->ppdf->SetFont('Times', '', 9);
        $ctr++;
        $this->ppdf->Cell(9, 7, $ctr, 1);
        $this->ppdf->Cell(35, 7, $lastname['lastname'], 1);
        $this->ppdf->Cell(35, 7, $firstname['firstname'], 1);
        $this->ppdf->Cell(46, 7, ucwords(strtolower($row['position'])), 1);
        $this->ppdf->Cell(20, 7, $row['emp_type'], 1);
        $this->ppdf->Cell(24, 7, $rb['sssno'], 1);
        $this->ppdf->Cell(27, 7, $rb['philhealth'], 1);
        $this->ppdf->Cell(33, 7, "", 1);
        $this->ppdf->Cell(33, 7, "", 1);

        $this->ppdf->Ln();
    
    } 

    
        $this->ppdf->SetY(-31); //-20
        // Arial italic 8
        $this->ppdf->SetFont('Times', 'I', 8);
        // Page number
        $this->ppdf->Cell(4);
        $this->ppdf->Cell(0, 10, 'Date printed: '.date('M d, Y', strtotime(date("Y-m-d"))), 0, 0, 'L');
        // Position at 1.8 cm from bottom
        $this->ppdf->SetY(-31); //-20
        // Times italic 8
        $this->ppdf->SetFont('Times', 'I', 8);
        // Page number
        $this->ppdf->Cell(105);
        $this->ppdf->Cell(0, 10, 'Prepared by: '.$this->session->userdata('employee_name'), 0, 0, 'L');
        // Position at 1.8 cm from bottom
        $this->ppdf->SetY(-31);
        // Times italic 8
        $this->ppdf->SetFont('Times', 'I', 8);
        // Page number
        $this->ppdf->Cell(0, 10, 'Page '. $this->ppdf->PageNo().'/{nb}', 0, 0, 'R');

            




$this->ppdf->Output();


?> 
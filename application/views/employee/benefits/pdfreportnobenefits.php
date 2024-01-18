<?php

$code	= $this->input->get('code');
$ec	 	= explode(".", $code);
$cc	   	= @$ec[0];
$bc		= @$ec[1];
$dc		= @$ec[2];
$sc		= @$ec[3];
/********************************************************************************/

    $where = "";
    if($cc != '') {
        if($sc !='') {
            @$where = " and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";
        } elseif($dc !='') {
            @$where = " and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";
        } elseif($bc !='') {
            @$where = " and company_code = '$cc' and bunit_code = '$bc' ";
        } elseif($cc !='') {
            @$where = " and company_code = '$cc'";
        } else {
            $loc = '';
        }
    }



            switch($this->input->get('ben')) {
                case "nosss": $b = "NO SSS NUMBER REPORT ";
                    break;
                case "noph": $b = "NO PHILHEALTH NUMBER REPORT";
                    break;
                case "nopgrtn": $b = "NO PAGIBIG TRACKING REPORT ";
                    break;
                case "nopgno": $b = "NO PAGIBIG MID REPORT ";
                    break;
            }

            $this->ppdf = new TCPDF();
            // $this->ppdf->AliasNbPages();
            $this->ppdf->setPrintHeader(false);
            $this->ppdf->setPrintFooter(false);
            $this->ppdf->AddPage("L", "Letter");
            $this->ppdf->SetAutoPageBreak(true, 10); // Set auto page breaks with a margin of 10mm
            $this->ppdf->SetMargins(15, 15, 15); // Set left, top, and right margins to 15mm

            $this->ppdf->SetFont('Times', '', 12);
            $this->ppdf->SetFont('Times', 'B', 12);

            $this->ppdf->Cell(100);
            $this->ppdf->Cell(72, 10, 'ALTURAS GROUP OF COMPANIES', 0, 0, 'C');
            $this->ppdf->Ln();
            $this->ppdf->Cell(100);
            $this->ppdf->Cell(60, 6, $b, 0, 0, 'C');
            $this->ppdf->Ln();
            $this->ppdf->Cell(100);
            $this->ppdf->SetFont('Times', 'I', 11);
            $this->ppdf->Cell(60, 6, "as of ".date('F d, Y'), 0, 0, 'C');
            $this->ppdf->SetFont('Times', 'B', 12);
            $this->ppdf->Ln(10);
            $this->ppdf->Cell(12, 7, '________________________________________________________________________________________________________________________________');
            $this->ppdf->Ln(7);
            $this->ppdf->Cell(10, 7, 'NO');
            $this->ppdf->Cell(55, 7, "NAME");
            $this->ppdf->Cell(45, 7, "EMPTYPE");
            $this->ppdf->Cell(52, 7, "POSITION");
            $this->ppdf->Cell(60, 7, "BUSINESS UNIT");
            $this->ppdf->Cell(90, 7, "DEPARTMENT");
            $this->ppdf->Ln(4);
            $this->ppdf->Cell(12, 4, '________________________________________________________________________________________________________________________________');
            $this->ppdf->Ln(5);

            
            switch($this->input->get('ben')) {
                case "nosss": $b = "sss_no = '' ";
                    break;
                case "noph": $b = "philhealth = '' ";
                    break;
                case "nopgrtn": $b = "pagibig_tracking = '' ";
                    break;
                case "nopgno": $b = "pagibig = '' ";
                    break;
            }

            $query = $this->db->query("SELECT 
                    emp_id, name, position, sss_no, company_code, bunit_code, dept_code, emp_type FROM employee3 INNER JOIN applicant_otherdetails on employee3.emp_id = applicant_otherdetails.app_id WHERE $b and current_status = 'Active' $where");
            // echo"SELECT 
            //         emp_id, name, position, sss_no, company_code, bunit_code, dept_code, emp_type FROM employee3 INNER JOIN applicant_otherdetails on employee3.emp_id = applicant_otherdetails.app_id WHERE $b and current_status = 'Active' $loc";
            $ctr = 0;
            foreach($query->result_array() as $row)   
            {
            $ctr++;

            $this->ppdf->SetFont('Times', '', 10);
            $this->ppdf->Cell(10, 7, $ctr);
            $this->ppdf->Cell(55, 7, ucwords(strtolower($row['name'])));
            $this->ppdf->Cell(45, 7, ucwords(strtolower($row['emp_type'])));
            $this->ppdf->Cell(52, 7, ucwords(strtolower($row['position'])));
            $this->ppdf->Cell(60, 7, $bunit->business_unit);
            $this->ppdf->Cell(90, 7, $dept->dept_name);
            $this->ppdf->Ln();

            
        }
        
        $this->ppdf->SetY(-25); //-20
        // Arial italic 8
        $this->ppdf->SetFont('Times', 'I', 8);
        // Page number
        $this->ppdf->Cell(4);
        $this->ppdf->Cell(0, 10, 'Date printed: '.date('M d, Y', strtotime(date("Y-m-d"))), 0, 0, 'L');
        // Position at 1.8 cm from bottom
        $this->ppdf->SetY(-25); //-20
        // Times italic 8
        $this->ppdf->SetFont('Times', 'I', 8);
        // Page number
        $this->ppdf->Cell(105);
        $this->ppdf->Cell(0, 10, 'Prepared by: '.$this->session->userdata('employee_name'), 0, 0, 'L');
        // Position at 1.8 cm from bottom
        $this->ppdf->SetY(-25);
        // Times italic 8
        $this->ppdf->SetFont('Times', 'I', 8);
        // Page number
        $this->ppdf->Cell(0, 10, 'Page '. $this->ppdf->PageNo().'/{nb}', 0, 0, 'R');



    $this->ppdf->Output();
    ?> 
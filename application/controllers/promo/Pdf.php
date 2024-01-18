<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdf extends CI_Controller
{
    public $today;
    public $adminUser;
    public $systemUser;
    public $ppdf;
    function __construct()
    {
        parent::__construct();
        $empId              = $this->session->userdata('emp_id');
        $this->today        = date('Y-m-d');
        $this->adminUser    = array('06359-2013', '01186-2023');
        $this->systemUser   = $this->session->userdata('emp_id');
        $this->load->library('ppdf');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function randomNo()
    {
        $newstring  = '';
        $length     = 5;
        $list       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        mt_srand((float)microtime() * 1000000);

        if ($length > 0) {
            while (strlen($newstring) < $length) {
                $newstring .= $list[mt_rand(0, strlen($list) - 1)];
            }
        }
        return $newstring;
    }

    public function generatePermit()
    {
        $input      = $this->input->post(NULL, TRUE);
        $id         = explode('_', $input['id']);
        $emp_id     = $id[0];
        $record_no  = $id[1];
        $contract   = $id[3];
        $store      = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $id[2]));
        $name       = $this->promo_model->getName_employee3($emp_id);
        $no         = $this->randomNo() . '-' . date('mdy');
        $table1     = ($contract == 1) ? 'employee3' : 'employmentrecord_';
        $table2     = ($contract == 1) ? 'promo_record' : 'promo_history_record';
        $row        = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $emp_id, 'e.record_no' => $record_no), $table1, $table2);
        $account    = $this->promo_model->selectAll_tcR('users', array('emp_id' => $emp_id, 'usertype' => 'employee'));
        $copy       = array("Supervisor's Copy", "201 Copy");
        $position   = ($row['type'] == 'Seasonal') ? $row['position'] . ' Seasonal' : $row['position'];
        $duty       = '';
        $special    = '';
        $i          = 0;
        if (!empty($row[$store['bunit_dutySched']])) {
            $duty       = $row[$store['bunit_dutySched']] . '/' . $row[$store['bunit_dutyDays']];
        }
        if (!empty($row[$store['bunit_specialSched']])) {
            $special    =  $row[$store['bunit_specialSched']] . '/' . $row[$store['bunit_specialDays']];
        }

        // Set PDF properties
        $this->ppdf->SetAuthor('Nald');
        $this->ppdf->SetTitle('Work Permit');
        $this->ppdf->SetSubject('Work Permit');
        $this->ppdf->SetKeywords('work permit');
        $this->ppdf->setPrintHeader(false);
        // Add a page
        $this->ppdf->AddPage();
        $this->ppdf->setPrintHeader(false);
        $this->ppdf->setPrintFooter(false);

        foreach ($copy as $page) {
            $i++;
            $this->ppdf->SetFont('Times', '', 8);
            $this->ppdf->Ln(8);
            $this->ppdf->Cell(5);
            $this->ppdf->Cell(0, 5, "(" . @$page . ")", 0, 0, 'L');
            $this->ppdf->SetX(160);
            $this->ppdf->Cell(0, 5, 'PERMIT NO. P' . $no);
            $this->ppdf->Ln(5);
            $this->ppdf->SetFont('Times', 'B', 12);
            $this->ppdf->Cell(0, 5, 'NOTICE OF ASSIGNMENT', 0, 0, 'C');
            $this->ppdf->Ln(5);
            $this->ppdf->SetFont('Times', '', 12);
            $this->ppdf->Cell(0, 5, 'For Promodiser - Merchandiser', 0, 0, 'C');
            $this->ppdf->Ln(5);
            $this->ppdf->Cell(0, 5, 'Assigned at ' . @$store['bunit_name'] . '', 0, 0, 'C');

            $this->ppdf->Ln(15);
            $this->ppdf->Cell(7);
            $this->ppdf->SetFont('Times', '', 12);
            $this->ppdf->Cell(5, 7, "NAME:", 0, 0, 'L');
            $this->ppdf->SetX(32);
            $this->ppdf->Cell(0, 7, @$name);
            $this->ppdf->SetX(32);
            $this->ppdf->Cell(0, 7, "__________________________________________");
            $this->ppdf->SetX(126);
            $this->ppdf->Cell(0, 7, "STATUS:");
            $this->ppdf->SetX(144);
            $this->ppdf->Cell(0, 7, @$row['type']);
            $this->ppdf->SetX(144);
            $this->ppdf->Cell(0, 7, "_________________________");

            $this->ppdf->Ln(7);
            $this->ppdf->Cell(7);
            $this->ppdf->Cell(5, 7, "DESIGNATION:", 0, 0, 'L');
            $this->ppdf->SetX(48);
            $this->ppdf->Cell(0, 7, @$position);
            $this->ppdf->SetX(48);
            $this->ppdf->Cell(0, 7, "_______________________");
            $this->ppdf->SetX(99);
            $this->ppdf->Cell(0, 7, "DEPT. ASSIGNED:");
            $this->ppdf->SetX(136);
            $this->ppdf->Cell(0, 7, @$row['promo_department']);
            $this->ppdf->SetX(136);
            $this->ppdf->Cell(0, 7, "____________________________");

            $this->ppdf->Ln(7);
            $this->ppdf->Cell(7);
            $this->ppdf->Cell(5, 7, "COMPANY/AGENCY:", 0, 0, 'L');
            $this->ppdf->SetX(61);
            $this->ppdf->Cell(0, 7, strtoupper(@$row['promo_company']));
            $this->ppdf->SetX(61);
            $this->ppdf->Cell(0, 7, "________________________________________________________________");

            $this->ppdf->Ln(7);
            $this->ppdf->Cell(7);
            $this->ppdf->Cell(5, 7, "INCLUSIVE DATES OF ASSIGNMENT:", 0, 0, 'L');
            $this->ppdf->SetX(90);
            $this->ppdf->Cell(0, 7, "");
            $this->ppdf->SetX(91);
            $this->ppdf->Cell(0, 7, date('F d, Y', strtotime($row['startdate'])) . ' - ' . date('F d, Y', strtotime($row['eocdate'])));
            $this->ppdf->SetX(91);
            $this->ppdf->Cell(0, 7, "__________________________________________________");

            $this->ppdf->Ln(7);
            $this->ppdf->Cell(7);
            $this->ppdf->Cell(5, 7, "DUTY SCHEDULE:", 0, 0, 'L');
            $this->ppdf->SetX(54);
            $this->ppdf->Cell(0, 7, $duty);
            $this->ppdf->SetX(55);
            $this->ppdf->Cell(0, 7, "___________________________________________________________________");
            $this->ppdf->Ln(7);
            $this->ppdf->SetX(54);
            $this->ppdf->Cell(0, 7, $special);
            $this->ppdf->SetX(55);
            $this->ppdf->Cell(0, 7, "___________________________________________________________________");

            $this->ppdf->Ln(7);
            $this->ppdf->Cell(7);
            $this->ppdf->Cell(5, 7, "DAY OFF:", 0, 0, 'L');
            $this->ppdf->SetX(37);
            $this->ppdf->Cell(0, 7, strtoupper($row['dayoff']));
            $this->ppdf->SetX(37);
            $this->ppdf->Cell(0, 7, "_______________");

            $this->ppdf->Ln(20);
            $this->ppdf->Cell(190, 7, "MS. MARIA NORA A. PAHANG", 0, 0, 'R');
            $this->ppdf->Ln(5);
            $this->ppdf->Cell(177, 7, "HRD MANAGER", 0, 0, 'R');

            if ($row['tag_as'] == 'new') {
                $username = '';
                $password = '';
                if (trim($account['username']) != '') {

                    $username = $account['username'];
                    $password = 'Hrms2014';
                }
                $this->ppdf->Ln(5);
                $this->ppdf->SetFont('Times', 'I', 8);
                $this->ppdf->Cell(7);
                $this->ppdf->Cell(12, 7, 'Username : ' . $username, 0, 0, 'L');
                $this->ppdf->Ln(4);
                $this->ppdf->Cell(7);
                $this->ppdf->Cell(12, 7, 'Password : ' . $password, 0, 0, 'L');
                $this->ppdf->Ln(3);
                $this->ppdf->SetFont('Times', '', 12);
            } else {
                $this->ppdf->Ln(10);
            }
            if ($i == '1') {
                $this->ppdf->Ln(15);
                $this->ppdf->Cell(0, 7, '-----------------------------------------------------------------------------------------------------------------------------------------', 0, 0, 'L');
                $this->ppdf->Ln(10);
            }
        }
        // Output the PDF

        if ($row[$store['bunit_permit']] == '') {
            $this->promo_model->update_twdA($table2, array('emp_id' => $emp_id, 'record_no' => $record_no), array($store['bunit_permit'] => $no));
            $filename       = 'permittowork_' . $emp_id . '_PermitNo_' . $no . '.pdf';
            $base_path      = $_SERVER['DOCUMENT_ROOT'];
            $relative_path  = '/hrmsv2/document/permit/';
            $path           = $base_path . $relative_path . $filename;
            $link           = 'id=' . $filename;
            $this->ppdf->Output($path, 'F');
            $activity       = 'Generate the Permit to Work of ' . $name . ' PermitNo:' . $no;
            $logs           = array(
                'activity'  => $activity,
                'date'      => $this->today,
                'time'      => date('H:i:s'),
                'user'      => $this->systemUser,
                'username'  => $this->session->userdata('username'),
            );
            $this->promo_model->insert_tdA('logs', $logs);
        } else {
            $filename   = 'permittowork_' . $emp_id . '_PermitNo_' . $row[$store['bunit_permit']] . '.pdf';
            $link       = 'id=' . $filename;
            $rLogs      = $this->promo_model->selectAll_tcR('reprint_logs', array('print_no' => $row[$store['bunit_permit']]));
            $rtimes     = ($rLogs['reprint_times'] + 1);
            $logs       = array(
                'reprint_name'      => 'Permit',
                'print_no'          => $row[$store['bunit_permit']],
                'reprint_times'     => $rtimes,
                'reprint_date'      => $this->today,
                'reprint_time'      => date('H:i:s'),
                'reprint_user'      => $this->systemUser,
                'reprint_username'  => $this->session->userdata('username'),
            );
            if (count($rLogs) > 0) {
                $this->promo_model->update_twdA('reprint_logs', array('print_no' => $row[$store['bunit_permit']]), $logs);
            } else {
                $this->promo_model->insert_tdA('reprint_logs', $logs);
            }
        }

        ob_clean();
        echo json_encode(['message' => 'success', 'id' => $link]);
    }

    public function generateContract()
    {
        $input      = $this->input->post(NULL, TRUE);
        $id         = explode('_', $input['id']);
        $emp_id     = $id[0];
        $record_no  = $id[1];
        $header_no  = $id[2];
        $contract   = $id[3];
        $no         = $this->randomNo() . '-' . date('mdy');
        $witness    = $this->promo_model->selectAll_tcR('employment_witness', array('emp_id' => $emp_id, 'rec_no' => $record_no));
        $header     = $this->promo_model->selectAll_tcR('contract_header', array('ccode_no' => $header_no));
        $headComp   = str_replace('-', ' - ', $header['company']);
        $table1     = ($contract == 1) ? 'employee3' : 'employmentrecord_';
        $table2     = ($contract == 1) ? 'promo_record' : 'promo_history_record';
        $row        = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $emp_id, 'e.record_no' => $record_no), $table1, $table2);
        $app        = $this->promo_model->selectAll_tcR('applicant', array('app_id' => $emp_id));
        $mn         = (!empty($app['middlename'])) ? ' ' . substr($app['middlename'], 0, 1) . '. ' : ' ';
        $suffix     = (!empty($app['suffix'])) ? ' ' . $app['suffix'] . ' ' : '';
        $fullname   = $app['firstname'] . $mn .  $app['lastname'] . $suffix;
        $sss_ctc    = ($witness['sss_ctc'] == 'Cedula') ? 'CTC No.' : 'SSS No.';
        $position   = ($row['type'] == 'Seasonal') ? $row['position'] . ' Seasonal' : $row['position'];
        $when       = date_create($witness['date_generated']);
        $month      = date_format($when, 'm'); //month
        $day        = date_format($when, 'jS'); //day
        $year       = date_format($when, 'Y'); //year
        $monthName  = date('F', mktime(0, 0, 0, $month, 10));
        $five       = ($header_no == 23) ? ucwords(strtolower($headComp)) : 'employer';
        $bUs        = $this->promo_model->locate_promo_bu('asc');
        $field      = '';
        foreach ($bUs as $bU) {
            $hasBu = $this->promo_model->empStores($table2, $emp_id, $record_no, $bU['bunit_field']);
            if ($hasBu > 0) {
                $array = array(
                    10 => 'tal_contract',
                    11 => 'pm_contract',
                    12 => 'icm_contract',
                    14 => 'asc_contract',
                    31 => 'tub_contract',
                    32 => 'colc_contract',
                    33 => 'alta_contract',
                    36 => 'fr_panglao_contract',
                    37 => 'fr_tubigon_contract',
                    23 => $bU['bunit_contract'],
                );
                if ($array[$header_no] == $bU['bunit_contract']) {
                    $field = $bU['bunit_contract'];
                }
            }
        }
        var_dump($header_no);
        // Set PDF properties
        $this->ppdf->SetAuthor('Nald');
        $this->ppdf->SetTitle('CONTRACT OF EMPLOYMENT');
        $this->ppdf->SetSubject('contract');
        $this->ppdf->SetKeywords('contract');
        $this->ppdf->setPrintHeader(false);

        // Add a page
        $this->ppdf->AddPage('P', 'LEGAL');
        $this->ppdf->setPrintHeader(false);
        $this->ppdf->setPrintFooter(false);

        $this->ppdf->Ln(1);
        $this->ppdf->SetFont('Times', 'B', 12);
        $this->ppdf->Cell(0, 0, 'CONTRACT OF EMPLOYMENT FOR A DEFINITE PERIOD', 0, 0, 'C');
        $this->ppdf->Ln(10);

        $this->ppdf->SetFont('Times', '', 12);
        $this->ppdf->Cell(144);
        $this->ppdf->Cell(40, 0, date('F d, Y', strtotime($witness['date_generated'])), 0, 0, 'R');
        $this->ppdf->Ln(1);
        $this->ppdf->SetX(158);
        $this->ppdf->Cell(40, 0, '__________________');
        $this->ppdf->Ln(4);
        $this->ppdf->SetX(171);
        $this->ppdf->Cell(40, 7, 'DATE');

        $this->ppdf->Ln(10);
        $this->ppdf->Cell(7);
        $this->ppdf->Cell(20, 7, $header['addresse'], 0, 0, 'L');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(7);
        $this->ppdf->Cell(32, 7, $headComp, 0, 0, 'L');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(7);
        $this->ppdf->Cell(32, 7, $header['address'], 0, 0, 'L');

        $this->ppdf->Ln(10);
        $this->ppdf->Cell(7);
        $this->ppdf->Cell(32, 7, 'SIR:', 0, 0, 'L');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(25);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(25);
        $this->ppdf->MultiCell(175, 5, 'Anent to my  employment with, assigned in ' . $row['promo_company'] . ', I hereby agree on the following terms and conditions in connection therewith to wit:', 0, 'L');

        $this->ppdf->Ln(4);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(17);
        $this->ppdf->Cell(5, 7, '1.', 0, 0, 'L');
        $this->ppdf->MultiCell(175, 5, 'To be assigned as ' . $position  . ' in the ' . $row['promo_department'] . ' Department of your establishment as may be determined by my employer from ' . date('F d, Y', strtotime($row['startdate'])) . ' to ' . date('F d, Y', strtotime($row['eocdate'])) . '.');

        $this->ppdf->Ln(4);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(17);
        $this->ppdf->Cell(5, 7, '2.', 0, 0, 'L');
        $this->ppdf->MultiCell(175, 5, 'Shall be entitled to a monthly compensation from my employer with my full knowledge and understanding that I am not an employee, nor connected in whatever capacity with ' . ucwords(strtolower($headComp)) . '.                           ');

        $this->ppdf->Ln(4);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(17);
        $this->ppdf->Cell(5, 7, '3.', 0, 0, 'L');
        if ($header_no == 23) {
            $this->ppdf->MultiCell(175, 5, 'To abide by the established working hours prescribed by ' . $row['promo_company'] . ' and while assigned at the Abenson, I will strictly comply with the rules and regulation and policies of the ' . ucwords(strtolower($headComp)) . '.                            ');
        } else {
            $this->ppdf->MultiCell(175, 5, 'To abide by the established working hours and strictly comply with the rules, regulation and policies of the ' . ucwords(strtolower($headComp)) . '.                            ');
        }

        $this->ppdf->Ln(4);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(17);
        $this->ppdf->Cell(5, 7, '4.', 0, 0, 'L');
        $this->ppdf->MultiCell(175, 5, 'Can be terminated from my assessment upon violation of the rules and regulation of ' . ucwords(strtolower($headComp)) . ' or for any violation of the law, decree or ordinance in connection therewith.                             ');

        $this->ppdf->Ln(4);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(17);
        $this->ppdf->Cell(5, 7, '5.', 0, 0, 'L');
        $this->ppdf->MultiCell(175, 5, 'All records and documents of ' . $five . ' and all information pertaining to its business affairs are confidential and that no authorized release, disclosure or reproduction of the same will be made at any time during or after my assignment.                            ');

        $this->ppdf->Ln(4);
        $this->ppdf->Cell(20);
        $this->ppdf->MultiCell(167, 3, 'IN WITNESS WHEREOF, I have  hereunto  set my hand  and affixed my signature this ' . $day . ' day');
        $this->ppdf->Cell(11);
        $this->ppdf->Cell(20, 5, ' of ' . strtoupper($monthName) . ', ' . $year . ' at Tagbilaran City, Bohol, Philippines.', 0, 0, 'L');

        $this->ppdf->SetFont('Times', 'U', 12);
        $this->ppdf->Ln(15);
        $this->ppdf->Cell(125);
        $this->ppdf->Cell(60, 7, mb_strtoupper($fullname), 0, 0, 'C');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(146);
        $this->ppdf->SetFont('Times', '', 12);
        $this->ppdf->Cell(20, 7, 'Employee', 0, 0, 'L');

        $this->ppdf->Ln(10);
        $this->ppdf->Cell(125);
        $this->ppdf->Cell(15, 15, $sss_ctc);
        $this->ppdf->SetFont('Times', 'U', 12);
        $this->ppdf->Cell(45, 15, $witness['sssno_ctcno']);
        $this->ppdf->SetFont('Times', '', 12);
        if ($witness['sss_ctc'] == 'Cedula') {
            $this->ppdf->Ln(5);
            $this->ppdf->Cell(125);
            $this->ppdf->Cell(17, 15, 'issued on ');
            $this->ppdf->SetFont('Times', 'U', 12);
            $this->ppdf->Cell(45, 15, date('F d, Y', strtotime($witness['issuedon'])));
        }
        $this->ppdf->SetFont('Times', '', 12);
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(125);
        $this->ppdf->Cell(5, 15, 'at ');
        $this->ppdf->SetFont('Times', 'U', 12);
        $this->ppdf->Cell(45, 15, $witness['issuedat']);
        $this->ppdf->SetFont('Times', '', 12);

        $this->ppdf->Ln(10);
        $this->ppdf->Cell(11);
        $this->ppdf->Cell(20, 7, 'CONFORME:', 0, 0, 'L');
        $this->ppdf->Ln(10);
        $this->ppdf->Cell(11);
        $this->ppdf->Cell(20, 7, $headComp, 0, 0, 'L');
        $this->ppdf->Ln();
        $this->ppdf->Cell(11);
        $this->ppdf->Cell(20, 7, 'By:', 0, 0, 'L');
        $this->ppdf->Ln(5);

        $this->ppdf->Cell(11);
        $this->ppdf->Cell(20, 7, '_______________________________', 0, 0, 'L');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(13);
        $this->ppdf->Cell(20, 7, 'MS. MARIA NORA A. PAHANG', 0, 0, 'L');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(25);
        $this->ppdf->Cell(20, 7, 'HRD MANAGER', 0, 0, 'L');

        $this->ppdf->Ln(10);
        $this->ppdf->Cell(11);
        $this->ppdf->Cell(20, 7, 'SIGNED IN THE PRESENCE OF:', 0, 0, 'L');
        $this->ppdf->Ln(10);
        $this->ppdf->SetFont('Times', 'U', 12);
        $this->ppdf->Cell(12);
        $this->ppdf->Cell(60, 7, $witness['witness1'], 0, 0, 'C');
        $this->ppdf->Cell(15);
        $this->ppdf->SetFont('Times', '', 12);
        $this->ppdf->Cell(20, 7, 'and', 0, 0, 'C');
        $this->ppdf->Cell(13);
        $this->ppdf->SetFont('Times', 'U', 12);
        $this->ppdf->Cell(60, 7, $witness['witness2'], 0, 0, 'C');
        $this->ppdf->Ln(5);
        $this->ppdf->Cell(34);
        $this->ppdf->SetFont('Times', '', 12);
        $this->ppdf->Cell(20, 7, 'Witness', 0, 0, 'L');
        $this->ppdf->Cell(88);
        $this->ppdf->Cell(20, 7, 'Witness', 0, 0, 'L');

        $this->ppdf->Ln(10);
        $this->ppdf->Cell(7);
        $this->ppdf->SetX(17);
        $this->ppdf->MultiCell(178, 6, 'SUBSCRIBE AND SWORN TO before me this ' . $day . ' day of ' . strtoupper($monthName) . ', ' . $year . ' in the City of Tagbilaran, Bohol, Philippines.                     ');

        $this->ppdf->Ln(8);
        $this->ppdf->Cell(120);
        $this->ppdf->Cell(20, 7, '_______________________________', 0, 0, 'L');
        $this->ppdf->Ln();
        $this->ppdf->Cell(126);
        $this->ppdf->Cell(20, 7, 'ADMINISTERING OFFICER', 0, 0, 'L');
        $this->ppdf->Ln();

        if ($field != '' && $row[$field] == '') {
            $this->promo_model->update_twdA($table2, array('emp_id' => $emp_id, 'record_no' => $record_no), array($field => $no));
            $filename       = 'contract_' . $emp_id . '_random_' . $no . '.pdf';
            $base_path      = $_SERVER['DOCUMENT_ROOT'];
            $relative_path  = '/hrmsv2/document/contract/';
            $path           = $base_path . $relative_path . $filename;
            $link           = 'id=' . $filename;
            $this->ppdf->Output($path, 'F');

            $activity   = "Generate the Contract of Employment of " . $fullname . " Record No." . $record_no . " Contract No." . $no;
            $logs       = array(
                'activity'  => $activity,
                'date'      => $this->today,
                'time'      => date("H:i:s"),
                'user'      => $this->systemUser,
                'username'  => $this->session->userdata('username'),
            );
            $this->promo_model->insert_tdA('logs', $logs);
        } else {
            $filename       = 'contract_' . $emp_id . '_random_' . $row[$field] . '.pdf';
            $link           = 'id=' . $filename;
            // $this->ppdf->Output();
            $rLogs = $this->promo_model->selectAll_tcR('reprint_logs', array('print_no' => $row[$field]));
            $rtimes = ($rLogs['reprint_times'] + 1);
            $logs           = array(
                'reprint_name'      => 'Contract',
                'print_no'          => $row[$field],
                'reprint_times'     => $rtimes,
                'reprint_date'      => $this->today,
                'reprint_time'      => date('H:i:s'),
                'reprint_user'      => $this->systemUser,
                'reprint_username'  => $this->session->userdata('username'),
            );
            if (count($rLogs) > 0) {
                $this->promo_model->update_twdA('reprint_logs', array('print_no' => $row[$field]), $logs);
            } else {
                $this->promo_model->insert_tdA('reprint_logs', $logs);
            }
        }

        ob_clean();
        echo json_encode(['message' => 'success', 'id' => $link]);
    }

    public function generateDueContractsPDF()
    {
        $input = $this->input->post(NULL, TRUE);

        // Set PDF properties
        $this->ppdf->SetAuthor('Nald');
        $this->ppdf->SetTitle('DUE CONTRACTS REPORT');
        $this->ppdf->SetSubject('DUE CONTRACTS REPORT');
        $this->ppdf->SetKeywords('DUE CONTRACTS REPORT');
        $this->ppdf->setPrintHeader(false);

        // Add a page
        $this->ppdf->AddPage('L', 'LEGAL');

        $this->ppdf->SetFont('Times', 'B', 12);
        $this->ppdf->Ln();
        $this->ppdf->Cell(145);
        $this->ppdf->Cell(30, 7, "DUE CONTRACTS REPORT", 0, 0, 'C');
        $this->ppdf->Ln();
        $this->ppdf->Cell(145);
        $this->ppdf->Cell(30, 7, "as of " . date('F d, Y'), 0, 0, 'C');
        $this->ppdf->SetFont('Times', 'B', 10);

        $this->ppdf->Ln(10);
        $this->ppdf->SetFont('Times', 'B', 10);
        $this->ppdf->Cell(12, 7, 'NO.', 1);
        $this->ppdf->Cell(55, 7, "NAME", 1);
        $this->ppdf->Cell(27, 7, "EMPTYPE", 1);
        $this->ppdf->Cell(25, 7, "STARTDATE ", 1);
        $this->ppdf->Cell(22, 7, 'EOCDATE', 1);
        $this->ppdf->Cell(45, 7, "POSITION", 1);
        $this->ppdf->Cell(110, 7, "STORE(S", 1);
        $this->ppdf->Cell(40, 7, "DEPARTMENT", 1);
        $this->ppdf->Ln();

        $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        if (!empty($input['store'])) {
            if ($input['store'] == 'allbu') {
                $query = $this->promo_model->locate_promo_bu('asc');
                $i = 0;
                $where .= ' AND (';
                foreach ($query as $key => $field) {
                    $i++;
                    $where .= ($i == 1) ? "$field[bunit_field] = 'T'" : " OR $field[bunit_field] = 'T'";
                }
                $where .= ')';
            } else {
                $bunit = explode('|', $input['store']);
                $where .= " AND $bunit[1]= 'T'";
            }
        }
        if (!empty($input['promo_department'])) {
            $where .= " AND promo_department = '$input[promo_department]'";
        } else {
            if ($input['store'] == 'allbu') {
                $query = $this->promo_model->locate_promo_bu('asc');
                $bunit_id = [];
                foreach ($query as $key => $field) {
                    $bunit_id[] = $field['bunit_id'];
                }
                $dept = $this->promo_model->whereIN_stcd('distinct', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit_id);
                $where .= ' AND (';
                $i = 0;
                foreach ($dept as $key => $value) {
                    $i++;
                    $where .= ($i == 1) ? "promo_department = '$value[dept_name]'" : " OR promo_department = '$value[dept_name]'";
                }
                $where .= ')';
            }
        }
        $where .= " AND current_status = 'Active' AND hr_location = 'asc'";
        $where .= " AND eocdate < '" . date('Y-m-d') . "'";

        $no = 0;
        $table1 = 'employee3';
        $table2 = 'promo_record';
        $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
        $order = "name|ASC";
        $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
        foreach ($query as $row) {
            $bUs = $this->promo_model->locate_promo_bu('asc');
            $no++;
            $i = 0;
            $stores = '';
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    $i++;
                    $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                }
            }
            $this->ppdf->SetFillColor(200, 220, 255);
            $this->ppdf->Cell(12, 7, $no, 1);
            $this->ppdf->Cell(55, 7, ucwords(strtolower($row['name'])), 1);
            $this->ppdf->Cell(27, 7, $row['emp_type'], 1);
            $this->ppdf->Cell(25, 7, date('m/d/Y', strtotime($row['startdate'])), 1);
            $this->ppdf->Cell(22, 7, date('m/d/Y', strtotime($row['eocdate'])), 1);
            $this->ppdf->Cell(45, 7, ucwords(strtolower($row['position'])), 1);
            $this->ppdf->Cell(110, 7, $stores, 1);
            $this->ppdf->Cell(40, 7, $row['promo_department'], 1);
            $this->ppdf->Ln();
        }

        $this->ppdf->SetFont('Times', 'I', 8);
        $this->ppdf->Cell(0, 10, 'Prepared by: ' . $this->promo_model->getName_employee3($this->systemUser), 0, 0, 'R');

        ob_clean();
        $this->ppdf->Output();
    }

    public function generateTermRepPdf()
    {
        $input = $this->input->post(NULL, TRUE);

        // Set PDF properties
        $this->ppdf->SetAuthor('Nald');
        $this->ppdf->SetTitle('TERMINATION OF CONTRACT REPORT');
        $this->ppdf->SetSubject('TERMINATION OF CONTRACT REPORT');
        $this->ppdf->SetKeywords('TERMINATION OF CONTRACT REPORT');
        $this->ppdf->setPrintHeader(false);

        // Add a page
        $this->ppdf->AddPage('P', 'LETTER');

        $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        if (!empty($input['store'])) {
            $bunit = explode('|', $input['store']);
            $where .= " AND $bunit[1]= 'T'";
        }
        if (!empty($input['promo_department'])) {
            $where .= " AND promo_department = '$input[promo_department]'";
        }
        if (!empty($input['month'])) {
            $month = explode('|', $input['month']);
            $y = date('Y') + 1;
            if ($month[1] == $y) {
                $year = $month[1];
                $date = $year . '-' . $month[0];
            } else {
                $year = date('Y');
                $date = $year . '-' . $month[0];
            }
            $where .= " AND eocdate LIKE '%$date%'";
        }
        if (!empty($input['promo_company'])) {
            $where .= " AND promo_company = '$input[promo_company]'";
        }
        $where .= " AND current_status = 'Active' AND hr_location = 'asc'";
        $no = 0;
        $table1 = 'employee3';
        $table2 = 'promo_record';
        $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
        $order = "name|ASC";
        $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
        foreach ($query as $row) {
            $no++;
            $bUs = $this->promo_model->locate_promo_bu('asc');
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {


                    $this->ppdf->SetFont('Times', 'B', 12);
                    $this->ppdf->Cell(85);

                    $this->ppdf->Ln(5);
                    $this->ppdf->SetFont('Times', 'B', 12);
                    $this->ppdf->Cell(0, 5, 'NOTICE OF TERMINATION', 0, 0, 'C');
                    $this->ppdf->Ln(5);
                    $this->ppdf->SetFont('Times', '', 12);
                    $this->ppdf->Cell(0, 5, 'For Promodiser - Merchandiser', 0, 0, 'C');
                    $this->ppdf->Ln(5);
                    $this->ppdf->Cell(0, 5, 'Assigned at ' . ucwords(strtolower($bu['business_unit'])) . '', 0, 0, 'C');

                    $this->ppdf->Ln(10);
                    $this->ppdf->SetX(150);
                    $this->ppdf->SetFont('Times', 'B', 11);
                    $this->ppdf->Cell(1, 7, 'Date:');
                    $this->ppdf->SetX(160);
                    $this->ppdf->SetFont('Times', 'U', 11);
                    $this->ppdf->Cell(30, 7, date('F d, Y'));

                    $this->ppdf->Ln(10);
                    $this->ppdf->Cell(5);
                    $this->ppdf->SetFont('Times', 'B', 11);
                    $this->ppdf->Cell(30, 7, 'TO');
                    $this->ppdf->Cell(5);
                    $this->ppdf->SetX(53);
                    $this->ppdf->Cell(5, 7, ':');
                    $this->ppdf->SetFont('Times', 'BU', 11);
                    $this->ppdf->Cell(35, 7, mb_convert_encoding(ucwords(strtolower($row['name'])), '', 'UTF-8'));
                    $this->ppdf->Ln();
                    $this->ppdf->Cell(5);
                    $this->ppdf->SetFont('Times', 'B', 11);
                    $this->ppdf->Cell(30, 7, 'COMPANY/AGENCY:');
                    $this->ppdf->Cell(5);
                    $this->ppdf->SetX(55);
                    $this->ppdf->SetFont('Times', 'B', 11);
                    $this->ppdf->Cell(80, 7, $row['promo_department'] . '-' . $row['promo_company']);
                    $this->ppdf->Ln(10);
                    $this->ppdf->SetFont('Times', '', 11);
                    $this->ppdf->Cell(35);

                    $this->ppdf->Cell(15, 7, 'Please be reminded that according to the Introductory Letter we received from your ');
                    $this->ppdf->Ln(5);
                    $this->ppdf->Cell(5);
                    $this->ppdf->Cell(15, 7, 'company/agency, your assignment on this establishment will expire on');
                    $this->ppdf->Ln(5);
                    $this->ppdf->Cell(5);
                    $this->ppdf->SetFont('Times', 'BU', 12);
                    $this->ppdf->Cell(15, 7, date('l', strtotime($row['eocdate'])) . ' ' . date('F d, Y', strtotime($row['eocdate'])) . '.', 'U');
                    $this->ppdf->Ln();
                    $this->ppdf->SetFont('Times', '', 11);
                    $this->ppdf->Cell(35);
                    $this->ppdf->Cell(15, 7, "In connection with this you are advised to yield all company properties under your care");
                    $this->ppdf->Ln();
                    $this->ppdf->Cell(5);
                    $this->ppdf->MultiCell(175, 5, 'and seek clearance before you leave the company premises of ' . ucwords(strtolower($bu['business_unit'])) . ' at the close of business hours on such day.', 0, 'L');

                    $this->ppdf->Cell(35);
                    $this->ppdf->Cell(15, 7, 'Thank you and good luck!');
                    $this->ppdf->Ln(15);
                    $this->ppdf->SetFont('Times', 'B', 11);
                    $this->ppdf->Cell(5);
                    $this->ppdf->Cell(15, 7, 'MS. MARIA NORA A. PAHANG');
                    $this->ppdf->Ln(5);
                    $this->ppdf->Cell(5);
                    $this->ppdf->Cell(15, 7, 'HRD MANAGER');
                    $this->ppdf->Ln(30);
                }
            }
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $data = array(
                'activity'  => 'Generate Termination of Contract Report of ' . $row['name'],
                'date'      => $date,
                'time'      => $time,
                'user'      => $this->systemUser,
                'username'  => $this->session->userdata('username')
            );
            $this->promo_model->insert_tdA('logs', $data);
        }

        ob_clean();
        $this->ppdf->Output();
    }

    public function generateTermContract()
    {
        $input = $this->input->get(NULL, TRUE);

        // Set PDF properties
        $this->ppdf->SetAuthor('Nald');
        $this->ppdf->SetTitle('TERMINATION OF CONTRACT REPORT');
        $this->ppdf->SetSubject('TERMINATION OF CONTRACT REPORT');
        $this->ppdf->SetKeywords('TERMINATION OF CONTRACT REPORT');
        $this->ppdf->setPrintHeader(false);

        if ($input['type'] == 'forStore') {
            // Add a page
            $this->ppdf->AddPage('P', 'LETTER');
        }

        if (isset($input['emp_id'])) {
            $ids = explode('|', $input['emp_id']);

            foreach ($ids as $emp_id) {
                $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
                $where .= " AND t1.emp_id ='$emp_id'";
                if (!empty($input['store'])) {
                    $bunit = explode('|', $input['store']);
                    $where .= " AND $bunit[1]= 'T'";
                }
                if (!empty($input['promo_department'])) {
                    $where .= " AND promo_department = '$input[promo_department]'";
                }
                if (!empty($input['month'])) {
                    $month = explode('|', $input['month']);
                    $y = date('Y') + 1;
                    if ($month[1] == $y) {
                        $year = $month[1];
                        $date = $year . '-' . $month[0];
                    } else {
                        $year = date('Y');
                        $date = $year . '-' . $month[0];
                    }
                    $where .= " AND eocdate LIKE '%$date%'";
                }
                if (!empty($input['promo_company'])) {
                    $where .= " AND promo_company = '$input[promo_company]'";
                }
                $where .= " AND current_status = 'Active' AND hr_location = 'asc'";

                $no = 0;
                $table1 = 'employee3';
                $table2 = 'promo_record';
                $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
                $order = "name|ASC";
                $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
                foreach ($query as $row) {
                    $no++;
                    $bUs = $this->promo_model->locate_promo_bu('asc');
                    if ($input['type'] == 'forStore') {
                        foreach ($bUs as $bu) {
                            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                            if ($hasBu > 0) {

                                $this->ppdf->SetFont('Times', 'B', 12);
                                $this->ppdf->Cell(85);

                                $this->ppdf->Ln(5);
                                $this->ppdf->SetFont('Times', 'B', 12);
                                $this->ppdf->Cell(0, 5, 'NOTICE OF TERMINATION', 0, 0, 'C');
                                $this->ppdf->Ln(5);
                                $this->ppdf->SetFont('Times', '', 12);
                                $this->ppdf->Cell(0, 5, 'For Promodiser - Merchandiser', 0, 0, 'C');
                                $this->ppdf->Ln(5);
                                $this->ppdf->Cell(0, 5, 'Assigned at ' . ucwords(strtolower($bu['business_unit'])) . '', 0, 0, 'C');

                                $this->ppdf->Ln(10);
                                $this->ppdf->SetX(150);
                                $this->ppdf->SetFont('Times', 'B', 11);
                                $this->ppdf->Cell(1, 7, 'Date:');
                                $this->ppdf->SetX(160);
                                $this->ppdf->SetFont('Times', 'U', 11);
                                $this->ppdf->Cell(30, 7, date('F d, Y'));

                                $this->ppdf->Ln(10);
                                $this->ppdf->Cell(5);
                                $this->ppdf->SetFont('Times', 'B', 11);
                                $this->ppdf->Cell(30, 7, 'TO');
                                $this->ppdf->Cell(5);
                                $this->ppdf->SetX(53);
                                $this->ppdf->Cell(5, 7, ':');
                                $this->ppdf->SetFont('Times', 'BU', 11);
                                $this->ppdf->Cell(35, 7, mb_convert_encoding(ucwords(strtolower($row['name'])), '', 'UTF-8'));
                                $this->ppdf->Ln();
                                $this->ppdf->Cell(5);
                                $this->ppdf->SetFont('Times', 'B', 11);
                                $this->ppdf->Cell(30, 7, 'COMPANY/AGENCY:');
                                $this->ppdf->Cell(5);
                                $this->ppdf->SetX(55);
                                $this->ppdf->SetFont('Times', 'B', 11);
                                $this->ppdf->Cell(80, 7, $row['promo_department'] . '-' . $row['promo_company']);
                                $this->ppdf->Ln(10);
                                $this->ppdf->SetFont('Times', '', 11);
                                $this->ppdf->Cell(35);

                                $this->ppdf->Cell(15, 7, 'Please be reminded that according to the Introductory Letter we received from your ');
                                $this->ppdf->Ln(5);
                                $this->ppdf->Cell(5);
                                $this->ppdf->Cell(15, 7, 'company/agency, your assignment on this establishment will expire on');
                                $this->ppdf->Ln(5);
                                $this->ppdf->Cell(5);
                                $this->ppdf->SetFont('Times', 'BU', 12);
                                $this->ppdf->Cell(15, 7, date('l', strtotime($row['eocdate'])) . ' ' . date('F d, Y', strtotime($row['eocdate'])) . '.', 'U');
                                $this->ppdf->Ln();
                                $this->ppdf->SetFont('Times', '', 11);
                                $this->ppdf->Cell(35);
                                $this->ppdf->Cell(15, 7, "In connection with this you are advised to yield all company properties under your care");
                                $this->ppdf->Ln();
                                $this->ppdf->Cell(5);
                                $this->ppdf->MultiCell(175, 5, 'and seek clearance before you leave the company premises of ' . ucwords(strtolower($bu['business_unit'])) . ' at the close of business hours on such day.', 0, 'L');

                                $this->ppdf->Cell(35);
                                $this->ppdf->Cell(15, 7, 'Thank you and good luck!');
                                $this->ppdf->Ln(15);
                                $this->ppdf->SetFont('Times', 'B', 11);
                                $this->ppdf->Cell(5);
                                $this->ppdf->Cell(15, 7, 'MS. MARIA NORA A. PAHANG');
                                $this->ppdf->Ln(5);
                                $this->ppdf->Cell(5);
                                $this->ppdf->Cell(15, 7, 'HRD MANAGER');
                                $this->ppdf->Ln(30);
                            }
                        }
                    } else {
                        $i = 0;
                        $stores = '';
                        foreach ($bUs as $bu) {
                            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                            if ($hasBu > 0) {
                                $i++;
                                $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                            }
                        }

                        $hrd = $this->promo_model->getName_employee3($this->systemUser);
                        $this->ppdf->AddPage('P', 'LETTER');
                        $this->ppdf->Image(base_url('assets/promo_assets/images/alturas.png'), 40, 10, 120);
                        $this->ppdf->Ln(19);
                        $this->ppdf->Cell(15);
                        $this->ppdf->SetFont('Times', '', 12);
                        $this->ppdf->Cell(0, 7, date('F d, Y'));

                        $this->ppdf->Ln(11);
                        $this->ppdf->Cell(15);
                        $this->ppdf->SetFont('Times', 'B', 12);
                        $this->ppdf->Cell(0, 7, $row['promo_company']);
                        $this->ppdf->Ln(8);
                        $this->ppdf->Cell(15);
                        $this->ppdf->Cell(0, 7, 'ATTENTION:	    PERSONNEL DEPARTMENT');

                        $this->ppdf->Ln(11);
                        $this->ppdf->Cell(15);
                        $this->ppdf->SetFont('Times', '', 12);
                        $this->ppdf->Cell(0, 7, "Dear Sir/Ma'am:");

                        $this->ppdf->Ln(14);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "Please be reminded that based on your Intro Letter, the termination of contract of the " . strtolower($row['position']) . " handling your product, will take effect on the date stated below:", 0, 'L');

                        $this->ppdf->Ln(9);
                        $this->ppdf->Cell(15);
                        $this->ppdf->Cell(45, 7, 'NAME', '1', 0);
                        $getXcomp = $this->ppdf->GetX();
                        $this->ppdf->Cell(63, 7, 'COMPANY', '1', 0);
                        $getXout = $this->ppdf->GetX();
                        $this->ppdf->Cell(45, 7, 'OUTLET', '1', 0);
                        $getXeoc = $this->ppdf->GetX();
                        $this->ppdf->Cell(17, 7, 'EOC', '1', '1');

                        $this->ppdf->SetFont('Times', '', 10);
                        $this->ppdf->Cell(15);
                        $getY = $this->ppdf->GetY();
                        $this->ppdf->MultiCell(45, 10, ucwords(strtolower($row['name'])), '1', 'L');
                        $this->ppdf->SetY($getY);
                        $this->ppdf->SetX($getXcomp);
                        $this->ppdf->MultiCell(63, 10, $row['promo_company'], '1', 'L');
                        $this->ppdf->SetY($getY);
                        $this->ppdf->SetX($getXout);
                        $this->ppdf->MultiCell(45, 10, $stores, '1', 'L');
                        $this->ppdf->SetY($getY);
                        $this->ppdf->SetX($getXeoc);
                        $this->ppdf->MultiCell(17, 10, date('m/d/y', strtotime($row['eocdate'])), '1', 'L');
                        $this->ppdf->Cell(15);

                        $this->ppdf->SetFont('Times', '', 12);
                        $this->ppdf->Ln(8);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "In view of the above, if you find his / her performance commendable for renewal, we would like to request your good office to send us an Introductory Letter. Otherwise, please endorse a possible applicant as replacement.", 0, 'L');

                        $this->ppdf->Ln(5);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "Kindly fax your Introductory Letter in this number (038) 501-9245 or you may email at corporatehrd@alturasbohol.com.", 0, 'L');

                        $this->ppdf->Ln(5);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "Thank you!", 0, 'L');

                        $this->ppdf->Ln(5);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "Very respectfully yours,", 0, 'L');

                        $this->ppdf->SetFont('Times', 'B', 12);
                        $this->ppdf->Ln(5);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "ALTURAS SUPERMARKET CORPORATION", 0, 'L');

                        $this->ppdf->Ln(7);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, ucwords(strtolower($hrd)), 0, 'L');

                        $this->ppdf->SetFont('Times', '', 12);
                        $this->ppdf->Ln(0);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "HRD - Promo Transaction", 0, 'L');

                        $this->ppdf->SetFont('Times', '', 12);
                        $this->ppdf->Ln(5);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "Noted By:", 0, 'L');

                        $this->ppdf->SetFont('Times', 'B', 12);
                        $this->ppdf->Ln(8);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "MS. MARIA NORA A. PAHANG", 0, 'L');

                        $this->ppdf->SetFont('Times', '', 12);
                        $this->ppdf->Ln(0);
                        $this->ppdf->Cell(15);
                        $this->ppdf->MultiCell(180, 7, "HRD Manager", 0, 'L');
                    }
                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    $data = array(
                        'activity'  => 'Generate Termination of Contract Report of ' . $row['name'],
                        'date'      => $date,
                        'time'      => $time,
                        'user'      => $this->systemUser,
                        'username'  => $this->session->userdata('username')
                    );
                    $this->promo_model->insert_tdA('logs', $data);
                }
            }
        }
        ob_clean();
        $this->ppdf->Output();
    }

    public function viewPdf()
    {
        $input = $this->input->get(NULL, TRUE);
        $id = explode('_', $input['id']);
        $code = $id[0];

        if ($code == 'contract') {
            $pdfPath = 'document/contract/' . $input['id'];
        } else if ($code == 'permittowork') {
            $pdfPath = 'document/permit/' . $input['id'];
        } else if ($code == 'duecontracts') {
            $pdfPath = 'document/contract/' . $input['id'];
        }
        if (file_exists($pdfPath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $input['id'] . '"');
            readfile($pdfPath);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo 'File not found';
        }
    }
}

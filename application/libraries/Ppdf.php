 <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    require_once dirname(__FILE__) . '../../third_party/tcpdf/tcpdf.php';
    //  require_once dirname(__FILE__) . '../../third_party/tcpdf/example/tcpdf_include.php';

    class Ppdf extends TCPDF
    {
        function __construct()
        {
            parent::__construct();
        }

        function Footer()
        {
            // Position at 1.8 cm from bottom
            $this->SetY(-18); //-20
            // Arial italic 8
            $this->SetFont('Times', 'I', 8);
            // Page number
            $this->Cell(4);
            $this->Cell(0, 10, 'Date printed: ' . date('M d, Y', strtotime(date("Y-m-d"))), 0, 0, 'L');
            // Position at 1.8 cm from bottom
            $this->SetY(-18); //-20
            // Arial italic 8
            $this->SetFont('Times', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R');
        }
    }

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */

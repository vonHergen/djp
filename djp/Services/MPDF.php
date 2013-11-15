<?php
namespace DJP\Services;

require_once dirname(__FILE__).'/../library/MPDF57/mpdf.php';

class MPDF
{
    protected $mpdf;

    public function __construct()
    {
        $this->mpdf = new \mPDF('utf-8', 'A4');
    }
    
    private function writeHTML($html)
    {
        $this->mpdf->WriteHTML($html);
    }
        
    public function generatePDF($name, $html)
    {        
        $this->writeHTML(utf8_encode($html));
        $this->mpdf->Output(str_replace(" ", "", $name) , "D");
    }
}
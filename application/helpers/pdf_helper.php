<?php

    // require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');


    class pdf_helper extends TCPDF {

        //Page header
        public function Header() {
            // Logo
            $image_file = FCPATH.'assets/img/logopdf.png';
            $this->setJPEGQuality(90);
            $this->Image($image_file, 120, 0, 200, 28, 'PNG', '', 'T', true, 300, 'C', false, false, 0, false, false, false);
            // $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-30, PDF_MARGIN_RIGHT);
            // Set font
            $this->SetFont('helvetica', 'B', 20);
            // Title
            // $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');

            $image_file2 = base_url('assets').'/img/watermart_logo_wika.png';
            $this->SetAlpha(0.3);
            $this->Image($image_file2, 80, 50, 100, 80, 'PNG', '', 'T', true, 300, 'C', false, false, 0, false, true, false);
            
            $image_file3 = base_url('assets').'/img/watermark_pattern.png';
            $this->SetAlpha(0.3);
            $this->Image($image_file3, 0, 0, 100, 100, 'PNG', '', 'T', true, 300, 'R', false, false, 0, false, false, 150);
        }
        
       
     
    }
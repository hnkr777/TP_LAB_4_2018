<?php

include_once "./FPDF/fpdf.php";

class PDF extends FPDF
{
    // Load data
    function LoadData($file)
    {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach($lines as $line)
            $data[] = explode(';',trim($line));
        return $data;
    }
    /*
    // Simple table
    function BasicTable($header, $data)
    {
        // Header
        foreach($header as $col)
            $this->Cell(80,7,$col,1);
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(80,6,$col,1);
            $this->Ln();
        }
    }
    */

    // Simple table modified
    function BasicTable($header, $data)
    {
        $widths = [0,0,0,0,0,0,0];
        
        // Widths data
        foreach($data as $row)
        {
            $counter = 0;
            foreach($row as $key => $col){
                
                if
                (
                    $this->GetStringWidth($col) > $widths[$counter]
                ){
                    $widths[$counter] = round($this->GetStringWidth($col));                    
                }
                
                $counter++;
                
            }
        }
        
        // Widths header
        $counter = 0;
        foreach($header as $col){
            
            if($this->GetStringWidth($col) > $widths[$counter]){
                $widths[$counter] = round($this->GetStringWidth($col));                    
            }
            
            $counter++;
            
        }
        
        // Header
        $counter = 0;
        $this->Cell(80);
        foreach($header as $col){
            $this->Cell($widths[$counter] + 5,7,$col,1);
            $counter++;
        }
        $this->Ln();
        // Data
        
        foreach($data as $row)
        {
            $this->Cell(80);
            $counter = 0;
            foreach($row as $col){
                
                $this->Cell($widths[$counter] + 5, 6 , utf8_decode($col) , 1);
                
                $counter++;
            }
            $this->Ln();
        }
    }
    
    // Better table
    function ImprovedTable($header, $data)
    {
        // Column widths
        $w = array(40, 35, 40, 45);
        // Header
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR');
            $this->Cell($w[1],6,$row[1],'LR');
            $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
            $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
    }
    
    // Colored table
    function FancyTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Header
        $w = array(40, 35, 40, 45);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
            $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
            $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
            $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
    }
}

?>
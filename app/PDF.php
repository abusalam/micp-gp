<?php
namespace App;

use FPDF;

class PDF extends FPDF
{

  function __construct()
  {
    parent::__construct();
    $this->SetAuthor('MICP Gate Pass');
    $this->SetCreator('NIC Malda');
    $this->AddPage();
    $this->AliasNbPages();
    $this->SetAutoPageBreak(true,10); 
  }
  // Page header
  function Header()
  {
    // // Logo
    // $this->Image('logo.png',10,6,30);
    // // Arial bold 15
    // $this->SetFont('Arial','B',15);
    // // Move to the right
    // $this->Cell(80);
    // // Line break
    // $this->Ln(20);

  }

  function FancyHeader($header)
  {
    // Colors, line width and bold font
    $this->SetFillColor(0,128,255);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,128,255);
    $this->SetLineWidth(.3);
    $this->SetFont('Courier','B',11);
    for($i=0;$i<count($header['col_names']);$i++)
    {
      $this->Cell($header['col_widths'][$i],7,$header['col_names'][$i],'T',0,'C',true);
    }
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
  }

  function FancyTable($header, $data)
  {
    $this->FancyHeader($header);

    // Data
    $fill = false;
    $slNo=1;
    foreach($data as $row)
    {
      //dd($row);
      $colNo=0;
      $this->Cell($header['col_widths'][$colNo++],6,$slNo++,'T',0,'R',$fill);
      $this->Cell($header['col_widths'][$colNo++],6,$row['blacklist_no'],'T',0,'R',$fill);
      $this->Cell($header['col_widths'][$colNo++],6, date('d/m/Y', strtotime($row['created_at'])),'T',0,'R',$fill);
      $this->MultiCell($header['col_widths'][$colNo++],6,$row['reason'],'T',0,'L',$fill);
      if(($this->GetY()+6)>$this->PageBreakTrigger)
			{
        // Closing line
        $this->Cell(0,0,'','T',1);  
        $this->FancyHeader($header);
			}
      //$this->Ln();
      $fill = !$fill;
    }
    // Closing line
    $this->Cell(0,0,'','T',1);  
  }

  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(0);
    // Page number
    $this->Cell(0,15,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }
}

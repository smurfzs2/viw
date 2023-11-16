<?php

require('fpdf186/fpdf.php');

include 'viel_connection.php';

// echo $_GET['sqlData'];
$select=($_GET['sqlData']!='')?$_GET['sqlData']:'SELECT * FROM  tbl_viel';

$result = $conn->query($select);
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);


class PDF extends Fpdf
{
    // Page header
    function Header()
    {
        // Arial bold 15
    $this -> SetFont('Arial','B',12);
    $this->SetFillColor(108,65,179, .70);
    $this->SetTextColor(255);
        // Move to the right (for Center Position)

        // header
        $this->Cell(10,10,'ID',1,0,'C',true);
        $this->Cell(40,10,'First Name',1,0,'C',true);
        $this->Cell(40,10,'Last Name',1,0,'C',true);
        $this->Cell(20,10,'Gender',1,0,'C',true);
        $this->Cell(100,10,'Address',1,0,'C',true);
        $this->Cell(30,10,'Birthday',1,0,'C',true);
        $this->Cell(40,10,'Depertment',1,0,'C',true);
        
        // Line break
    $this -> Ln(10);
    }
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
    $this->SetY(-15);
        // Arial italic 8
    $this->SetFont('Arial','B',9);
        // Page number
    $this->Cell(0,10,'Page '.$this -> PageNo().'out of {nb}',0,0,'C');
    }
    
}
    $pdf = new PDF('L','mm','A4');
    /* A4 - 210 * 297 mm */
    $pdf -> AliasNbPages(); // Must for print total no of page
    $pdf -> AddPage();

    $pdf->SetFont('Arial','B',9.5);
    


    $counter=1;

    while($row = $result->fetch_object())
    {
    
    $id = $row->id;
    $firstName = $row->firstName;
    $lastName = $row->lastName;
    $gender = $row->gender;
    $address = $row->address;
    $birthday = $row->birthday;
    $department = $row->departmentName;

    
    
    if($counter%2==0)
    {
        $pdf->SetFillColor(225,170,227, .89);
        $pdf->Cell(10,10,$counter,1,0,'C',true);
        $pdf->Cell(40,10,$firstName,1,0,'C',true);
        $pdf->Cell(40,10,$lastName,1,0,'C',true);
        $pdf->Cell(20,10,($gender)== '0'? "Male":"Female",1,0,'C',true);
        $pdf->Cell(100,10,$address,1,0,'C',true);
        $pdf->Cell(30,10,($birthday),1,0,'C',true);
        $pdf->Cell(40,10,($department),1,0,'C',true);
        $pdf->Ln();


    }else
    {
        $pdf->SetFillColor(253,198,190, .99);
        $pdf->Cell(10,10,$counter,1,0,'C',true);
        $pdf->Cell(40,10,$firstName,1,0,'C',true);
        $pdf->Cell(40,10,$lastName,1,0,'C',true);
        $pdf->Cell(20,10,($gender)== '0'? "Male":"Female",1,0,'C',true);
        $pdf->Cell(100,10,$address,1,0,'C',true);
        $pdf->Cell(30,10,($birthday),1,0,'C',true);
        $pdf->Cell(40,10,($department),1,0,'C',true);
        $pdf->Ln();
    }
    
    $counter ++;
    }

$pdf->Output();

?>
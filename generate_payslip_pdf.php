<?php
require('fpdf/fpdf.php'); // Ensure you have the FPDF library included

$conn = new mysqli('localhost', 'root', '', 'SwiftPay');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$payslip_id = $_GET['id'];
$sql = "SELECT e.name, p.amount, p.date 
        FROM payslips p 
        JOIN employees e ON p.employee_id = e.id 
        WHERE p.id='$payslip_id'";
$result = $conn->query($sql);
$payslip = $result->fetch_assoc();

$conn->close();

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Payslip', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Employee Name: ' . $payslip['name']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Amount: ' . $payslip['amount']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Date: ' . $payslip['date']);

$pdf->Output();
?>

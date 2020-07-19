<?php
$xmlparser = xml_parser_create();
$fp = fopen("mail.xml", "r");
$xmldata = fread($fp, 4096);

// Parse XML data into an array
xml_parse_into_struct($xmlparser, $xmldata, $values);
xml_parser_free($xmlparser);

require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

foreach ($values as $value) {

	$pdf->SetFont('Arial', '', 12);
	$pdf->Ln();
	$val = $value["tag"];
	
	if ($value["tag"] != "NOTE") {
		$pdf->Cell(12, 1, $value["tag"]." : ", 0);
	}
	$pdf->SetTitle($title);
	$pdf->SetAuthor('Edgar');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(12, 1, $value["value"], 0);
	$pdf->Ln();
	$pdf->Ln();
}



fclose($fp);


// Recipient 
$to = 'climathe1@gmail.com'; 
 
// Sender 
$from = 'edgar@gmail.com'; 
$fromName = 'Edgar'; 
 
// Email subject 
$subject = 'PHP Email with Attachment by CodexWorld';  
 
// Attachment file 
$file = $pdf->Output(); 
 
// Email body content 
$htmlContent = ' 
    <p>Good day Mr/Mrs,<br/> please find my attached document. kind regards <br /> Edgar</p> 
'; 
 
// Header for sender info 
$headers = "From: $fromName"." <".$from.">"; 
 
// Boundary  
$semi_rand = md5(time());  
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
 
// Headers for attachment  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
// Multipart boundary  
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
 
// Preparing attachment 
if(!empty($file) > 0){ 
    if(is_file($file)){ 
        $message .= "--{$mime_boundary}\n"; 
        $fp =    @fopen($file,"rb"); 
        $data =  @fread($fp,filesize($file)); 
 
        @fclose($fp); 
        $data = chunk_split(base64_encode($data)); 
        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
        "Content-Description: ".basename($file)."\n" . 
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
    } 
} 
$message .= "--{$mime_boundary}--"; 
$returnpath = "-f" . $from; 
 
// Send email 
$mail = @mail($to, $subject, $message, $headers, $returnpath);  
 
// Email sending status 
echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>"; 
 
?>
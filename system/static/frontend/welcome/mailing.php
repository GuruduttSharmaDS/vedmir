<?php 
$action=$_POST['action'];
if ($action=='sendmail') {
    $to = 'hello@vedmir.com'; 
    $dsubject = ($_POST['dtype']=='0')?"Subscribe":'New Enquiry';
	$email=(isset($_POST['email']))?$_POST['email']:'';
    $dmessage = "";
	if ($_POST['dtype']=='0')
		$dmessage = "A user has requested for your subscription services.Subscriber  Email Id : ".$email;
	else
	{
		$name=(isset($_POST['name']))?$_POST['name']:'';
		$subject=(isset($_POST['subject']))?$_POST['subject']:'';
		$message=(isset($_POST['message']))?$_POST['message']:'';
		
		$dmessage.= "A user has requested for new enquiry following details showing below <br/>";
		$dmessage.= "Name : ".$name ."<br/>";
		$dmessage.= "Email : ".$email ."<br/>";
		$dmessage.= "Subject : ".$subject ."<br/>";
		$dmessage.= "Message : ".$message ."<br/>";
	}
    $from = "hello@vedmir.com";
    $headers = "From: VEDMIR <".$from.">\r\n";
    $headers .= "Reply-To: <".$from.">\r\n";  
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$return['valid']=(mail($to, $dsubject, $dmessage, $headers))?true:false;
}
header('Content-Type: application/json');
echo json_encode($return);
?>
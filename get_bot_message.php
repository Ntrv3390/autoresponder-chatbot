<?php
date_default_timezone_set('Asia/Kolkata');
include('database.inc.php');
$txt=mysqli_real_escape_string($con,$_POST['txt']);
$sql="select answer from chats where question like '%$txt%'";
$res=mysqli_query($con,$sql);
if(mysqli_num_rows($res)>0){
	$row=mysqli_fetch_assoc($res);
	$html=$row['answer'];
}else{
	$html="I was not able to understand that query, will try to get an answer for it in the future.";
}
$added_on=date('Y-m-d h:i:s');
$session_id = $_SESSION["ID"];
mysqli_query($con,"insert into message(message,added_on,type,session_id) values('$txt','$added_on','user','$session_id')");
$added_on=date('Y-m-d h:i:s');
mysqli_query($con,"insert into message(message,added_on,type,session_id) values('$html','$added_on','bot','$session_id')");
echo $html;
?>
<?php
session_start();

// Set the timezone
date_default_timezone_set('Asia/Kolkata');

// Include the database configuration
include('database.inc.php');
$ch = curl_init();
// Retrieve user input
$txt = isset($_POST['txt']) ? mysqli_real_escape_string($con, $_POST['txt']) : '';

// Retrieve session ID
$session_id = $_SESSION['ID'];


curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$postdata=array(
    "model"=>"text-davinci-001",
  "prompt"=> $txt,
  "temperature"=> 0.4,
  "max_tokens"=> 30,
  "top_p"=> 1,
  "frequency_penalty"=> 0,
  "presence_penalty"=> 0
);
$postdata=json_encode($postdata);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization:Bearer YOUR_OPENAI_API_KEY_HERE';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
$result=json_decode($result,true);

$html=$result['choices'][0]['text'];
curl_close($ch);
// Insert user and bot messages into the database
$added_on = date('Y-m-d H:i:s');
mysqli_query($con, "INSERT INTO message (message, added_on, type, session_id) VALUES ('$txt', '$added_on', 'user', '$session_id')");
mysqli_query($con, "INSERT INTO message (message, added_on, type, session_id) VALUES ('$html', '$added_on', 'bot', '$session_id')");

// Convert bot's response to audio
$html_enc = strip_tags($html);
$html_encoded = htmlspecialchars($html_enc);
$html_encoded = rawurlencode($html_encoded);
$html1 = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $html_encoded . '&tl=en-US');
$audio_player = "<audio style='display:none' controls='controls' autoplay><source src='data:audio/mpeg;base64," . base64_encode($html1) . "'></audio>";

// Send the response to the client
echo $html;
echo $audio_player;
?>
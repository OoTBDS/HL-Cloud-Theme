<?php
require_once "Mail.php";
$username = $_POST['username'];
$displayname = $_POST['displayname'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$email = $_POST['email'];
if($password1 != $password2)
{
    header('Location: /register.html');
	die();
}
if(strlen($username) > 30)
{
    header('Location: /register.html');
	die();
}

$password = hash('sha1', $password1);

$mysqli = new mysqli('localhost', 'OotBDS', '63a912762ab77ba7d26c794de49d37bdb6cded534a14c42d0d15d2698e7b3252', 'OoTBDS_v8_1_3');

if (mysqli_connect_errno()) {
    exit();
}
$username = $mysqli->real_escape_string($username);

$unique = $mysqli->query("SELECT * FROM oc_users WHERE uid = '$username'");
if (!$unique) {
  die($mysqli->error);
}
if ($unique->num_rows > 0) {
//   echo "Duplicate";
   die("Duplicate");
}
$query = "INSERT INTO oc_users ( uid, displayname, password ) VALUES ( '$username', '$displayname', '$password' );";
$query .= "INSERT INTO oc_preferences ( userid, appid, configkey, configvalue ) VALUES ( '$username', 'settings', 'email', '$email' );";
$result = mysqli_multi_query($mysqli, $query);
//$mysqli->query($query);
//$from = "Out of The Box Data Security <benmichael@ootwcs.com>";
$from = "no-reply@ootwcs.com";
$subject = "Welcome to Out of The Box Data Security";
$body = "Thank you for signing up with Out of The Box Data Security, your account is now active. Please feel free to contact us should you need any support or have queries: admin@ootwcs.com.";
$host = "smtp.gmail.com";
$port = "587";
$username = "admin@ootwcs.com";
$password = "48944894";
$headers = array ('From' => $from,
	'To' => $email,
	'Subject' => $subject);

$smtp = Mail::factory('smtp',
array ('host' => $host,
	'port' => $port,
	'auth' => true,
	'username' => $username,
	'password' => $password));

$mail = $smtp->send($email, $headers, $body);

//header('Location: https://console.mygateglobal.com/');
//http_redirect('http://client.ootbds.com/');
?>

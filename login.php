<?php
include_once('../../connectFiles/connect_sr.php');
$google_id = $_POST['google_id'];
$full_name = $_POST['full_name'];
$given_name = $_POST['given_name'];
$family_name = $_POST['family_name'];
$image_url = $_POST['image_url'];
$email = $_POST['email'];
// $current_url = $_POST['current_url'];


$search_for_id = $sr_db->prepare("Select * from Users where google_id= ? ");
$search_for_id->bind_param("s", $google_id);
$search_for_id->execute();
$result = $search_for_id->get_result();

if (mysqli_num_rows($result)==0) {

  $add_user = $sr_db->prepare("Insert into Users (google_id, full_name, given_name, family_name, image_url, email)
  values (?, ?, ?, ?, ?, ?)");
  $add_user->bind_param("ssssss", $google_id, $full_name, $given_name, $family_name, $image_url, $email);
  $add_user->execute();
  $result = $add_user->get_result();
  $user_id = $sr_db->insert_id;


} else {
  $update_user = $sr_db->prepare("UPDATE Users SET full_name = ?, given_name = ?, family_name = ?, image_url = ?, email = ? WHERE google_id = ? ");
  $update_user->bind_param("ssssss", $full_name, $given_name, $family_name, $image_url, $email, $google_id);
$update_user->execute();
$update_user_result = $update_user->get_result();

  $get_user_query = $sr_db->prepare("Select editor, user_id, cas from Users where google_id = ? ");
  $get_user_query->bind_param("s", $google_id);
  $get_user_query->execute();
  $result = $get_user_query->get_result();

  $user = $result->fetch_assoc();
  $editor = $user['editor'];
  $user_id = $user['user_id'];
  $cas = $user['cas'];
}
if(!isset($_SESSION)){session_start();}
$_SESSION['user_id'] = $user_id;
$_SESSION['google_id'] = $google_id;
$_SESSION['given_name'] = $given_name;
$_SESSION['family_name'] = $family_name;
$_SESSION['image_url'] = $image_url;
$_SESSION['email'] = $email;

$_SESSION['logged_in'] = "yes";
$_SESSION['editor'] = $editor;

$_SESSION['cas'] = $cas;


echo  "<meta HTTP-EQUIV='REFRESH' content='0; url=index.php?page=instructions'>";

?>

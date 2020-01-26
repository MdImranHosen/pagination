<?php
//action.php
include 'crud.php';
$object = new Crud();
if(isset($_POST["action"]))
{
 if($_POST["action"] == "Load")
 {
  $record_per_page = 2;
  $page = '';

  if(isset($_POST["page"]))
  {
   $page = $_POST["page"];
  }
  else
  {
   $page = 1;
  }
  $start_from = ($page - 1) * $record_per_page;

  echo $object->get_data_in_table("SELECT * FROM users ORDER BY id DESC LIMIT $start_from, $record_per_page");
  echo '<br /><div align="center">';
  echo $object->make_pagination_link("SELECT * FROM users ORDER by id", $record_per_page);
  echo '</div><br />';

 }
 if($_POST["action"] == "Insert")
 {
  $first_name = mysqli_real_escape_string($object->connect, $_POST["first_name"]);
  $last_name = mysqli_real_escape_string($object->connect, $_POST["last_name"]);
  $image = $object->upload_file($_FILES["user_image"]);
  $query = "INSERT INTO users (first_name, last_name, image) VALUES ('".$first_name."', '".$last_name."', '".$image."')";
  $object->execute_query($query);
  echo 'Data Inserted';
 }
 if($_POST["action"] == "Fetch Single Data")
 {
  $output = '';
  $query = "SELECT * FROM users WHERE id = '".$_POST["user_id"]."'";
  $result = $object->execute_query($query);
  while($row = mysqli_fetch_array($result))
  {
   $output["first_name"] = $row['first_name'];
   $output["last_name"] = $row['last_name'];
   $output["image"] = '<img src="upload/'.$row['image'].'" class="img-thumbnail" width="50" height="35" />';
   $output["user_image"] = $row['image'];
  }
  echo json_encode($output);
 }

 if($_POST["action"] == "Edit")
 {
  $image = '';
  if($_FILES["user_image"]["name"] != '')
  {
   $image = $object->upload_file($_FILES["user_image"]);
  }
  else
  {
   $image = $_POST["hidden_user_image"];
  }
  $first_name = mysqli_real_escape_string($object->connect, $_POST["first_name"]);
  $last_name = mysqli_real_escape_string($object->connect, $_POST["last_name"]);
  $query = "UPDATE users SET first_name = '".$first_name."', last_name = '".$last_name."', image = '".$image."' WHERE id = '".$_POST["user_id"]."'";
  $object->execute_query($query);
  echo 'Data Updated';
  //echo $query;
 }
 
 if($_POST["action"] == "Delete")
 {
  $query = "DELETE FROM users WHERE id = '".$_POST["user_id"]."'";
  $object->execute_query($query);
  echo "Data Deleted";
 }
 
 if($_POST["action"] == "Search")
 {
  $search = mysqli_real_escape_string($object->connect, $_POST["query"]);
  $query = "SELECT * FROM users 
  WHERE first_name LIKE '%".$search."%' 
  OR last_name LIKE '%".$search."%' 
  ORDER BY id DESC";
  echo $object->get_data_in_table($query);  
 }
 
}
?>
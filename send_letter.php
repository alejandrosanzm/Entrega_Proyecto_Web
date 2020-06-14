<?php
// error_reporting(0);

if(isset($_POST["submit"])) {
  require 'system/encrypt.php';
  require './system/connection.php';
  $name = $_POST["name"];
  $profile = isset($_POST['profile']) ? $_POST ['profile'] : "1";
  $profile= (int)$profile;
  $letter = $_POST['letter'];
  $public = 0;
  $image_path = "";

  if(isset($_POST['public'])){
    $public = 1 ;
  } else {
    $letter = $encriptar($_POST['letter']);
  }

  echo "nya  .$profile.";
  $stmt = $conn->prepare("INSERT INTO `letters` (`id`, `writer`, `letter`, `date`, `profile`, `public`, `public_likes`, `image`)
  VALUES (NULL, ?, ? ,current_timestamp(), ? , ?, 0 ,'');");
  //  $stmt->bind_param('ssii', $name, $letter, $profile, $public);
  $stmt->bind_param('ssii', $name, $letter, $profile , $public);
  $stmt->execute();
  $post_id = $conn->insert_id;

  if ($_FILES["image"]["size"]>0) {
     $exist = true;
     do {
        $randnum = (rand(42,4200)*rand(42,4200));
        $bbdd_name = $post_id.'p'.$randnum.'s'.basename($_FILES["image"]["name"]);
        $image_path = "./res/img/".$bbdd_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            echo "The file has been uploaded.<br>";
            chmod($image_path, 0777);
            $exist = false;
        }
      } while($exist);

    $sql = "UPDATE letters SET image='".$bbdd_name."' WHERE id=".$post_id;
    if ($conn->query($sql) === TRUE) {
      echo "Cons OK";
    } else {
      echo "Error at set post image<br>";
      header("Location: index.php?post_error=error_at_set_post_image");
    }$sql = "UPDATE letters SET image='".$bbdd_name."' WHERE id=".$post_id;
    if ($conn->query($sql) === TRUE) {
      echo "Cons OK";
    } else {
      echo "Error at set post image<br>";
      header("Location: index.php?post_error=error_at_set_post_image");
    }
  }

  // if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
  //     echo "The file has been uploaded.<br>";
  // } else {
  //     echo "Sorry, there was an error uploading your file.<br>";
  //     header("Location: index.php?post_error=error_at_upload_image");
  // }

  // Añadimos la ruta de la imagen al post
  $sql = "UPDATE letters SET image='".$bbdd_name."' WHERE id=".$post_id;
  if ($conn->query($sql) === TRUE) {
    echo "Cons OK";
  } else {
    echo "Error at set post image<br>";
    header("Location: index.php?post_error=error_at_set_post_image");
  }


  require 'validate.php';


  $conn->close();
  header("Location: index.php?post_submit=Post_Sended");
}
?>

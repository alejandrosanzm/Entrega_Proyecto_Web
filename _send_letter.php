<?php
  // error_reporting(0);

 if(isset($_POST["submit"])) {
    require 'system/encrypt.php';
    require './system/connection.php';
    $name = $_POST["name"];
    $profile = isset($_POST['profile']) ? $_POST ['profile'] : "1";
    $profile= (int)$profile;
    $letter = openssl_encrypt($_POST["letter"], $encMethod, $encPass, true, $encIv);
    $public = 0;
    $image_path = "";

  if(isset($_POST['public'])){
    $public = 1 ;
  }

      echo "nya  .$profile.";
      $stmt = $conn->prepare("INSERT INTO `letters` (`id`, `writer`, `letter`, `date`, `profile`, `public`, `public_likes`, `image`)
        VALUES (NULL, ?, ? ,current_timestamp(), ? , ?, 0 ,'prov_image');");
    //  $stmt->bind_param('ssii', $name, $letter, $profile, $public);
      $stmt->bind_param('ssii', $name, $letter, $profile , $public);
      $stmt->execute();
      $post_id = $conn->insert_id;
      $randnum = (rand(42,4200)^2);
    $image_path = "./res/img/".$post_id.'p'.$randnum.'s'.basename($_FILES["image"]["name"]);
    $bbdd_name = $post_id.'p'.$randnum.'s'.basename($_FILES["image"]["name"]);

    while(file_exists($image_path)) {
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
          echo "The file has been uploaded.<br>";
      }
      else {
          echo "Sorry, there was an error uploading your file.<br>";
          $randnum = (rand(42,4200)^2);
        $image_path = "./res/img/".$post_id.'p'.$randnum.'s'.basename($_FILES["image"]["name"]);
        $bbdd_name = $post_id.'p'.$randnum.'s'.basename($_FILES["image"]["name"]);

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

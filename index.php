<?php

  $uploadDir    = "uploads/";
  $fileUploaded = false;
  $uniqueId     = uniqid( "", true );

  function createDir( $uploadPath, $uniqueDirName ) {
    mkdir( ($uploadPath . $uniqueDirName), 0755 );
    return $uniqueDirName;
  }
  
  function sortedFilesArray( $unSortedFilesArray ) {
    $sortedArray = [];
    $filesCount  = count( $unSortedFilesArray[ "name" ] );
    $filesKeys   = array_keys( $unSortedFilesArray );

    for ( $i = 0; $i < $filesCount; $i++ ) {
      foreach ( $filesKeys as $keys ) {
        $sortedArray[$i][$keys] = $unSortedFilesArray[$keys][$i];
      }
    }

    return $sortedArray;
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log filter</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/styles/normalize.min.css">
  <link rel="stylesheet" href="assets/styles/bulma.min.css">
  <link rel="stylesheet" href="assets/styles/styles.min.css">

  <!-- Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500&display=swap">
</head>
<body class="upload-page">

  <header>
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item logo" href="index.php">Log filter</a>

        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div class="navbar-menu">
        <div class="navbar-end">
          <div class="navbar-item">
            <a href="index.php" class="navbar-item menu-item is-active">Upload</a>
            <a href="filter.php" class="navbar-item menu-item">Filter</a>
          </div>
        </div>
      </div>
      </div>
    </nav>
  </header>

  <section class="section">
      <div class="upload-area">
        <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
          <div class="drop-zone">
            <input type="file" name="file[]" multiple>
            <p>Drop your files here or click this area!</p>
          </div>
          <button class="btn" type="submit" name="submit">Upload</button>
        </form>
      </div>
  </section>

  

  <?php
    if ( isset( $_POST[ "submit" ] ) ) {
      $files      = sortedFilesArray($_FILES[ "file" ]);
      $createdDir = createDir( $uploadDir, $uniqueId );

      for ( $i = 0; $i < count( $files ); $i++ ) {
        if ( $files[ $i ][ "error" ] ) {
          echo "There was an error uploading your files!";
        } else {
          $extentions = [ "log", "txt" ];
          $fileExt    = explode( ".", $files[ $i ][ "name" ] );
          $fileExt    = end( $fileExt );

          if ( !in_array( $fileExt, $extentions ) ) {
            echo "File format is not supported!";
          } else {
            if ( $files[ $i ][ "size" ] > 10000000 ) {
              echo "File size is too big!";
            } else {
              move_uploaded_file( $files[ $i ][ "tmp_name" ], $uploadDir . $createdDir . "/" . $files[ $i ][ "name" ] );

              if ( $fileUploaded === false ) {
                $fileUploaded = true;
                header( "Location: filter.php?dir=" . $createdDir );
              }
            }
          }
        }
      }
    }
  ?>

  <!-- Scripts -->
  <script src="assets/js/jquery-3.4.1.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>

<?php

  $uploadDir    = "uploads/";

  if ( isset($_GET["dir"]) ) {
    $dirName    = $_GET["dir"];
  } else {
    $dirName    = "";
  }

  if ( isset( $_POST["search"] ) ) {
    $searchfor  = $_POST["search"];
  } else {
    $searchfor  = "";
  }

  $dirPath      = $uploadDir . $dirName;
  $scannedDir   = scandir( $dirPath );
  

  function filterFiles( $directory ) {
    $new_array = [];

    for ( $i = 0; $i < count( $directory ); $i++ ) {
      if ( $directory[$i] !== "." && $directory[ $i ] !== ".." ) {
        array_push( $new_array, $directory[ $i ] );
      }
    }
    
    return $new_array;
  }

  

  $scannedFiles = filterFiles( $scannedDir );

  // escape special characters in the query
  $pattern = preg_quote( $searchfor, "/" );

  // finalise the regular expression, matching the whole line
  // $pattern = "/^.*$pattern.*\$/m";
  $pattern = "/^.*$pattern.*\$/mi";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filter</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/styles/normalize.min.css">
  <link rel="stylesheet" href="assets/styles/bulma.min.css">
  <link rel="stylesheet" href="assets/styles/styles.min.css">

  <!-- Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500&display=swap">
</head>
<body>

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
            <a href="index.php" class="navbar-item menu-item">Upload</a>
            <a href="filter.php" class="navbar-item menu-item is-active">Filter</a>
          </div>
        </div>
      </div>
      </div>
    </nav>
  </header>
  
  <div class="container">
    <section class="section">
      <form action="" method="POST">
        <input type="text" class="input" placeholder="Search texts" name="search" required>
        <button class="btn" type="submit" name="submit">Search</button>
      </form>
    </section>

    <section class="section">
      <div class="code-container">
        <?php
          echo "<pre>";
          echo "<code>";

          if ( isset( $_GET["dir"] ) ) {
            if ( strlen( $_GET["dir"] ) < 23 ) {
              echo "Upload from Upload page!";
            } else {
              if ( strlen( $searchfor ) < 2 ) {
                echo "Put some text to search!";
              } else {

                for ( $i = 0; $i < count( $scannedFiles ); $i++ ) {
                  // get the file contents, assuming the file to be readable (and exist)
                  $contents = file_get_contents( $dirPath . "/" . $scannedFiles[$i] );
      
                  if ( preg_match_all( $pattern, $contents, $matches ) ){
                    echo "<b class='name'>" . $scannedFiles[$i] . "</b>" . "\n";
                    echo htmlspecialchars( implode( "\n", $matches[0] ) );
                    echo "\n\n";
                  }
                  else {
                    echo "<b class='name'>No matches found in " . $scannedFiles[$i] . ".</b>" . "\n\n";
                  }
                }

              }
            }
          } else {
            echo "Upload files from Upload page!";
          }
          
          echo "</code>";
          echo "</pre>";
        ?>
      </div>
    </section>
  </div>

  <!-- Scripts -->
  <script src="assets/js/jquery-3.4.1.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>

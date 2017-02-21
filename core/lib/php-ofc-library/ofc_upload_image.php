<?php

//
// In Open Flash Chart -> save_image debug mode, you
// will see the 'echo' text in a new window.
//


exit(); // NS

// default path for the image to be stored //
$default_path = '../tmp-upload-images/';

if (!file_exists($default_path)) mkdir($default_path, 0777, true);

// NS
$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_URL);
// full path to the saved image including filename //
$destination = $default_path . basename( $_GET[ 'name' ] );

echo 'Saving your image to: '. $destination;
exit();


//
// PHP5:
//


// default path for the image to be stored //
$default_path = 'tmp-upload-images/';

if (!file_exists($default_path)) mkdir($default_path, 0777, true);

// full path to the saved image including filename //
$destination = $default_path . basename( $_FILES[ 'Filedata' ][ 'name' ] );

// move the image into the specified directory //
if (move_uploaded_file($_FILES[ 'Filedata' ][ 'tmp_name' ], $destination)) {
    echo "The file " . basename( $_FILES[ 'Filedata' ][ 'name' ] ) . " has been uploaded;";
} else {
    echo "FILE UPLOAD FAILED";
}


?>

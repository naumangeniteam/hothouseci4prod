<?php 
require('../../connectors/php/config.php');

if (isset($_REQUEST['fileUrl'], $_REQUEST['fileName'])) {

    $filename = basename($_REQUEST['fileName']);

    // Strict filename validation
    if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
        http_response_code(400);
        exit('Invalid file name');
    }

    $fileurl = str_replace('/'.$filename, '', $_REQUEST['fileUrl']);
    $fileurlarray = explode('/', $fileurl);

    if (end($fileurlarray) === 'file') {
        @unlink($Config['FileTypesAbsolutePath']['File'].$filename);
    } elseif (end($fileurlarray) === 'image') {
        @unlink($Config['FileTypesAbsolutePath']['Image'].$filename);
    } elseif (end($fileurlarray) === 'flash') {
        @unlink($Config['FileTypesAbsolutePath']['Flash'].$filename);
    } elseif (end($fileurlarray) === 'media') {
        @unlink($Config['FileTypesAbsolutePath']['Media'].$filename);
    }

    echo htmlentities($filename, ENT_QUOTES, 'UTF-8');
}
die;

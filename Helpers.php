<?php
/**
 * Created by PhpStorm.
 * User: Nampth
 * Date: 1/19/2021
 * Time: 5:06 PM
 */

/**
 * curl submit file from PHP to remote API with base64 image data
 * param @url: remote API path
 * param @files: array File with format [['file_data'=> '...' , 'name'=>'...'],...]
 */
function postFile($url, $files)
{
    $eol = "\r\n"; //default line-break for mime type
    $BOUNDARY = md5(time()); //random boundaryid, is a separator for each param on my post curl function
    $BODY = ""; //init my curl body

    foreach ($files as $file) {
        $BODY .= '--' . $BOUNDARY . $eol; // start 2nd param,
        $BODY .= 'Content-Disposition: form-data; name="' . $file['name'] . '"; filename="' . $file['name'] . '"' . $eol; //first Content data for post file, remember you only put 1 when you are going to add more Contents, and 2 on the last, to close the Content Instance
        $BODY .= 'Content-Type: application/octet-stream;' . $eol; //Same before row
        $BODY .= 'Content-Transfer-Encoding: base64;' . $eol . $eol; // we put the last Content and 2 $eol,
        $BODY .= base64_decode($file['data']) . $eol; // we write the Base64 File Content and the $eol to finish the data,

    }

    $BODY .= '--' . $BOUNDARY . '--' . $eol . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.

    $ch = curl_init(); //init curl
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: ' . TOKEN_DEMO,
        "Content-Type: multipart/form-data; boundary=" . $BOUNDARY) //setting our mime type for make it work on $_FILE variable
    );
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/1.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0'); //setting our user agent
    curl_setopt($ch, CURLOPT_URL, $url); //setting our api post url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // call return content
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_POST, true); //set as post
    curl_setopt($ch, CURLOPT_POSTFIELDS, $BODY); // set our $BODY


    $response = curl_exec($ch); // start curl navigation

    return $response;

}
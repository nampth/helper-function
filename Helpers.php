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

if (!function_exists('post_file')) {
    function post_file($url, $files)
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
}


/**
 * remove unicode character
 * param @input: input string which you want to format
 * param @removeSpace: remove white space or not
 * param @$specialCharacter: remove special character or not
 */
if (!function_exists('remove_unicode_characters')) {
    function remove_unicode_characters($input, $removeSpace = true, $specialCharacter = true)
    {
        $str = trim($input);
        $str = preg_replace('/a|à|á|ả|ã|ạ|ă|ằ|ắ|ẳ|ẵ|ặ|â|ầ|ấ|ẩ|ẫ|ậ|A|À|Á|Ả|Ã|Ạ|Ă|Ằ|Ắ|Ẳ|Ẵ|Ặ|Â|Ầ|Ấ|Ẩ|Ẫ|Ậ/', 'a', $str);
        $str = preg_replace('/e|è|é|ẻ|ẽ|ẹ|ê|ề|ế|ể|ễ|ệ|E|È|É|Ẻ|Ẽ|Ẹ|Ê|Ề|Ế|Ể|Ễ|Ệ/', 'e', $str);
        $str = preg_replace('/u|ù|ú|ủ|ũ|ụ|ư|ừ|ứ|ử|ữ|ự|U|Ù|Ú|Ủ|Ũ|Ụ|Ư|Ừ|Ứ|Ử|Ữ|Ự/', 'u', $str);
        $str = preg_replace('/o|ò|ó|ỏ|õ|ọ|ô|ồ|ố|ổ|ỗ|ộ|ơ|ờ|ớ|ở|ỡ|ợ|O|Ò|Ó|Ỏ|Õ|Ọ|Ô|Ồ|Ố|Ổ|Ỗ|Ộ|Ơ|Ờ|Ớ|Ở|Ỡ|Ợ/', 'o', $str);
        $str = preg_replace('/i|ì|í|ỉ|ĩ|ị|I|Ì|Í|Ỉ|Ĩ|Ị/', 'i', $str);
        $str = preg_replace('/y|ỳ|ý|ỷ|ỹ|ỵ|Y|Ỳ|Ý|Ỷ|Ỹ|Ỵ/', 'y', $str);
        $str = preg_replace('/d|đ|D|Đ/', 'd', $str);
        if ($specialCharacter) {
            $str = preg_replace('/\,|\.|\-/', '', $str);
        }
        if ($removeSpace) {
            $str = preg_replace('/\s+/', '', $str);
        }
        $str = strtolower($str);
        return $str;
    }
}

/**
 * send data to dropbox
 */
if (!function_exists(send_data_to_dropbox)) {
    function send_data_to_dropbox()
    {
        $content = file_get_contents(storage_path('...'));
        $ch = curl_init();
        $fileName = date('Y-m-d') . "-....";

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . env('DROPBOX_ACCESS_TOKEN'),
            'Content-Type: application/octet-stream',
            "Dropbox-API-Arg: {\"path\": \"/backup-data/$fileName\",\"mode\": \"overwrite\",\"autorename\": false,\"mute\": false,\"strict_conflict\": false}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/upload');

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
    }
}
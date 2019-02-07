<?php namespace Kuldsuda\Kuldsuda\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Cms\Classes\Theme;
use Kuldsuda\Kuldsuda\Models\Acknowledgeduser as User;
use Mail;

/**
 * Acknowledgeduser Back-end Controller
 */
class Acknowledgeduser extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Kuldsuda.Kuldsuda', 'kuldsuda', 'acknowledgeduser');
    }

    public function testController()
    {
        return "test";
    }

    public function saveImage()
    {
        define('UPLOAD_DIR', './/themes//kuldsuda//assets//images//genereeritud_tunnustused//');
        $img = post('imgBase');
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $fileid = uniqid();
        $filename = $fileid . '.png';
        $file = UPLOAD_DIR . $filename;
        while(file_exists($file)){
            $filename = uniqid() . '.png';
            $file = UPLOAD_DIR . $filename;
        }
        $success = file_put_contents($file, $data);

        $this->bindPictureToUser($file, post('lineId'));

        $this->createImageLandingPage($fileid, post('lang'));

        echo $filename;
    }

    private function bindPictureToUser($pictureLocation, $lineId)
    {
        $acknowledgedUsers = User::where('id', $lineId)->first();
        $acknowledgedUsers->picture_location = $pictureLocation;
        $acknowledgedUsers->save();
    }

    public function sendEmail()
    {
        $vars = ['senderEmail' => post('senderEmail'),
                 'receiverEmail' => post('receiverEmail'),
                 'imageSrc' => post('imageSrc'),
                 'messageBody' => post('messageBody'),
                 'senderName' => post('senderName')];

        Mail::send('kuldsuda.kuldsuda::mail.message', $vars, function ($message) {
            $message->to(post('receiverEmail'));
            $message->from(post('senderEmail'), post('senderName'));
            $message->subject('Oled tunnustatud');
            $message->attach('.//themes//kuldsuda//assets//images//genereeritud_tunnustused//'.post('imageSrc'));
        });
    }

    public function saveUserAnswer()
    {
        $acknowledgedUsers = User::where('id', post('id'))->first();
        $acknowledgedUsers->name = post('senderName');
        $acknowledgedUsers->sender_email = post('senderEmail');
        $acknowledgedUsers->sent_type = post('sentTo');
        $acknowledgedUsers->save();
    }

    public function saveRecognition()
    {
        $acknowledgedUsers = new User();
        $acknowledgedUsers->email = post('email');
        $acknowledgedUsers->reason = post('reason');
        $acknowledgedUsers->picture_type = post('pictureType');
        $acknowledgedUsers->acknowledged_name = post('recognizedName');
        $acknowledgedUsers->language = post('lang');
        $acknowledgedUsers->save();
        echo $acknowledgedUsers->id;
    }

    public function getTolge()
    {
        return User::where('kood', post('kood'))->get([post('keel').'_sisu']);
    }

    private function createImageLandingPage($fileId, $languageId) {
        define('UPLOAD_DIR_HTML', './/themes//kuldsuda//pages//');
        $newFilename = $fileId . ".htm";
        $pictureFile = $fileId . ".png";
        $htmlFile = UPLOAD_DIR_HTML . $newFilename;

        $myFile = fopen($htmlFile, 'w');
        chmod($htmlFile, 0755);

        if($languageId == 'ru') {
            $txt = 'title = "Tunnustused"
url = "/'.$fileId.'"
is_hidden = 0
==
<!doctype HTML>
<html>
    <head>
        <link rel="stylesheet" href="./themes/kuldsuda/assets/styles/style.css" type="text/css">
        <link rel="stylesheet" href="./themes/kuldsuda/assets/scripts/bootstrap/css/bootstrap.min.css" type="text/css">

        <meta property="og:title"              content="золотое сердце" />
        <meta property="og:description"        content="Награди коллегу или образец подражания!!" />
        <meta property="og:image" 	   		   content="https://www.kuldsuda.ee/themes/kuldsuda/assets/images/genereeritud_tunnustused/'.$pictureFile.'" />
        <meta property="og:image:width" 	   content="1200" /> 
        <meta property="og:image:height" 	   content="630" />

        <title>Tunnustatud kolleeg</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="shortcut icon" type="image/x-icon" href="./themes/kuldsuda/assets/images/Fav-01.png" />				
        
        <script>
            function changeUrl() {
                window.location.href="https://www.kuldsuda.ee/ru";
            }						
        </script>
    </head>
    <body>
        <div align="center">
            <img class="col-8" src="./themes/kuldsuda/assets/images/genereeritud_tunnustused/'.$pictureFile.'" />
            <button onclick="changeUrl()" id="button" class="forwardButton" align="center">Награди коллегу или</button>
        </div>
    </body>
</html>';

        } else {

            $txt = 'title = "Tunnustused"
url = "/'.$fileId.'"
is_hidden = 0
==
<!doctype HTML>
<html>
    <head>
        <link rel="stylesheet" href="./themes/kuldsuda/assets/styles/style.css" type="text/css">
        <link rel="stylesheet" href="./themes/kuldsuda/assets/scripts/bootstrap/css/bootstrap.min.css" type="text/css">

        <meta property="og:title"              content="Kuldsüda" />
        <meta property="og:description"        content="Tunnusta head kolleegi või märgatud eeskuju!" />
        <meta property="og:image" 	   		   content="https://www.kuldsuda.ee/themes/kuldsuda/assets/images/genereeritud_tunnustused/'.$pictureFile.'" />
        <meta property="og:image:width" 	   content="1200" /> 
        <meta property="og:image:height" 	   content="630" />

        <title>Tunnustatud kolleeg</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="shortcut icon" type="image/x-icon" href="./themes/kuldsuda/assets/images/Fav-01.png" />				
        
        <script>
            function changeUrl() {
                window.location.href="https://www.kuldsuda.ee/ru";
            }						
        </script>
    </head>
    <body>
        <div align="center">
            <img class="col-8" src="./themes/kuldsuda/assets/images/genereeritud_tunnustused/'.$pictureFile.'" />
            <button onclick="changeUrl()" id="button" class="forwardButton" align="center">Soovin ka kuldsüdamega tunnustada</button>
        </div>
    </body>
</html>';
        }

        fwrite($myFile, $txt);
        fclose($myFile);
    }
}

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
            $message->subject('Oled tunnustatud');
            $message->attach(post('imageSrc'));
        });
    }

    public function saveUserAnswer()
    {
        $acknowledgedUsers = User::where('id', post('lineId'))->first();
        $acknowledgedUsers->name = post('name');
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
        $acknowledgedUsers->save();
        echo $acknowledgedUsers->id;
    }
}

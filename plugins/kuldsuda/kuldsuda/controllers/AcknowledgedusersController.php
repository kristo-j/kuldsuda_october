<?php namespace Kuldsuda\Kuldsuda\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Kuldsuda\Kuldsuda\Models\Acknowledgedusers;

/**
 * Acknowledgedusers Back-end Controller
 */
class AcknowledgedusersController extends Controller
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

        BackendMenu::setContext('Kuldsuda.Kuldsuda', 'kuldsuda', 'acknowledgedusers');
    }

    public function testController()
    {
        return "test";
    }

    public function saveImage()
    {
        define('UPLOAD_DIR', './/themes//kuldsuda//assets//images//genereeritud_tunnustused//');

        $img = post('imageData');
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

        return $file;
    }

    public function sendEmail()
    {

    }

    public function saveUserAnswer()
    {
        $acknowledgedUsers = new Acknowledgedusers();

    }
}

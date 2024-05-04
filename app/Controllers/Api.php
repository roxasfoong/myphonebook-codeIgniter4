<?php

namespace App\Controllers;
use App\Models\Contact_model;
use CodeIgniter\Controller;
use App\Helpers\string_helper;
use CodeIgniter\Security\Security;
use CodeIgniter\Validation\ValidationInterface;
use CodeIgniter\Validation\Validation;
use App\Validation\MyRules;

class Api extends BaseController
{
    protected $contact_model;
    protected $session;
    protected $validation;
    protected $request;
    protected $security;
    protected $helpers = ['string_helper'];


    public function __construct()
    {
        $this->contact_model = new Contact_model();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->security = service('security');
        helper(['string_helper']);

    }

    public function c_seeder($number)
    {
        if (!session()->has('user_id')) {
            $this->session->setFlashdata('error', 'Session Expired: Please Login Again.');
            return redirect()->to('login')->withInput();
        }

        $number = intval($number);
        if ($this->contact_model->load_database()) {
            for ($x = 0; $x < $number; $x++) {


                $data = array(
                    'user_id' => session()->get('user_id'),
                    'name' => $this->generate_random_string(14,'alpha'),
                    'address' => $this->generate_random_string(50,'alnum'),
                    'email' => $this->generate_random_email(15,'alnum'),
                    'phone_number' => $this->generate_random_string(20,'numeric'),
                    'image_location' => '/assets/img/empty-profile-picture.webp',
                    'remark' => $this->generate_random_string(30,'alpha')
                );



                if ($this->contact_model->insert_contact($data)) {
                    echo "Successfully Insert to Database <br>";
                } else {
                    echo "Fail to Insert to Database <br>";
                }

                sleep(1);
            }
        }
    }

    public function add_contact()
    {
        helper('validation_helper');
        if (!session()->has('user_id')) {
            session()->getFlashdata('error', 'Session Expired. Please Login Again');
            return redirect()->to('login')->withInput();
        }

        if ($this->contact_model->load_database()) {
            $unsupportedMessage = '';
            if ($this->request->isAJAX()) {

                $validationRules = [
                    'name' => 'required|trim',
                    'email' => 'valid_email|trim',
                    'address' => 'trim',
                    'phone_number' => 'numeric|trim|required',
                    'remark' => 'trim',
                    'image_location' => [
                        'label' => 'Image',
                        'rules' => 'validateImageSize',
                        'errors' => [ 
                            'validateImageSize' => 'The {field} size must not exceed 10 MB.',
                        ],
                    ],

                ];



                if ($this->validation->setRules($validationRules)->withRequest($this->request)->run()===false) {
                    $response['status'] = 'error';
                    $errors = $this->validation->getErrors();
                    $errorString = '<ul>';
                    foreach ($errors as $field => $error) {
                        $errorString .= '<li>' . $error . '</li>';
                    }
                    $errorString .= '</ul>';
                    $response['message'] = $errorString;
                    echo json_encode($response);
                } else {

                    $raw_data = $this->request->getPost();
                    $file = fopen(FCPATH . 'log/log.txt', 'w');
                    ob_start();
                    var_dump($raw_data);
                    fwrite($file, ob_get_clean());
                    fclose($file);
                    $raw_data['user_id'] = session()->get('user_id');

                    if (!empty($_FILES['image_location']['name'])) {

                        $image_location = $_FILES['image_location']['tmp_name'];
                        $image_type = $_FILES['image_location']['type'];


                        if (file_exists($image_location)) {
                            // Get the filesize in bytes
                            //$filesize = filesize($image_location);

                            // Convert filesize to a human-readable format (e.g., KB or MB)
                            //$filesize_kb = $filesize / 1024;



                            $random_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.webp';
                            $img_path = 'assets/contact_image/' . $random_filename;
                            $file_path = FCPATH . $img_path;


                            switch ($image_type) {
                                case 'image/jpeg':
                                    $original_image = imagecreatefromjpeg($image_location);
                                    break;
                                case 'image/png':
                                    $original_image = imagecreatefrompng($image_location);
                                    break;
                                case 'image/gif':
                                    $original_image = imagecreatefromgif($image_location);
                                    break;
                                case 'image/bmp':
                                    $original_image = imagecreatefrombmp($image_location); // Custom function to create image from BMP (not directly supported by GD)
                                    break;
                                case 'image/xbm':
                                    $original_image = imagecreatefromxbm($image_location); // Create image from XBM
                                    break;
                                case 'image/xpm':
                                    $original_image = imagecreatefromxpm($image_location); // Create image from XPM
                                    break;
                                case 'image/webp':
                                    $original_image = imagecreatefromwebp($image_location); // Create image from WebP
                                    break;
                                case 'image/tiff':
                                case 'image/tif':
                                    $original_image = imagecreatefromtiff($image_location); // Create image from TIFF
                                    break;
                                case 'image/pcx':
                                    $original_image = imagecreatefrompcx($image_location); // Create image from PCX
                                    break;
                                case 'image/vnd.wap.wbmp':
                                    $original_image = imagecreatefromwbmp($image_location); // Create image from WBMP
                                    break;
                                case 'image/ico':
                                case 'image/icon':
                                    $original_image = imagecreatefromico($image_location); // Create image from ICO
                                    break;

                                default:
                                    $raw_data['image_location'] = '/assets/img/empty-profile-picture.webp';
                                    $unsupportedMessage = 'File Type: ' .  $image_type . 'is not supported.';
                                    break;
                            }

                            $new_width = 200;
                            $new_height = 250;
                            $resized_image = imagecreatetruecolor($new_width, $new_height);
                            imagecopyresampled($resized_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, imagesx($original_image), imagesy($original_image));
                            $quality = 10;
                            imagewebp($resized_image, $file_path, $quality);
                            imagedestroy($original_image);
                            imagedestroy($resized_image);

                            $raw_data['image_location'] = $img_path;
                        } else {

                            $raw_data['image_location'] = '/assets/img/empty-profile-picture.webp';
                        }
                    } else {
                        $raw_data['image_location'] = '/assets/img/empty-profile-picture.webp';
                    }
                   


                    // Call the model method to insert the contact into the database
                    try {
                        $result = $this->contact_model->insert_contact($raw_data);

                        // Check if the contact was successfully inserted
                        if ($result) {
                            // Send success response
                            $response['status'] = 'success';
                            $response['message'] = 'Contact added successfully. <br>' . $unsupportedMessage;
                        } else {
                            // Send error response
                            $response['status'] = 'error';
                            $response['message'] = 'Unable to add due to duplicated phone number : ' . $raw_data['phone_number'];
                        }
                        echo json_encode($response);
                    } catch (Exception $e) {

                        echo json_encode($response);
                        // Check if the error is due to duplicate entry
                        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            // Handle duplicate entry error
                            http_response_code(400); // or any other appropriate code

                            // Prepare the error response
                            $response = array(
                                'status' => 'error',
                                'message' => 'Unable to add due to some error',
                            );

                            echo json_encode($response);
                        } else {
                            // Handle other types of errors
                            // Set the HTTP response code
                            http_response_code(400); // or any other appropriate code

                            // Prepare the error response
                            $response = array(
                                'status' => 'error',
                                'message' => 'Unable to add due to some error',
                            );

                            echo json_encode($response);
                        }
                    }
                }
            } else {
                // If it's not an AJAX request, show an error message
                session()->getFlashdata('error', 'Direct Access Not Allowed.');
                return redirect()->to('dashboard')->withInput();
            }
        } else {
            session()->getFlashdata('error', 'Unable to Communicate with Database...');
            return redirect()->to('dashboard')->withInput();
        }
    }




    public function get_last_contact()
    {
        if (!session()->has('user_id')) {
            session()->getFlashdata('error', 'Session Expired. Please Login Again');
            return redirect()->to('login')->withInput();
        }

        if ($this->contact_model->load_database()) {

            if ($this->request->isAJAX()) {

                $result = $this->contact_model->get_recently_added_contact(session()->get('user_id'));

                if (!empty($result)) {

                    $response = array(
                        'status' => 'success',
                        'message' => 'Successfully Retrieved From Database',
                        'data' => $result
                    );
                } else {

                    $response = array(
                        'status' => 'error',
                        'message' => 'You Have Not Added Any Contact',
                        'data' => null
                    );
                }

                echo json_encode($response);
            } else {
                // If it's not an AJAX request, show an error message
                session()->getFlashdata('error', 'Direct Access Not Allowed.');
                return redirect()->to('dashboard')->withInput();
            }
        } else {
            session()->getFlashdata('error', 'Unable to Communicate with Database...');
            return redirect()->to('dashboard')->withInput();
        }
    }

    public function delete_contact()
    {
        if (!session()->has('user_id')) {
            session()->getFlashdata('error', 'Session Expired. Please Login Again');
            return redirect()->to('login')->withInput();
        }

        if ($this->contact_model->load_database()) {

            if ($this->request->isAJAX()) {
                
                $raw_phone_number = $this->request->getGet();
                
                $phone_number = $raw_phone_number['phone_number'];
                $user_id = session()->get('user_id');
                $result = $this->contact_model->delete_contact_by_phone_with_user_id($phone_number, $user_id);

                if ($result) {

                    $response['status'] = 'success';
                    $response['message'] = 'Contact Delete Successfully. ' . $phone_number;
                } else {

                    $response['status'] = 'error';
                    $response['message'] = 'Unable to Delete <br> Because Phone Number :' . $phone_number . ' <br>Cannot be Found';
                }
                echo json_encode($response);
            } else {
                // If it's not an AJAX request, show an error message
                session()->getFlashdata('error', 'Direct Access Not Allowed.');
                return redirect()->to('dashboard')->withInput();
            }
        } else {
            session()->getFlashdata('error', 'Unable to Communicate with Database...');
            return redirect()->to('dashboard')->withInput();
        }
    }

    public function get_contact_for_edit()
    {

        if (!session()->has('user_id')) {

            session()->getFlashdata('error', 'Session Expired. Please Login Again');
            return redirect()->to('login')->withInput();
        }

        if ($this->contact_model->load_database()) {

            if ($this->request->isAJAX()) {

                $raw_phone_number = $this->request->getGet();
                
                $phone_number = $raw_phone_number['phone_number'];
                $user_id = session()->get('user_id');
                $result = $this->contact_model->get_contact_by_phone_with_user_id($phone_number, $user_id);

                if (!empty($result)) {

                    $response = array(
                        'status' => 'success',
                        'message' => 'Successfully Retrieved From Database',
                        'data' => $result
                    );
                } else {

                    $response = array(
                        'status' => 'error',
                        'message' => 'Unable to Find <br> Contact with Phone number : ' . $phone_number,
                        'data' => null
                    );
                }

                echo json_encode($response);
            } else {

                session()->getFlashdata('error', 'Direct Access Not Allowed.');
                return redirect()->to('dashboard')->withInput();
            }
        } else {

            session()->getFlashdata('error', 'Unable to Communicate with Database...');
            return redirect()->to('dashboard')->withInput();
        }
    }

    public function update_contact()
    {
        if (!session()->has('user_id')) {
            session()->getFlashdata('error', 'Session Expired. Please Login Again');
            return redirect()->to('login')->withInput();
        }

        if ($this->contact_model->load_database()) {

            if ($this->request->isAJAX()) {

                    $validationRules = [
                        'name' => 'required|trim',
                        'email' => 'valid_email|trim',
                        'address' => 'trim',
                        'phone_number' => 'numeric|trim|required',
                        'remark' => 'trim',
                        'image_location' => [
                            'label' => 'Image',
                            'rules' => 'validateImageSize',
                            'errors' => [ 
                                'validateImageSize' => 'The {field} size must not exceed 10 MB.',
                            ],
                        ],
    
                    ];
    
                    if ($this->validation->setRules($validationRules)->withRequest($this->request)->run()===false) {
                        $response['status'] = 'error';
                        $errors = $this->validation->getErrors();
                        $errorString = '<ul>';
                        foreach ($errors as $field => $error) {
                            $errorString .= '<li>' . $error . '</li>';
                        }
                        $errorString .= '</ul>';
                        $response['message'] = $errorString;
                        echo json_encode($response);
                    }
                    else 
                    {


                    $raw_data = $this->request->getPost();
                    $raw_data['user_id'] = session()->get('user_id');
                    $contactID = $this->contact_model->get_contact_id($raw_data['phone_number'], $raw_data['user_id']);
                    if (empty($_FILES['image_location']['name'])) {
                        $raw_data['image_location'] = $this->contact_model->get_image_location($contactID);
                    }

                    if (!empty($_FILES['image_location']['name'])) {

                        $image_location = $_FILES['image_location']['tmp_name'];
                        $image_type = $_FILES['image_location']['type'];


                        if (file_exists($image_location)) {

                            //$filesize = filesize($image_location);
                            //$filesize_kb = $filesize / 1024;

                            $random_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.webp';
                            $img_path = 'assets/contact_image/' . $random_filename;
                            $file_path = FCPATH . $img_path;

                            switch ($image_type) {
                                case 'image/jpeg':
                                    $original_image = imagecreatefromjpeg($image_location);
                                    break;
                                case 'image/png':
                                    $original_image = imagecreatefrompng($image_location);
                                    break;
                                case 'image/gif':
                                    $original_image = imagecreatefromgif($image_location);
                                    break;
                                case 'image/bmp':
                                    $original_image = imagecreatefrombmp($image_location); // Custom function to create image from BMP (not directly supported by GD)
                                    break;
                                case 'image/xbm':
                                    $original_image = imagecreatefromxbm($image_location); // Create image from XBM
                                    break;
                                case 'image/xpm':
                                    $original_image = imagecreatefromxpm($image_location); // Create image from XPM
                                    break;
                                case 'image/webp':
                                    $original_image = imagecreatefromwebp($image_location); // Create image from WebP
                                    break;
                                case 'image/tiff':
                                case 'image/tif':
                                    $original_image = imagecreatefromtiff($image_location); // Create image from TIFF
                                    break;
                                case 'image/pcx':
                                    $original_image = imagecreatefrompcx($image_location); // Create image from PCX
                                    break;
                                case 'image/vnd.wap.wbmp':
                                    $original_image = imagecreatefromwbmp($image_location); // Create image from WBMP
                                    break;
                                case 'image/ico':
                                case 'image/icon':
                                    $original_image = imagecreatefromico($image_location); // Create image from ICO
                                    break;

                                default:
                                    $raw_data['image_location'] = '/assets/img/empty-profile-picture.webp';
                                    $unsupportedMessage = 'File Type: ' .  $image_type . 'is not supported.';
                                    break;
                            }

                            $new_width = 200;
                            $new_height = 250;
                            $resized_image = imagecreatetruecolor($new_width, $new_height);
                            imagecopyresampled($resized_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, imagesx($original_image), imagesy($original_image));
                            $quality = 100;
                            imagewebp($resized_image, $file_path, $quality);
                            imagedestroy($original_image);
                            imagedestroy($resized_image);

                            $raw_data['image_location'] = $img_path;
                        } else {

                            $raw_data['image_location'] = '/assets/img/empty-profile-picture.webp';
                        }
                    }



                    if (!empty($contactID)) {

                        /*                             if (empty($_FILES['image_location']['name'])) {
                                $response['status'] = 'success';
                                $response['message'] = $raw_data['image_location'];
                                echo json_encode($response);
                                return;
                            } */
                        $result = $this->contact_model->update_contact($contactID, $raw_data);

                        if ($result === TRUE) {

                            $response['status'] = 'success';
                            $response['message'] = 'Successfully Updated the Contact';
                            echo json_encode($response);
                        } else {

                            $response['status'] = 'error';
                            $response['message'] = 'Unable to Update Contact Due to Database Related Error...';
                            echo json_encode($response);
                        }
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Unable to Find the Contact In The Database...';
                        echo json_encode($response);
                    }
                }
            } else {
                // If it's not an AJAX request, show an error message
                session()->getFlashdata('error', 'Direct Access Not Allowed.');
                return redirect()->to('dashboard')->withInput();
            }
        } else {
            session()->getFlashdata('error', 'Unable to Communicate with Database...');
            return redirect()->to('dashboard')->withInput();
        }
    }
    

    public function update_contact_view(){
        //Need Number of Total Contact By User_ID
        //Need Current Page to use as offset

        if (!session()->has('user_id')) {
            session()->getFlashdata('error', 'Session Expired. Please Login Again');
            return redirect()->to('login')->withInput();
        }

        if ($this->contact_model->load_database()) {

            if ($this->request->isAJAX()) 
            {
                $raw_data = $this->request->getGet();
                //$this->putToLog(intval($raw_data['current_page']));
                if ($raw_data['current_page'] === null || empty($raw_data['current_page']) || $raw_data['current_page'] === 0) {
                    $current_page = 1;
                } else {
                    $current_page = intval($raw_data['current_page']);
                }
                
                $user_id = session()->get('user_id');

                $result = $this->contact_model->get_all_contacts_with_offset($user_id,$current_page);

                if ($result) {
           
                    $response['status'] = 'success';
                    $response['message'] = 'Retrieve Data Successfully... ';
                    $response['data'] = $result;
                    $response['total_row'] = $this->contact_model->count_total_row($user_id);
                    $response['max_page'] = $this->contact_model->count_max_page($user_id);
                    $response['new_current_page'] = $this->contact_model->compare_page_number($user_id,$current_page);

                } else {
               
                    $response['status'] = 'success';
                    $response['message'] = 'Unable to Get Data Called From refreshContactView()...';
                    $response['total_row'] = $this->contact_model->count_total_row($user_id);
                }
                echo json_encode($response);  

            }
            else {
                // If it's not an AJAX request, show an error message
                session()->getFlashdata('error', 'Direct Access Not Allowed.');
                return redirect()->to('dashboard')->withInput();
            }

        }
        else {
            session()->getFlashdata('error', 'Unable to Communicate with Database...');
            return redirect()->to('dashboard')->withInput();
        }

    }

    public function putToLog($data)
    {

        $file = fopen(FCPATH . 'log/log.txt', 'w');
        ob_start();
        var_dump($data);
        fwrite($file, ob_get_clean());
        fclose($file);

    }

    public function validateImageSize($image_location)
    {
        if (empty($_FILES[$image_location]['name'])) {
            return true;
        }

        $uploaded_file_info = $_FILES[$image_location];
        $file_size = $uploaded_file_info['size'];

        $max_size_bytes = 10 * 1024 * 1024; // 10 MB

        if (! preg_match('/^image\//', $_FILES[$image_location]['type'])) {
            return false; // Not an image
        }

        if ($file_size > $max_size_bytes) {
            return false; // Exceeds maximum size
        }

        return true; // Validation passed
    }

    function generate_random_string($length = 10, $type = 'alnum')
    {
        $length = intval($length);
        $pool = '';
        switch ($type) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'hex':
                $pool = '0123456789abcdef';
                break;
            default:
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }

        return substr(str_shuffle($pool), 0, (int)$length);
    }



    function generate_random_email($length = 10, $domain = 'example.com')
    {
        $length = intval($length);
        $username = $this->generate_random_string($length);
        return $username . '@' . $domain;
    }

}

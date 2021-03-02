<?php
class HomeController extends Controller 
{
    public function index() {
        if (isset($_POST['action'])) {
            $theUser = $this->model('User')->findUser($_POST['username']);
            if ($theUser != null && password_verify($_POST['password'], $theUser->password_hash)) {
                $_SESSION['user_id'] = $theUser->user_id;

                $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
                if ($profile != null) {
                    $profile->loginUpdate();
                }

                header('location:/public/profile/index');
            }
            else {
                $this->view('home/index', 'Incorrect username or password');
            }
        }
        else {
            $this->view('home/index');
        }
    }

    public function register() {
        if (isset($_POST['action'])) {
            $newUser = $this->model('User');
            $theUser = $newUser->findUser($_POST['username']);
            if ($theUser == null && $_POST['password'] == $_POST['password_confirm']) {
                $newUser->username = $_POST['username'];
                $newUser->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $newUser->create();
                header('location:/public/home/index');
            }
            else {
                $this->view('home/register', 'Username already in use or passwords did not match');
            }
        }
        else {
            $this->view('home/register');
        }
    }
	
    public function contact() {
        if (isset($_POST['action'])) {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && count(str_split($_POST['message'])) > 1) {
                $newMessage = $this->model('Message');
                $newMessage->message_text = $_POST['email'] . "\n" . $_POST['message'];
                $newMessage->messageAdmin();
                header('location:/public/home/index');
            }
            else {
                $this->view('home/contact', 'Invalid email address or no message found');
            }
        }
        else {
            $this->view('home/contact');
        }
    }	

    /**    
       @accessFilter:{LoginFilter, ProfileFilter}
    */    
    public function password() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);

        if (isset($_POST['action'])) {
            $theUser = $this->model('User')->find($profile->user_id);
            if ($theUser != null && password_verify($_POST['password'], $theUser->password_hash) 
            && $_POST['new_password'] == $_POST['password_confirm']) {
                $theUser->changePassword(password_hash($_POST['new_password'], PASSWORD_DEFAULT));
                header('location:/public/profile/index');
                return;
            }
            else {
                $this->view('home/password', 'Incorrect passwords');
            }
        }
        else {
            $this->view('home/password');
        }        
    }

    public function logout() {
        session_destroy();
        header('location:/public/home/index');
    }
}
?>
<?php
/**    
    @accessFilter:{LoginFilter}
*/  
class ProfileController extends Controller 
{
    /**    
        @accessFilter:{ProfileFilter}
    */  
    public function index() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $this->model('FriendLink');
        $profile->newFL = count(FriendLink::getNewreceivedPending($profile->profile_id));
        $this->model('Message');
        $profile->newMsg = count(Message::getNewMessage($profile->profile_id));
        $this->model('ListUser');
        $profile->pendingLists = count(ListUser::findAllPending($profile->profile_id));

        if (isset($_POST['action']) && !empty($_POST['search'])) {
            header('location:/public/profile/search/' . $_POST['search']);
            return;
        }
        else {
            $this->view('profile/index', $profile);
        }
    }    

    public function create() {
        $existingProfile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        if ($existingProfile != null) {
            header('location:/public/profile/index');
            return;
        }        

        if (isset($_POST['action'])) {
            if (empty($_POST['privacy_flag'])) {
                $privacy_flag = 0;
            }
            else {
                $privacy_flag = 1;
            }
            
            if (empty($_POST['first_name'])) {
                $this->view('profile/create', 'Please enter your first name');
            }
            elseif (empty($_POST['last_name'])) {
                $this->view('profile/create', 'Please enter your last name');
            }
            elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $this->view('profile/create', 'Please enter a valid email');
            }
            else {
                $newProfile = $this->model('Profile');
                $newProfile->first_name = $_POST['first_name'];
                $newProfile->last_name = $_POST['last_name'];
                $newProfile->gender = $_POST['gender'];
                $newProfile->privacy_flag = $privacy_flag;
                $newProfile->phone = $_POST['phone'];
                $newProfile->email = $_POST['email'];
                $newProfile->create();
                header('location:/public/profile/index');
            }
        }
        else {
            $this->view('profile/create');
        }
    } 

    /**    
        @accessFilter:{ProfileFilter}
    */      
    public function edit() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);

        if (isset($_POST['action'])) {
            if (empty($_POST['privacy_flag'])) {
                $privacy_flag = 0;
            }
            else {
                $privacy_flag = 1;
            }

            if (empty($_POST['first_name'])) {
                $_SESSION['error'] = 'Please enter your first name';
                $this->view('profile/edit', $profile);
            }
            elseif (empty($_POST['last_name'])) {
                $_SESSION['error'] = 'Please enter your last name';
                $this->view('profile/edit', $profile);
            }
            elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Please enter a valid email';
                $this->view('profile/edit', $profile);
            }
            else {
                $profile->first_name = $_POST['first_name'];
                $profile->last_name = $_POST['last_name'];
                $profile->gender = $_POST['gender'];
                $profile->privacy_flag = $privacy_flag;
                $profile->phone = $_POST['phone'];
                $profile->email = $_POST['email'];
                $profile->editProfile();
                header('location:/public/profile/index');
            }            
        }
        else {
            $this->view('profile/edit', $profile);
        }            
    }

    /**    
        @accessFilter:{ProfileFilter}
    */      
    public function delete() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);

        if (isset($_POST['action'])) { 
            $user = $this->model('User')->find($profile->user_id);
            if (password_verify($_POST['password'], $user->password_hash) && $_POST['password'] == $_POST['password_confirm']) {
                $listuser_owned = $this->model('ListUser')->findAllListsOwned($profile->profile_id);
                foreach ($listuser_owned as $lu_owned) {
                    $allOwners =  $this->model('ListUser')->findAllOwners($lu_owned->list_id);
                    if (count($allOwners) <= 1) {
                        $list = $this->model('EList')->find($lu_owned->list_id);
                        $this->model('ListItem')->listitemDeleteAllForList($list->list_id);
                        $this->model('ListUser')->listuserDeleteAllForList($list->list_id);
                        $this->model('Conversation')->conversationDeleteAllForList($list->list_id);            
                        $list->delete();
                    }
                }

                $this->model('ListUser')->listuserDeleteAllForProfile($profile->profile_id);
                $this->model('Message')->messageDeleteAllForProfile($profile->profile_id);                
                $this->model('FriendLink')->friendlinkDeleteAllForProfile($profile->profile_id);

                $profile->delete();
                header('location:/public/profile/create');
                return;
            } 
            else {
                $this->view('profile/delete', 'Incorrect passwords');
            }
        }
        else {
            $this->view('profile/delete');
        }
    }

    public function setPicture() {
        if ($_FILES['file']['error'] == 0) {
            $savePath = '../public/img/';
            $fileExtenstion = strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
            $fileName = uniqid() . '.' . $fileExtenstion;

            if ($fileExtenstion == 'jpg' || $fileExtenstion == 'png' || $fileExtenstion == 'jpeg' || $fileExtenstion == 'gif') {
                while (file_exists('img/' . $fileName)) {
                    $fileName = uniqid() . '.' . $fileExtenstion;
                }
    
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $savePath . $fileName)) {
                    $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
                    if (!empty($profile->picture_path) && file_exists($profile->picture_path)) {
                        unlink($profile->picture_path);
                    }
                    $profile->setPicturePath('img/' . $fileName);
                }
            }
        }
        header('location:/public/profile/index');
    }
  
    public function deletePicture() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        if (!empty($profile->picture_path) && file_exists($profile->picture_path)) {
            unlink($profile->picture_path);          
        }
        $profile->setPicturePath(NULL);
        header('location:/public/profile/index');
    }    

    /**    
        @accessFilter:{ProfileFilter}
    */        
    public function search($search) {
        if (isset($_POST['action'])) {
            header('location:/public/profile/search/' . $_POST['search']);
        }

        if (empty($search)) {
            $this->view('profile/search');
        }
        else {
            $searchQ = preg_replace('#[^0-9a-z]#i','',$search);
            $this->model('Profile');
            $this->model('FriendLink');
            $searchResults = Profile::searchProfiles($searchQ);
            $currentProfile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);

            /**
             * We add properties on the fly here to each Profile object we return in search results.
             * friendShip::0/1 bool, true if a friendlink exists
             * flSender::0/1   bool, true if searched profile is sender of friendlink
             * flAccepted::0/1 bool, true if friendlink is accepted
             */
            if (count($searchResults) > 0) {
                foreach($searchResults as $searchedProfile) {
                    $friendship = null;
                    $friendship = FriendLink::findLink($currentProfile->profile_id, $searchedProfile->profile_id);
                    if (empty($friendship)) {
                        $searchedProfile->friendShip = 0;
                    }
                    else {
                        $searchedProfile->friendShip = 1;
                        foreach($friendship as $friendlink) {
                            if ($friendlink->sender == $currentProfile->profile_id) {
                                $searchedProfile->flSender = 1;
                            }
                            else {
                                $searchedProfile->flSender = 0;
                            }

                            if ($friendlink->approved) {
                                $searchedProfile->flAccepted = 1;
                            }
                            else {
                                $searchedProfile->flAccepted = 0;
                            }
                        }
                    }
                }

                $this->view('profile/search', ['profiles' => $searchResults]);
            }
            else {
                $this->view('profile/search', 'No matches found');
            }           
        }
    }

    public function display($id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);

        if (isset($_POST['action']) && !empty($_POST['search'])) {
            header('location:/public/profile/search/' . $_POST['search']);
            return;
        }
        else {
            $viewProfile = $this->model('Profile')->findProfile($id);
            $this->model('FriendLink');
            $friendlink = FriendLink::findLink($profile->profile_id, $viewProfile->profile_id);

            if (empty($viewProfile) || $viewProfile->privacy_flag && empty($friendlink)) {
                header('location:/public/profile/index');
                return;
            }
            else {
                $viewProfile->friendShip = 0;
                if (!empty($friendlink)) {
                    foreach ($friendlink as $fl) {
                        if ($fl->approved) {
                            $viewProfile->friendShip = 1;
                        }
                    }

                    if ($viewProfile->friendShip == 0) {
                        if ($friendlink[0]->sender == $profile->profile_id) {
                            $viewProfile->friendShip = 2;
                        }
                        else {
                            $viewProfile->friendShip = 3;
                        }
                    }
                }
                $this->view('profile/display', $viewProfile);
                return;
            }
        }
    }
}
?>
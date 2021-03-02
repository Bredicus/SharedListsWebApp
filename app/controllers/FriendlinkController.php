<?php
/**    
    @accessFilter:{LoginFilter, ProfileFilter}
*/  
class FriendLinkController extends Controller 
{
    public function index() {
        if (isset($_POST['action']) && !empty($_POST['search'])) {
            header('location:/public/profile/search/' . $_POST['search']);
            return;
        }

        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']); 
        $this->model('FriendLink');
        $friendLinks = FriendLink::getFriends($profile->profile_id);
        $_SESSION['newFriendRequest'] = count(FriendLink::getNewreceivedPending($profile->profile_id));
         
        if (!empty($friendLinks)) {
            foreach($friendLinks as $fl) {
                if ($fl->sender == $profile->profile_id) {
                    $friends[] = Profile::findProfile($fl->receiver);
                }
                else {
                    $friends[] = Profile::findProfile($fl->sender);
                }
            }
            $this->view('friendlink/index', ['profiles' => $friends]);
        }
        else {
            $this->view('friendlink/index', 'Add friends for them to be displayed here');
        }
    }

    public function sent() {
        if (isset($_POST['action']) && !empty($_POST['search'])) {
            header('location:/public/profile/search/' . $_POST['search']);
            return;
        }

        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']); 
        $this->model('FriendLink');
        $friendLinks = FriendLink::getSentPending($profile->profile_id);
        $_SESSION['newFriendRequest'] = count(FriendLink::getNewreceivedPending($profile->profile_id));
        
        if (!empty($friendLinks)) {
            foreach($friendLinks as $fl) {
                $friends[] = Profile::findProfile($fl->receiver);
            }
            $this->view('friendlink/sent', ['profiles' => $friends]);
        }
        else {
            $this->view('friendlink/sent', 'No sent friend requests that are currently pending');
        }
    }

    public function received() {
        if (isset($_POST['action']) && !empty($_POST['search'])) {
            header('location:/public/profile/search/' . $_POST['search']);
            return;
        }

        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']); 
        $this->model('FriendLink');
        $friendLinks = FriendLink::getreceivedPending($profile->profile_id);
     
        if (!empty($friendLinks)) {
            foreach($friendLinks as $fl) {
                $isNew = false;
                if (!$fl->is_read) {
                    $fl->read();
                    $isNew = true;
                }
                
                $friends[] = Profile::findProfile($fl->sender);
                $friends[count($friends) - 1]->isNew = $isNew;
            }
            $this->view('friendlink/received', ['profiles' => $friends]);
        }
        else {
            $this->view('friendlink/received', 'No incoming friend requests that are currently pending');
        }
    }

    /**
     * $currentView is a code for which view called the function 
     * (add more as needed)
     * 0 = profile/search
     * 1 = profile/display
     * 2 = friendlink/index
     * 3 = friendlink/sent
     * 4 = friendlink/received
     */
    public function approve($selectedUserId, $currentView, $queryStr) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $fl = $this->model('FriendLink')->getLink($selectedUserId, $profile->profile_id);
        $fl->approve();

        if ($currentView == 0) {
            header('location:/public/profile/search/' . $queryStr);
            return;            
        }        
        elseif ($currentView == 1) {
            header('location:/public/profile/display/' . $selectedUserId);
            return;            
        }  
        elseif ($currentView == 2) {
            header('location:/public/friendlink/index');
            return;            
        }  
        elseif ($currentView == 3) {
            header('location:/public/friendlink/sent');
            return;            
        }  
        elseif ($currentView == 4) {
            header('location:/public/friendlink/received');
            return;            
        }  
    }       

    public function remove($selectedUserId, $currentView, $queryStr) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $fl = $this->model('FriendLink')->delete($profile->profile_id, $selectedUserId);

        if ($currentView == 0) {
            header('location:/public/profile/search/' . $queryStr);
            return;            
        }        
        elseif ($currentView == 1) {
            header('location:/public/profile/display/' . $selectedUserId);
            return;            
        }  
        elseif ($currentView == 2) {
            header('location:/public/friendlink/index');
            return;            
        }  
        elseif ($currentView == 3) {
            header('location:/public/friendlink/sent');
            return;            
        }  
        elseif ($currentView == 4) {
            header('location:/public/friendlink/received');
            return;            
        }  
    }    

    public function add($selectedUserId, $currentView, $queryStr) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $fl = $this->model('FriendLink');
        $fl->sender = $profile->profile_id;
        $fl->receiver = $selectedUserId;
        $fl->create();

        if ($currentView == 0) {
            header('location:/public/profile/search/' . $queryStr);
            return;            
        }        
        elseif ($currentView == 1) {
            header('location:/public/profile/display/' . $selectedUserId);
            return;            
        }  
        elseif ($currentView == 2) {
            header('location:/public/friendlink/index');
            return;            
        }  
        elseif ($currentView == 3) {
            header('location:/public/friendlink/sent');
            return;            
        }  
        elseif ($currentView == 4) {
            header('location:/public/friendlink/received');
            return;            
        }  
    }    
}
?>
<?php
/**    
    @accessFilter:{LoginFilter, ProfileFilter}
*/  
class MessageController extends Controller 
{
    public function index() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $allMessages = $this->model('Message')->getAllMessages($profile->profile_id);
        $conversations = array();
        $allMessages = array_reverse($allMessages);

        for ($i = 0; $i < count($allMessages); $i++) {
            $otherProfileId = $profile->profile_id == $allMessages[$i]->sender ? $allMessages[$i]->receiver : $allMessages[$i]->sender;
            $otherProfile = $this->model('Profile')->findProfile($otherProfileId);
            $isFirst = true;
            $isFriend = true;

            for ($j = 0; $j < count($conversations); $j++) {
                $addedProfileId = $profile->profile_id == $conversations[$j]->sender ? $conversations[$j]->receiver : $conversations[$j]->sender;
                if ($addedProfileId == $otherProfileId && $otherProfileId != NULL) {
                    $isFirst = false;
                }
            }

            if (!empty($otherProfile) && $otherProfile->privacy_flag == 1) {
                $this->model('FriendLink');
                $friendlink = FriendLink::findLink($profile->profile_id, $otherProfile->profile_id);
                $isFriend = false;

                foreach ($friendlink as $fl) {
                    if ($fl->approved) {
                        $isFriend = true;
                    }
                }
            }

            if ($isFirst) {
                $conversations[] = $allMessages[$i];

                if (!empty($otherProfile)) {
                    $conversations[count($conversations) - 1]->name = $otherProfile->first_name . ' ' . $otherProfile->last_name;
                    $conversations[count($conversations) - 1]->profile_id = $otherProfile->profile_id;
                    $conversations[count($conversations) - 1]->picture_path = $otherProfile->picture_path;
                    $conversations[count($conversations) - 1]->isFriend = $isFriend;
                    if ($conversations[count($conversations) - 1]->sender == $profile->profile_id) {
                        $conversations[count($conversations) - 1]->isSender = true;
                    }
                    else {
                        $conversations[count($conversations) - 1]->isSender = false;
                    }
                } 
                else {
                    $conversations[count($conversations) - 1]->name = 'Unknown';
                    $conversations[count($conversations) - 1]->profile_id = -1;
                    $conversations[count($conversations) - 1]->isFriend = false;
                }
            }
        }

        if (count($conversations) > 0) {
            $this->view('message/index', ['conversations' => $conversations]);
        }
        else {
            $this->view('message/index', "When you send or receive messages, they will be displayed here");
        }
    }

    public function new($receiver_id) { 
        $currentProfile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $otherProfile = $this->model('Profile')->findProfile($receiver_id);
        
        if ($otherProfile == null) {
            header('location:/public/message/index');
        }

        $otherProfileName = $otherProfile->first_name . ' ' . $otherProfile->last_name;

        if ($otherProfile->privacy_flag) {
            $friendship = false;
            $this->model('FriendLink');
            $friendlink = FriendLink::findLink($currentProfile->profile_id, $otherProfile->profile_id);

            if (empty($friendlink)) {
                header('location:/public/message/index');
            }
            else {
                foreach ($friendlink as $fl) {
                    if ($fl->approved) {
                        $friendship = true;
                    }
                }                
            }

            if ($friendship == false) {
                if (isset($_POST['action'])) {
                    header('location:/public/message/index');
                }
                else {
                    header('location:/public/message/display/' . $otherProfile->profile_id);
                }
            }
        }

        if (isset($_POST['action'])) {
            $newMessage = $this->model('Message');
            $newMessage->sender = $currentProfile->profile_id;
            $newMessage->receiver = $receiver_id;
            $newMessage->message_text = $_POST['message'];
            $newMessage->newMessage();
            header('location:/public/message/new/' . $receiver_id);
        }
        else {
            $allMessages = $this->model('Message')->getConversation($currentProfile->profile_id, $otherProfile->profile_id);
            foreach ($allMessages as $message) {
                $message->currentIsSender = $currentProfile->profile_id == $message->sender ? true : false;
                if ($message->currentIsSender) {
                    $message->name = $currentProfile->first_name . ' ' . $currentProfile->last_name;
                }
                else {
                    $message->name = $otherProfileName;
                    $message->setMessageRead($message->message_id);
                }
            }

            $_SESSION['message_name'] = $otherProfileName;
            $this->view('message/new', ['messages' => $allMessages]);
        }        
    }

    public function display($receiver_id) { 
        if ($receiver_id < 0) {
            $message_id = $receiver_id * -1;
            $message = $this->model('Message')->getMessage($message_id);
            $message->currentIsSender = false;
            $message->name = 'Unknown';
            $message->setMessageRead($message->message_id);
            $singleMessageArray = array();
            $singleMessageArray[] = $message;
            
            $_SESSION['message_name'] = 'Unknown';
            $this->view('message/display', ['messages' => $singleMessageArray]);
            return;
        }
        else {
            $currentProfile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
            $otherProfile = $this->model('Profile')->findProfile($receiver_id);
            $otherProfileName = $otherProfile->first_name . ' ' . $otherProfile->last_name;
            $allMessages = $this->model('Message')->getConversation($currentProfile->profile_id, $otherProfile->profile_id);
            foreach ($allMessages as $message) {
                $message->currentIsSender = $currentProfile->profile_id == $message->sender ? true : false;
                if ($message->currentIsSender) {
                    $message->name = $currentProfile->first_name . ' ' . $currentProfile->last_name;
                }
                else {
                    $message->name = $otherProfileName;
                    $message->setMessageRead($message->message_id);
                }
            }
            $_SESSION['message_name'] = $otherProfileName;
            $this->view('message/display', ['messages' => $allMessages]);
            return;
        }
    }

    public function delete($message_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $message = $this->model('Message')->getMessage($message_id);
        if ($profile->profile_id == $message->sender) {
            $message->delete();
            header('location:/public/message/new/' . $message->receiver);
            return;
        }
        else {
            header('location:/public/message/index');
            return;
        }
    }  
}
?>
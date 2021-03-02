<?php
/**    
    @accessFilter:{LoginFilter, ProfileFilter}
*/  
class ListController extends Controller 
{
    public function index() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $this->model('EList');
        $this->model('ListUser');
        $_SESSION['newLists'] = count(ListUser::findAllPending($profile->profile_id));
        $allListUser = ListUser::findAllLists($profile->profile_id);
        $allLists = array();

        foreach ($allListUser as $list) {
            $allLists[] = $this->model('EList')->find($list->list_id);

            $allLists[count($allLists) - 1]->bookmarked = $list->is_bookmarked ? true : false;
        }

        if (count($allLists) > 0) {
            $this->view('list/index', ['lists' => $allLists]);
        }
        else {
            $this->view('list/index', 'Create a list for it to be displayed here');
        }
    }

    public function create() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        if (isset($_POST['action'])) {
            if (empty($_POST['list_name'])) {
                $this->view('list/create', 'Fields reset. Please enter a name for your list');
            }
            else {
                $newList = $this->model('EList');
                $newList->list_name = $_POST['list_name'];
                if (!empty($_POST['deadline'])) {
                    $newList->deadline = $_POST['deadline'];
                }
                else {
                    $newList->deadline = NULL;
                }
                $list_id = $newList->create($profile->profile_id);

                $newListUser = $this->model('ListUser');
                $newListUser->list_id = $list_id;
                $newListUser->profile_id = $profile->profile_id;
                $newListUser->sender_id = $profile->profile_id;
                $newListUser->create();
                $newListUser->makeOwner();
                $newListUser->setAccepted();
                
                header('location:/public/list/index');
            }
        }
        else {
            $this->view('list/create');
        }
    }

    public function bookmark() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $this->model('EList');
        $this->model('ListUser');
        $_SESSION['newLists'] = count(ListUser::findAllPending($profile->profile_id));
        $allListUser = ListUser::findAllBookmarked($profile->profile_id);
        $allLists = array();

        foreach ($allListUser as $list) {
            $allLists[] = $this->model('EList')->find($list->list_id);
        }

        if (count($allLists) > 0) {
            $this->view('list/bookmark', ['lists' => $allLists]);
        }
        else {
            $this->view('list/bookmark', 'Bookmark a list for it to be displayed here');
        }
    }

    public function setBookmark($list_id, $setMode, $returnMode) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);

        if ($setMode == 0) {
            $listUser->setBookmark();
        }
        else {
            $listUser->unsetBookmark();
        }

        if ($returnMode == 0) {
            header('location:/public/list/index');
            return;
        }
        elseif ($returnMode == 1) {
            header('location:/public/list/bookmark');
            return;
        }
    }

    public function leave($list_id, $returnMode) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);

        if ($listUser->is_owner) {
            $this->model('ListUser');
            $allOwners = ListUser::findAllOwners($list_id);

            if (count($allOwners) <= 1) {
                header('location:/public/list/warning/' . $list_id . '/' . $returnMode);
                echo 'this';
                return;
            }
        }

        $listUser->delete();

        if ($returnMode == 0) {
            header('location:/public/list/index');
            return;
        }
        elseif ($returnMode == 1) {
            header('location:/public/list/bookmark');
            return;
        }
    }    

    public function warning($list_id, $returnMode) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $list = $this->model('EList')->find($list_id);

        if (isset($_POST['action'])) {            
            if ($listUser->is_owner) {
                $this->model('ListUser');
                $allOwners = ListUser::findAllOwners($list_id);
    
                if (count($allOwners) == 1) {
                    //DELETE EVERYTHING ASSOCIATED WITH THE LIST HERE (items)
                    $allListUsers = ListUser::findAllProfiles($list_id);
                    foreach ($allListUsers as $lu) {
                        $lu->delete();
                    }

                    $list->delete();
                }
            }

            if ($returnMode == 0) {
                header('location:/public/list/index');
                return;
            }
            elseif ($returnMode == 1) {
                header('location:/public/list/bookmark');
                return;
            }
        }
        elseif (isset($_POST['back'])) {
            if ($returnMode == 0) {
                header('location:/public/list/index');
                return;
            }
            elseif ($returnMode == 1) {
                header('location:/public/list/bookmark');
                return;
            }
        }
        else {
            $this->view('list/warning', $list);
            return;
        }
    } 

    /**    
        @accessFilter:{ListMemberFilter}
    */ 
    public function display($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $list = $this->model('EList')->find($list_id);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $listItems = $this->model('ListItem')->findAll($list_id);

        $list->is_owner = $listUser->is_owner;

        if (count($listItems) > 0) {
            $list->noItems = false;

            usort($listItems,function($first,$second) {
                return $first->priority < $second->priority;
            }); 

            $list->items = $listItems;
        }
        else {
            $list->noItems = true;
        }

        $this->view('list/display', $list);
    }

   

    /**    
        @accessFilter:{ListMemberFilter}
    */ 
    public function members($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $members = $this->model('ListUser')->findAllActiveProfiles($list_id);
        $list = $this->model('EList')->find($list_id);

        if ($listUser->is_owner) {
            $list->currentIsOwner = true;
        }
        else {
            $list->currentIsOwner = false;
        }

        foreach ($members as $member) {
            $memberProfile = $this->model('Profile')->findProfile($member->profile_id);
            $member->name = $memberProfile->first_name . ' ' . $memberProfile->last_name;

            if ($profile->profile_id != $member->profile_id) {          
                if ($memberProfile->privacy_flag) {
                    $member->canView = 0;
                    $this->model('FriendLink');
                    $friendship = FriendLink::findLink($profile->profile_id, $memberProfile->profile_id);
                    if (!empty($friendship)) {
                        foreach($friendship as $friendlink) {
                            if ($friendlink->approved) {
                                $member->canView = 0;
                            }
                        }
                    }
                }
                else {
                    $member->canView = 1;
                }
            }
            else {
                $member->canView = 2;
            }
        }

        $list->members = $members;

        $this->view('list/members', $list);
    }

    /**    
        @accessFilter:{ListOwnerFilter}
    */ 
    public function edit($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $list = $this->model('EList')->find($list_id);

        if (isset($_POST['action'])) {
            if ($listUser->is_owner) {
                if (!empty($_POST['list_name'])) {
                    $list->list_name = $_POST['list_name'];
                    $list->setName();
                }
                
                $list->deadline = $_POST['deadline'];               
                $list->setDeadline();
            }

            header('location:/public/list/display/' . $list_id);
            return;
        }
        elseif (isset($_POST['back'])) {
            header('location:/public/list/display/' . $list_id);
            return;
        }
        else {
            $this->view('list/edit', $list);
            return;
        }
    }

    /**    
        @accessFilter:{ListOwnerFilter}
    */ 
    public function delete($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $list = $this->model('EList')->find($list_id);

        if (isset($_POST['action'])) {
            if ($listUser->is_owner) {
                $this->model('ListItem')->listitemDeleteAllForList($list->list_id);
                $this->model('ListUser')->listuserDeleteAllForList($list->list_id);
                $this->model('Conversation')->conversationDeleteAllForList($list->list_id);            

                $list->delete();
            }

            header('location:/public/list/index');
            return;
        }
        elseif (isset($_POST['back'])) {
            header('location:/public/list/display/' . $list_id);
            return;
        }
        else {
            $this->view('list/delete', $list);
            return;
        }
    }    

    /**    
        @accessFilter:{ListOwnerFilter}
    */     
    public function setOwner($list_id, $profile_id, $mode) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        
        if ($listUser->is_owner) {
            if ($profile->profile_id == $profile_id) {
                $this->model('ListUser');
                $allOwners = ListUser::findAllOwners($list_id);

                if (count($allOwners) == 1) {
                    header('location:/public/list/warning/' . $list_id . '/0');
                    return;
                }
            }

            $membersListUser = $this->model('ListUser')->find($list_id, $profile_id);
            if ($mode == 0) {
                $membersListUser->removeOwner();
            }
            elseif ($mode == 1) {
                $membersListUser->makeOwner();
            }
        }

        header('location:/public/list/members/' . $list_id);
    }

    /**    
        @accessFilter:{ListOwnerFilter}
    */     
    public function removeMember($list_id, $profile_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        
        if ($listUser->is_owner) {
            if ($profile->profile_id == $profile_id) {
                $this->model('ListUser');
                $allOwners = ListUser::findAllOwners($list_id);

                if (count($allOwners) == 1) {
                    header('location:/public/list/warning/' . $list_id . '/0');
                    return;
                }
            }

            $membersListUser = $this->model('ListUser')->find($list_id, $profile_id);
            $membersListUser->delete();
        }

        header('location:/public/list/members/' . $list_id);
    }

    /**    
        @accessFilter:{ListOwnerFilter}
    */     
    public function invite($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $list = $this->model('EList')->find($list_id);
        $this->model('FriendLink');
        $friendLinks = FriendLink::getFriends($profile->profile_id);
        $friends = array();

        foreach ($friendLinks as $fl) {
            $friend_id = $fl->sender == $profile->profile_id ? $fl->receiver : $fl->sender;
            $listUser = $this->model('ListUser')->find($list_id, $friend_id);

            if ($listUser == NULL) {
                $friendProfile = $this->model('Profile')->findProfile($friend_id);
                $friends[] = $friendProfile;
            }
        }

        if (count($friends) == 0) {
            $list->InfoStr = 'Add friends to be able to invite them to the list';
        }        

        $list->currentOwnerFriends = $friends;

        $this->view('list/invite', $list);
    }

    /**    
        @accessFilter:{ListOwnerFilter}
    */      
    public function inviteMember($list_id, $invitee_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        
        if ($listUser->is_owner) {
            $newListUser = $this->model('ListUser');
            $newListUser->list_id = $list_id;
            $newListUser->profile_id = $invitee_id;
            $newListUser->sender_id = $profile->profile_id;
            $newListUser->create();
        }
        
        header('location:/public/list/invite/' . $list_id);
    }

    public function pending() {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $this->model('ListUser');
        $allPending = ListUser::findAllPending($profile->profile_id);

        if (count($allPending) > 0) {
            foreach ($allPending as $listUser) {
                $list = $this->model('EList')->find($listUser->list_id);
                $sender = $this->model('Profile')->findProfile($listUser->sender_id);
                $listUser->list_name = $list->list_name;
                $listUser->sent_by = $sender->first_name . ' ' . $sender->last_name;
            }

            $this->view('list/pending', ['allPending' => $allPending]);
        }
        else {
            $this->view('list/pending', 'When you get an invitation to join a list, it will be displayed here');
        }
    }

    public function join($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $listUser->setAccepted();
        header('location:/public/list/index');
    }

    public function setComplete($list_id, $mode) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        
        if ($listUser->is_owner) {
            $list = $this->model('EList')->find($listUser->list_id);

            if ($mode == 1) {
                $list->setComplete();
            }
            elseif ($mode == 0) {
                $list->setIncomplete();
            }
        }

        header('location:/public/list/display/' . $list_id);
    }

    /**    
        @accessFilter:{ListMemberFilter}
    */        
    public function conversation($list_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $list = $this->model('EList')->find($list_id);
        $conversation = $this->model('Conversation')->findAll($list_id);

        if (isset($_POST['action'])) {
            $convo_msg = $this->model('Conversation');
            $convo_msg->list_id = $list_id;
            $convo_msg->message_text = $_POST['message'];
            $convo_msg->sender = $profile->profile_id;
            $convo_msg->create();
        }

        $conversation = $this->model('Conversation')->findAll($list_id);
        foreach ($conversation as $msg) {
            $senderProfile = $this->model('Profile')->findProfile($msg->sender);
            $msg->sender_name = $senderProfile->first_name . ' ' . $senderProfile->last_name;

            if ($msg->sender == $profile->profile_id) {
                $msg->currentIsSender = true;
            }
            else {
                $msg->currentIsSender = false;
            }
        }

        $list->conversation = $conversation;

        $this->view('list/conversation', $list);      
    }

    public function deleteMsg($message_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $message = $this->model('Conversation')->find($message_id);

        if ($profile->profile_id == $message->sender) {
            $message->delete();
        }

        header('location:/public/list/conversation/' . $message->list_id);
    }
}
?>
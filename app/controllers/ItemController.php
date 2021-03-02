<?php
/**    
    @accessFilter:{LoginFilter, ProfileFilter, ListMemberFilter}
*/  
class ItemController extends Controller 
{
    /**    
        @accessFilter:{ListOwnerFilter}
    */     
    public function create($list_id) { 
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);

        if ($listUser->is_owner) {
            if (isset($_POST['action'])) {
                if (!empty($_POST['item_data']) && strlen($_POST['item_data']) > 1) {
                    $newItem = $this->model('ListItem');
                    $newItem->list_id = $list_id;
                    $newItem->item_data = $_POST['item_data'];
                    $newItem->priority = $_POST['priority'];
                    $newItem->created_by = $profile->profile_id;
                    if (!empty($_POST['deadline'])) {
                        $newItem->deadline = $_POST['deadline'];
                    }
                    $newItem->create();
                }
            }

            header('location:/public/list/display/' . $list_id);
        }
    }

    /**    
        @accessFilter:{ListOwnerFilter}
    */   
    public function edit($list_id, $item_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        $list = $this->model('EList')->find($list_id);
        $item = $this->model('ListItem')->find($item_id);
        $list->item = $item;

        if ($listUser->is_owner) {
            if (isset($_POST['action'])) {
                if (!empty($_POST['item_data']) && strlen($_POST['item_data']) > 1) {
                    $item->list_id = $list_id;
                    $item->item_data = $_POST['item_data'];
                    $item->priority = $_POST['priority'];
                    if (empty($_POST['deadline'])) {
                        $item->deadline = NULL;
                    }
                    else {
                        $item->deadline = $_POST['deadline'];
                    }
                    $item->edit();

                    header('location:/public/list/display/' . $list_id);
                    return;
                }
                else {
                    $list->InfoStr = 'Please enter data for the item';
                }
            }

            $this->view('list/edititem', $list);
        }
    }

    public function delete($list_id, $item_id) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        
        if ($listUser->is_owner) {
            $item = $this->model('ListItem')->find($item_id);
            $item->delete();
        }

        header('location:/public/list/display/' . $list_id);
    }    

    public function setComplete($list_id, $item_id, $mode) {
        $profile = $this->model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $listUser = $this->model('ListUser')->find($list_id, $profile->profile_id);
        
        if ($listUser->is_owner) {
            $item = $this->model('ListItem')->find($item_id);

            if ($mode == 1) {
                $item->setComplete();
            }
            elseif ($mode == 0) {
                $item->setIncomplete();
            }
        }

        header('location:/public/list/display/' . $list_id);
    }
}
?>
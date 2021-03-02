<?php
class Filter extends Controller 
{
	public static function LoginFilter($params) {
        if ($_SESSION['user_id'] == null) {
			return 'home/index';
		}		
		else {
			return false;
		}
	}

	public static function ProfileFilter($params) {
        //$profile = self::model('Profile')->findProfile_User_id($_SESSION['user_id']);
        $profile = (new Profile)->findProfile_User_id($_SESSION['user_id']);
        if ($profile == null) {
			return 'profile/create';
		}		
		else {
			return false;
        }         
    }    
    
	public static function ListMemberFilter($params) {
		//$profile = self::model('Profile')->findProfile_User_id($_SESSION['user_id']);
		$profile = (new Profile)->findProfile_User_id($_SESSION['user_id']);
		//$listUser = self::model('ListUser')->find($params[0], $profile->profile_id);
		$listUser = (new ListUser)->find($params[0], $profile->profile_id);
		if ($listUser == null || !$listUser->accepted) {
			return 'list/index';
		}
		else {
			return false;
        }  
	}

	public static function ListOwnerFilter($params) {
		//$profile = self::model('Profile')->findProfile_User_id($_SESSION['user_id']);
		$profile = (new Profile)->findProfile_User_id($_SESSION['user_id']);
		//$listUser = self::model('ListUser')->find($params[0], $profile->profile_id);
		$listUser = (new ListUser)->find($params[0], $profile->profile_id);
		if ($listUser == null || !$listUser->is_owner) {
			return 'list/display/' . $params[0];
		}
		else {
			return false;
        }  
	}
}
?>
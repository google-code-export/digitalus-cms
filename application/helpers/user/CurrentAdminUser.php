<?php
class  DSF_View_Helper_User_CurrentAdminUser
{

	/**
	 * comments
	 */
	public function CurrentAdminUser(){
		$user = DSF_Auth::getIdentity();

		if($user)
		{
			$xhtml = "<ul>
					<li>Current User: {$user->first_name}  {$user->last_name}</li>
					<li>Role: {$user->role}</li>
					<li><a href='/core/auth/logout/'>Log Out</a></li>
				</ul>";
			return $xhtml;
		}else{
		    return false;
		}
	}
}
			
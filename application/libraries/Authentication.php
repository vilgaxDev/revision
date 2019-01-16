<?php
defined('BASEPATH') or exit('No direct script access allowed!');

class Authentication
{
	/**
	 *  Define global variables
	 */
	private $ip_address, $sid_cookie, $password;
	public $uid, $username, $firstname, $lastname, $email, $user_group, $premium, $premium_until, $errorMessage, $active;
	protected $ci;

	/**
	 * Get instance of codeigniter
	 * @return 		none
	 * @access		public
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since		1.0 (initial release)
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->helper('cookie');
		$this->ci->load->model('authenticationModel');
	}

	/**
	 * Checks if a user is allready loggedin (authenticated)
	 *
	 * @return 		boolean		True or false whether the user is authenticated or not
	 * @access		public
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	public function logged_in()
	{
		//Assing user ip_address to a private variable
		$this->ip_address = $this->ci->input->ip_address();

		//Assign session cookie to a private variable
		$this->sid_cookie = $this->ci->input->cookie('sid');

		//Check if the session cookie exists and is not empty
		if(empty($this->sid_cookie) == FALSE)
		{
			//Decrypt the sid cookie
			$this->sid_cookie = $this->decrypt($this->sid_cookie);

			//Run a sql query to get session data
			$query = $this->ci->authenticationModel->session_data($this->sid_cookie);

			//Check if the query returned any results
			if(!empty($query))
			{
				//Check if the session isn't older than 1h
				if($query['time']+3600 > time())
				{
					//Check if the user ip address is the same as the one stored in the database
					if($query['ip_address'] == $this->ip_address)
					{
						//Assign the uid form the query to a global varaible
						$this->uid = $query['uid'];
						//Get all user information
						$this->getUserInformation($this->uid);

						//If the session is older than 15 minutes create a new one
						if($query['time']+900 < time())
						{
							$this->createSession();
						}
						//User successfully authenticated
						return true;
					}
				}
			}
		}

		return false;
	}


	/**
	 * Performs a login request
	 *
	 * @return 		boolean		True or false whether the user credentials are correct or not
	 * @access		public
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	public function login($username, $password)
	{

		//Assign the password to a private variable
		$this->password = $this->encryptSHA($password);

		//Run a sql query to check if the users exists
		$query = $this->ci->authenticationModel->getUser($username);

		//Check if the query returned any results
		if(!empty($query))
		{
			//Check if the entered and the saved password matches
			if($query['password'] === $this->password)
			{
				//Assign the user_id to a private variable
				$this->uid = $query['uid'];
				//Get all user information
				$this->getUserInformation($this->uid);

				//Check if the account is active
				if(!$this->active){
					$this->errorMessage = 'User account has not been activated yet';
					return false;
				}

				//Create the user session
				$this->createSession();

				return true;
			}

		}
		//Wrong password / no account
		$this->errorMessage = 'Email and password do not match';

		return false;
	}


	/**
	 * Creates a new session
	 *
	 * @return 		none
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function createSession()
	{
		//Delete all old session(s) for this ip address
		$this->deleteSession();

		//Create unique session id
		$sid = $this->generate_sid();

		//Create $data array for the sql query
		$data = array(
			'sid' => $sid,
			'uid' => $this->uid,
			'ip_address' => $this->ci->input->ip_address(),
			'time' => time()
		);

		//Insert $data into database
		$this->ci->authenticationModel->insertSession($data);

		//Create session cookie
		$this->generate_sid_cookie($sid);
	}


	/**
	 * Generates a session id (called form createSession())
	 *
	 * @return 		none
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function generate_sid()
	{
		$length = 64;
		do
		{
			//Get random string and save it as $sid
			$sid = $this->generateRandomString($length);
			$length++;
		}while($this->unique_sid($sid) == FALSE);

		return $sid;
	}


	/**
	 * Makes sure that the generated sid from generate_sid() is unique
	 *
	 * @return 		none
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function unique_sid($sid)
	{
		//Check if string is unique
		if($this->ci->authenticationModel->checkUniqueSid($sid))
		{
			return true;
		}else{
			return false;
		}
	}


	/**
	 * Generates the session cookie to identify the user when he refreshes the page
	 *
	 * @return 		none
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function generate_sid_cookie($sid)
	{
		$sid = $this->encrypt($sid);
		set_cookie('sid', $sid, 3600);
	}

	/**
	 * Deletes all session from the current ip address
	 *
	 * @return 		none
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function deleteSession()
	{
		//Delete session cookie
		delete_cookie('sid');

		//Delete session form database
		$this->ci->authenticationModel->deleteSession($this->ci->input->ip_address());
	}


	/**
	 * Encrypts a string with mcrypt and the given key in config/custom.php
	 *
	 * @return 		string		Generated string
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	 private function encrypt($string)
	 	{
	     	/*$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	     	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	     	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, trim(file_get_contents(APPPATH.'config/encrypt.key')), $string, MCRYPT_MODE_ECB, $iv));*/
	 			$output = false;
	 	    $encrypt_method = "AES-256-CBC";
	 	    $secret_key = trim(file_get_contents(APPPATH.'config/encrypt.key'));
	 	    $secret_iv = 'VerySecretIV';
	 	    // hash
	 	    $key = hash('sha256', $secret_key);

	 	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	 	    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	       $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	       $output = base64_encode($output);
	 	    return $output;
	 	}
	private function encryptSHA($data){
		return hash('sha256', $data);
	}


	/**
	 * Decrypts a string with mcrypt and the given key in config/custom.php
	 *
	 * @return 		string		Generated string
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	 private function decrypt($string)
 	{
     	/*$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
     	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
 		//Use base64_decode here because the string was encoded in $this->encrypt to save it in the database
     	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, trim(file_get_contents(APPPATH.'config/encrypt.key')), base64_decode($string), MCRYPT_MODE_ECB, $iv));*/

 			$output = false;
 	    $encrypt_method = "AES-256-CBC";
 			$secret_key = trim(file_get_contents(APPPATH.'config/encrypt.key'));
 	    $secret_iv = 'VerySecretIV';
 	    // hash
 	    $key = hash('sha256', $secret_key);

 	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
 	    $iv = substr(hash('sha256', $secret_iv), 0, 16);
 	    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
 	    return $output;
 	}


	/**
	 * Generates a random string for the createSession() method
	 *
	 * @return 		string		Generated string
	 * @access		private
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function generateRandomString($length)
	{
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        	$randomString .= $characters[rand(0, $charactersLength - 1)];
    	}

		return $randomString;
	}

	/**
	 * Retrieves all necessary user info for use in the controllers or template enginee
	 *
	 * @return 		none
	 * @access		public
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	private function getUserInformation($uid){
		$data = $this->ci->authenticationModel->userInformation($uid);
		$this->email = $data['email'];
		$this->firstname = $data['firstname'];
		$this->lastname = $data['lastname'];
		$this->user_group = $data['group_id'];
		$this->premium = $data['premium'];
		$this->premium_until = $data['premium_until'];
		$this->active = $data['active'];

		$this->permissions = $this->ci->PermissionInteraction->groupPermissions($this->user_group);
	}

	/**
	 * Checks if an user has administrative permissions
	 *
	 * @return 		boolean | True or False
	 * @access		public
	 * @author		Niklas (Nytaso)
	 * @copyright	Nytaso.pw 2016
	 * @since 		1.0 (initial release)
	 */
	 public function is_admin(){
		 if($this->ci->authenticationModel->check_member_of($this->uid, 'administrative') == True){
			 return true;
		 }
		 return false;
	 }

	 /**
 	 * Checks if an user is allowed to access the backend
 	 *
 	 * @return 		boolean | True or False
 	 * @access		public
 	 * @author		Niklas (Nytaso)
 	 * @copyright	Nytaso.pw 2016
 	 * @since 		1.0 (initial release)
 	 */
 	 public function is_staff(){
 		 if($this->ci->authenticationModel->check_member_of($this->uid, 'staff') == True){
 			 return true;
 		 }
 		 return false;
 	 }

	 /**
 	 * Deletes an user session (cookie and database record)
 	 *
 	 * @return 		boolean | True or False
 	 * @access		public
 	 * @author		Niklas (Nytaso)
 	 * @copyright	Nytaso.pw 2016
 	 * @since 		1.0 (initial release)
 	 */
	 public function logout(){
		 $this->deleteSession();
		 return true;
	 }

	 /**
 	 * Updates the users credentials
 	 *
 	 * @return 		true
 	 * @access		public
 	 * @author		Niklas (Nytaso)
 	 * @copyright	Nytaso.pw 2016
 	 * @since 		1.0 (initial release)
 	 */
	 public function update($uid, $data){
		 //Encrypt the password if it's set
		 if(isset($data['password'])){
			 //Encrypt the password
			 $data['password'] = $this->encrypt($data['password']);
		 }
		 $this->ci->authenticationModel->update($uid, $data);
		 return true;
	 }

	 /**
 	 * Checks if the supplied data (array) is unique in the user database
 	 *
 	 * @return 		true
 	 * @access		public
 	 * @author		Niklas (Nytaso)
 	 * @copyright	Nytaso.pw 2016
 	 * @since 		1.0 (initial release)
 	 */
	 public function checkUniqueCredentials($data, $id=null){
		 if($id !== NULL){
			 $user_id = $id;
		 }else{
			 $user_id = '';
		 }
		 foreach($data as $key => $d){
			 if(!$this->ci->authenticationModel->checkUnique($key, $d, $user_id) == true){
				 return false;
			 }
		 }
		 return true;
	 }

	 /**
 	 * Registers a new user and adds him to the specified group
 	 *
 	 * @return 		true
 	 * @access		public
 	 * @author		Niklas (Nytaso)
 	 * @copyright	Nytaso.pw 2016
 	 * @since 		1.0 (initial release)
 	 */
	 public function register($credentials){
		 //Check if the username and the email address are unique
		 if($this->ci->authenticationModel->checkUnique('email', $credentials['email']) == TRUE){
			 //Check if the group parameter is present
			 if(isset($credentials['group'])){
				 $group = $credentials['group'];
				 unset($credentials['group']);
			 }else{
				 //Set the default group
				 $group = $this->ci->PermissionInteraction->defaultGroup();
			 }
			 //Encrypt the user password
			 $credentials['password'] = $this->encryptSHA($credentials['password']);
			 //Create the user
			 $user_id = $this->ci->authenticationModel->createUser($credentials);
			 $this->ci->authenticationModel->setUserGroup($user_id, $group);
			 return $user_id;
		 }
	 }

	 /**
 	 * Deletes an existing user
 	 *
 	 * @return 		true
 	 * @access		public
 	 * @author		Niklas (Nytaso)
 	 * @copyright	Nytaso.pw 2016
 	 * @since 		1.0 (initial release)
 	 */
	 public function delete_user($user_id){
		 $this->ci->authenticationModel->delete($user_id);
		 return true;
	 }

	 //Generates a reset code for a password reset
	 public function resetpw($email){
		 //Check if the user exists
		 return $this->ci->authenticationModel->reset_password($email, $this->generateRandomString(12));
	 }

	 //Checks if an reset code exits and sets a new password if specified
	 public function reset_code($code, $new_password=NULL){
		 	if($this->ci->authenticationModel->reset_code($code) == TRUE){
				if($new_password==NULL){
					return true;
				}else{
					//Set a new password
					$this->ci->authenticationModel->reset_code($code, $this->encryptSHA($new_password));
					return true;
				}
			}
	 }

	 public function setUserGroup($user_id, $group){
		 $this->ci->authenticationModel->setUserGroup($user_id, $group);
		 return true;
	 }

	 public function has_permission($permission, $return = true){
		 if($this->is_admin() == FALSE){
			 if($this->permissions[$permission] == TRUE){
				 return true;
			 }else{
				 if($return == true){
					 ob_clean();
					 echo 'You do not have permission to view this page!';
					 exit();
				 }else{
					 return false;
				 }
			 }
		 }else{
			 return true;
		 }
		 return false;
	 }
}

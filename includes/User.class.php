<?php

class User{

	private $orm;

	/**
	 * Find a user by a token string. Only valid tokens are taken into
	 * consideration. A token is valid for 7 days after it has been generated.
	 * @param string $token The token to search for
	 * @return User
	 */
	public static function findByToken($token){

		$result = ORM::for_table('users')
						->where('token', $token)
						->where_raw('token_validity > NOW()')
						->find_one();

		if(!$result){
			return false;
		}

		return new User($result);
	}


	/**
	 * Either login or register a user.
	 * @param string $email The user's email address
	 * @return User
	 */
	public static function loginOrRegister($email){

		if(User::exists($email)){
			return new User($email);
		}

		return User::create($email);
	}

	/**
	 * Create a new user and save it to the database
	 * @param string $email The user's email address
	 * @return User
	 */
	private static function create($email){

		$result = ORM::for_table('users')->create();
		$result->email = $email;
		$result->save();

		return new User($result);
	}


	/**
	 * Check whether a user exists in the database and return a boolean.
	 * @param string $email The user's email address
	 * @return boolean
	 */
	public static function exists($email){
		$result = ORM::for_table('users')
					->where('email', $email)
					->count();

		return $result == 1;
	}


	/**
	 * Create a new user object
	 * @param $param ORM instance, id, email or null
	 * @return User
	 */
	public function __construct($param = null){

		if($param instanceof ORM){

			$this->orm = $param;

		} else if(is_string($param)){

			$this->orm = ORM::for_table('users')
							->where('email', $param)
							->find_one();
		} else{

			$id = 0;

			if(is_numeric($param)){
				$id = $param;
			} else if(isset($_SESSION['loginid'])){
				$id = $_SESSION['loginid'];
			}

			$this->orm = ORM::for_table('users')
							->where('id', $id)
							->find_one();
		}

	}

	/**
	 * Generates a new SHA1 login token, writes it to the database and returns it.
	 * @return string
	 */
	public function generateToken(){
		// generate a token for the logged in user. Save it to the database.

		$token = sha1($this->email.time().rand(0, 1000000));

		// Save the token to the database,
		// and mark it as valid for the next 7 days only

		$this->orm->set('token', $token);
		$this->orm->set_expr('token_validity', "ADDTIME(NOW(),'168:00')");
		$this->orm->save();

		return $token;
	}

	/**
	 * Login this user
	 * @return void
	 */
	public function login(){

		// Mark the user as logged in
		$_SESSION['loginid'] = $this->orm->id;

		// Update the last_login db field
		$this->orm->set_expr('last_login', 'NOW()');
		$this->orm->save();
	}


	/**
	 * Destroy the session and logout the user.
	 * @return void
	 */
	public function logout(){
		$_SESSION = array();
		unset($_SESSION);
	}


	/**
	 * Check whether the user is logged in.
	 * @return boolean
	 */
	public function loggedIn(){
		return isset($this->orm->id) && $_SESSION['loginid'] == $this->orm->id;
	}


	/**
	 * Check whether the user is an administrator
	 * @return boolean
	 */
	public function isAdmin(){
		return $this->rank() == 'admin' || $this->rank() == 'admin, treasurer';
	}


	/**
	 * Check whether the user is a treasurer
	 * @return boolean
	 */
	public function isTreasurer(){
		return $this->rank() == 'treasurer' || $this->rank() == 'admin, treasurer';
	}

	public function isBoth() {
		return $this->rank() == 'admin, treasurer';
	}

	/**
	 * Find the type of user. It can be admin, treasurer or regular.
	 * @return string
	 */
	public function rank(){
		if ($this->orm->rank == 2) {
			return 'admin';
		} else if ($this->orm->rank == 1) {
			return 'treasurer';
		} else if ($this->orm->rank == 3) {
			return 'admin, treasurer';
		}

		return 'regular';
	}


	/**
	 * Helper method for accessing the elements of the private
	 * $orm instance as properties of the user object
	 * @param string $key The accessed property's name
	 * @return mixed
	 */
	public function __get($key){
		if(isset($this->orm->$key)){
			return $this->orm->$key;
		}

		return null;
	}
}
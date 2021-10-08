<?php


require_once '../dbconnect.php';

class UserLogic
{
	/**
	 * ユーザを登録する
	 * @param array $userData
	 * @return bool $result
	 */
	public static function createUser($userData)
	{
		$result = false;
		$sql = 'INSERT INTO users (name, email,
		password) VALUES (?, ?, ?)';

//ユーザデータを配列に入れる
$arr = [];
$arr[] =$userData['username']; //name
$arr[] = $userData['email'];//email
$arr[] =password_hash($userData['password'],
PASSWORD_DEFAULT);//password
try{
	$stmt= connect()->prepare($sql);
	$result = $stmt->execute($arr);
	return $result;
}catch(\Exception $e) {
return $result;
}

	}

		/**
	 * ログイン処理
	 * @param string $email
	 * @param string $password
	 * @return bool $result
	 */
	public static function login($email,$password)
	{
		//結果
		$result = false;
		//ユーザをemailから検索して取得
		$user = self::getUserBYEmail($email);
		//var_dump($user);
		//return;

		if (!$user){
			$_SESSION['msg'] = 'emailが一致しません';
			return $result;
		}

		//パスワードの照会
		if (password_verify($password, $user['password'])){
			//ログイン成功
			session_regenerate_id(true);
			$_SESSION['login_user']= $user;
			$result = true;
			return $result;
		}
		$_SESSION['msg'] = 'パスワードが一致しません';
			return $result;
	}
			/**
	 * emailからユーザを取得
	 * @param string $email
	 * @return array|bool $user|false 
	 */
	public static function getUserBYEmail($email)
	{
		$sql = 'SELECT * FROM users WHERE email = ?';

//ユーザデータを配列に入れる
$arr = [];
$arr[] =$email;

try{
	$stmt= connect()->prepare($sql);
$stmt->execute($arr);
$user = $stmt->fetch();
	return $user;
}catch(\Exception $e) {
return false;
}

	}
				/**
	 * ログインチェック
	 * @param void
	 * @return bool $result
	 */
	public static function checkLogin()
	{
		$result = false;

		//セッションにログインユーザが入っていないかったらfalse
		if (isset($_SESSION['login_user']) &&
		$_SESSION['login_user']['id'] > 0) {
			return $result = true;
		}

		return $result;

	}

/**
 * ログアウト処理
 * 
 */
public static function logout()
{
	$_SESSION = array();
if (ini_get("session.use_cookies")) {

	@session_destroy();
}

}}
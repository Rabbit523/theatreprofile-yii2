<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User {
	public $verifyPassword;
	public $verifyCode;
	
	public function rules() {
		$rules = array(
			array('username, password, verifyPassword, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 8,'message' => UserModule::t("Invalid user name or email address (length between 8 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 8,'message' => UserModule::t("Invalid password (minimum length is 8 characters).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => UserModule::t("User name already exists.")),
			array('email', 'unique', 'message' => UserModule::t("Email address already registered with an account.")),
			//array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Retype Password is incorrect.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Invalid characters (A-z0-9).")),
		);
		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')) {
			array_push($rules,array('verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')));
		}
		
		array_push($rules,array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Passwords do not match.")));
		return $rules;
	}
	
}
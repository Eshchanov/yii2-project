<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;

	private $_user = false;


	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['username', 'password'], 'required'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			$date = date('Y-m-d');
			$dateBegin = null;
			$dateEnd = null;
			$blockUserId = null;
			if ($user)
			{
				if ($user->organ)
				{
					if ($user->organ->date_begin_block)
					{
						$dateBegin = $user->organ->date_begin_block;
						$blockUserId = $user->organ->block_user_id;
					}
					if ($user->organ->date_end_block)
					{
						$dateEnd = $user->organ->date_end_block;
						$blockUserId = $user->organ->block_user_id;
					}

					$organ = $user->organ;
					if ($organ and ($organ->status == 3 or $organ->status == 4))
					{
						$this->addError($attribute, 'Ваш аккаунт заблокирован. Обратитесь к администратору. 71 202 00 11 (1019, 1042)');
					}
				}
			}
			if (!$user || !$user->validatePassword($this->password))
			{
				$this->addError($attribute, 'Логин или пароль неправильно.');
			}
			elseif ($user->status == 2)
			{
				date_default_timezone_set('Asia/Tashkent');
				$time = date('H:i:s');
				$day_week = date('D');
				$today = date('Y-m-d');
				
				if ($day_week == "Sun")
				{ // yakshanba
					$this->addError($attribute, 'Сегодня нерабочий день');
				}
				// elseif ($day_week == "Sat")
				// { // shanba
				// 	$this->addError($attribute, 'Сегодня нерабочий день');
				// }
				else
				{
					if ($time >= '18:00:00' or $time <= '09:00:00') {
						$this->addError($attribute, 'Сейчас нерабочее время');
					}
				}
			}
			elseif ($user->status == 3)
			{
				$this->addError($attribute, 'Ваш аккаунт заблокирован. Обратитесь к администратору. 71 202 00 11 (1019, 1042)');
			}
			elseif ($user->status == 4)
			{
				$this->addError($attribute, 'Ваш аккаунт заблокирован. Обратитесь к администратору. 71 202 00 11 (1019, 1042)');
			}
			elseif ($date >= $dateBegin and $date <= $dateEnd)
			{
				if ($blockUserId == 2) {
					$this->addError($attribute, 'Ваш аккаунт заблокирован. Обратитесь по номеру. 71 202 00 11 (1019, 1042)');
				}
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return bool whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
			// return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 0 : 0);
		}
		return false;
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$this->_user = UsersUser::findByUsername($this->username);
		}

		return $this->_user;
	}

	public function attributeLabels()
	{
		return [
			'username' => Yii::t('app', 'username'),
			'password' => Yii::t('app', 'password'),
			'rememberMe' => Yii::t('app', 'rememberMe'),
		];
	}
}

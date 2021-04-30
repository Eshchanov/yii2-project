<?php
namespace app\components;

use Yii;
use yii\web\Controller;
use app\models\UsersUser;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BaseController extends Controller
{
	public function __construct($id, $module, $config = array()) {
		
		date_default_timezone_set('Asia/Tashkent');
		$user_id = Yii::$app->user->id;
		if ($user_id)
		{
			
			$user = UsersUser::findOne($user_id);
			$session_id = Yii::$app->session->getId();

			if ($user->session_id != $session_id)
			{
				Yii::$app->user->logout();
				Yii::$app->session->setFlash('error', 'Из вашего логина был другой вход!');
			}

			$userDate = $user->active_until;
			
			// $userDate = date('Y-m-d', strtotime($userDate));
			$today = date('Y-m-d');

			if ($today >= $userDate and $user->status == 1)
			{
				$user->status = 2;
				$user->save(false);
			}

			$status = $user->status;

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
				}

				$organ = $user->organ;
				if ($organ and ($organ->status == 3 or $organ->status == 4))
				{
					Yii::$app->user->logout();
					Yii::$app->session->setFlash('error', 'Ваш аккаунт заблокирован. Обратитесь по номеру. 71 202 00 11 (1019, 1042)');
				}
			}
			
			if ($status == 2)
			{
				$time = date('H:i:s');
				$day_week = date('D');
				
				if ($day_week == "Sun")
				{ // yakshanba
					Yii::$app->user->logout();
				}
				// elseif ($day_week == "Sat")
				// { // shanba
				// 	Yii::$app->user->logout();
				// }
				else
				{
					if ($time >= '18:00:00' or $time <= '09:00:00')
					{
						Yii::$app->user->logout();
					}
				}
			}
			elseif ($status == 3) {
				Yii::$app->user->logout();
			}
			elseif ($status == 4) {
				Yii::$app->user->logout();
			}
			elseif ($date >= $dateBegin and $date <= $dateEnd)
			{
				Yii::$app->user->logout();
			}
		}

		parent::__construct($id, $module, $config);
	}

	public function behaviors()
	{
		$user_id = Yii::$app->user->id;
		if ($user_id) {
			
			$type = Yii::$app->user->identity->type_id;
			$action = Yii::$app->controller->action->id;
			
			if ($type == 8 or $type == 2) {
				if ($action == 'create' or $action == 'update' or $action == 'delete' or $action == 'blank') {
					throw new \yii\web\ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
				}
			}
		}

		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
					'delete' => ['post'],
				],
			],
		];
	}
}
?>
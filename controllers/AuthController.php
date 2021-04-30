<?php

namespace app\controllers;

use Yii;
use yii\httpclient\Client;
use app\controllers\BaseController;
use app\components\EsiClient;
use app\models\UsersEsi;
use app\models\UsersUser;

class AuthController extends BaseController
{
	public function actionAuth()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$code = Yii::$app->getRequest()->get('code');
		$resutl = [];
		
		if (!$code)
		{
			$newParams = Yii::$app->controller->getRoute();

	        $url = Yii::$app->getUrlManager()->createAbsoluteUrl($newParams);

	        $redirectUrl = 'https://esi.uz/oauth2/authorize?client_id=969E356D0D51A249&scope=public-info+certificate-info&response_type=code&state={"custom":"state"}&redirect_uri=' . $url;

	        Yii::$app->getResponse()->redirect($redirectUrl);
		}
		else
		{
        	$code = Yii::$app->getRequest()->get('code');
        	$oauthClient = new EsiClient();
        	$accessToken = $oauthClient->fetchAccessToken($code);

        	if ($accessToken)
        	{
	        	$client = new Client([
					'transport' => 'yii\httpclient\CurlTransport'
				]);

				$response = $client->createRequest()
					->setMethod('GET')
					->addHeaders(['Authorization' => 'Bearer ' . $accessToken])
					->setUrl('https://esi.uz/oauth2/api?get=certificate-info')
					->send();
        		
				if (isset($response) and $response->isOk)
				{
					$esiCertificateGiven = json_decode($response->content, true);
					if (isset($esiCertificateGiven['subjectName']))
					{
						$subjectNames = explode(',', $esiCertificateGiven['subjectName']);
						foreach ($subjectNames as $subjectName) {
							$subjectName = explode('=', $subjectName);
							if (isset($subjectName[0]) and isset($subjectName[1])) {
								$resutl[$subjectName[0]] = $subjectName[1];
							}
						}
					}

					$esi = null;
					$flagYurLitso = false;
					$flagUser = false;

					if (isset($resutl['UID']))
					{
						$esi = UsersEsi::findOne(['user_uid_tin' => $resutl['UID']]);

						if ($esi)
						{
							$flagUser = true;
						}
						else
						{
							$esi = new UsersEsi();
						}
					}
					else
					{
						$esi = new UsersEsi();
					}

					$esi->full_name = isset($resutl['CN']) ? $resutl['CN'] : null;
					$esi->name = isset($resutl['Name']) ? $resutl['Name'] : null;
					$esi->surname = isset($resutl['SURNAME']) ? $resutl['SURNAME'] : null;
					$esi->organization_name = isset($resutl['O']) ? $resutl['O'] : null;
					$esi->district = isset($resutl['L']) ? $resutl['L'] : null;
					$esi->region = isset($resutl['ST']) ? $resutl['ST'] : null;
					$esi->republic = isset($resutl['C']) ? $resutl['C'] : null;
					$esi->user_uid_tin = isset($resutl['UID']) ? $resutl['UID'] : null;
					$esi->pinfl = isset($resutl['1.2.860.3.16.1.2']) ? $resutl['1.2.860.3.16.1.2'] : null;
					$esi->position = isset($resutl['T']) ? $resutl['T'] : null;
					$esi->organization_uid_tin = isset($resutl['1.2.860.3.16.1.1']) ? $resutl['1.2.860.3.16.1.1'] : null;
					$esi->business_category = isset($resutl['BusinessCategory']) ? $resutl['BusinessCategory'] : null;
					if ($flagUser)
					{
						$esi->updated_at = date('Y-m-d H:i:s');
					}
					else
					{
						$esi->created_at = date('Y-m-d H:i:s');
						$esi->updated_at = date('Y-m-d H:i:s');
					}

					if ($esi->organization_uid_tin)
					{
						$flagYurLitso = true;
						$esi->user_type = 'Legal';
					}
					else
					{
						$esi->user_type = 'Individual';
					}

					$user = UsersUser::findOne(['inn' => $esi->user_uid_tin]);

					if ($user)
					{
						$esi->user_id = $user->id;
						if ($esi->save() && Yii::$app->user->login($user, 0))
						{
							$session_id = Yii::$app->session->getId();
							$user->session_id = $session_id;
							$user->save(false);

							return $this->goHome();
						}
						else
						{
							Yii::$app->session->setFlash('error', 'Ваша ЭЦП не зарегистрирована');
							return $this->redirect(['login']);
						}
					}
					else
					{
						$esi->save();
						Yii::$app->session->setFlash('error', 'Ваша ЭЦП не зарегистрирована');
						return $this->redirect(['login']);
					}
				}
        	}
		}
	}

	public function actionAcceptedApplications($organ_ids = null)
	{
		$searchModel = new ZayavkaSearch();
		$params = Yii::$app->request->queryParams;

		$user_id = Yii::$app->user->id;

		$type = Yii::$app->user->identity->type->type;
		$type_id = Yii::$app->user->identity->type_id;

		$organ = new OrganFilter();

		if (!($type == 'superadmin' or $type == 'admin' or $type_id == 8)) {
			$params['ZayavkaSearch']['user_id'] = Yii::$app->user->identity->organ_id;
		}
		elseif ($user_id == 4 or $user_id == 658) {
			if ($organ_ids) {
				$organ_ids = explode(',', $organ_ids);
				$organ_ids = $organ_ids;
			}
			else {
				$organ_ids = UsersOrgans::find()
								->where(['organ_id' => 1003])
								->all();
				$organ_ids = ArrayHelper::map($organ_ids, 'id', 'id');
			}

			if ($organ->load(Yii::$app->request->post())) {
				$organ_ids = $organ->organ_id;
			}
			else {
				$organ->organ_id = $organ_ids;
			}
			$params['ZayavkaSearch']['user_id'] = $organ_ids;
		}

		$params['ZayavkaSearch']['oc_new'] = 1;

		$dataProvider = $searchModel->search($params);

		return $this->render('accepted-applications', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'organ' => $organ,
		]);
	}

	public function actionLogin()
	{
		$this->layout = 'login';
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			
			$user_id = Yii::$app->user->id;
			
			
			$user = UsersUser::findOne($user_id);
			$session_id = Yii::$app->session->getId();
			$user->session_id = $session_id;
			$user->save(false);
			
			return $this->goBack();
		}
		return $this->render('login', [
			'model' => $model,
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}
}

?>
<?php

namespace backend\controllers;

use Yii;
use common\models\Setting;
use common\models\User;
use common\models\Country;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\AccountForm;
/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'box';
        
        $user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest) {
            if($user && $user->has2fa && !Yii::$app->session->get('authen_2fa')){
                return $this->redirect(['/authen']);
            }
        }
        
        $model = new AccountForm();
        $model->username = $user->username;
        $model->email = $user->email;
        if ($model->load($_POST) && $model->validate()) {
            $user->username = $model->username;
            $user->email = $model->email;
            if ($model->password) {
                $user->setPassword($model->password);
            }
            $user->save();
            Yii::$app->session->setFlash('alert', [
                'options'=>['class'=>'alert-success'],
                'body'=>Yii::t('backend', 'Your account has been successfully saved')
            ]);
            return $this->refresh();
        }
        
        $profile = Yii::$app->user->identity->userProfile;
        if ($profile->load($_POST) && $profile->save()) {
            Yii::$app->session->setFlash('alert', [
                'options'=>['class'=>'alert-success'],
                'body'=>Yii::t('backend', 'Your profile has been successfully saved', [], $profile->locale)
            ]);
            return $this->refresh();
        }
        $referrer = null;
        if($user->referrer){
            $referrer = User::findOne($user->referrer);
        }
        return $this->render('index', ['model'=>$model,'profile'=>$profile,'referrer'=> $referrer,'countries'=>Country::find()->active()->all()]);
    }



    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

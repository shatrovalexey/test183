<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;
use common\models\{User, Language, Profile, Schedule};

/**
 * API переводчиков
 */
class TranslatorController extends ActiveController
{
    public $modelClass = User::class;

    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET");

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [Response::FORMAT_JSON,];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return ['error' => ['class' => \yii\web\ErrorAction::class,],];
    }

    public function actionIndex()
    {
        return $this->actionList();
    }

    /**
    * Список переводчиков
    */
    public function actionList()
    {
        return User::find()
            ->select(['id as translator_id', 'username',])
            ->asArray()
            ->all();
    }

    /**
    * Список доступных языков
    */
    public function actionLanguage()
    {
        return Language::find()
            ->select(['language.id', 'language.title',])
            ->innerJoin('profile', 'language.id IN (profile.language1_id, profile.language2_id)')
            ->distinct()
            ->orderBy('language.title')
            ->all();
    }

    /**
    * Рабочее расписание переводчика по переводу с языка1 на язык2
    *
    * @param int $translator_id
    * @param int $language1_id
    * @param int $language2_id
    */
    public function actionSchedule(int $translator_id, int $language1_id, int $language2_id)
    {
        return Schedule::find()
            ->select(['profile_id', 'date_at', 'is_vikhodnoy',])
            ->innerJoin('profile', 'schedule.profile_id = profile.id')
            ->where([
                'profile.user_id' => $translator_id
                , 'profile.language1_id' => $language1_id
                , 'profile.language2_id' => $language2_id
                ,
            ])->asArray()->all();
    }

    /**
    * С какого на какой язык переводит переводчик
    *
    * @param int $translator_id
    */
    public function actionProfile(int $translator_id)
    {
        return Profile::find()
            ->innerJoin('language l1', 'l1.id = profile.language1_id')
            ->innerJoin('language l2', 'l2.id = profile.language2_id')
            ->select([
                'l1.id AS language1_id'
                , 'l2.id AS language2_id'
                , 'l1.title AS language1_title'
                , 'l2.title AS language2_title'
                ,
            ])
            ->where(['user_id' => $translator_id,])
            ->asArray()
            ->all();
    }

    /**
    * С какого на какой язык переводят переводчики
    *
    * @param int $translator_id
    */
    public function actionLanguages()
    {
        return Profile::find()
            ->innerJoin('language l1', 'l1.id = profile.language1_id')
            ->innerJoin('language l2', 'l2.id = profile.language2_id')
            ->select([
                'l1.id AS language1_id'
                , 'l2.id AS language2_id'
                , 'l1.title AS language1_title'
                , 'l2.title AS language2_title'
                ,
            ])
            ->distinct()
            ->asArray()
            ->all();
    }

    /**
    * Рабочее расписание переводчика по переводу с языка1 на язык2 или наоборот
    *
    * @param int $language1_id
    * @param int $language2_id
    */
    public function actionLangFromTo(int $language1_id, int $language2_id)
    {
        return Yii::$app->db->createCommand('
SELECT
    `s1`.`date_at`
    , `s1`.`is_vikhodnoy`
FROM
    `profile` AS `p1`
    
        INNER JOIN `schedule` AS `s1`
            ON (`s1`.`profile_id` = `p1`.`id`)
WHERE
    (`p1`.`language1_id` IN(:language1_id, :language2_id))
        AND (`p1`.`language2_id` IN (:language1_id, :language2_id))
ORDER BY
    1 ASC;
        ')->bindValues([':language1_id' => $language1_id, ':language2_id' => $language2_id,])->queryAll();
    }

    /**
    * Список перевочиков с указанного языка или наоборот
    *
    * @param int $language_id
    */
    public function actionLangFromOrTo(int $language_id)
    {
        return Yii::$app->db->createCommand('
SELECT
    `p1`.`user_id` AS `translator_id`
    , `s1`.`date_at`
    , `s1`.`is_vikhodnoy`
FROM
    `profile` AS `p1`
    
        INNER JOIN `schedule` AS `s1`
            ON (`s1`.`profile_id` = `p1`.`id`)
WHERE
    (:language_id IN (`p1`.`language1_id`, `p1`.`language2_id`));
        ')->bindValues([':language_id' => $language_id,])->queryAll();
    }
}

<?php
namespace console\controllers;

use yii\console\Controller;
use Faker\Factory as FakerFactory;
use common\models\{User, Language, Profile, Schedule};
use Ramsey\Uuid;

class FakeDataController extends Controller
{
   public function actionUser($language_count = 10, $user_count = 100, $duration = 365, $probability = 0.5)
   {
        $faker = FakerFactory::create();
        $languages = [];
        $users = [];
        $profiles = [];
        $probability *= mt_getrandmax();
        $day = 60 * 60 * 24;
        $time = $duration * $day;
        $result = 0;

       { // языки
            error_log('languages');
            while ($language_count -- > 0) {
                $obj = new Language(['title' => $faker->word,]);

                if (!$obj->save()) continue;

                $languages[] = $obj->id;

                error_log("languages: {$obj->id}");
            }
        }

       { // пользователи
            error_log('users');
            while ($user_count -- > 0) {
                $auth_key = md5(rand());
                $obj = new User([
                    'username' => $faker->userName
                    , 'email' => $faker->safeEmail
                    , 'password_hash' => \Yii::$app->security->generatePasswordHash($auth_key)
                    , 'auth_key' => $auth_key
                    ,
                ]);

                try {
                    $obj->save();

                    $users[] = $obj->id;

                    error_log("users: {$obj->id}");
                } catch(\Throwable $exception) {
                    error_log($exception->getMessage());
                }
            }
       }

       { // профили пользователей
            error_log('profiles');
            foreach($users as $user_id) foreach ($languages as $language1_id) foreach ($languages as $language2_id)
                if ($language1_id != $language2_id)
                    if (mt_rand() > $probability) {
                        $obj = new Profile([
                            'user_id' => $user_id
                            , 'language1_id' => $language1_id
                            , 'language2_id' => $language2_id
                            ,
                        ]);

                        if (!$obj->save()) continue;

                        $profiles[$user_id][] = $obj->id;

                        error_log("profiles: {$obj->id}");
                    }
       }

       { // расписание работы
            $was = [];

            error_log('schedules');
            foreach ($profiles as $user_id => &$profile) {
                for ($i = 0; $i < $duration; $i ++) {
                    if (mt_rand() <= $probability) continue;

                    $profile_id = $profile[array_rand($profile)];
                    $date_at = date('Y-m-d', time() + rand(0, $time));
                    $was_key = implode(' / ', [$user_id, $date_at,]);

                    // каждый переводчик в день занимается только одним типом перевода
                    if (array_key_exists($was_key, $was)) continue;

                    $was[$was_key] = true;
                    $obj = new Schedule(['profile_id' => $profile_id, 'date_at' => $date_at,]);

                    if (!$obj->save()) continue;

                    error_log("schedule: {$was_key}");

                    $result ++;
                }
            }
       }

        error_log("done: {$result}");
   }

    /**
    */
    public function actionUserDown()
    {
        User::deleteAll();
        Language::deleteAll();
    }
}
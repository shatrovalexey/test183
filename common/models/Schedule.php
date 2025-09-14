<?php

namespace common\models;

use Yii;

/**
 * Расписание
 *
 * @property int $id
 * @property int $profile_id
 * @property string $datetime_from
 * @property string $datetime_to
 *
 * @property Profile $profile
 */
class Schedule extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'date_at',], 'required',]
            , [['profile_id',], 'integer',]
            , [['profile_id', 'date_at',], 'unique', 'targetAttribute' => ['profile_id', 'date_at',],]
            , [['profile_id',], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'id',],]
            ,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profile_id' => 'Profile ID',
            'date_at' => 'Datetime',
        ];
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['id' => 'profile_id']);
    }

}

<?php

namespace common\models;

use Yii;

/**
 * Язык
 *
 * @property int $id
 * @property string|null $title
 *
 * @property Profile[] $profiles
 * @property Profile[] $profiles0
 */
class Language extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'default', 'value' => null],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::class, ['language1_id' => 'id']);
    }

    /**
     * Gets query for [[Profiles0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles0()
    {
        return $this->hasMany(Profile::class, ['language2_id' => 'id']);
    }

}

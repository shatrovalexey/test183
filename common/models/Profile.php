<?php

namespace common\models;

use Yii;

/**
 * Перевочик переводит с языка1 на язык2
 *
 * @property int $id
 * @property int $user_id
 * @property int $language1_id
 * @property int $language2_id
 *
 * @property Language $language1
 * @property Language $language2
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'language1_id', 'language2_id'], 'required'],
            [['user_id', 'language1_id', 'language2_id'], 'integer'],
            [['user_id', 'language1_id', 'language2_id'], 'unique', 'targetAttribute' => ['user_id', 'language1_id', 'language2_id']],
            [['language1_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language1_id' => 'id']],
            [['language2_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language2_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['language1_id', 'compare', 'compareAttribute' => 'language2_id', 'operator' => '!='],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'language1_id' => 'Language1 ID',
            'language2_id' => 'Language2 ID',
        ];
    }

    /**
     * Gets query for [[Language1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage1()
    {
        return $this->hasOne(Language::class, ['id' => 'language1_id']);
    }

    /**
     * Gets query for [[Language2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage2()
    {
        return $this->hasOne(Language::class, ['id' => 'language2_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}

<?php

use yii\db\Migration;

class m250913_045713_create_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey()
            , 'user_id' => $this->integer()->notNull()
            , 'language1_id' => $this->integer()->notNull()
            , 'language2_id' => $this->integer()->notNull()
            ,
        ]);

        $this->createIndex(
            'idx-user_id_language1_id_language2_id'
            , '{{%profile}}'
            , ['user_id', 'language1_id', 'language2_id',]
            , true
        );
        $this->createIndex('idx-language1_id', '{{%profile}}', 'language1_id');
        $this->createIndex('idx-language2_id', '{{%profile}}', 'language2_id');
        $this->addForeignKey('fk-profile-user_id', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-profile-language1_id', '{{%profile}}', 'language1_id', '{{%language}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-profile-language2_id', '{{%profile}}', 'language2_id', '{{%language}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-profile-user_id', '{{%profile}}');
        $this->dropForeignKey('fk-profile-language1_id', '{{%profile}}');
        $this->dropForeignKey('fk-profile-language2_id', '{{%profile}}');
        $this->dropTable('{{%profile}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250913_045713_create_profile cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%schedule}}`.
 */
class m250913_060818_create_schedule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%schedule}}', [
            'profile_id' => $this->integer()->notNull()
            , 'date_at' => $this->date()->notNull()
            ,
        ]);

        $this->execute('
ALTER TABLE
		{{%schedule}}
ADD COLUMN `is_vikhodnoy` TINYINT UNSIGNED GENERATED ALWAYS AS (dayofweek(`date_at`) in (1,7));
		');

        $this->createIndex(
            'idx-schedule_profile_id_date_at'
            , '{{%schedule}}'
            , ['profile_id', 'date_at',]
            , true
        );
        $this->addForeignKey('fk-profile-profile_id', '{{%schedule}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%schedule}}');
    }
}

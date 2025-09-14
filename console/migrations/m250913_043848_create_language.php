<?php

use yii\db\Migration;

class m250913_043848_create_language extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('{{%language}}', [
			'id' => $this->primaryKey()
			, 'title' => $this->string()
			,
		]);

		$this->createIndex(
			'idx-title'
			, '{{%language}}'
			, 'title'
			, true
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropTable('{{%language}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250913_053848_create_language cannot be reverted.\n";

        return false;
    }
    */
}

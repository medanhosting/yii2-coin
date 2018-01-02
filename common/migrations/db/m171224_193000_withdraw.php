<?php

use common\models\User;
use yii\db\Schema;
use yii\db\Migration;

class m171224_193000_withdraw extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%withdraw}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'sender' => $this->string(),
            'receiver' => $this->string(),
            'amount' => $this->float(),
            'txid' => $this->string(),
            'type' => $this->smallInteger()->defaultValue(1), // 1 bit, 2 tkc
            'status' => $this->integer(1)->defaultValue('0')->unsigned(),
            'manager_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'requested_at' => $this->integer(),
            'completed_at' => $this->integer()
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('{{%withdraw}}');
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201443_logs extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%log_on_urls}}', [
            'id' => Schema::TYPE_PK,
            'date_time' => Schema::TYPE_TIMESTAMP  . ' WITH TIME ZONE NOT NULL',
            'ip' => 'cidr NOT NULL',
            'url_from' => Schema::TYPE_STRING  . ' NOT NULL CHECK (url_from !=\'\')',
            'url_to' => Schema::TYPE_STRING  . ' NOT NULL CHECK (url_from !=\'\')',
        ]);

        $this->createTable('{{%log_on_os}}', [
            'id' => Schema::TYPE_PK,
            'ip' => 'cidr NOT NULL',
            'browser' => Schema::TYPE_STRING  . ' NOT NULL CHECK (browser !=\'\')',
            'os' => Schema::TYPE_STRING  . ' NOT NULL CHECK (os !=\'\')',
        ]);

        $this->createIndex('log_on_urls_ip', '{{%log_on_urls}}', ['ip', 'date_time'], true);
        $this->createIndex('log_on_os_ip', '{{%log_on_os}}', ['ip', 'browser', 'os'], true);
    }

    public function safeDown()
    {
    }
}

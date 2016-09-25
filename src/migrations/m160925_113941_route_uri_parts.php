<?php

namespace craft\app\migrations;

use Craft;
use craft\app\db\Migration;

/**
 * m160925_113941_route_uri_parts migration.
 */
class m160925_113941_route_uri_parts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('{{%routes}}', 'urlParts', 'uriParts');
        $this->renameColumn('{{%routes}}', 'urlPattern', 'uriPattern');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m160925_113941_route_uri_parts cannot be reverted.\n";
        return false;
    }
}

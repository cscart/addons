<?php

use Phinx\Migration\AbstractMigration;

class AddonUpdateVersion extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */

    /**
     * Migrate Up.
     */
    public function up()
    {
        $options = $this->adapter->getOptions();
        $pr = $options['prefix'];

        $this->execute("UPDATE {$pr}addons SET `version` = '1.2' WHERE `addon` = 'upgrade'");

        // Any SQLs can be executed here
        // (!) Cart functions are available here, but you should not use them! Use pure SQL queries.
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $options = $this->adapter->getOptions();
        $pr = $options['prefix'];

        $this->execute("UPDATE {$pr}addons SET `version` = '1.1' WHERE `addon` = 'upgrade'");
        // Any SQLs can be executed here
        // (!) Cart functions are not available here
    }
}

<?php
namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Api data
 */
class Version20131211001428 extends AbstractMigration
{

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `api` VALUES(1, NULL, 'TariochEveapiFetcherEveWorker', 'server', 'ServerStatus', 5);");
        $this->addSql("INSERT INTO `api` VALUES(2, 33554432, 'TariochEveapiFetcherEveWorker', 'account', 'AccountStatus', 120);");
        $this->addSql("INSERT INTO `api` VALUES(3, NULL, 'TariochEveapiFetcherEveWorker', 'account', 'APIKeyInfo', 60);");
        $this->addSql("INSERT INTO `apiCall` SET apiID=1, active=1;");
    }

    /**
     * @inheritdoc
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `api` WHERE apiID IN (1, 2, 3);");
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423205822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_HOTEL ADD CONSTRAINT FK_A2A6B370C4DC529D FOREIGN KEY (USR_ID) REFERENCES MTC_USER (USR_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_NEGOCIATION ADD CONSTRAINT FK_C24C62D4C4DC529D FOREIGN KEY (USR_ID) REFERENCES MTC_USER (USR_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_PAYMENT ADD CONSTRAINT FK_62BCB8F7C4DC529D FOREIGN KEY (USR_ID) REFERENCES MTC_USER (USR_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_RESERVATION ADD CONSTRAINT FK_35651C59C4DC529D FOREIGN KEY (USR_ID) REFERENCES MTC_USER (USR_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_ROOM ADD CONSTRAINT FK_56E1861A7972BF64 FOREIGN KEY (HTL_ID) REFERENCES MTC_HOTEL (HTL_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_ROOM ADD CONSTRAINT FK_56E1861A8EC56762 FOREIGN KEY (RES_ID) REFERENCES MTC_RESERVATION (RES_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_SEARCH_HISTORY ADD CONSTRAINT FK_A30B305AC4DC529D FOREIGN KEY (USR_ID) REFERENCES MTC_USER (USR_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_THRESHOLD ADD CONSTRAINT FK_84AC54D87972BF64 FOREIGN KEY (HTL_ID) REFERENCES MTC_HOTEL (HTL_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_LIKE ADD CONSTRAINT FK_881D9732C4DC529D FOREIGN KEY (USR_ID) REFERENCES MTC_USER (USR_ID)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_LIKE ADD CONSTRAINT FK_881D9732531FC869 FOREIGN KEY (ROOM_ID) REFERENCES MTC_ROOM (ROOM_ID)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_SEARCH_HISTORY DROP FOREIGN KEY FK_A30B305AC4DC529D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_PAYMENT DROP FOREIGN KEY FK_62BCB8F7C4DC529D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_RESERVATION DROP FOREIGN KEY FK_35651C59C4DC529D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_NEGOCIATION DROP FOREIGN KEY FK_C24C62D4C4DC529D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_LIKE DROP FOREIGN KEY FK_881D9732C4DC529D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_LIKE DROP FOREIGN KEY FK_881D9732531FC869
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_ROOM DROP FOREIGN KEY FK_56E1861A7972BF64
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_ROOM DROP FOREIGN KEY FK_56E1861A8EC56762
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_THRESHOLD DROP FOREIGN KEY FK_84AC54D87972BF64
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_HOTEL DROP FOREIGN KEY FK_A2A6B370C4DC529D
        SQL);
    }
}

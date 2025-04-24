<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424184213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_RESERVATION ADD CONSTRAINT FK_35651C5954177093 FOREIGN KEY (room_id) REFERENCES MTC_ROOM (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_ROOM ADD CONSTRAINT FK_56E1861A7972BF64 FOREIGN KEY (HTL_ID) REFERENCES MTC_HOTEL (HTL_ID)
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
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_LIKE DROP FOREIGN KEY FK_881D9732C4DC529D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_LIKE DROP FOREIGN KEY FK_881D9732531FC869
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_RESERVATION DROP FOREIGN KEY FK_35651C5954177093
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_THRESHOLD DROP FOREIGN KEY FK_84AC54D87972BF64
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_ROOM DROP FOREIGN KEY FK_56E1861A7972BF64
        SQL);
    }
}

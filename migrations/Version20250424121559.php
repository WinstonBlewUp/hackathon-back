<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424121559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_HOTEL ADD CAT_ID INT DEFAULT NULL, DROP HTL_CATEGORIE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_HOTEL ADD CONSTRAINT FK_A2A6B3702E182825 FOREIGN KEY (CAT_ID) REFERENCES MTC_CATEGORIE (CAT_ID)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A2A6B3702E182825 ON MTC_HOTEL (CAT_ID)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_HOTEL DROP FOREIGN KEY FK_A2A6B3702E182825
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A2A6B3702E182825 ON MTC_HOTEL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE MTC_HOTEL ADD HTL_CATEGORIE VARCHAR(255) NOT NULL, DROP CAT_ID
        SQL);
    }
}

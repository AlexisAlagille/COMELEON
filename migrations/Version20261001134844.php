<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261001134844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A704850C80E95E18 ON demande_prestation (demande_id)');
        $this->addSql('ALTER TABLE demande_prestation RENAME INDEX FK_DEMANDE_PRESTATION_PRESTATION TO IDX_A704850C9E45C554');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C80E95E18');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C9E45C554');
        $this->addSql('DROP INDEX IDX_A704850C80E95E18 ON demande_prestation');
        $this->addSql('ALTER TABLE demande_prestation RENAME INDEX IDX_A704850C9E45C554 TO FK_DEMANDE_PRESTATION_PRESTATION');
        $this->addSql('ALTER TABLE demande_prestation ENGINE = MyISAM');
    }
}

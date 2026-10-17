<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261017125300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove the unused reponse entity table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS reponse');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, date_reponse DATETIME NOT NULL, demande_id INT NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC780E95E18 (demande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC780E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
    }
}

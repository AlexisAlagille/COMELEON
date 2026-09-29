<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260929120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow users to register without a phone number.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user MODIFY telephone VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE user SET telephone = '' WHERE telephone IS NULL");
        $this->addSql('ALTER TABLE user MODIFY telephone VARCHAR(20) NOT NULL');
    }
}
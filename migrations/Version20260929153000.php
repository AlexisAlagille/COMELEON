<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260929153000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Enable InnoDB and enforce foreign keys for related entities.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE demande ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestation ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role ENGINE = InnoDB');
        $this->addSql('ALTER TABLE statut ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ENGINE = InnoDB');

        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A59E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id_statut)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC780E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id_role)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A76ED395');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A59E45C554');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5F6203804');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC780E95E18');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');

        $this->addSql('ALTER TABLE demande ENGINE = MyISAM');
        $this->addSql('ALTER TABLE prestation ENGINE = MyISAM');
        $this->addSql('ALTER TABLE reponse ENGINE = MyISAM');
        $this->addSql('ALTER TABLE role ENGINE = MyISAM');
        $this->addSql('ALTER TABLE statut ENGINE = MyISAM');
        $this->addSql('ALTER TABLE user ENGINE = MyISAM');
    }
}
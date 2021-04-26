<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210428133713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE besoin ADD id_company INT DEFAULT NULL COMMENT \'Identifiant technique d\'\'un company\'');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E8119122A03F FOREIGN KEY (id_company) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_8118E8119122A03F ON besoin (id_company)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE besoin DROP FOREIGN KEY FK_8118E8119122A03F');
        $this->addSql('DROP INDEX IDX_8118E8119122A03F ON besoin');
        $this->addSql('ALTER TABLE besoin DROP id_company');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329165940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'une answer\', company_id INT DEFAULT NULL COMMENT \'Identifiant technique d\'\'un company\', besoin_id INT DEFAULT NULL COMMENT \'Identifiant technique d\'\'un besoin\', message VARCHAR(1500) NOT NULL, dt_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_DADD4A25979B1AD6 (company_id), INDEX IDX_DADD4A25FE6EED44 (besoin_id), UNIQUE INDEX besoin_company_index (besoin_id, company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Entité représentant une Anwser.\' ');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25FE6EED44 FOREIGN KEY (besoin_id) REFERENCES besoin (id)');
        $this->addSql('ALTER TABLE besoin CHANGE description description VARCHAR(1000) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniqueBesoinStatutCode ON besoin_statut (code)');
        $this->addSql('CREATE UNIQUE INDEX uniqueCompanyStatutCode ON company_statut (code)');
        $this->addSql('DROP INDEX uniquegroupcode ON sous_category');
        $this->addSql('CREATE UNIQUE INDEX uniqueSousCategoryCode ON sous_category (code)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE answer');
        $this->addSql('ALTER TABLE besoin CHANGE description description VARCHAR(2000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX uniqueBesoinStatutCode ON besoin_statut');
        $this->addSql('DROP INDEX uniqueCompanyStatutCode ON company_statut');
        $this->addSql('DROP INDEX uniquesouscategorycode ON sous_category');
        $this->addSql('CREATE UNIQUE INDEX uniqueGroupCode ON sous_category (code)');
    }
}

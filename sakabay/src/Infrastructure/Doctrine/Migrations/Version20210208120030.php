<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210208120030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE besoin (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un besoin\', id_category INT NOT NULL COMMENT \'Identifiant technique d\'\'un category\', id_author INT NOT NULL COMMENT \'Identifiant technique d\'\'un user\', id_besoin_statut INT NOT NULL COMMENT \'Identifiant technique d\'\'un Besoin Statut\', title VARCHAR(191) NOT NULL, description VARCHAR(1000) NOT NULL, dt_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, dt_updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_8118E8115697F554 (id_category), INDEX IDX_8118E8119B986D25 (id_author), INDEX IDX_8118E8119CB4C5D1 (id_besoin_statut), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Entité représentant un Besoin.\' ');
        $this->addSql('CREATE TABLE besoin_sous_categories (besoin_id INT NOT NULL COMMENT \'Identifiant technique d\'\'un besoin\', id_besoin INT NOT NULL COMMENT \'Identifiant technique d\'\'une sous category\', INDEX IDX_238FC76FE6EED44 (besoin_id), INDEX IDX_238FC76A72F5DFC (id_besoin), PRIMARY KEY(besoin_id, id_besoin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE besoin_statut (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un Besoin Statut\', name VARCHAR(191) NOT NULL, code VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Entité représentant le statut d\'\'un besoin.\' ');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E8115697F554 FOREIGN KEY (id_category) REFERENCES category (id)');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E8119B986D25 FOREIGN KEY (id_author) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E8119CB4C5D1 FOREIGN KEY (id_besoin_statut) REFERENCES besoin_statut (id)');
        $this->addSql('ALTER TABLE besoin_sous_categories ADD CONSTRAINT FK_238FC76FE6EED44 FOREIGN KEY (besoin_id) REFERENCES besoin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE besoin_sous_categories ADD CONSTRAINT FK_238FC76A72F5DFC FOREIGN KEY (id_besoin) REFERENCES sous_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE besoin_sous_categories DROP FOREIGN KEY FK_238FC76FE6EED44');
        $this->addSql('ALTER TABLE besoin DROP FOREIGN KEY FK_8118E8119CB4C5D1');
        $this->addSql('DROP TABLE besoin');
        $this->addSql('DROP TABLE besoin_sous_categories');
        $this->addSql('DROP TABLE besoin_statut');
    }
}

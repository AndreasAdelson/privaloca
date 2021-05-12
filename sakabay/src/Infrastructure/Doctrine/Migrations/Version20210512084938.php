<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512084938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advantage (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un avantage\', message VARCHAR(191) NOT NULL, code VARCHAR(100) NOT NULL, priority INT NOT NULL, UNIQUE INDEX uniqueAdvantageCode (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Entité représentant un avantage.\' ');
        $this->addSql('CREATE TABLE subscriptions_advantages (id_subscription INT NOT NULL COMMENT \'Identifiant technique d\'\'un subscription\', id_advantage INT NOT NULL COMMENT \'Identifiant technique d\'\'un avantage\', INDEX IDX_F99CC4C2800711A1 (id_subscription), INDEX IDX_F99CC4C2A9BB9D86 (id_advantage), PRIMARY KEY(id_subscription, id_advantage)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscriptions_advantages ADD CONSTRAINT FK_F99CC4C2800711A1 FOREIGN KEY (id_subscription) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE subscriptions_advantages ADD CONSTRAINT FK_F99CC4C2A9BB9D86 FOREIGN KEY (id_advantage) REFERENCES advantage (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriptions_advantages DROP FOREIGN KEY FK_F99CC4C2A9BB9D86');
        $this->addSql('DROP TABLE advantage');
        $this->addSql('DROP TABLE subscriptions_advantages');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607142428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_status (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un SubscriptionStatus\', name VARCHAR(191) NOT NULL, code VARCHAR(100) NOT NULL, UNIQUE INDEX uniqueSubscriptionStatusCode (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Entité représentant le statut de l entreprise.\' ');
        $this->addSql('ALTER TABLE company_subscription ADD id_subscription_status INT NOT NULL COMMENT \'Identifiant technique d\'\'un SubscriptionStatus\'');
        $this->addSql('ALTER TABLE company_subscription ADD CONSTRAINT FK_5D0BAE1DF41CE150 FOREIGN KEY (id_subscription_status) REFERENCES subscription_status (id)');
        $this->addSql('CREATE INDEX IDX_5D0BAE1DF41CE150 ON company_subscription (id_subscription_status)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_subscription DROP FOREIGN KEY FK_5D0BAE1DF41CE150');
        $this->addSql('DROP TABLE subscription_status');
        $this->addSql('DROP INDEX IDX_5D0BAE1DF41CE150 ON company_subscription');
        $this->addSql('ALTER TABLE company_subscription DROP id_subscription_status');
    }
}

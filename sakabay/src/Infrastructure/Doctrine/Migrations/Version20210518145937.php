<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518145937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique de la méthode de payment\', id_company INT NOT NULL COMMENT \'Identifiant technique d\'\'un company\', stripe_id VARCHAR(100) NOT NULL, last4 VARCHAR(4) NOT NULL, fingerprint VARCHAR(16) NOT NULL, country VARCHAR(2) NOT NULL, INDEX IDX_7B61A1F69122A03F (id_company), UNIQUE INDEX uniquePaymentMethodFingerprint (fingerprint), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Entité pour définir la validité de l\'\'abonnement.\' ');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F69122A03F FOREIGN KEY (id_company) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company_subscription ADD stripe_id VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD stripe_id VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('ALTER TABLE company_subscription DROP stripe_id');
        $this->addSql('ALTER TABLE subscription DROP stripe_id');
    }
}

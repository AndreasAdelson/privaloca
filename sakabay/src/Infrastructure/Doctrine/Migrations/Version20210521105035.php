<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521105035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniquePaymentMethodFingerprint ON payment_method');
        $this->addSql('ALTER TABLE payment_method ADD default_method TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniquePaymentMethodFingerprint ON payment_method (fingerprint, id_company)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniquePaymentMethodFingerprint ON payment_method');
        $this->addSql('ALTER TABLE payment_method DROP default_method');
        $this->addSql('CREATE UNIQUE INDEX uniquePaymentMethodFingerprint ON payment_method (fingerprint)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820134530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE example (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un example\', nom VARCHAR(100) DEFAULT NULL, consigne VARCHAR(300) DEFAULT NULL, PRIMARY KEY(id)) COMMENT = \'Example d\'\'un crud.\' ');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique de la fonction\', code VARCHAR(50) NOT NULL COMMENT \'Code de la fonction\', description VARCHAR(255) NOT NULL COMMENT \'Nom de la fonction\', UNIQUE INDEX uniqueFonctionCode (code), PRIMARY KEY(id)) COMMENT = \'Entité technique regroupant les fonctions de l\'\'application. On associe une fonction à un rôle pour donner à ce rôle les droits sur cette fonction.\' ');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un group\', name VARCHAR(50) NOT NULL COMMENT \'Nom du groupe\', code VARCHAR(255) NOT NULL COMMENT \'Code du groupe\', UNIQUE INDEX uniqueGroupCode (code), PRIMARY KEY(id)) COMMENT = \'Entité technique regroupant les rôles de l\'\'application. On associe un utilisateur à un groupe pour donner à cet utilisateur les droits du groupe.\' ');
        $this->addSql('CREATE TABLE groupes_roles (id_group INT NOT NULL COMMENT \'Identifiant technique d\'\'un group\', id_role INT NOT NULL COMMENT \'Identifiant technique d\'\'un role\', INDEX IDX_5F256D89834505F5 (id_group), INDEX IDX_5F256D89DC499668 (id_role), PRIMARY KEY(id_group, id_role))');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un role\', name VARCHAR(300) NOT NULL, code VARCHAR(100) NOT NULL, PRIMARY KEY(id)) COMMENT = \'Entité représentant un Role.\' ');
        $this->addSql('CREATE TABLE roles_fontions (id_role INT NOT NULL COMMENT \'Identifiant technique d\'\'un role\', id_fonction INT NOT NULL COMMENT \'Identifiant technique de la fonction\', INDEX IDX_2D7645A5DC499668 (id_role), INDEX IDX_2D7645A559DB3928 (id_fonction), PRIMARY KEY(id_role, id_fonction))');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL COMMENT \'Identifiant technique d\'\'un user\', login VARCHAR(300) NOT NULL, email VARCHAR(300) NOT NULL, first_name VARCHAR(300) NOT NULL, password VARCHAR(300) NOT NULL, last_name VARCHAR(300) NOT NULL, UNIQUE INDEX uniqueGroupCode (login, email), PRIMARY KEY(id)) COMMENT = \'Entité représentant un user\' ');
        $this->addSql('CREATE TABLE utilisateurs_groupes (id_user INT NOT NULL COMMENT \'Identifiant technique d\'\'un user\', id_group INT NOT NULL COMMENT \'Identifiant technique d\'\'un group\', INDEX IDX_59950F8C6B3CA4B (id_user), INDEX IDX_59950F8C834505F5 (id_group), PRIMARY KEY(id_user, id_group))');
        $this->addSql('ALTER TABLE groupes_roles ADD CONSTRAINT FK_5F256D89834505F5 FOREIGN KEY (id_group) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE groupes_roles ADD CONSTRAINT FK_5F256D89DC499668 FOREIGN KEY (id_role) REFERENCES role (id)');
        $this->addSql('ALTER TABLE roles_fontions ADD CONSTRAINT FK_2D7645A5DC499668 FOREIGN KEY (id_role) REFERENCES role (id)');
        $this->addSql('ALTER TABLE roles_fontions ADD CONSTRAINT FK_2D7645A559DB3928 FOREIGN KEY (id_fonction) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE utilisateurs_groupes ADD CONSTRAINT FK_59950F8C6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateurs_groupes ADD CONSTRAINT FK_59950F8C834505F5 FOREIGN KEY (id_group) REFERENCES groupe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roles_fontions DROP FOREIGN KEY FK_2D7645A559DB3928');
        $this->addSql('ALTER TABLE groupes_roles DROP FOREIGN KEY FK_5F256D89834505F5');
        $this->addSql('ALTER TABLE utilisateurs_groupes DROP FOREIGN KEY FK_59950F8C834505F5');
        $this->addSql('ALTER TABLE groupes_roles DROP FOREIGN KEY FK_5F256D89DC499668');
        $this->addSql('ALTER TABLE roles_fontions DROP FOREIGN KEY FK_2D7645A5DC499668');
        $this->addSql('ALTER TABLE utilisateurs_groupes DROP FOREIGN KEY FK_59950F8C6B3CA4B');
        $this->addSql('DROP TABLE example');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupes_roles');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE roles_fontions');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateurs_groupes');
    }
}

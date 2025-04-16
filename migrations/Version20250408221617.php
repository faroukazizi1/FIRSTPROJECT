<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408221617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE absence (id_abs INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, nbr_abs INT NOT NULL, type VARCHAR(255) NOT NULL, cin INT NOT NULL, image_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_abs)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE bulletinpaie (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, mois VARCHAR(255) NOT NULL, annee INT NOT NULL, salaire_brut NUMERIC(10, 0) NOT NULL, deductions NUMERIC(10, 0) NOT NULL, salaire_net NUMERIC(10, 0) NOT NULL, date_generation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE demandeconge (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, type_conge VARCHAR(255) DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_demande DATETIME NOT NULL, file_attachment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE formateur (id_formateur INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, nom_f VARCHAR(255) NOT NULL, prenom_f VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, PRIMARY KEY(id_formateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE formation (id_form INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, date_d DATE DEFAULT NULL, date_f DATE DEFAULT NULL, duree INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, id_Formateur INT DEFAULT NULL, INDEX IDX_404021BFEFB240CB (id_Formateur), PRIMARY KEY(id_form)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE penalite (id_pen INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, seuil_abs INT NOT NULL, cin INT NOT NULL, PRIMARY KEY(id_pen)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pret (id_pret INT AUTO_INCREMENT NOT NULL, montant_pret DOUBLE PRECISION NOT NULL, date_pret DATE NOT NULL, tmm DOUBLE PRECISION NOT NULL, taux DOUBLE PRECISION NOT NULL, revenus_bruts DOUBLE PRECISION NOT NULL, age_employe INT NOT NULL, duree_remboursement INT NOT NULL, categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id_pret)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, statut VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_task (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATE NOT NULL, statut VARCHAR(255) NOT NULL, user_id INT NOT NULL, INDEX IDX_6BEF133D166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, type_promo VARCHAR(255) NOT NULL, raison VARCHAR(255) NOT NULL, poste_promo VARCHAR(255) NOT NULL, date_promo DATE DEFAULT NULL, nouv_sal NUMERIC(10, 0) NOT NULL, avantage_supp VARCHAR(255) NOT NULL, INDEX IDX_C11D7DD16B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reponse (id_reponse INT AUTO_INCREMENT NOT NULL, date_reponse DATE NOT NULL, montant_demande DOUBLE PRECISION NOT NULL, revenus_bruts DOUBLE PRECISION NOT NULL, taux_interet DOUBLE PRECISION NOT NULL, mensualite_credit DOUBLE PRECISION NOT NULL, potentiel_credit DOUBLE PRECISION NOT NULL, duree_remboursement INT NOT NULL, montant_autorise DOUBLE PRECISION NOT NULL, assurance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_reponse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, cin INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, numero INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_test (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ADD CONSTRAINT FK_404021BFEFB240CB FOREIGN KEY (id_Formateur) REFERENCES formateur (id_Formateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE formation DROP FOREIGN KEY FK_404021BFEFB240CB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE absence
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE bulletinpaie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE demandeconge
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE penalite
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pret
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE promotion
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reponse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_test
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}

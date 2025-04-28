<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417150117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE absence CHANGE type type VARCHAR(255) NOT NULL, CHANGE image_path image_path VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bulletinpaie DROP cin, CHANGE mois mois VARCHAR(255) NOT NULL, CHANGE salaire_brut salaire_brut NUMERIC(10, 2) DEFAULT NULL, CHANGE deductions deductions NUMERIC(10, 2) DEFAULT NULL, CHANGE salaire_net salaire_net NUMERIC(10, 2) DEFAULT NULL, CHANGE date_generation date_generation DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demandeconge ADD mois INT DEFAULT NULL, ADD annee INT DEFAULT NULL, ADD salaire_brut INT DEFAULT NULL, ADD deductions DOUBLE PRECISION DEFAULT NULL, ADD salaire_net DOUBLE PRECISION DEFAULT NULL, DROP commentaire, DROP file_attachment, CHANGE type_conge type_conge VARCHAR(255) DEFAULT NULL, CHANGE statut statut VARCHAR(255) DEFAULT NULL, CHANGE date_demande date_demande DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX Email ON formateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formateur CHANGE Nom_F nom_f VARCHAR(255) NOT NULL, CHANGE Prenom_F prenom_f VARCHAR(255) NOT NULL, CHANGE Email email VARCHAR(255) NOT NULL, CHANGE Specialite specialite VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation DROP FOREIGN KEY fk_formateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation DROP FOREIGN KEY fk_formateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation CHANGE Description description LONGTEXT DEFAULT NULL, CHANGE id_Formateur id_Formateur INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ADD CONSTRAINT FK_404021BFEFB240CB FOREIGN KEY (id_Formateur) REFERENCES formateur (id_Formateur)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_formateur ON formation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_404021BFEFB240CB ON formation (id_Formateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ADD CONSTRAINT fk_formateur FOREIGN KEY (id_Formateur) REFERENCES formateur (id_Formateur) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_absence ON penalite
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE penalite CHANGE type type VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pret CHANGE Categorie categorie VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX project_id ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX user_id ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task CHANGE description description LONGTEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion CHANGE type_promo type_promo VARCHAR(255) NOT NULL, CHANGE raison raison VARCHAR(255) NOT NULL, CHANGE poste_promo poste_promo VARCHAR(255) NOT NULL, CHANGE nouv_sal nouv_sal NUMERIC(10, 0) NOT NULL, CHANGE avantage_supp avantage_supp VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_user ON promotion
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C11D7DD16B3CA4B ON promotion (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL, CHANGE sexe sexe VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test CHANGE nom nom VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE absence CHANGE type type VARCHAR(254) NOT NULL, CHANGE image_path image_path VARCHAR(254) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bulletinpaie ADD cin INT NOT NULL, CHANGE mois mois VARCHAR(10) NOT NULL, CHANGE date_generation date_generation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE salaire_brut salaire_brut NUMERIC(10, 2) NOT NULL, CHANGE deductions deductions NUMERIC(10, 2) NOT NULL, CHANGE salaire_net salaire_net NUMERIC(10, 2) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demandeconge ADD commentaire TEXT DEFAULT NULL, ADD file_attachment VARCHAR(255) DEFAULT NULL, DROP mois, DROP annee, DROP salaire_brut, DROP deductions, DROP salaire_net, CHANGE type_conge type_conge VARCHAR(50) DEFAULT NULL, CHANGE statut statut VARCHAR(255) DEFAULT 'en_attente', CHANGE date_demande date_demande DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formateur CHANGE nom_f Nom_F VARCHAR(50) NOT NULL, CHANGE prenom_f Prenom_F VARCHAR(50) NOT NULL, CHANGE email Email VARCHAR(100) NOT NULL, CHANGE specialite Specialite VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX Email ON formateur (Email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation DROP FOREIGN KEY FK_404021BFEFB240CB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation DROP FOREIGN KEY FK_404021BFEFB240CB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation CHANGE description Description TEXT DEFAULT NULL, CHANGE id_Formateur id_Formateur INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ADD CONSTRAINT fk_formateur FOREIGN KEY (id_Formateur) REFERENCES formateur (id_Formateur) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_404021bfefb240cb ON formation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_formateur ON formation (id_Formateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation ADD CONSTRAINT FK_404021BFEFB240CB FOREIGN KEY (id_Formateur) REFERENCES formateur (id_Formateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE penalite CHANGE type type VARCHAR(254) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_absence ON penalite (cin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pret CHANGE categorie Categorie VARCHAR(245) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE titre titre VARCHAR(254) NOT NULL, CHANGE description description VARCHAR(254) NOT NULL, CHANGE statut statut VARCHAR(254) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task CHANGE description description TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX project_id ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX user_id ON project_task (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion CHANGE type_promo type_promo VARCHAR(30) NOT NULL, CHANGE raison raison VARCHAR(50) NOT NULL, CHANGE poste_promo poste_promo VARCHAR(30) NOT NULL, CHANGE nouv_sal nouv_sal DOUBLE PRECISION NOT NULL, CHANGE avantage_supp avantage_supp VARCHAR(50) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_c11d7dd16b3ca4b ON promotion
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_user ON promotion (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE email email VARCHAR(40) NOT NULL, CHANGE username username VARCHAR(40) NOT NULL, CHANGE role role VARCHAR(30) NOT NULL, CHANGE sexe sexe VARCHAR(20) NOT NULL, CHANGE adresse adresse VARCHAR(40) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test CHANGE nom nom VARCHAR(100) NOT NULL
        SQL);
    }
}

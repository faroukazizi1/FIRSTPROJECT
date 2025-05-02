<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501172915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE absence CHANGE date date DATE DEFAULT NULL, CHANGE cin cin VARCHAR(8) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bulletinpaie CHANGE mois mois VARCHAR(255) NOT NULL, CHANGE salaire_brut salaire_brut DOUBLE PRECISION NOT NULL, CHANGE deductions deductions DOUBLE PRECISION NOT NULL, CHANGE salaire_net salaire_net DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formateur CHANGE Numero numero VARCHAR(20) NOT NULL, CHANGE nom_f nom_f VARCHAR(100) NOT NULL, CHANGE prenom_f prenom_f VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(150) NOT NULL, CHANGE specialite specialite VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE penalite CHANGE seuil_abs seuil_abs INT DEFAULT NULL, CHANGE cin cin INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pret CHANGE Categorie categorie VARCHAR(50) NOT NULL, CHANGE cin cin VARCHAR(20) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX cin ON pret
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_52ECE979ABE530DA ON pret (cin)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_user ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_project ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_cin ON reponse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse CHANGE Date_reponse date_reponse DATE DEFAULT NULL, CHANGE Montant_demande montant_demande DOUBLE PRECISION DEFAULT NULL, CHANGE Revenus_bruts revenus_bruts DOUBLE PRECISION DEFAULT NULL, CHANGE Taux_interet taux_interet DOUBLE PRECISION DEFAULT NULL, CHANGE Mensualite_credit mensualite_credit DOUBLE PRECISION DEFAULT NULL, CHANGE Potentiel_credit potentiel_credit DOUBLE PRECISION DEFAULT NULL, CHANGE Duree_remboursement duree_remboursement INT DEFAULT NULL, CHANGE Montant_autorise montant_autorise DOUBLE PRECISION DEFAULT NULL, CHANGE Assurance assurance DOUBLE PRECISION DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP google_authenticator_secret
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE absence CHANGE date date DATE NOT NULL, CHANGE cin cin INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bulletinpaie CHANGE mois mois INT NOT NULL, CHANGE salaire_brut salaire_brut NUMERIC(10, 2) DEFAULT NULL, CHANGE deductions deductions NUMERIC(10, 2) DEFAULT NULL, CHANGE salaire_net salaire_net NUMERIC(10, 2) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formateur CHANGE nom_f nom_f VARCHAR(255) NOT NULL, CHANGE prenom_f prenom_f VARCHAR(255) NOT NULL, CHANGE numero Numero INT NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE specialite specialite VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE penalite CHANGE seuil_abs seuil_abs INT NOT NULL, CHANGE cin cin INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pret CHANGE cin cin VARCHAR(20) DEFAULT NULL, CHANGE categorie Categorie VARCHAR(245) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_52ece979abe530da ON pret
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX cin ON pret (cin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_user ON project_task (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_project ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse CHANGE date_reponse Date_reponse DATE NOT NULL, CHANGE montant_demande Montant_demande DOUBLE PRECISION NOT NULL, CHANGE revenus_bruts Revenus_bruts DOUBLE PRECISION NOT NULL, CHANGE taux_interet Taux_interet DOUBLE PRECISION NOT NULL, CHANGE mensualite_credit Mensualite_credit DOUBLE PRECISION NOT NULL, CHANGE potentiel_credit Potentiel_credit DOUBLE PRECISION NOT NULL, CHANGE duree_remboursement Duree_remboursement INT NOT NULL, CHANGE montant_autorise Montant_autorise DOUBLE PRECISION NOT NULL, CHANGE assurance Assurance DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_cin ON reponse (cin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD google_authenticator_secret VARCHAR(255) DEFAULT NULL
        SQL);
    }
}

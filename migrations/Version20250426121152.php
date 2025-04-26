<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426121152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX uq_cin ON pret
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pret DROP cin
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE description description VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_project ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_user ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task CHANGE project_id project_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX cin ON reponse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP cin
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE pret ADD cin VARCHAR(50) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uq_cin ON pret (cin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE description description LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task CHANGE project_id project_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_project ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_user ON project_task (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD cin VARCHAR(50) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (cin) REFERENCES pret (cin)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX cin ON reponse (cin)
        SQL);
    }
}

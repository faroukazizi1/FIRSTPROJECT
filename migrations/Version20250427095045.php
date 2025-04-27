<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427095045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD updated_at DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_project ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_user ON project_task
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP updated_at, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_project ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_user ON project_task (user_id)
        SQL);
    }
}

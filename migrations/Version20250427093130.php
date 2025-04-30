<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427093130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD created_at DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_project ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6BEF133D166D1F9C ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_project_task_user ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6BEF133DA76ED395 ON project_task (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_6bef133d166d1f9c ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_project ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_6bef133da76ed395 ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_project_task_user ON project_task (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }
}

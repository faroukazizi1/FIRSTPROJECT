<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417141244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE absence CHANGE Date date DATE DEFAULT NULL, CHANGE cin cin VARCHAR(8) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE penalite CHANGE seuil_abs seuil_abs INT DEFAULT NULL, CHANGE cin cin INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE description description LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task CHANGE project_id project_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6BEF133D166D1F9C ON project_task (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6BEF133DA76ED395 ON project_task (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE absence CHANGE date Date DATE NOT NULL, CHANGE cin cin INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE penalite CHANGE seuil_abs seuil_abs INT NOT NULL, CHANGE cin cin INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project CHANGE description description VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6BEF133D166D1F9C ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6BEF133DA76ED395 ON project_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_task CHANGE project_id project_id INT NOT NULL
        SQL);
    }
}

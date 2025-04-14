<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414125842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_c11d7dd16b3ca4b ON promotion
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C11D7DD16B3CA4B ON promotion (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL, CHANGE sexe sexe VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test CHANGE nom nom VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_c11d7dd16b3ca4b ON promotion
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_C11D7DD16B3CA4B ON promotion (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649E7927C74 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE email email VARCHAR(40) NOT NULL, CHANGE username username VARCHAR(40) NOT NULL, CHANGE role role VARCHAR(30) NOT NULL, CHANGE sexe sexe VARCHAR(20) NOT NULL, CHANGE adresse adresse VARCHAR(40) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test CHANGE nom nom VARCHAR(100) NOT NULL
        SQL);
    }
}

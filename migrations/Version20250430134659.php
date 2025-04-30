<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430134659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add google_authenticator_secret column to user table';
    }

    public function up(Schema $schema): void
    {
        // Add google_authenticator_secret column
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD google_authenticator_secret VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Remove google_authenticator_secret column
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP google_authenticator_secret
        SQL);
    }
}

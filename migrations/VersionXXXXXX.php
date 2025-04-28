<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230901AddDateGenerationToDemandeconge extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_generation column to demandeconge table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE demandeconge ADD date_generation DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE demandeconge MODIFY date_demande DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE demandeconge DROP date_generation');
    }
}
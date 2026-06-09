<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Add downtime_budget to edgerunner
 */
final class Version20260609144500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add downtime_budget to edgerunner';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE edgerunner ADD downtime_budget INT DEFAULT 24 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE edgerunner DROP downtime_budget');
    }
}

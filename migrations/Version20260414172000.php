<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260414172000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add is_critical to transaction_log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE log ADD is_critical BOOLEAN NOT NULL DEFAULT FALSE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE log DROP is_critical');
    }
}

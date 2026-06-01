<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260523113000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add is_ammo boolean column to item table (default false)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE item ADD is_ammo BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE item DROP is_ammo');
    }
}

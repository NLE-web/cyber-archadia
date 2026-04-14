<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Renomme transaction_log en log et rend amount nullable
 */
final class Version20260414171500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renomme transaction_log en log et rend amount nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transaction_log RENAME TO log');
        $this->addSql('ALTER TABLE log ALTER amount DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE log ALTER amount SET NOT NULL');
        $this->addSql('ALTER TABLE log RENAME TO transaction_log');
    }
}

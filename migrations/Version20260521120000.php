<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260521120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add totalXpSpent and totalMoneySpent to Edgerunner';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE edgerunner ADD total_xp_spent INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE edgerunner ADD total_money_spent INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE edgerunner DROP total_xp_spent');
        $this->addSql('ALTER TABLE edgerunner DROP total_money_spent');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425143909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edgerunner ADD xp INT DEFAULT 10 NOT NULL');
        $this->addSql('ALTER TABLE item ADD stock INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD is_infinite_stock BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edgerunner DROP xp');
        $this->addSql('ALTER TABLE item DROP stock');
        $this->addSql('ALTER TABLE item DROP is_infinite_stock');
    }
}

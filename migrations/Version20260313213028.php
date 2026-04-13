<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313213028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edgerunner ADD lostlife INT DEFAULT NULL');
        $this->addSql('ALTER TABLE edgerunner ADD lostcyber INT NOT NULL');
        $this->addSql('ALTER TABLE edgerunner ADD loststress INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edgerunner DROP lostlife');
        $this->addSql('ALTER TABLE edgerunner DROP lostcyber');
        $this->addSql('ALTER TABLE edgerunner DROP loststress');
    }
}

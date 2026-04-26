<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425221931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ALTER is_consume SET DEFAULT false');
        $this->addSql('ALTER TABLE item ALTER price SET DEFAULT 0');
        $this->addSql('ALTER TABLE item ALTER charge_price SET DEFAULT 0');
        $this->addSql('ALTER TABLE item ALTER is_legal SET DEFAULT true');
        $this->addSql('ALTER TABLE item ALTER is_cumbersome SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ALTER is_consume DROP DEFAULT');
        $this->addSql('ALTER TABLE item ALTER price DROP DEFAULT');
        $this->addSql('ALTER TABLE item ALTER charge_price DROP DEFAULT');
        $this->addSql('ALTER TABLE item ALTER is_legal DROP DEFAULT');
        $this->addSql('ALTER TABLE item ALTER is_cumbersome DROP DEFAULT');
    }
}

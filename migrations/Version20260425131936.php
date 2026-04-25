<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425131936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_feat ADD xptot INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE character_skill ADD xptot INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE feat ADD xpcost INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD xpcost INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD level_up_active BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_feat DROP xptot');
        $this->addSql('ALTER TABLE character_skill DROP xptot');
        $this->addSql('ALTER TABLE feat DROP xpcost');
        $this->addSql('ALTER TABLE skill DROP xpcost');
        $this->addSql('ALTER TABLE "user" DROP level_up_active');
    }
}

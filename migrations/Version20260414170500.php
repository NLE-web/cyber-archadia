<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Create Log table
 */
final class Version20260414170500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create transaction_log table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE transaction_log (id SERIAL NOT NULL, character_id INT NOT NULL, amount INT NOT NULL, description VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E67008701136BE75 ON transaction_log (character_id)');
        $this->addSql('COMMENT ON COLUMN transaction_log.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE transaction_log ADD CONSTRAINT FK_E67008701136BE75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) ON DELETE CASCADE NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE transaction_log');
    }
}

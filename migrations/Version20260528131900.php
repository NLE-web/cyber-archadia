<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Keyword system
 */
final class Version20260528131900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create keyword table and relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE keyword (id SERIAL NOT NULL, key VARCHAR(255) NOT NULL, display VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE keyword_item (keyword_id INT NOT NULL, item_id INT NOT NULL, PRIMARY KEY(keyword_id, item_id))');
        $this->addSql('CREATE INDEX IDX_9E3C8A79115D4552 ON keyword_item (keyword_id)');
        $this->addSql('CREATE INDEX IDX_9E3C8A79126F525E ON keyword_item (item_id)');
        $this->addSql('CREATE TABLE keyword_feat (keyword_id INT NOT NULL, feat_id INT NOT NULL, PRIMARY KEY(keyword_id, feat_id))');
        $this->addSql('CREATE INDEX IDX_933E1A6D115D4552 ON keyword_feat (keyword_id)');
        $this->addSql('CREATE INDEX IDX_933E1A6D2E0B3252 ON keyword_feat (feat_id)');
        $this->addSql('CREATE TABLE keyword_action (keyword_id INT NOT NULL, action_id INT NOT NULL, PRIMARY KEY(keyword_id, action_id))');
        $this->addSql('CREATE INDEX IDX_F207D31C115D4552 ON keyword_action (keyword_id)');
        $this->addSql('CREATE INDEX IDX_F207D31C9D32F035 ON keyword_action (action_id)');
        $this->addSql('CREATE TABLE keyword_skill (keyword_id INT NOT NULL, skill_id INT NOT NULL, PRIMARY KEY(keyword_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_8DE9266E115D4552 ON keyword_skill (keyword_id)');
        $this->addSql('CREATE INDEX IDX_8DE9266E5585C142 ON keyword_skill (skill_id)');
        $this->addSql('ALTER TABLE keyword_item ADD CONSTRAINT FK_9E3C8A79115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_item ADD CONSTRAINT FK_9E3C8A79126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_feat ADD CONSTRAINT FK_933E1A6D115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_feat ADD CONSTRAINT FK_933E1A6D2E0B3252 FOREIGN KEY (feat_id) REFERENCES feat (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_action ADD CONSTRAINT FK_F207D31C115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_action ADD CONSTRAINT FK_F207D31C9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_skill ADD CONSTRAINT FK_8DE9266E115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE keyword_skill ADD CONSTRAINT FK_8DE9266E5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE keyword_item DROP CONSTRAINT FK_9E3C8A79115D4552');
        $this->addSql('ALTER TABLE keyword_item DROP CONSTRAINT FK_9E3C8A79126F525E');
        $this->addSql('ALTER TABLE keyword_feat DROP CONSTRAINT FK_933E1A6D115D4552');
        $this->addSql('ALTER TABLE keyword_feat DROP CONSTRAINT FK_933E1A6D2E0B3252');
        $this->addSql('ALTER TABLE keyword_action DROP CONSTRAINT FK_F207D31C115D4552');
        $this->addSql('ALTER TABLE keyword_action DROP CONSTRAINT FK_F207D31C9D32F035');
        $this->addSql('ALTER TABLE keyword_skill DROP CONSTRAINT FK_8DE9266E115D4552');
        $this->addSql('ALTER TABLE keyword_skill DROP CONSTRAINT FK_8DE9266E5585C142');
        $this->addSql('DROP TABLE keyword');
        $this->addSql('DROP TABLE keyword_item');
        $this->addSql('DROP TABLE keyword_feat');
        $this->addSql('DROP TABLE keyword_action');
        $this->addSql('DROP TABLE keyword_skill');
    }
}

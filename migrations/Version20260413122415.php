<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260413122415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_action DROP CONSTRAINT fk_e48e4f019d32f035');
        $this->addSql('ALTER TABLE character_action DROP CONSTRAINT fk_e48e4f011136be75');
        $this->addSql('ALTER TABLE character_action ADD CONSTRAINT FK_E48E4F019D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE character_action ADD CONSTRAINT FK_E48E4F011136BE75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE character_item DROP CONSTRAINT fk_8e73186126f525e');
        $this->addSql('ALTER TABLE character_item DROP CONSTRAINT fk_8e731861136be75');
        $this->addSql('ALTER TABLE character_item ADD CONSTRAINT FK_8E73186126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE character_item ADD CONSTRAINT FK_8E731861136BE75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE character_skill DROP CONSTRAINT fk_a0fe03155585c142');
        $this->addSql('ALTER TABLE character_skill DROP CONSTRAINT fk_a0fe03151136be75');
        $this->addSql('ALTER TABLE character_skill ADD CONSTRAINT FK_A0FE03155585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE character_skill ADD CONSTRAINT FK_A0FE03151136BE75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) ON DELETE CASCADE NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_action DROP CONSTRAINT FK_E48E4F011136BE75');
        $this->addSql('ALTER TABLE character_action DROP CONSTRAINT FK_E48E4F019D32F035');
        $this->addSql('ALTER TABLE character_action ADD CONSTRAINT fk_e48e4f011136be75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_action ADD CONSTRAINT fk_e48e4f019d32f035 FOREIGN KEY (action_id) REFERENCES action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_item DROP CONSTRAINT FK_8E731861136BE75');
        $this->addSql('ALTER TABLE character_item DROP CONSTRAINT FK_8E73186126F525E');
        $this->addSql('ALTER TABLE character_item ADD CONSTRAINT fk_8e731861136be75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_item ADD CONSTRAINT fk_8e73186126f525e FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_skill DROP CONSTRAINT FK_A0FE03151136BE75');
        $this->addSql('ALTER TABLE character_skill DROP CONSTRAINT FK_A0FE03155585C142');
        $this->addSql('ALTER TABLE character_skill ADD CONSTRAINT fk_a0fe03151136be75 FOREIGN KEY (character_id) REFERENCES edgerunner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_skill ADD CONSTRAINT fk_a0fe03155585c142 FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}

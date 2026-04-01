<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401144927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_hike');
        $this->addSql('ALTER TABLE hike ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E464D218E FOREIGN KEY (location_id) REFERENCES location (location_id)');
        $this->addSql('CREATE INDEX IDX_2301D7E464D218E ON hike (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_hike (user_id INT NOT NULL, hike_id INT NOT NULL, PRIMARY KEY (user_id, hike_id))');
        $this->addSql('CREATE INDEX idx_59809b2da76ed395 ON user_hike (user_id)');
        $this->addSql('CREATE INDEX idx_59809b2d71d4de21 ON user_hike (hike_id)');
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT FK_2301D7E464D218E');
        $this->addSql('DROP INDEX IDX_2301D7E464D218E');
        $this->addSql('ALTER TABLE hike DROP location_id');
    }
}

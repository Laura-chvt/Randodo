<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401074726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP CONSTRAINT location_pkey');
        $this->addSql('ALTER TABLE location ADD location_name VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE location ADD location_gps VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE location DROP name');
        $this->addSql('ALTER TABLE location DROP gps');
        $this->addSql('ALTER TABLE location RENAME COLUMN id TO location_id');
        $this->addSql('ALTER TABLE location ADD PRIMARY KEY (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP CONSTRAINT location_pkey');
        $this->addSql('ALTER TABLE location ADD name VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE location ADD gps VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE location DROP location_name');
        $this->addSql('ALTER TABLE location DROP location_gps');
        $this->addSql('ALTER TABLE location RENAME COLUMN location_id TO id');
        $this->addSql('ALTER TABLE location ADD PRIMARY KEY (id)');
    }
}

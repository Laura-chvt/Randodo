<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260331121932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT hike_pkey');
        $this->addSql('ALTER TABLE hike RENAME COLUMN id TO hike_id');
        $this->addSql('ALTER TABLE hike RENAME COLUMN name TO hike_name');
        $this->addSql('ALTER TABLE hike RENAME COLUMN height TO hike_height');
        $this->addSql('ALTER TABLE hike RENAME COLUMN "time" TO hike_time');
        $this->addSql('ALTER TABLE hike RENAME COLUMN level TO hike_leveel');
        $this->addSql('ALTER TABLE hike RENAME COLUMN length TO hike_length');
        $this->addSql('ALTER TABLE hike RENAME COLUMN family TO hike_family');
        $this->addSql('ALTER TABLE hike RENAME COLUMN description TO hike_description');
        $this->addSql('ALTER TABLE hike RENAME COLUMN thumbnail TO hike_thumbnail');
        $this->addSql('ALTER TABLE hike ADD PRIMARY KEY (hike_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT hike_pkey');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_id TO id');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_name TO name');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_height TO height');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_time TO "time"');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_leveel TO level');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_length TO length');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_family TO family');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_description TO description');
        $this->addSql('ALTER TABLE hike RENAME COLUMN hike_thumbnail TO thumbnail');
        $this->addSql('ALTER TABLE hike ADD PRIMARY KEY (id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260331123030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX user_pkey');
        $this->addSql('ALTER TABLE "user" ADD user_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD user_firstname VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD user_mail VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP name');
        $this->addSql('ALTER TABLE "user" DROP firstname');
        $this->addSql('ALTER TABLE "user" DROP mail');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN id TO user_id');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN image TO user_image');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN password TO user_password');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN description TO user_description');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN role TO user_role');
        $this->addSql('ALTER TABLE "user" ADD PRIMARY KEY (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT "user_pkey"');
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD firstname VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD mail VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP user_name');
        $this->addSql('ALTER TABLE "user" DROP user_firstname');
        $this->addSql('ALTER TABLE "user" DROP user_mail');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_id TO id');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_image TO image');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_password TO password');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_description TO description');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_role TO role');
        $this->addSql('ALTER TABLE "user" ADD PRIMARY KEY (id)');
    }
}

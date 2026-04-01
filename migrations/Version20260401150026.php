<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260401150026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_hike (user_id INT NOT NULL, hike_id INT NOT NULL, PRIMARY KEY (user_id, hike_id))');
        $this->addSql('CREATE INDEX IDX_59809B2DA76ED395 ON user_hike (user_id)');
        $this->addSql('CREATE INDEX IDX_59809B2D71D4DE21 ON user_hike (hike_id)');
        $this->addSql('ALTER TABLE user_hike ADD CONSTRAINT FK_59809B2DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (user_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_hike ADD CONSTRAINT FK_59809B2D71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (hike_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD hike_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD user_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (hike_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5F0EBBFF FOREIGN KEY (user_comment_id) REFERENCES "user" (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C71D4DE21 ON comment (hike_id)');
        $this->addSql('CREATE INDEX IDX_9474526C5F0EBBFF ON comment (user_comment_id)');
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT fk_2301d7e464d218e');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E464D218E FOREIGN KEY (location_id) REFERENCES location (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_hike DROP CONSTRAINT FK_59809B2DA76ED395');
        $this->addSql('ALTER TABLE user_hike DROP CONSTRAINT FK_59809B2D71D4DE21');
        $this->addSql('DROP TABLE user_hike');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C71D4DE21');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C5F0EBBFF');
        $this->addSql('DROP INDEX IDX_9474526C71D4DE21');
        $this->addSql('DROP INDEX IDX_9474526C5F0EBBFF');
        $this->addSql('ALTER TABLE comment DROP hike_id');
        $this->addSql('ALTER TABLE comment DROP user_comment_id');
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT FK_2301D7E464D218E');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT fk_2301d7e464d218e FOREIGN KEY (location_id) REFERENCES location (location_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}

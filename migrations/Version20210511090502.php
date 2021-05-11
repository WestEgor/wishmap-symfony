<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511090502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wish_map_comments (wish_map_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_A91565306CD6FAA1 (wish_map_id), INDEX IDX_A915653063379586 (comments_id), PRIMARY KEY(wish_map_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wish_map_comments ADD CONSTRAINT FK_A91565306CD6FAA1 FOREIGN KEY (wish_map_id) REFERENCES `wish_map` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wish_map_comments ADD CONSTRAINT FK_A915653063379586 FOREIGN KEY (comments_id) REFERENCES `comments` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE wish_map_comments');
    }
}

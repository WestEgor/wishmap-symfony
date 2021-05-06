<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506151331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `wish_map` (id INT AUTO_INCREMENT NOT NULL, persons_id INT NOT NULL, comments_id INT NOT NULL, description VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, finish_date DATETIME NOT NULL, INDEX IDX_6EF6168FE812AD (persons_id), INDEX IDX_6EF616863379586 (comments_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `wish_map` ADD CONSTRAINT FK_6EF6168FE812AD FOREIGN KEY (persons_id) REFERENCES `person` (id)');
        $this->addSql('ALTER TABLE `wish_map` ADD CONSTRAINT FK_6EF616863379586 FOREIGN KEY (comments_id) REFERENCES `comments` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `wish_map`');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506150535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `comments` (id INT AUTO_INCREMENT NOT NULL, send_persons_id INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_5F9E962A10E980D6 (send_persons_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `comments` ADD CONSTRAINT FK_5F9E962A10E980D6 FOREIGN KEY (send_persons_id) REFERENCES `person` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `comments`');
    }
}

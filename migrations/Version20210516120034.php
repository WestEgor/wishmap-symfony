<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210516120034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `comments` (id INT AUTO_INCREMENT NOT NULL, send_user_id INT NOT NULL, comment VARCHAR(255) NOT NULL, INDEX IDX_5F9E962A7127A6FB (send_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nickname VARCHAR(50) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, profile_description VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `wish_map` (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, persons_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT CURRENT_TIMESTAMP, finish_date DATETIME NOT NULL, process DOUBLE PRECISION DEFAULT \'0\', INDEX IDX_6EF616812469DE2 (category_id), INDEX IDX_6EF6168FE812AD (persons_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wish_map_comments (wish_map_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_A91565306CD6FAA1 (wish_map_id), UNIQUE INDEX UNIQ_A9156530F8697D13 (comment_id), PRIMARY KEY(wish_map_id, comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `comments` ADD CONSTRAINT FK_5F9E962A7127A6FB FOREIGN KEY (send_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `wish_map` ADD CONSTRAINT FK_6EF616812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE `wish_map` ADD CONSTRAINT FK_6EF6168FE812AD FOREIGN KEY (persons_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE wish_map_comments ADD CONSTRAINT FK_A91565306CD6FAA1 FOREIGN KEY (wish_map_id) REFERENCES `wish_map` (id)');
        $this->addSql('ALTER TABLE wish_map_comments ADD CONSTRAINT FK_A9156530F8697D13 FOREIGN KEY (comment_id) REFERENCES `comments` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `wish_map` DROP FOREIGN KEY FK_6EF616812469DE2');
        $this->addSql('ALTER TABLE wish_map_comments DROP FOREIGN KEY FK_A9156530F8697D13');
        $this->addSql('ALTER TABLE `comments` DROP FOREIGN KEY FK_5F9E962A7127A6FB');
        $this->addSql('ALTER TABLE `wish_map` DROP FOREIGN KEY FK_6EF6168FE812AD');
        $this->addSql('ALTER TABLE wish_map_comments DROP FOREIGN KEY FK_A91565306CD6FAA1');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `comments`');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE `wish_map`');
        $this->addSql('DROP TABLE wish_map_comments');
    }
}

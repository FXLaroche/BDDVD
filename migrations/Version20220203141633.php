<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203141633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film (id INT AUTO_INCREMENT NOT NULL, omdb_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, year INT DEFAULT NULL, plot LONGTEXT DEFAULT NULL, poster VARCHAR(512) DEFAULT NULL, UNIQUE INDEX UNIQ_8244BE2230650DD1 (omdb_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film_user (film_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E83C3C4A567F5183 (film_id), INDEX IDX_E83C3C4AA76ED395 (user_id), PRIMARY KEY(film_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE film_user ADD CONSTRAINT FK_E83C3C4A567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_user ADD CONSTRAINT FK_E83C3C4AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film_user DROP FOREIGN KEY FK_E83C3C4A567F5183');
        $this->addSql('ALTER TABLE film_user DROP FOREIGN KEY FK_E83C3C4AA76ED395');
        $this->addSql('DROP TABLE film');
        $this->addSql('DROP TABLE film_user');
        $this->addSql('DROP TABLE user');
    }
}

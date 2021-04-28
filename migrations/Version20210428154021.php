<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210428154021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_tutorial (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, tutorial_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, notes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_DB2F9CEF60BB6FE6 (auteur_id), INDEX IDX_DB2F9CEF89366B7B (tutorial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_tutorial ADD CONSTRAINT FK_DB2F9CEF60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES `user__user` (id)');
        $this->addSql('ALTER TABLE comment_tutorial ADD CONSTRAINT FK_DB2F9CEF89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment_tutorial');
    }
}

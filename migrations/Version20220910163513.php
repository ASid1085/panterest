<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220910163513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between pins and users';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pins AS SELECT id, title, description, created_at, updated_at, image_name FROM pins');
        $this->addSql('DROP TABLE pins');
        $this->addSql('CREATE TABLE pins (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        , image_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_3F0FE980A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pins (id, title, description, created_at, updated_at, image_name) SELECT id, title, description, created_at, updated_at, image_name FROM __temp__pins');
        $this->addSql('DROP TABLE __temp__pins');
        $this->addSql('CREATE INDEX IDX_3F0FE980A76ED395 ON pins (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pins AS SELECT id, title, description, image_name, created_at, updated_at FROM pins');
        $this->addSql('DROP TABLE pins');
        $this->addSql('CREATE TABLE pins (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO pins (id, title, description, image_name, created_at, updated_at) SELECT id, title, description, image_name, created_at, updated_at FROM __temp__pins');
        $this->addSql('DROP TABLE __temp__pins');
    }
}

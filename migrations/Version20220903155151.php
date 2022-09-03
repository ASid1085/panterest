<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903155151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created_at and updated_at fields to pins table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pins AS SELECT id, title, description FROM pins');
        $this->addSql('DROP TABLE pins');
        $this->addSql('CREATE TABLE pins (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO pins (id, title, description) SELECT id, title, description FROM __temp__pins');
        $this->addSql('DROP TABLE __temp__pins');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pins AS SELECT id, title, description FROM pins');
        $this->addSql('DROP TABLE pins');
        $this->addSql('CREATE TABLE pins (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO pins (id, title, description) SELECT id, title, description FROM __temp__pins');
        $this->addSql('DROP TABLE __temp__pins');
    }
}

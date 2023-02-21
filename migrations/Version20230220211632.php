<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220211632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request ADD created_by_id INT NOT NULL, ADD updated_by_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD title VARCHAR(128) NOT NULL, ADD text LONGTEXT NOT NULL, ADD attachment VARCHAR(255) DEFAULT NULL, ADD status VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FB03A8386 ON request (created_by_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F896DBBDE ON request (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FB03A8386');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F896DBBDE');
        $this->addSql('DROP INDEX IDX_3B978F9FB03A8386 ON request');
        $this->addSql('DROP INDEX IDX_3B978F9F896DBBDE ON request');
        $this->addSql('ALTER TABLE request DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP title, DROP text, DROP attachment, DROP status');
    }
}

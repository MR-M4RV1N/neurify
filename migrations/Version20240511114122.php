<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511114122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD ensemble_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7B268ECB1 FOREIGN KEY (ensemble_id) REFERENCES ensemble (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7B268ECB1 ON event (ensemble_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7B268ECB1');
        $this->addSql('DROP INDEX IDX_3BAE0AA7B268ECB1 ON event');
        $this->addSql('ALTER TABLE event DROP ensemble_id');
    }
}

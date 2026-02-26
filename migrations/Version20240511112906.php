<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511112906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ensemble_participant (id INT AUTO_INCREMENT NOT NULL, ensemble_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CDBF96E8B268ECB1 (ensemble_id), INDEX IDX_CDBF96E8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ensemble_participant ADD CONSTRAINT FK_CDBF96E8B268ECB1 FOREIGN KEY (ensemble_id) REFERENCES ensemble (id)');
        $this->addSql('ALTER TABLE ensemble_participant ADD CONSTRAINT FK_CDBF96E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ensemble_participant');
    }
}

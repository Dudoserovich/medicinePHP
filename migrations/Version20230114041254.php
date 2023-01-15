<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114041254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT fk_437ee93975fa0ff2');
        $this->addSql('DROP INDEX idx_437ee93975fa0ff2');
        $this->addSql('ALTER TABLE visit RENAME COLUMN visit_id TO appointment_id');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_437EE939E5B533F9 ON visit (appointment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE939E5B533F9');
        $this->addSql('DROP INDEX IDX_437EE939E5B533F9');
        $this->addSql('ALTER TABLE visit RENAME COLUMN appointment_id TO visit_id');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT fk_437ee93975fa0ff2 FOREIGN KEY (visit_id) REFERENCES visit (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_437ee93975fa0ff2 ON visit (visit_id)');
    }
}

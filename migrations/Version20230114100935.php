<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114100935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor ALTER full_name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE doctor ALTER address TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE doctor ALTER phone TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE doctor ALTER full_name TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE doctor ALTER address TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE doctor ALTER phone TYPE VARCHAR(15)');
    }
}

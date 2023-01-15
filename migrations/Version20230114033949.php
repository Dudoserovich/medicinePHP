<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114033949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE doctor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE patient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE specialization_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE doctor (id INT NOT NULL, specialization_id INT DEFAULT NULL, full_name VARCHAR(50) NOT NULL, sex BOOLEAN NOT NULL, address VARCHAR(40) NOT NULL, phone VARCHAR(15) DEFAULT NULL, work_experience INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FC0F36AFA846217 ON doctor (specialization_id)');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, full_name VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, policy INT NOT NULL, sex BOOLEAN NOT NULL, birthday DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBF07D0516 ON patient (policy)');
        $this->addSql('CREATE TABLE specialization (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36AFA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE doctor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE patient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE specialization_id_seq CASCADE');
        $this->addSql('ALTER TABLE doctor DROP CONSTRAINT FK_1FC0F36AFA846217');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE specialization');
    }
}

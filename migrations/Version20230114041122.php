<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114041122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE appointment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE doctor_schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE medicine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE visit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, medicine_id INT DEFAULT NULL, visit_id INT DEFAULT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE38F8442F7D140A ON appointment (medicine_id)');
        $this->addSql('CREATE INDEX IDX_FE38F84475FA0FF2 ON appointment (visit_id)');
        $this->addSql('CREATE TABLE doctor_schedule (id INT NOT NULL, doctor_id INT DEFAULT NULL, schedule_id INT DEFAULT NULL, place_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F2CC63CD87F4FB17 ON doctor_schedule (doctor_id)');
        $this->addSql('CREATE INDEX IDX_F2CC63CDA40BC2D5 ON doctor_schedule (schedule_id)');
        $this->addSql('CREATE INDEX IDX_F2CC63CDDA6A219 ON doctor_schedule (place_id)');
        $this->addSql('CREATE TABLE medicine (id INT NOT NULL, name VARCHAR(30) NOT NULL, usage VARCHAR(100) NOT NULL, actions VARCHAR(80) NOT NULL, effects VARCHAR(80) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE visit (id INT NOT NULL, patient_id INT DEFAULT NULL, visit_id INT DEFAULT NULL, symptoms VARCHAR(100) NOT NULL, diagnosis VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_437EE9396B899279 ON visit (patient_id)');
        $this->addSql('CREATE INDEX IDX_437EE93975FA0FF2 ON visit (visit_id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8442F7D140A FOREIGN KEY (medicine_id) REFERENCES medicine (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84475FA0FF2 FOREIGN KEY (visit_id) REFERENCES visit (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctor_schedule ADD CONSTRAINT FK_F2CC63CD87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctor_schedule ADD CONSTRAINT FK_F2CC63CDA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE doctor_schedule ADD CONSTRAINT FK_F2CC63CDDA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE9396B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE93975FA0FF2 FOREIGN KEY (visit_id) REFERENCES visit (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE appointment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE doctor_schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE medicine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE visit_id_seq CASCADE');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F8442F7D140A');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F84475FA0FF2');
        $this->addSql('ALTER TABLE doctor_schedule DROP CONSTRAINT FK_F2CC63CD87F4FB17');
        $this->addSql('ALTER TABLE doctor_schedule DROP CONSTRAINT FK_F2CC63CDA40BC2D5');
        $this->addSql('ALTER TABLE doctor_schedule DROP CONSTRAINT FK_F2CC63CDDA6A219');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE9396B899279');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE93975FA0FF2');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE doctor_schedule');
        $this->addSql('DROP TABLE medicine');
        $this->addSql('DROP TABLE visit');
    }
}

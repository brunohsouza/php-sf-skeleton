<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210717131044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates basic database structure for the project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE employee_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, name VARCHAR(255) NOT NULL, position VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F5E237E06 ON company (name)');
        $this->addSql('CREATE TABLE employee (id INT NOT NULL, company_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(70) NOT NULL, salary DOUBLE PRECISION DEFAULT NULL, bonus DOUBLE PRECISION DEFAULT NULL, insurance_amount DOUBLE PRECISION DEFAULT NULL, employment_start_date DATE NOT NULL, employment_end_date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1E7927C74 ON employee (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1979B1AD6 ON employee (company_id)');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, employee_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, position VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE8C03F15C ON project (employee_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE5E237E068C03F15C462CE4F5 ON project (name, employee_id, position)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1979B1AD6');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE8C03F15C');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE employee_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE project');
    }
}

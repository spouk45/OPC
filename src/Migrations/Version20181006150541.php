<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181006150541 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, phone VARCHAR(15) NOT NULL, phone2 VARCHAR(15) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, information LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_heating (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, heating_id INT NOT NULL, mark VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, serial VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, contract_date DATETIME DEFAULT NULL, anniversary_date DATETIME DEFAULT NULL, last_maintenance_date DATETIME DEFAULT NULL, contract_finish TINYINT(1) DEFAULT NULL, INDEX IDX_4E70CCE09395C3F3 (customer_id), INDEX IDX_4E70CCE0520F822 (heating_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extraction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heating (id INT AUTO_INCREMENT NOT NULL, heating_type_id INT NOT NULL, source_type_id INT DEFAULT NULL, extraction_type_id INT DEFAULT NULL, on_the_ground TINYINT(1) NOT NULL, designation VARCHAR(255) NOT NULL, INDEX IDX_52AD685EAEA01003 (heating_type_id), INDEX IDX_52AD685E8C9334FB (source_type_id), INDEX IDX_52AD685E8D560E01 (extraction_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heating_source (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heating_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_heating ADD CONSTRAINT FK_4E70CCE09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer_heating ADD CONSTRAINT FK_4E70CCE0520F822 FOREIGN KEY (heating_id) REFERENCES heating (id)');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685EAEA01003 FOREIGN KEY (heating_type_id) REFERENCES heating_type (id)');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685E8C9334FB FOREIGN KEY (source_type_id) REFERENCES heating_source (id)');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685E8D560E01 FOREIGN KEY (extraction_type_id) REFERENCES extraction (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_heating DROP FOREIGN KEY FK_4E70CCE09395C3F3');
        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685E8D560E01');
        $this->addSql('ALTER TABLE customer_heating DROP FOREIGN KEY FK_4E70CCE0520F822');
        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685E8C9334FB');
        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685EAEA01003');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_heating');
        $this->addSql('DROP TABLE extraction');
        $this->addSql('DROP TABLE heating');
        $this->addSql('DROP TABLE heating_source');
        $this->addSql('DROP TABLE heating_type');
        $this->addSql('DROP TABLE user');
    }
}

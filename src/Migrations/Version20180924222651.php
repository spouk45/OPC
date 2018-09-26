<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180924222651 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE extraction_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heating (id INT AUTO_INCREMENT NOT NULL, heating_type_id INT NOT NULL, source_type_id INT DEFAULT NULL, extraction_type_id INT DEFAULT NULL, on_the_ground TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, contrat_date DATE DEFAULT NULL, maintenance_anniversary DATE DEFAULT NULL, last_maintenance_date DATE DEFAULT NULL, contract_in_progress TINYINT(1) NOT NULL, INDEX IDX_52AD685EAEA01003 (heating_type_id), INDEX IDX_52AD685E8C9334FB (source_type_id), INDEX IDX_52AD685E8D560E01 (extraction_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heating_source (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heating_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685EAEA01003 FOREIGN KEY (heating_type_id) REFERENCES heating_type (id)');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685E8C9334FB FOREIGN KEY (source_type_id) REFERENCES heating_source (id)');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685E8D560E01 FOREIGN KEY (extraction_type_id) REFERENCES extraction_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685E8D560E01');
        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685E8C9334FB');
        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685EAEA01003');
        $this->addSql('DROP TABLE extraction_type');
        $this->addSql('DROP TABLE heating');
        $this->addSql('DROP TABLE heating_source');
        $this->addSql('DROP TABLE heating_type');
    }
}

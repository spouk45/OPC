<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180926185436 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_heating (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, heating_id INT NOT NULL, mark VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, serial VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, contract_date DATETIME DEFAULT NULL, anniversary_date DATETIME DEFAULT NULL, last_maintenance_date DATETIME DEFAULT NULL, contract_finish TINYINT(1) DEFAULT NULL, INDEX IDX_4E70CCE09395C3F3 (customer_id), INDEX IDX_4E70CCE0520F822 (heating_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_heating ADD CONSTRAINT FK_4E70CCE09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer_heating ADD CONSTRAINT FK_4E70CCE0520F822 FOREIGN KEY (heating_id) REFERENCES heating (id)');
        $this->addSql('ALTER TABLE heating DROP FOREIGN KEY FK_52AD685E9395C3F3');
        $this->addSql('DROP INDEX IDX_52AD685E9395C3F3 ON heating');
        $this->addSql('ALTER TABLE heating DROP customer_id, DROP comment, DROP contrat_date, DROP maintenance_anniversary, DROP last_maintenance_date, DROP contract_in_progress');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customer_heating');
        $this->addSql('ALTER TABLE heating ADD customer_id INT DEFAULT NULL, ADD comment LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD contrat_date DATE DEFAULT NULL, ADD maintenance_anniversary DATE DEFAULT NULL, ADD last_maintenance_date DATE DEFAULT NULL, ADD contract_in_progress TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE heating ADD CONSTRAINT FK_52AD685E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_52AD685E9395C3F3 ON heating (customer_id)');
    }
}

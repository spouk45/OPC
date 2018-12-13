<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181213111615 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE intervention_report (id INT AUTO_INCREMENT NOT NULL, type_intervention_report_id INT NOT NULL, customer_id INT NOT NULL, planned_date DATETIME DEFAULT NULL, realised_date DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_2E8F55A1D5F055DC (type_intervention_report_id), INDEX IDX_2E8F55A19395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_intervention_report (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervention_report ADD CONSTRAINT FK_2E8F55A1D5F055DC FOREIGN KEY (type_intervention_report_id) REFERENCES type_intervention_report (id)');
        $this->addSql('ALTER TABLE intervention_report ADD CONSTRAINT FK_2E8F55A19395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intervention_report DROP FOREIGN KEY FK_2E8F55A1D5F055DC');
        $this->addSql('DROP TABLE intervention_report');
        $this->addSql('DROP TABLE type_intervention_report');
    }
}

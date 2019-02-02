<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190201215833 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intervention_report DROP FOREIGN KEY FK_2E8F55A1D5F055DC');
        $this->addSql('DROP INDEX IDX_2E8F55A1D5F055DC ON intervention_report');
        $this->addSql('ALTER TABLE intervention_report DROP type_intervention_report_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intervention_report ADD type_intervention_report_id INT NOT NULL');
        $this->addSql('ALTER TABLE intervention_report ADD CONSTRAINT FK_2E8F55A1D5F055DC FOREIGN KEY (type_intervention_report_id) REFERENCES type_intervention_report (id)');
        $this->addSql('CREATE INDEX IDX_2E8F55A1D5F055DC ON intervention_report (type_intervention_report_id)');
    }
}

<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209124341 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer ADD contract_date DATETIME DEFAULT NULL, ADD anniversary_date DATETIME DEFAULT NULL, ADD contract_finish TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_heating DROP contract_date, DROP anniversary_date, DROP contract_finish');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer DROP contract_date, DROP anniversary_date, DROP contract_finish');
        $this->addSql('ALTER TABLE customer_heating ADD contract_date DATETIME DEFAULT NULL, ADD anniversary_date DATETIME DEFAULT NULL, ADD contract_finish TINYINT(1) DEFAULT NULL');
    }
}

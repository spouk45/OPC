<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190113175549 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FC3568B40');
        $this->addSql('DROP INDEX IDX_C53D045FC3568B40 ON image');
        $this->addSql('ALTER TABLE image ADD customer_id INT DEFAULT NULL, DROP customers_id, DROP link, DROP date');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F9395C3F3 ON image (customer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F9395C3F3');
        $this->addSql('DROP INDEX IDX_C53D045F9395C3F3 ON image');
        $this->addSql('ALTER TABLE image ADD customers_id INT NOT NULL, ADD link VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD date DATETIME NOT NULL, DROP customer_id');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FC3568B40 FOREIGN KEY (customers_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FC3568B40 ON image (customers_id)');
    }
}

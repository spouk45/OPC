<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209084316 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer ADD full_adress VARCHAR(255) NOT NULL, ADD complement_adress VARCHAR(255) NOT NULL, DROP adress, DROP house_number, DROP street, DROP road_type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer ADD adress VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD house_number VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD street VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD road_type VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci, DROP full_adress, DROP complement_adress');
    }
}

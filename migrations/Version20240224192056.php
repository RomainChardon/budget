<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224192056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensualite ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mensualite ADD CONSTRAINT FK_764608DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_764608DCA76ED395 ON mensualite (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensualite DROP FOREIGN KEY FK_764608DCA76ED395');
        $this->addSql('DROP INDEX IDX_764608DCA76ED395 ON mensualite');
        $this->addSql('ALTER TABLE mensualite DROP user_id');
    }
}

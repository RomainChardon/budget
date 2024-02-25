<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224174435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense ADD mensualite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757B5B467BF FOREIGN KEY (mensualite_id) REFERENCES mensualite (id)');
        $this->addSql('CREATE INDEX IDX_34059757B5B467BF ON depense (mensualite_id)');
        $this->addSql('ALTER TABLE revenu ADD mensualite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE revenu ADD CONSTRAINT FK_7DA3C045B5B467BF FOREIGN KEY (mensualite_id) REFERENCES mensualite (id)');
        $this->addSql('CREATE INDEX IDX_7DA3C045B5B467BF ON revenu (mensualite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757B5B467BF');
        $this->addSql('DROP INDEX IDX_34059757B5B467BF ON depense');
        $this->addSql('ALTER TABLE depense DROP mensualite_id');
        $this->addSql('ALTER TABLE revenu DROP FOREIGN KEY FK_7DA3C045B5B467BF');
        $this->addSql('DROP INDEX IDX_7DA3C045B5B467BF ON revenu');
        $this->addSql('ALTER TABLE revenu DROP mensualite_id');
    }
}

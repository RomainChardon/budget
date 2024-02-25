<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223180032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_depense ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie_depense ADD CONSTRAINT FK_6B8639F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6B8639F5A76ED395 ON categorie_depense (user_id)');
        $this->addSql('ALTER TABLE categorie_revenu ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie_revenu ADD CONSTRAINT FK_FB856A7CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FB856A7CA76ED395 ON categorie_revenu (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_revenu DROP FOREIGN KEY FK_FB856A7CA76ED395');
        $this->addSql('DROP INDEX IDX_FB856A7CA76ED395 ON categorie_revenu');
        $this->addSql('ALTER TABLE categorie_revenu DROP user_id');
        $this->addSql('ALTER TABLE categorie_depense DROP FOREIGN KEY FK_6B8639F5A76ED395');
        $this->addSql('DROP INDEX IDX_6B8639F5A76ED395 ON categorie_depense');
        $this->addSql('ALTER TABLE categorie_depense DROP user_id');
    }
}

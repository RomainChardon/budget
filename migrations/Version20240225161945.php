<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225161945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prevues (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, mensualite_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, categorie_revenues_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_ABD4B13DA76ED395 (user_id), INDEX IDX_ABD4B13DB5B467BF (mensualite_id), INDEX IDX_ABD4B13DBCF5E72D (categorie_id), INDEX IDX_ABD4B13DF75E7C4 (categorie_revenues_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prevues ADD CONSTRAINT FK_ABD4B13DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prevues ADD CONSTRAINT FK_ABD4B13DB5B467BF FOREIGN KEY (mensualite_id) REFERENCES mensualite (id)');
        $this->addSql('ALTER TABLE prevues ADD CONSTRAINT FK_ABD4B13DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_depense (id)');
        $this->addSql('ALTER TABLE prevues ADD CONSTRAINT FK_ABD4B13DF75E7C4 FOREIGN KEY (categorie_revenues_id) REFERENCES categorie_revenu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prevues DROP FOREIGN KEY FK_ABD4B13DA76ED395');
        $this->addSql('ALTER TABLE prevues DROP FOREIGN KEY FK_ABD4B13DB5B467BF');
        $this->addSql('ALTER TABLE prevues DROP FOREIGN KEY FK_ABD4B13DBCF5E72D');
        $this->addSql('ALTER TABLE prevues DROP FOREIGN KEY FK_ABD4B13DF75E7C4');
        $this->addSql('DROP TABLE prevues');
    }
}

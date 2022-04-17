<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417143756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE punch ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE punch ADD CONSTRAINT FK_2CE17058F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2CE17058F675F31B ON punch (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE punch DROP FOREIGN KEY FK_2CE17058F675F31B');
        $this->addSql('DROP INDEX IDX_2CE17058F675F31B ON punch');
        $this->addSql('ALTER TABLE punch DROP author_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250102155927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_AC74095AC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_monitor (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, monitor_id INT NOT NULL, INDEX IDX_E147EF6581C06096 (activity_id), INDEX IDX_E147EF654CE1C902 (monitor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, number_of_monitors INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monitor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, email VARCHAR(150) NOT NULL, phone VARCHAR(9) NOT NULL, photo VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AC54C8C93 FOREIGN KEY (type_id) REFERENCES activity_type (id)');
        $this->addSql('ALTER TABLE activity_monitor ADD CONSTRAINT FK_E147EF6581C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE activity_monitor ADD CONSTRAINT FK_E147EF654CE1C902 FOREIGN KEY (monitor_id) REFERENCES monitor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AC54C8C93');
        $this->addSql('ALTER TABLE activity_monitor DROP FOREIGN KEY FK_E147EF6581C06096');
        $this->addSql('ALTER TABLE activity_monitor DROP FOREIGN KEY FK_E147EF654CE1C902');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_monitor');
        $this->addSql('DROP TABLE activity_type');
        $this->addSql('DROP TABLE monitor');
    }
}

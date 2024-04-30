<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430090550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE encounter (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, opponent VARCHAR(255) NOT NULL, is_at_home TINYINT(1) NOT NULL, happens_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_victory TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_69D229CA296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', close_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', place VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, additional_info LONGTEXT DEFAULT NULL, INDEX IDX_3BAE0AA7F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, coach_id INT DEFAULT NULL, INDEX IDX_C4E0A61F12469DE2 (category_id), INDEX IDX_C4E0A61F3C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE encounter ADD CONSTRAINT FK_69D229CA296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F3C105691 FOREIGN KEY (coach_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD team_id INT DEFAULT NULL, ADD lastname VARCHAR(255) NOT NULL, ADD firstname VARCHAR(255) NOT NULL, ADD relationship VARCHAR(255) DEFAULT NULL, ADD has_to_change_password TINYINT(1) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD api_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649296CD8AE ON user (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('ALTER TABLE encounter DROP FOREIGN KEY FK_69D229CA296CD8AE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F675F31B');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F12469DE2');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F3C105691');
        $this->addSql('DROP TABLE encounter');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP INDEX IDX_8D93D649296CD8AE ON `user`');
        $this->addSql('ALTER TABLE `user` DROP team_id, DROP lastname, DROP firstname, DROP relationship, DROP has_to_change_password, DROP created_at, DROP api_token');
    }
}

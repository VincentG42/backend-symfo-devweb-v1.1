<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627131511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_user_type (user_id INT NOT NULL, user_type_id INT NOT NULL, INDEX IDX_F6BF9604A76ED395 (user_id), INDEX IDX_F6BF96049D419299 (user_type_id), PRIMARY KEY(user_id, user_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_user_type ADD CONSTRAINT FK_F6BF9604A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_type ADD CONSTRAINT FK_F6BF96049D419299 FOREIGN KEY (user_type_id) REFERENCES user_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_user_type DROP FOREIGN KEY FK_F6BF9604A76ED395');
        $this->addSql('ALTER TABLE user_user_type DROP FOREIGN KEY FK_F6BF96049D419299');
        $this->addSql('DROP TABLE user_user_type');
        $this->addSql('DROP TABLE user_type');
    }
}

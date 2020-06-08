<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200601134110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborations  (id INT AUTO_INCREMENT NOT NULL, artist_type_id INT NOT NULL, shows INT NOT NULL, INDEX IDX_F90F069B7203D2A4 (artist_type_id), INDEX IDX_F90F069B6C3BF144 (shows), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborations  ADD CONSTRAINT FK_F90F069B7203D2A4 FOREIGN KEY (artist_type_id) REFERENCES artist_type (id)');
        $this->addSql('ALTER TABLE collaborations  ADD CONSTRAINT FK_F90F069B6C3BF144 FOREIGN KEY (shows) REFERENCES shows (id)');
        $this->addSql('DROP TABLE collaborations');
        $this->addSql('ALTER TABLE artist_type DROP FOREIGN KEY FK_3060D1B6C54C8C93');
        $this->addSql('ALTER TABLE artist_type ADD CONSTRAINT FK_3060D1B6C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id)');
        $this->addSql('ALTER TABLE locations DROP FOREIGN KEY FK_17E64ABA88823A92');
        $this->addSql('ALTER TABLE locations ADD CONSTRAINT FK_17E64ABA88823A92 FOREIGN KEY (locality_id) REFERENCES localities (id)');
        $this->addSql('ALTER TABLE representations DROP FOREIGN KEY FK_C90A401D0C1FC64');
        $this->addSql('ALTER TABLE representations CHANGE schedule schedule DATETIME NOT NULL');
        $this->addSql('ALTER TABLE representations ADD CONSTRAINT FK_C90A401D0C1FC64 FOREIGN KEY (show_id) REFERENCES shows (id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE shows DROP FOREIGN KEY FK_6C3BF14464D218E');
        $this->addSql('DROP INDEX IDX_6C3BF14464D218E ON shows');
        $this->addSql('ALTER TABLE shows CHANGE location location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shows ADD CONSTRAINT FK_6C3BF14464D218E FOREIGN KEY (location_id) REFERENCES locations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6C3BF14464D218E ON shows (location_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborations (id INT AUTO_INCREMENT NOT NULL, artist_type_id INT NOT NULL, shows INT NOT NULL, INDEX IDX_F90F069B7203D2A4 (artist_type_id), INDEX IDX_F90F069B6C3BF144 (shows), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE collaborations ADD CONSTRAINT FK_F90F069B6C3BF144 FOREIGN KEY (shows) REFERENCES shows (id)');
        $this->addSql('ALTER TABLE collaborations ADD CONSTRAINT FK_F90F069B7203D2A4 FOREIGN KEY (artist_type_id) REFERENCES artist_type (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE collaborations ');
        $this->addSql('ALTER TABLE artist_type DROP FOREIGN KEY FK_3060D1B6C54C8C93');
        $this->addSql('ALTER TABLE artist_type ADD CONSTRAINT FK_3060D1B6C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE locations DROP FOREIGN KEY FK_17E64ABA88823A92');
        $this->addSql('ALTER TABLE locations ADD CONSTRAINT FK_17E64ABA88823A92 FOREIGN KEY (locality_id) REFERENCES localities (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE representations DROP FOREIGN KEY FK_C90A401D0C1FC64');
        $this->addSql('ALTER TABLE representations CHANGE schedule schedule DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE representations ADD CONSTRAINT FK_C90A401D0C1FC64 FOREIGN KEY (show_id) REFERENCES shows (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shows DROP FOREIGN KEY FK_6C3BF14464D218E');
        $this->addSql('DROP INDEX IDX_6C3BF14464D218E ON shows');
        $this->addSql('ALTER TABLE shows CHANGE location_id location INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shows ADD CONSTRAINT FK_6C3BF14464D218E FOREIGN KEY (location) REFERENCES locations (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6C3BF14464D218E ON shows (location)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}

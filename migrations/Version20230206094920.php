<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206094920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, institution_id INT NOT NULL, type VARCHAR(50) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, subject VARCHAR(50) NOT NULL, external_note LONGTEXT DEFAULT NULL, internal_note LONGTEXT DEFAULT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_AC74095A2B6FCFB2 (guid), INDEX IDX_AC74095AA76ED395 (user_id), INDEX IDX_AC74095A10405986 (institution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_contact (activity_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_4C3090D881C06096 (activity_id), INDEX IDX_4C3090D8E7A1254A (contact_id), PRIMARY KEY(activity_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_external_participant (activity_id INT NOT NULL, external_participant_id INT NOT NULL, INDEX IDX_FA6499DE81C06096 (activity_id), INDEX IDX_FA6499DE8C7528FF (external_participant_id), PRIMARY KEY(activity_id, external_participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_tag (activity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_71B0290181C06096 (activity_id), INDEX IDX_71B02901BAD26311 (tag_id), PRIMARY KEY(activity_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email1 VARCHAR(100) NOT NULL, email2 VARCHAR(100) DEFAULT NULL, phone_number1 VARCHAR(50) NOT NULL, phone_number2 VARCHAR(50) DEFAULT NULL, job_title VARCHAR(100) NOT NULL, exact_guid VARCHAR(255) DEFAULT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4C62E6382B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE external_participant (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BF5826662B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE institution (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(100) NOT NULL, house_number VARCHAR(10) NOT NULL, city VARCHAR(100) NOT NULL, zip_code VARCHAR(10) NOT NULL, country VARCHAR(50) NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, postbox VARCHAR(10) DEFAULT NULL, exact_sales VARCHAR(50) DEFAULT NULL, exact_guid VARCHAR(255) DEFAULT NULL, client_id VARCHAR(50) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3A9F98E52B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE institution_contact (institution_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_289C10AA10405986 (institution_id), INDEX IDX_289C10AAE7A1254A (contact_id), PRIMARY KEY(institution_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, status VARCHAR(25) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, INDEX IDX_8F3F68C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, activity_id INT NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C42F77842B6FCFB2 (guid), INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F778481C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_attachment (id INT AUTO_INCREMENT NOT NULL, report_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6A1DF5802B6FCFB2 (guid), INDEX IDX_6A1DF5804BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_email (id INT AUTO_INCREMENT NOT NULL, report_id INT NOT NULL, email VARCHAR(100) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CD8E7B0D2B6FCFB2 (guid), INDEX IDX_CD8E7B0D4BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_389B7832B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, description VARCHAR(255) NOT NULL, completed TINYINT(1) DEFAULT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_527EDB252B6FCFB2 (guid), INDEX IDX_527EDB2581C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, exact_sales VARCHAR(50) DEFAULT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6492B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_institution (user_id INT NOT NULL, institution_id INT NOT NULL, INDEX IDX_93845170A76ED395 (user_id), INDEX IDX_9384517010405986 (institution_id), PRIMARY KEY(user_id, institution_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voice_memo (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8689B7F12B6FCFB2 (guid), UNIQUE INDEX UNIQ_8689B7F181C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A10405986 FOREIGN KEY (institution_id) REFERENCES institution (id)');
        $this->addSql('ALTER TABLE activity_contact ADD CONSTRAINT FK_4C3090D881C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_contact ADD CONSTRAINT FK_4C3090D8E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_external_participant ADD CONSTRAINT FK_FA6499DE81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_external_participant ADD CONSTRAINT FK_FA6499DE8C7528FF FOREIGN KEY (external_participant_id) REFERENCES external_participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B0290181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B02901BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE institution_contact ADD CONSTRAINT FK_289C10AA10405986 FOREIGN KEY (institution_id) REFERENCES institution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE institution_contact ADD CONSTRAINT FK_289C10AAE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE report_attachment ADD CONSTRAINT FK_6A1DF5804BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE report_email ADD CONSTRAINT FK_CD8E7B0D4BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2581C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE user_institution ADD CONSTRAINT FK_93845170A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_institution ADD CONSTRAINT FK_9384517010405986 FOREIGN KEY (institution_id) REFERENCES institution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voice_memo ADD CONSTRAINT FK_8689B7F181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AA76ED395');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A10405986');
        $this->addSql('ALTER TABLE activity_contact DROP FOREIGN KEY FK_4C3090D881C06096');
        $this->addSql('ALTER TABLE activity_contact DROP FOREIGN KEY FK_4C3090D8E7A1254A');
        $this->addSql('ALTER TABLE activity_external_participant DROP FOREIGN KEY FK_FA6499DE81C06096');
        $this->addSql('ALTER TABLE activity_external_participant DROP FOREIGN KEY FK_FA6499DE8C7528FF');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B0290181C06096');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B02901BAD26311');
        $this->addSql('ALTER TABLE institution_contact DROP FOREIGN KEY FK_289C10AA10405986');
        $this->addSql('ALTER TABLE institution_contact DROP FOREIGN KEY FK_289C10AAE7A1254A');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778481C06096');
        $this->addSql('ALTER TABLE report_attachment DROP FOREIGN KEY FK_6A1DF5804BD2A4C0');
        $this->addSql('ALTER TABLE report_email DROP FOREIGN KEY FK_CD8E7B0D4BD2A4C0');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2581C06096');
        $this->addSql('ALTER TABLE user_institution DROP FOREIGN KEY FK_93845170A76ED395');
        $this->addSql('ALTER TABLE user_institution DROP FOREIGN KEY FK_9384517010405986');
        $this->addSql('ALTER TABLE voice_memo DROP FOREIGN KEY FK_8689B7F181C06096');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_contact');
        $this->addSql('DROP TABLE activity_external_participant');
        $this->addSql('DROP TABLE activity_tag');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE external_participant');
        $this->addSql('DROP TABLE institution');
        $this->addSql('DROP TABLE institution_contact');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_attachment');
        $this->addSql('DROP TABLE report_email');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_institution');
        $this->addSql('DROP TABLE voice_memo');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

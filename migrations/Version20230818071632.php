<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818071632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, review_id INT NOT NULL, path VARCHAR(255) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_795FD9BB2B6FCFB2 (guid), INDEX IDX_795FD9BB3E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, review_id INT NOT NULL, email VARCHAR(100) NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E7927C742B6FCFB2 (guid), INDEX IDX_E7927C743E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME on update CURRENT_TIMESTAMP, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_794381C62B6FCFB2 (guid), UNIQUE INDEX UNIQ_794381C681C06096 (activity_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB3E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
		$this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C743E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
		$this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
		$this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
		$this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778481C06096');
		$this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
		$this->addSql('ALTER TABLE report_attachment DROP FOREIGN KEY FK_6A1DF5804BD2A4C0');
		$this->addSql('ALTER TABLE report_email DROP FOREIGN KEY FK_CD8E7B0D4BD2A4C0');
		$this->addSql('DROP TABLE report');
		$this->addSql('DROP TABLE report_attachment');
		$this->addSql('DROP TABLE report_email');
		$this->addSql('ALTER TABLE activity ADD email_sent_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
		$this->addSql('ALTER TABLE activity CHANGE email_sent_at email_sent_at DATETIME(6) DEFAULT NULL');
	}

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, activity_id INT NOT NULL, title VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, guid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F778481C06096 (activity_id), UNIQUE INDEX UNIQ_C42F77842B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
		$this->addSql('CREATE TABLE report_attachment (id INT AUTO_INCREMENT NOT NULL, report_id INT DEFAULT NULL, path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, guid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6A1DF5802B6FCFB2 (guid), INDEX IDX_6A1DF5804BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
		$this->addSql('CREATE TABLE report_email (id INT AUTO_INCREMENT NOT NULL, report_id INT NOT NULL, email VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, guid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CD8E7B0D2B6FCFB2 (guid), INDEX IDX_CD8E7B0D4BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
		$this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
		$this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
		$this->addSql('ALTER TABLE report_attachment ADD CONSTRAINT FK_6A1DF5804BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
		$this->addSql('ALTER TABLE report_email ADD CONSTRAINT FK_CD8E7B0D4BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
		$this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB3E2E969B');
		$this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C743E2E969B');
		$this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C681C06096');
		$this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
		$this->addSql('DROP TABLE attachment');
		$this->addSql('DROP TABLE email');
		$this->addSql('DROP TABLE review');
		$this->addSql('ALTER TABLE activity DROP email_sent_at');
		$this->addSql('ALTER TABLE activity CHANGE email_sent_at email_sent_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE modified modified DATETIME(6) DEFAULT NULL');
	}
}

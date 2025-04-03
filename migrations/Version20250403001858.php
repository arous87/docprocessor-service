<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403001858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE paper (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, anchor VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE paper_tag (paper_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_520EFB56E6758861 (paper_id), INDEX IDX_520EFB56BAD26311 (tag_id), PRIMARY KEY(paper_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paper_tag ADD CONSTRAINT FK_520EFB56E6758861 FOREIGN KEY (paper_id) REFERENCES paper (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paper_tag ADD CONSTRAINT FK_520EFB56BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE paper_tag DROP FOREIGN KEY FK_520EFB56E6758861
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paper_tag DROP FOREIGN KEY FK_520EFB56BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE paper
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE paper_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
    }
}

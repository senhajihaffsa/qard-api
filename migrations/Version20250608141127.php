<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608141127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE financial_statement (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(36) NOT NULL, year INT DEFAULT NULL, revenue DOUBLE PRECISION DEFAULT NULL, net_income DOUBLE PRECISION DEFAULT NULL, ebitda DOUBLE PRECISION DEFAULT NULL, published_at DATETIME DEFAULT NULL, INDEX IDX_C71BA024A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE officer (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(100) DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, status VARCHAR(100) DEFAULT NULL, start_date DATETIME DEFAULT NULL, INDEX IDX_8273CFDAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE financial_statement ADD CONSTRAINT FK_C71BA024A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE officer ADD CONSTRAINT FK_8273CFDAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_profile CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE siren siren VARCHAR(20) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE ape_code ape_code VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE siren siren VARCHAR(20) DEFAULT NULL, CHANGE type type VARCHAR(20) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE status status VARCHAR(50) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE financial_statement DROP FOREIGN KEY FK_C71BA024A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE officer DROP FOREIGN KEY FK_8273CFDAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE financial_statement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE officer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_profile CHANGE name name VARCHAR(255) DEFAULT 'NULL', CHANGE siren siren VARCHAR(20) DEFAULT 'NULL', CHANGE country country VARCHAR(255) DEFAULT 'NULL', CHANGE ape_code ape_code VARCHAR(255) DEFAULT 'NULL', CHANGE postal_code postal_code VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT 'NULL' COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE siren siren VARCHAR(20) DEFAULT 'NULL', CHANGE type type VARCHAR(20) DEFAULT 'NULL', CHANGE created_at created_at DATETIME DEFAULT 'NULL', CHANGE status status VARCHAR(50) DEFAULT 'NULL'
        SQL);
    }
}

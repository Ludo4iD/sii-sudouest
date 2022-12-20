<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration Init DataBase
 */
final class Version1 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initialisation de la Base de données';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bookmark (
            id INT AUTO_INCREMENT NOT NULL,
            url VARCHAR(255) NOT NULL,
            provider VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            created_on DATE NOT NULL,
            published_on DATE NOT NULL,
            width INT DEFAULT NULL,
            height INT DEFAULT NULL,
            duration INT DEFAULT NULL,
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bookmark');
    }
}

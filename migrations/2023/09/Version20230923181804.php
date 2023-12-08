<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230923181804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create working_day and booking tables.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE booking (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', working_day_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', booker_first_name VARCHAR(64) NOT NULL, booker_last_name VARCHAR(64) NOT NULL, booker_email VARCHAR(255) NOT NULL, time_from TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', time_to TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', INDEX IDX_E00CEDDEC2FEC4CB (working_day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE working_day (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', staffer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', working_hours JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEC2FEC4CB FOREIGN KEY (working_day_id) REFERENCES working_day (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEC2FEC4CB');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE working_day');
    }
}

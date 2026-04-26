<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260426115243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, title, is_pinned, status, user_id, priority_id, folder_id FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, is_pinned BOOLEAN DEFAULT NULL, status VARCHAR(255) DEFAULT \'pending\' NOT NULL, user_id INTEGER NOT NULL, priority_id INTEGER DEFAULT NULL, folder_id INTEGER DEFAULT NULL, CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_527EDB25162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task (id, title, is_pinned, status, user_id, priority_id, folder_id) SELECT id, title, is_pinned, status, user_id, priority_id, folder_id FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB25162CB942 ON task (folder_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB25497B19F9 ON task (priority_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, title, is_pinned, status, user_id, folder_id, priority_id FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, is_pinned BOOLEAN DEFAULT NULL, status VARCHAR(255) DEFAULT \'pending\' NOT NULL, user_id INTEGER NOT NULL, folder_id INTEGER NOT NULL, priority_id INTEGER DEFAULT NULL, CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_527EDB25162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task (id, title, is_pinned, status, user_id, folder_id, priority_id) SELECT id, title, is_pinned, status, user_id, folder_id, priority_id FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25162CB942 ON task (folder_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB25497B19F9 ON task (priority_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310124604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id UUID NOT NULL, customer_id UUID NOT NULL, status VARCHAR(255) NOT NULL, channels TEXT NOT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN notification.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.channels IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE notification_call (id UUID NOT NULL, notification_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, scheduled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(255) NOT NULL, channel VARCHAR(255) NOT NULL, provider VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_74B26404EF1A9D84 ON notification_call (notification_id)');
        $this->addSql('COMMENT ON COLUMN notification_call.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification_call.notification_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification_call.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notification_call.scheduled_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE provider_configuration (id UUID NOT NULL, provider VARCHAR(255) NOT NULL, channel VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, priority SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN provider_configuration.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE notification_call ADD CONSTRAINT FK_74B26404EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notification_call DROP CONSTRAINT FK_74B26404EF1A9D84');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_call');
        $this->addSql('DROP TABLE provider_configuration');
    }
}

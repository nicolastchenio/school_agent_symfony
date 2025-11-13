<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251113063710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_agent (utilisateur_id INT NOT NULL, agent_id INT NOT NULL, INDEX IDX_E04EC443FB88E14F (utilisateur_id), INDEX IDX_E04EC4433414710B (agent_id), PRIMARY KEY(utilisateur_id, agent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur_agent ADD CONSTRAINT FK_E04EC443FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_agent ADD CONSTRAINT FK_E04EC4433414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD utilisateur_id INT DEFAULT NULL, ADD agent_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E93414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9FB88E14F ON conversation (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E93414710B ON conversation (agent_id)');
        $this->addSql('ALTER TABLE matiere ADD agent_id INT NOT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('CREATE INDEX IDX_9014574A3414710B ON matiere (agent_id)');
        $this->addSql('ALTER TABLE message ADD conversation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
        $this->addSql('ALTER TABLE userlog ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE userlog ADD CONSTRAINT FK_4E107E60FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E107E60FB88E14F ON userlog (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur ADD niveau_scolaire_id INT NOT NULL, ADD userlog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3C3584218 FOREIGN KEY (niveau_scolaire_id) REFERENCES niveau_scolaire (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3829D4A42 FOREIGN KEY (userlog_id) REFERENCES userlog (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3C3584218 ON utilisateur (niveau_scolaire_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3829D4A42 ON utilisateur (userlog_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur_agent DROP FOREIGN KEY FK_E04EC443FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_agent DROP FOREIGN KEY FK_E04EC4433414710B');
        $this->addSql('DROP TABLE utilisateur_agent');
        $this->addSql('ALTER TABLE userlog DROP FOREIGN KEY FK_4E107E60FB88E14F');
        $this->addSql('DROP INDEX UNIQ_4E107E60FB88E14F ON userlog');
        $this->addSql('ALTER TABLE userlog DROP utilisateur_id');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9FB88E14F');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E93414710B');
        $this->addSql('DROP INDEX IDX_8A8E26E9FB88E14F ON conversation');
        $this->addSql('DROP INDEX IDX_8A8E26E93414710B ON conversation');
        $this->addSql('ALTER TABLE conversation DROP utilisateur_id, DROP agent_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3C3584218');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3829D4A42');
        $this->addSql('DROP INDEX IDX_1D1C63B3C3584218 ON utilisateur');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3829D4A42 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP niveau_scolaire_id, DROP userlog_id');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A3414710B');
        $this->addSql('DROP INDEX IDX_9014574A3414710B ON matiere');
        $this->addSql('ALTER TABLE matiere DROP agent_id');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396 ON message');
        $this->addSql('ALTER TABLE message DROP conversation_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251112112101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utiliser (agent_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5C9491093414710B (agent_id), INDEX IDX_5C949109A76ED395 (user_id), PRIMARY KEY(agent_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utiliser ADD CONSTRAINT FK_5C9491093414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utiliser ADD CONSTRAINT FK_5C949109A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere ADD agent_id INT NOT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('CREATE INDEX IDX_9014574A3414710B ON matiere (agent_id)');
        $this->addSql('ALTER TABLE user ADD niveau_scolaire_id INT NOT NULL, DROP niveau_education');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C3584218 FOREIGN KEY (niveau_scolaire_id) REFERENCES niveau_scolaire (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C3584218 ON user (niveau_scolaire_id)');
        $this->addSql('ALTER TABLE user_log ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_log ADD CONSTRAINT FK_6429094EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6429094EFB88E14F ON user_log (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utiliser DROP FOREIGN KEY FK_5C9491093414710B');
        $this->addSql('ALTER TABLE utiliser DROP FOREIGN KEY FK_5C949109A76ED395');
        $this->addSql('DROP TABLE utiliser');
        $this->addSql('ALTER TABLE user_log DROP FOREIGN KEY FK_6429094EFB88E14F');
        $this->addSql('DROP INDEX UNIQ_6429094EFB88E14F ON user_log');
        $this->addSql('ALTER TABLE user_log DROP utilisateur_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C3584218');
        $this->addSql('DROP INDEX IDX_8D93D649C3584218 ON user');
        $this->addSql('ALTER TABLE user ADD niveau_education VARCHAR(100) NOT NULL, DROP niveau_scolaire_id');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A3414710B');
        $this->addSql('DROP INDEX IDX_9014574A3414710B ON matiere');
        $this->addSql('ALTER TABLE matiere DROP agent_id');
    }
}

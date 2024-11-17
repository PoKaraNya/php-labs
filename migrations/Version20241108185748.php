<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108185748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_order_item (id INT AUTO_INCREMENT NOT NULL, purchase_order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price_per_unit INT NOT NULL, total_price INT NOT NULL, INDEX IDX_5ED948C3A45D7E6A (purchase_order_id), INDEX IDX_5ED948C34584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_order_item ADD CONSTRAINT FK_5ED948C3A45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES purchase_order (id)');
        $this->addSql('ALTER TABLE purchase_order_item ADD CONSTRAINT FK_5ED948C34584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_order_items DROP FOREIGN KEY FK_193D85494584665A');
        $this->addSql('ALTER TABLE purchase_order_items DROP FOREIGN KEY FK_193D8549A45D7E6A');
        $this->addSql('DROP TABLE purchase_order_items');
        $this->addSql('ALTER TABLE order_item ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_order_items (id INT AUTO_INCREMENT NOT NULL, purchase_order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price_per_unit INT NOT NULL, total_price INT NOT NULL, INDEX IDX_193D8549A45D7E6A (purchase_order_id), INDEX IDX_193D85494584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE purchase_order_items ADD CONSTRAINT FK_193D85494584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_order_items ADD CONSTRAINT FK_193D8549A45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES purchase_order (id)');
        $this->addSql('ALTER TABLE purchase_order_item DROP FOREIGN KEY FK_5ED948C3A45D7E6A');
        $this->addSql('ALTER TABLE purchase_order_item DROP FOREIGN KEY FK_5ED948C34584665A');
        $this->addSql('DROP TABLE purchase_order_item');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('DROP INDEX IDX_52EA1F094584665A ON order_item');
        $this->addSql('ALTER TABLE order_item DROP product_id');
    }
}

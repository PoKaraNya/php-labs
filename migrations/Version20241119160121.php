<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119160121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP total_amount');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09FCDAEAAA');
        $this->addSql('DROP INDEX IDX_52EA1F09FCDAEAAA ON order_item');
        $this->addSql('ALTER TABLE order_item CHANGE order_id_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        $this->addSql('ALTER TABLE purchase_order DROP total_cost');
        $this->addSql('ALTER TABLE purchase_order_item DROP total_price');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DCFCDAEAAA');
        $this->addSql('DROP INDEX IDX_2CB20DCFCDAEAAA ON shipment');
        $this->addSql('ALTER TABLE shipment CHANGE order_id_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_2CB20DC8D9F6D38 ON shipment (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD total_amount INT NOT NULL');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('DROP INDEX IDX_52EA1F098D9F6D38 ON order_item');
        $this->addSql('ALTER TABLE order_item CHANGE order_id order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F09FCDAEAAA ON order_item (order_id_id)');
        $this->addSql('ALTER TABLE purchase_order ADD total_cost INT NOT NULL');
        $this->addSql('ALTER TABLE purchase_order_item ADD total_price INT NOT NULL');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC8D9F6D38');
        $this->addSql('DROP INDEX IDX_2CB20DC8D9F6D38 ON shipment');
        $this->addSql('ALTER TABLE shipment CHANGE order_id order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DCFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_2CB20DCFCDAEAAA ON shipment (order_id_id)');
    }
}

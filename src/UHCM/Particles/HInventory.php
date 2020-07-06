<?php

namespace UHCM\Particles;

use pocketmine\block\Block;
use pocketmine\inventory\CustomInventory;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TE;

class HInventory extends CustomInventory{

    protected $holder;

    public function __construct(Position $position){
        parent::__construct($position);
        $this->setItem(0, $this->setPack1());
        $this->setItem(1, $this->setPack2());
    }

    public function getName(): string{
        return "Hopper";
    }

    public function getDefaultSize(): int{
        return 5;
    }

    public function getNetworkType(): int{
        return WindowTypes::HOPPER;
    }

    public function onOpen(Player $who): void{
        $block = Block::get(Block::HOPPER_BLOCK);
        $block->x = $this->getHolder()->getX();
        $block->y = $this->getHolder()->getY();
        $block->z = $this->getHolder()->getZ();
        $block->level = $this->getHolder()->getLevel();
        $who->getLevel()->sendBlocks([$who], [$block]);
        $w = new NetworkLittleEndianNBTStream;
        $nbt = new CompoundTag("", []);
        $nbt->setString("id", "Hopper");
        $nbt->setString("CustomName", TE::GREEN . "KITS Scena");
        $pk = new BlockActorDataPacket();
        $pk->x = $this->getHolder()->getX();
        $pk->y = $this->getHolder()->getY();
        $pk->z = $this->getHolder()->getZ();
        $pk->namedtag = $w->write($nbt);
        $who->dataPacket($pk);
        parent::onOpen($who);
    }

    public function onClose(Player $who): void{
        $block = Block::get(Block::AIR);
        $block->x = $this->getHolder()->getX();
        $block->y = $this->getHolder()->getY();
        $block->z = $this->getHolder()->getZ();
        $block->level = $this->getHolder()->getLevel();
        $who->getLevel()->sendBlocks([$who], [$block]);
        parent::onClose($who);
    }

    public function getHolder(): Position{
        return $this->holder;
    }


    public function setPack1(): Item{
        $item = Item::get(Item::BLAZE_ROD);
        $item->setCustomName(TE::RED . "KIT FIRELEES");
        $item->setLore([" ",  TE::YELLOW . "full diamond ",TE::YELLOW . "fireproof",TE::YELLOW . "gold apple x5: ",TE::YELLOW . "enchants x2: "]);
        return $item;
    }

    public function setPack2(): Item{
        $item = Item::get(Item::EMERALD);
        $item->setCustomName(TE::RED . "KIT NOFALL");
        $item->setLore([" ",  TE::YELLOW . "full diamond ",TE::YELLOW . "nofall",TE::YELLOW . "gold apple x10: ",TE::YELLOW . "enchants x1: "]);
        return $item;
    }
    
   
    
}

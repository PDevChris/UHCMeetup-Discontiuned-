<?php

namespace UHCM\manager;



use pocketmine\Player;

use pocketmine\item\Item;

use pocketmine\inventory\Inventory;

use pocketmine\tile\Chest;

use pocketmine\tile\Tile;

use pocketmine\nbt\NBT;

use pocketmine\math\Vector3;

use pocketmine\block\Block;

use pocketmine\level\Position;

trait DropTrailt {

	

	public function kickChest(Player $sender){

    

    $level = $sender->getPosition();

    $world = $sender->getLevel();

    $block = Block::get(Block::CHEST);

    $x = ((int)$level->getX());

    $y = ((int)$level->getY());

    $z = ((int)$level->getZ());

    $world->setBlock(new Vector3($x,$y,$z),$block);

    $world->setblock(new Vector3($x+1,$y,$z),$block);

    

    $nbt = Chest::createNBT(new Vector3($x,$y,$z));

    $nbt2 = Chest::createNBT(new Vector3($x+1,$y,$z));

    $tile = Tile::createTile(Tile::CHEST, $world, $nbt);

    $tile2 = Tile::createTile(Tile::CHEST, $world, $nbt2);

    

    $tile->pairwith($tile2);

    $tile2->pairwith($tile);

    if($tile instanceof Chest){

    $item = $sender->getInventory()->getContents();
$armor = $sender->getArmorInventory()->getContents();
    foreach($armor as $tool){

    $tile->getInventory()->addItem($tool); 

    }
    
    foreach($item as $i){

    $tile->getInventory()->addItem($i); 

    }

    }

	}

	

	

	

	}

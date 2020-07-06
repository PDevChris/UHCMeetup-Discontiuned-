<?php


namespace UHCM\events;


use pocketmine\event\player\{PlayerInteractEvent,PlayerItemHeldEvent};

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use UHCM\Particles\HInventory;

use pocketmine\utils\TextFormat as TE;
use pocketmine\level\Position;

use pocketmine\item\Item;


use pocketmine\Player;


use pocketmine\entity\EntityFactory;


use pocketmine\level\Level;


use pocketmine\event\Listener;


use pocketmine\tile\Sign;


use pocketmine\utils\Config;


use pocketmine\math\{Vector3,Vector2};


use pocketmine\network\mcpe\protocol\LevelEventPacket;


use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;


use UHCM\manager\Settings;


use UHCM\UHCM;


class ItemsTap implements Listener {





    public $plugin;


	public $author = "[TG]";


	public $version = 1.14;


	public $main = "events";


	


	public function __construct(UHCM $plugin){


		    $this->plugin = $plugin;


			$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);


	}


 


 


 public function itemsTapEvent(PlayerInteractEvent $event) {


		$item = $event->getPlayer()->getInventory()->getItemInHand()->getName();


        $player = $event->getPlayer();


       if($event->getAction()==PlayerInteractEvent::RIGHT_CLICK_BLOCK) {


         if($item == TE::DARK_GREEN."KITS"){
/*
            $pos = new Position(intval($player->getX()), intval($player->getY()) + 2, intval($player->getZ()), $player->getLevel());
        	$player->addWindow(new HInventory($pos));
*/
$this->plugin->setKitU($player);

        }  if($item == TE::DARK_GREEN."EXPULSE PLAYER"){
            $level = $player->getLevel()->getFolderName();
               $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);
		            $rule = $config->get($level."Game");
				     if($rule==Settings::PRE_TO_START_1) {
        	$this->plugin->manager->setExpulse($player,$level);
             }
        }  if($item == TE::DARK_GREEN."START GAME"){
        	$level = $player->getLevel()->getFolderName();
                 $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);
		            $rule = $config->get($level."Game");
				     if($rule==Settings::PRE_TO_START_1) {
					    $counter = $this->plugin->manager->hasArenaCount($level) ?? 0; 
					   if($counter>=2) {
					    $lob = $this->plugin->getServer()->getLevelByName($level);
					    $config->set($level."Game",Settings::PRE_TELEPORT_2);
                        $config->set($level."ToStartime", Settings::TIME_TO_START_1);
                        $config->set($level."TeleportTime", Settings::TIME_TELEPORT_2+16);
                        $config->save();
                        $this->plugin->getJoinItem($player);
                        $player->getInventory()->removeItem(Item::get(Item::CLOCK,0,1));
                        foreach($lob->getPlayers() as $loss){  
                        $this->plugin->addSounds($loss,"item.trident.return");
                        $loss->sendMessage("§8[§7GAME-START§8]".TE::WHITE."»by ".TE::GREEN.$player->getName().TE::GRAY." Game started preparations in ".TE::AQUA." 20sg"); }
                        } else {
                            $player->sendMessage("§c[ERROR]§6 Cannot start without a player!");
                            }
					}
	       }  
    } 


      }





public function onInv(InventoryTransactionEvent $e): void{
        $tr = $e->getTransaction();
        foreach($tr->getActions() as $act){
            if($act instanceof SlotChangeAction){
                $inv = $act->getInventory();
                if($inv instanceof HInventory){
                    $player = $tr->getSource();
                    $e->setCancelled();
                    switch($act->getSourceItem()->getId()){
                        case Item::BLAZE_ROD:
                        $this->plugin->manager->addKit($player->getName(),"FIRELESS");
                        $player->sendMessage("§l§8[§a+§8]§r§6Have chosen Kit:§6 firelees");
                        $this->plugin->addSounds($player,"random.shulkerboxopen");
                            break;
                        case Item::EMERALD;
                        $this->plugin->manager->addKit($player->getName(),"NOFALL");
                        $player->sendMessage("§l§8[§a+§8]§r§6Have chosen Kit:§6 nofall");
                        $this->plugin->addSounds($player,"random.shulkerboxopen");
                            break;
                            default:
                            
                            break;
                    }
                    $inv->onClose($player);
                }
            }
        }
    }






 }

<?php


namespace UHCM\events;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\event\block\BlockPlaceEvent;


use pocketmine\utils\TextFormat as TE;


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


class PlaceBreak implements Listener {





    public $plugin;


	public $author = "[TG]";


	public $version = 1.14;


	public $main = "events";


	


	public function __construct(UHCM $plugin){


		    $this->plugin = $plugin;


			$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);


	}


 


 


 public function onEventBreaks(BlockBreakEvent $ev) {


		   $player = $ev->getPlayer();


            $level = $player->getLevel()->getFolderName();


            $block = $ev->getBlock();


            $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


            if(isset($players2[$player->getName()])) {


            $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


            $rule = $config->get($level."Game");


            if($rule==Settings::PRE_TO_START_1) {


            $ev->setCancelled(true);


              } else if($rule==Settings::PRE_TELEPORT_2) {


            $ev->setCancelled(true);


              } else if($rule==Settings::PRE_END_4) {


            $ev->setCancelled(true);


              }


           } 


           


	}


		public function onMoveBorde(PlayerMoveEvent $ev){
		$player = $ev->getPlayer();
		$arena = $player->getLevel()->getFolderName();
		$config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
		$border = $config->get($arena."BORDE");
            $rule = $config->get($arena."Game");
            if($rule==Settings::PRE_START_3) {
           $players2 = $this->plugin->manager->hasPlayers($arena)== is_null(null) ? $this->plugin->manager->hasPlayers($arena) : null;
            if(isset($players2[$player->getName()])) {
       $x = round($player->getX());
       $y = round($player->getY());
       $z = round($player->getZ());
		if($x > $border+1) {
          	$player->teleport(new Vector3($border-5,70,$player->getZ()));
          	$player->setMotion(new Vector3(-5,0,0));
          $player->addTitle("§c§lᴄᴜɪᴅᴀᴅᴏ!","§6§lɴᴏ sᴀʟɢᴀs ᴅᴇʟ ʙᴏʀᴅᴇ", 20,40,20);
          }
          if($x < -$border-1) {
          	$player->teleport(new Vector3($border+5,70,$player->getZ()));
          	$player->setMotion(new Vector3(5,0,0));
          $player->addTitle("§c§lᴄᴜɪᴅᴀᴅᴏ!","§6§lɴᴏ sᴀʟɢᴀs ᴅᴇʟ ʙᴏʀᴅᴇ", 20,40,20);
          }
          if($z > $border+1) {
          	$player->teleport(new Vector3($border,70,$player->getZ()-5));
          	$player->setMotion(new Vector3(0,0,-5));
          $player->addTitle("§c§lᴄᴜɪᴅᴀᴅᴏ!","§6§lɴᴏ sᴀʟɢᴀs ᴅᴇʟ ʙᴏʀᴅᴇ", 20,40,20);
          }
          if($z < -$border-1) {
          $player->teleport(new Vector3($border,70,$player->getZ()+5));
          	$player->setMotion(new Vector3(0,0,5));
          $player->addTitle("§c§lᴄᴜɪᴅᴀᴅᴏ!","§6§lɴᴏ sᴀʟɢᴀs ᴅᴇʟ ʙᴏʀᴅᴇ", 20,40,20);
          }
		}
	}
}


		


		


	public function onEventPlace(BlockPlaceEvent $event) {


		$player = $event->getPlayer();


		$level = $player->getLevel()->getFolderName();


		$block = $event->getBlock();


        $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


            $rule = $config->get($level."Game");


             $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


            if(isset($players2[$player->getName()])) {


            if($rule==Settings::PRE_TO_START_1) {


                            $event->setCancelled(true);


                        } else if($rule==Settings::PRE_TELEPORT_2) {


                            $event->setCancelled(true);


                        } else if($rule==Settings::PRE_END_4) {


                            $event->setCancelled(true);


                        }


        }


}


 


 


 }
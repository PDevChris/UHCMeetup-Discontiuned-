<?php


namespace UHCM\events;


use pocketmine\event\player\PlayerQuitEvent;


use pocketmine\event\entity\EntityLevelChangeEvent;


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


class QuitGame implements Listener {





    public $plugin;


	public $author = "[TG]";


	public $version = 1.14;


	public $main = "events";


	


	public function __construct(UHCM $plugin){


		    $this->plugin = $plugin;


			$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);


	}


 


 public function onQuit(PlayerQuitEvent $ev) {


		    $player = $ev->getPlayer();


            $level = $player->getLevel()->getFolderName();


            $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


            if(isset($players2[$player->getName()])) {

$player->getArmorInventory()->clearAll();
            $player->getInventory()->clearAll();




            $this->plugin->manager->delPlayer($player->getName(),$player->getLevel()->getFolderName());


           }


        $player->removeAllEffects();


        $player->setHealth(20);


        $player->setFood(20);

        $this->plugin->manager->delKill($player->getName());

$this->plugin->delOnline($player->getName());
$this->plugin->deleteCode($player);
$this->plugin->score->remove($player);
        $this->plugin->manager->delPlayer($player->getName(),$player->getLevel()->getFolderName());


        $this->plugin->manager->delFakeSpec($player->getName(),$player->getLevel()->getFolderName());


   }


	


	public function onLeaveLevel(EntityLevelChangeEvent $event) {
               $pl = $event->getEntity();
            if($pl instanceof Player) {
                $this->plugin->score->remove($pl);
                $pl->removeAllEffects();
               $pl->getArmorInventory()->clearAll();
                $pl->getInventory()->clearAll();
                $pl->setHealth(20);
                $pl->setFood(20);
                $this->plugin->manager->delKill($pl->getName());
                $this->plugin->delOnline($pl->getName());
                $this->plugin->score->remove($pl);
                $this->plugin->manager->delPlayer($pl->getName(),$pl->getLevel()->getFolderName());
                $this->plugin->manager->delFakeSpec($pl->getName(),$pl->getLevel()->getFolderName());
         } 


      }


 


 


 


 }
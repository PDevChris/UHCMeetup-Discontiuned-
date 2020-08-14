<?php

namespace UHCM;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;
use pocketmine\utils\Config;
use UHCM\score\ScoreAPI;
use pocketmine\item\Item;
use pocketmine\entity\Skin;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\{
LevelEventPacket,
PlaySoundPacket,
LevelSoundEventPacket
};

use UHCM\events\{
DamageTouch,
PlaceBreak,
QuitGame,
ItemsTap
};

use UHCM\traits\{
CommandsTrait,
MapsPrivate,
JoinTrait
};

use UHCM\task\{
UHCTask,
SignTask
};

use UHCM\manager\{
Settings
};

use UHCM\entities\{
NPCUHCm
};

use UHCM\form\FormUI;

class UHCM extends PluginBase implements Listener {
	use FormUI;
	use CommandsTrait,MapsPrivate,JoinTrait;
	
	public static $instance = null;
	public $score = null;
	
	public $damageEv = null; 
	public $blockEv = null; 
	public $change = null; 
	public $itemsE = null; 
	public $signEv = null; 
	
	public $startslot = 2;
	public $manager = null;
	
	public $arenas = [];
	public $signOps = [];
	public $yts = [];
	public $mapp = [];
	public $listbox = [];
	
	public $pricestore = 50;
	public $kits = [];
	public $online = [];
	public $skinsNPC = ["NPC.png","NPC.json"];
	
	public function onEnable() : void {
		self::$instance = $this;
		
		$this->damageEv = new DamageTouch($this);
		$this->blockEv = new PlaceBreak($this);
		$this->change = new QuitGame($this);
		$this->itemsE = new ItemsTap($this);
		//$this->signEv = new SignCreate($this);
		$this->manager = new Settings($this);
		$this->score = new ScoreAPI($this);
		
		Entity::registerEntity(NPCUHCm::class,true);
		
		@mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder()."TG/");
        @mkdir($this->getDataFolder()."CAGE/");
        @mkdir($this->getDataFolder()."DATA/");
        @mkdir($this->getDataFolder()."USERS/");
        $this->getServer()->getLogger()->info("§a[Speed-UHC-ONLINE] Si sirve we :v");
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);  
        $this->manager->setWorlsGame();
        foreach($this->skinsNPC as $file){
			$this->saveResource($file);
		} 
     //   $this->getScheduler()->scheduleRepeatingTask(new SignTask($this), 10);
        $this->getScheduler()->scheduleRepeatingTask(new UHCTask($this), 20);
        }


public static function getInstance() {
         return self::$instance;
}

public function addLevelSound(Player $pl,int $pitch = 0) : void {
                $pl->getLevel()->broadcastLevelSoundEvent($pl, LevelSoundEventPacket::SOUND_LEVELUP,$pitch);
    }



public function addSounds(Player $pl,string $name = "mob.guardian.ambient",int $pitch = 1) {
  	                      $pk = new PlaySoundPacket();
                            $pk->soundName = $name;
                            $pk->x = $pl->x;
                            $pk->y = $pl->y;
                            $pk->z = $pl->z;
                            $pk->volume = 2;
                            $pk->pitch = $pitch;
                            $pl->dataPacket($pk);
  }

public function addOnline(string $name) : void {
    if(!isset($this->online[$name])) {
        $this->online[$name] = $name;
      }
    }
    
    public function delOnline(string $name) : void {
    if(isset($this->online[$name])) {
        unset($this->online[$name]);
      }
    }
    
    
    public function getOnline() : int {
     $int = count($this->online) ?? 0;
     return $int;
    }
    
    
    public function setSkinNPC($player,$SkinName){
        $skin = $player->getSkin();
        $path = $this->getDataFolder().$SkinName.".png";
        $img = @imagecreatefrompng($path);
        $skinbytes = "";
        $s = (int)@getimagesize($path)[1];

        for($y = 0; $y < $s; $y++) {
            for($x = 0; $x < 64; $x++) {
                $colorat = @imagecolorat($img, $x, $y);
                $a = ((~((int)($colorat >> 24))) << 1) & 0xff;
                $r = ($colorat >> 16) & 0xff;
                $g = ($colorat >> 8) & 0xff;
                $b = $colorat & 0xff;
                $skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        $player->setSkin(new Skin($skin->getSkinId(), $skinbytes, "", "geometry.".$SkinName,file_get_contents($this->getDataFolder().$SkinName.".json")));
        $player->sendSkin();
    }
    
    
    public function getJoinItem(Player $player) {
		$s = Item::get(Item::CHEST, 0, 1);
		$f = Item::get(Item::STAINED_GLASS, 14, 1);
        $efficiency = Enchantment::getEnchantment(0);
        $efficiency1 = new EnchantmentInstance($efficiency, 1);
        $s->addEnchantment($efficiency1);
        $f->addEnchantment($efficiency1);
        $s->setCustomName(TE::DARK_GREEN."KITS");
        $f->setCustomName(TE::RED."RETURN TO");
        $player->getInventory()->setItem(0, $s);
        $player->getInventory()->setItem(8, $f);
  }
  
  public function getJoinItemStart(Player $player) {
		$s = Item::get(Item::CLOCK, 0, 1);
		$b = Item::get(Item::BOOK, 0, 1);
		$f = Item::get(Item::STAINED_GLASS, 14, 1);
        $efficiency = Enchantment::getEnchantment(0);
        $efficiency1 = new EnchantmentInstance($efficiency, 1);
        $s->addEnchantment($efficiency1);
        $b->addEnchantment($efficiency1);
        $f->addEnchantment($efficiency1);
        $s->setCustomName(TE::DARK_GREEN."START GAME");
        $b->setCustomName(TE::DARK_GREEN."EXPULSE PLAYER");
        $player->getInventory()->setItem(0, $s);
         $f->setCustomName(TE::RED."RETURN TO");
        $player->getInventory()->setItem(8, $f);
        $player->getInventory()->setItem(1, $b);
        
  }
  
  public function removeItems(Player $pl) {
		$pl->getInventory()->removeItem(Item::get(Item::STAINED_GLASS,14,1));
		$pl->getInventory()->removeItem(Item::get(Item::CHEST,0,1));
		$pl->getInventory()->removeItem(Item::get(Item::CLOCK,0,1));
		$pl->getInventory()->removeItem(Item::get(Item::BOOK,0,1));
  }

    
    public function setKitU(Player $player) { 
         $form = $this->createSimpleForm(function(Player $player, ?int $data){
               if( !is_null($data)) {
                switch($data) {
            case 0:
            $this->manager->addKit($player->getName(),"FIRELESS");
            $player->sendMessage("§l§8[§a+§8]§r§6Has chosen el Kit:§6 fireless");
            $this->addSounds($player,"random.shulkerboxopen");
            break;
            case 1:
            $this->manager->addKit($player->getName(),"NOFALL");
            $player->sendMessage("§l§8[§a+§8]§r§6Has chosen el Kit:§6 nofall");
            $this->addSounds($player,"random.shulkerboxopen");
            break;
            
              default:
                return;
                } } });
          $form->setTitle("§e§lKITS SCENARIO");
          $form->addButton("§l§cKIT: FIRELESS\n§r§frandom enchants");
          $form->addButton("§l§aKIT: NOFALL\n§r§frandom enchants");
        
          $form->sendToPlayer($player);
         }
         



}

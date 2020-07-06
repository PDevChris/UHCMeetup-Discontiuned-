<?php





namespace Sw\task;


use pocketmine\scheduler\Task;


use pocketmine\utils\TextFormat as TE;


use pocketmine\utils\Config;


use pocketmine\level\Position;


use pocketmine\Player;


use pocketmine\tile\Sign;


use pocketmine\math\AxisAlignedBB;


use Sw\Sw;


use pocketmine\level\Level;


use pocketmine\math\Vector3;


use Sw\manager\Settings;


use pocketmine\block\Block;


class SignTask extends Task {


	


	public $plugin;


	public $author = "[TG]";


	public $version = 1.14;


	public $main = "task";


    


	public function __construct(Sw $plugin){


		$this->plugin = $plugin;


	}


	public function onRun(int $currentTick) : void{

		$allplayers = $this->plugin->getServer()->getOnlinePlayers();


		$level = $this->plugin->getServer()->getDefaultLevel();


		$tiles = $level->getTiles();


		foreach($tiles as $t) {


			if($t instanceof Sign) {	


				$text = $t->getText();


				if($text[0]==Settings::SIGN_PREFIX_TOSTART || $text[0]==Settings::SIGN_PREFIX_TELEPORT || $text[0]==Settings::SIGN_PREFIX_START || $text[0]==Settings::SIGN_PREFIX_END) {


					$mapa = str_replace([Settings::SIGN_MAP_1,Settings::SIGN_MAP_2,Settings::SIGN_MAP_3,Settings::SIGN_MAP_4],"",$text[2]);


					$slots = $this->plugin->manager->hasArenaCount($mapa) == null ? "?" : $this->plugin->manager->hasArenaCount($mapa);


					$play = $this->plugin->getServer()->getLevelByName($mapa);


					$ac = new Config($this->plugin->getDataFolder() . "TG/$mapa.yml", Config::YAML);


                    $slotlvl = $ac->get("slots");


					$ingame = TE::GREEN."Waiting";


					$title = Settings::SIGN_PREFIX_TOSTART;


					$scolor = Settings::SIGN_MAP_1;


					$config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);


					if($config->get($mapa. "TeleportTime")!=Settings::TIME_TELEPORT_2)


					{ $ingame = TE::YELLOW."Preparing game"; $title = Settings::SIGN_PREFIX_TELEPORT; $scolor = Settings::SIGN_MAP_2; }


					if($config->get($mapa. "PlayTime")!=Settings::TIME_START_3)


					{ $ingame = TE::RED."Spectator"; $title = Settings::SIGN_PREFIX_START; $scolor = Settings::SIGN_MAP_3; }


					if($config->get($mapa . "EndTime")!=11)


					{ $ingame = TE::LIGHT_PURPLE."Storing wait."; $title = Settings::SIGN_PREFIX_END; $scolor = Settings::SIGN_MAP_4; } 


					


					elseif($slots>=$slotlvl) {


                     $ingame = TE::BOLD.TE::YELLOW."FULL-GAME"; }


					$t->setText($title,TE::WHITE.$slots." \ $slotlvl",$scolor.$mapa,$ingame); } } } }


//241






      


      }



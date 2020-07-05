<?php


namespace UHCM\traits;


use UHCM\form\FormUI;


use pocketmine\plugin\PluginBase;


use pocketmine\scheduler\PluginTask;


use pocketmine\event\Listener;


use pocketmine\utils\TextFormat as TE;


use pocketmine\utils\Config;


use pocketmine\level\Position;


use pocketmine\math\Vector3;

use pocketmine\entity\Entity;
use pocketmine\Player;


use pocketmine\command\CommandSender;


use pocketmine\network\mcpe\protocol\LevelEventPacket;


use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;


use pocketmine\command\Command;
use UHCM\entities\{
NPCUHCm
};


use UHCM\manager\Settings as SETTING;


trait CommandsTrait {


	


	


	public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool {


		$config = new Config($this->getDataFolder().'config.yml', Config::YAML);


        switch($cmd->getName()){


        	case "uhcm":


            if(isset($args[0])) {


            	


            	if($args[0]=="help") {


$task = "§9(TG)§c"; 


$player->sendMessage($task . "§7=====----------------------====="); 


$player->sendMessage($task . "/uhcm new (world) (slots):§f Crear nueva arena"); 


$player->sendMessage($task . "/uhcm spawn (slot/numer):§f Crear spawns"); 


$player->sendMessage($task . "/uhcm tops:§f crear tops posicion"); 


$player->sendMessage($task . "/uhcm sign (world):§f crear cartel posicion"); 


$player->sendMessage($task . "/uhcm time (world) (time):§f modifica tiempo"); 

$player->sendMessage($task . "§7=====----------------------====="); 


            } 

else if($args[0]=="new" && $player->isOp()) { if(!empty($args[1])){ if(!empty($args[2]) && is_numeric($args[2])){ 


 if(in_array($args[1],$this->arenas)){


$map = new Config($this->getDataFolder() . "TG/$args[1].yml", Config::YAML);


$map->set("slots", (int) $args[2]);


$map->save();


$player->sendMessage(TE::RED."Se ha modificado los slots en:".TE::AQUA.$args[1]);


     } else {


$configMap = new Config($this->getDataFolder() . "TG/$args[1].yml", Config::YAML);

$url = new Config($this->getDataFolder()."CAGE/mapsurl.yml", Config::YAML);
$url->set($args[1],null);
$url->save();
$configMap->set("slots", (int) $args[2]);


$configMap->save();


$this->getServer()->loadLevel($args[1]);


$this->getServer()->getLevelByName($args[1])->loadChunk($this->getServer()->getLevelByName($args[1])->getSafeSpawn()->getFloorX(), $this->getServer()->getLevelByName($args[1])->getSafeSpawn()->getFloorZ());


array_push($this->arenas,$args[1]);


$config->set("arenas",$this->arenas);


$config->set($args[1]."Game",SETTING::GAME_STATUS_DEFAULT);


$config->set($args[1]. "ToStartime", SETTING::TIME_TO_START_1);


$config->set($args[1]. "TeleportTime", SETTING::TIME_TELEPORT_2);


$config->set($args[1]. "PlayTime", SETTING::TIME_START_3);


$config->set($args[1]."EndTime", SETTING::TIME_END_4);


$config->save(); 


$this->zipType($player, $args[1]);


$player->sendMessage(SETTING::GAME_PREFIX.TE::WHITE."Has creado nuevo mundo: ".TE::GOLD.$args[1].TE::WHITE." Slots: ".TE::GOLD.$args[2]);


     } } } }


  else if ($args[0] === "spawn" && $player->isOp()){ if(!empty($args[1])) {


 $yaw = $player->yaw;


 $pit = $player->pitch;


 $x = floor($player->x);


 $y = floor($player->y);


 $z = floor($player->z);


 $xyz = array($x, $y, $z, $yaw, $pit);


 $arena = $player->getLevel()->getFolderName();


 $config->set($arena."Spawn" .$args[1],[


 "X"=>$x,


 "Y"=>$y,


 "Z"=>$z,


 "YAW"=>$yaw,


 "PITCH"=>$pit


 ]);


 $config->save();


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Spawn registrado ".TE::WHITE."#".$args[1]);


    } }


 else if ($args[0] === "lobby" && $player->isOp()){


 $x = $player->getX(); $y = $player->getY(); $z = $player->getZ(); $xyz = array(intval($x), intval($y), intval($z));


 $arena = $player->getLevel()->getFolderName();


 $config->set($arena."Lobby",$xyz);


 $config->save();


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Lobby registrado");


    } 


  else if ($args[0] === "sign" && $player->isOp()){ if(!empty($args[1])) {


 $this->manager->namesign = (string)$args[1];


 $this->signOps[$player->getName()] = 1;


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Toca un cartel");


    } }


  

    else if ($args[0] === "money" && $player->isOp()){ if(!empty($args[1])) {


$this->manager->setCoins($args[1],500);


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se han entragado 500 coins¡");


    } } else if ($args[0] === "cg" && $player->isOp()){ if(!empty($args[1])) {
$my = new Config($this->getDataFolder()."DATA/".$player->getName().".yml", Config::YAML);
                    if($my->get("DEFAULT")!=null) {
                        $this->setCageVIP($player,ucfirst($my->get("DEFAULT")));
                         $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se puso nueva caja¡");
                        } else {
                             $player->sendMessage(SETTING::GAME_PREFIX.TE::RED."No tienes ninguna caja default¡");
                            }
    } } else if ($args[0] === "setcage" && $player->isOp()){ if(!empty($args[1])) {
$online = $this->getServer()->getPlayer($args[1]);
$this->loadPrivateVIP($args[1]);
 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se han entragado interfast de cajas¡");
    } } else if ($args[0] === "npc" && $player->isOp()){ 
 $this->addNPC($player);
 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se ha agrega el npc¡");
    } 
    
    else if ($args[0] === "cage" && $player->isOp()){ if(!empty($args[1])) {
$my = new Config($this->getDataFolder()."DATA/".$player->getName().".yml", Config::YAML);
$my->set("DEFAULT",ucfirst($args[1]));
$my->save();
 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se han cambiado de caja¡");
    } } else if ($args[0] === "me" && $player->isOp()){ 
$this->loadMyCages($player);
    } else if ($args[0] === "store" && $player->isOp()){ 
$this->loadStored($player);
    } 
    
    else if ($args[0] === "addstore" && $player->isOp()){ if(!empty($args[1])) {
$my = new Config($this->getDataFolder()."CAGE/store.yml", Config::YAML);
$cage = $my->get("CAGES",[]);
$cage[] = ucfirst($args[1]);
$my->set("CAGES",$cage);
$my->save();
$url = new Config($this->getDataFolder()."DATA/url.yml", Config::YAML);
$url->set(ucfirst($args[1]),null);
$url->save();
 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se agrego nueva caga en la tienda¡");
    } } else if ($args[0] === "addvip" && $player->isOp()){ if(!empty($args[1])) {
$my = new Config($this->getDataFolder()."CAGE/private.yml", Config::YAML);
$cage = $my->get("CAGES",[]);
$cage[] = ucfirst($args[1]);
$my->set("CAGES",$cage);
$my->save();
$url = new Config($this->getDataFolder()."DATA/url.yml", Config::YAML);
$url->set(ucfirst($args[1]),null);
$url->save();
 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se agrego nueva caga privada¡");
 
    } }


    else if ($args[0] === "slot" && $player->isOp()){ if(!empty($args[1])) {


$this->startslot = $args[1];


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Slot modificado");


    } } else if ($args[0] === "price" && $player->isOp()){ if(!empty($args[1])) {


$this->pricestore = (int)$args[1];


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."Se cambio la cantida de coins en la tienda de cajas");


    } } else if ($args[0] === "tp" && $player->isOp()){ 


  $e = $args[1] ?? "SW";


  $slot = $args[2] ?? 1;


 $stun = $config->get($e."Spawn".$slot);


 $player->teleport(new Vector3($stun["X"]+0.4, $stun["Y"]+0.2,$stun["Z"]+0.4));


 $player->yaw = $stun["YAW"];


 $player->pitch = $stun["PITCH"];


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."ECHO");


 } 


else if ($args[0] === "time" && $player->isOp()){ if(!empty($args[1])) { if(!empty($args[2])) {


$config->set($args[1]. "PlayTime", $args[2]);


$config->save(); 


 $player->sendMessage(SETTING::GAME_PREFIX.TE::AQUA."running");


    } } }





 }


            return true;


            break;


            default:


            return false;


            break;


           


        }


   }


	


	public function zipType($player, $name) {


                   $path = realpath($player->getServer()->getDataPath() . 'worlds/' . $name);


				   $zip = new \ZipArchive;


				  @mkdir($this->getDataFolder() . 'arenas/', 0755);


				   $zip->open($this->getDataFolder() . 'arenas/' . $name . '.zip', $zip::CREATE | $zip::OVERWRITE);


				   $files = new \RecursiveIteratorIterator(


					new \RecursiveDirectoryIterator($path),


					\RecursiveIteratorIterator::LEAVES_ONLY);


                foreach ($files as $datos) {


				if (!$datos->isDir()) {


			    $relativePath = $name . '/' . substr($datos, strlen($path) + 1);


				$zip->addFile($datos, $relativePath); } }


				$zip->close();


				$player->getServer()->loadLevel($name);


				unset($zip, $path, $files); }


	


		public function openAllSW(Player $player) { 
         $form = $this->createSimpleForm(function(Player $player, ?int $data){
               if( !is_null($data)) {
                switch($data) {
            case 0:
            $this->loadMyCages($player);
            break;
            case 1:
            $this->loadStored($player);
            break;
            
              default:
                return;
                } } });
          $form->setTitle("§3§lSKYWARS");
          $form->addButton("§l§fMY CAGES\n§8my cages sw");
          $form->addButton("§l§fSHOP CAGES\n§8shop cages sw");
          $form->sendToPlayer($player);
         }



		


		public function addNPC(Player $player) : void {
	  $nbt = Entity::createBaseNBT(new Vector3($player->x,$player->y+0.2,$player->z), null, $player->yaw, $player->pitch);
      $nbt->setTag($player->namedtag->getTag("Skin"));
      $entity = Entity::createEntity("NPCUHCm", $player->getLevel(), $nbt);
      $entity->setNameTag("§a§l  UHCMEETUP  \n§fcargando datos..");
      $entity->spawnToAll();
      $this->setSkinNPC($entity,"NPC");
		}


	


	


	


	

	}
<?php
namespace UHCM\traits;

use pocketmine\utils\TextFormat as TE;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use UHCM\manager\Settings;

trait JoinTrait {
    
    
    public function joinSW(Player $player,string $mapa,string $code = "wcserveriscool") : void {
   $config = new Config($this->getDataFolder()."/config.yml", Config::YAML);
  $lob = $this->getServer()->getLevelByName($mapa);
 $levelArena = $this->getServer()->getLevelByName($mapa);
 $playersArena = $levelArena->getPlayers();
 $ac = new Config($this->getDataFolder() . "TG/$mapa.yml", Config::YAML);
  $slotlvl = $ac->get("slots");
  $onlin = $this->manager->hasArenaCount($mapa)+1?? 1;
  $sl = $this->manager->getSpawn($player->getName(),$mapa);
 $stun = $config->get($mapa."Spawn".$onlin);
 $player->teleport(new Position($stun["X"]+0.5, $stun["Y"]+1,$stun["Z"]+0.5,$levelArena),$stun["YAW"],$stun["PITCH"]);
 $player->setAllowFlight(false);  
 $player->setGamemode(2);
 $player->getInventory()->clearAll();
$player->getArmorInventory()->clearAll();
 $player->removeAllEffects();
 foreach($lob->getPlayers() as $playersinarena){  
$this->addSounds($playersinarena,"random.orb");
 $playersinarena->sendMessage("§8[§7JOIN-GAME§8][".$sl."]".TE::WHITE."» ".TE::YELLOW.$player->getName().TE::YELLOW." joined the game ".TE::RED."(".$onlin."/".$slotlvl.")"); }
 $player->setFood(20);
 $player->addTitle(TE::AQUA.TE::BOLD."WELCOME",TE::WHITE."You have entered the game",20,40,20);
 $player->setHealth(20);
 $this->getJoinItem($player);
 if($this->isArenaUse($mapa)==true) {
  $world = new Config($this->getDataFolder()."DATA/".$code.".yml", Config::YAML);
  $cage = $world->get("CAGE");
  $author = $world->get("AUTHOR");
  if($author==$player->getName()) {
       $player->getInventory()->clearAll();
       $player->getArmorInventory()->clearAll();
       $this->getJoinItemStart($player);
      }
     } else {
       $onliin = $this->manager->hasArenaCount($mapa) != null ? $this->manager->hasArenaCount($mapa) :  1;
      $this->speedStart($mapa,$onlin,$slotlvl);
}
 $this->addOnline($player->getName());
 $this->manager->setPlayer($player->getName(),$mapa);
 $player->setImmobile(true);
         }
         
       public function isMapsOnline(Player $pl) : void {
           $config = new Config($this->getDataFolder()."/config.yml", Config::YAML);
		if($config->get("arenas")!=null) { 
			$maps = $config->get("arenas",[]);
			 foreach($maps as $online) {
				  $rule = $config->get($online."Game");
				     if($rule==Settings::PRE_TO_START_1) {
					   if($this->isArenaUse($online)==true) continue;
					     $this->joinSW($pl,$online);
					     break;
					  } else {
                    $pl->sendTip("§f[SUHC-SOLO]§6 Looking match...!");
                       }
				   }
                } else {
                    $pl->sendMessage("§c[ERROR]§6 No maps found");
                    }
           }
         
         
         public function openSWTouch(Player $player) { 
         $form = $this->createSimpleForm(function(Player $player, ?int $data){
               if( !is_null($data)) {
                switch($data) {
            case 0:
            $this->isMapsOnline($player);
            break;
            case 1:
            $this->addCodePrivate($player);
            break;
            case 2:
            if($this->isCreate($player)==true) {
                $player->sendMessage("§8[§cERROR§8]§f You made a arena!");
                } else {
            $this->loadPrivateMap($player);
            }
            break;
              default:
                return;
                } } });
          $online = $this->getOnline();
          $form->setTitle("§3§lClick to join match");
          $form->addButton("§l§2SpeedUHC§8-§aSOLO\n§r§8Connected players:§b ".$online);
          $form->addButton("§l§2SpeedUHC-§aCODE\n§r§8Private rooms");
          if($player->hasPermission("create.sw.private")) {
          $form->addButton("§l§2NEW§8-§aROOM\n§r§8Create room");
          }
          $form->sendToPlayer($player);
         }
         
         
         public function createMapPrivate(Player $player) : void {
		$form = $this->createCustomForm(function(Player $player,array $data = null){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$hack = $data[0] ?? "NOT";
			$config = new Config($this->getDataFolder()."DATA/".strtolower("codes").".yml", Config::YAML);
			$maps = new Config($this->getDataFolder()."DATA/".strtolower("maps").".yml", Config::YAML);
		if(is_string($data[0])) {
			if(strlen($hack)>=7) {
		       $up = $maps->get("LIST",[]);
		       if(!in_array($this->mapp[$player->getName()],$up)) {
			          $all = $config->get("CODES",[]);
			       if(!in_array($result,$all)) {
			   $cc = $config->get("CODES",[]);
			   $cc[] = $hack;
			
		       $config->set("CODES",$cc);
		       $config->save();
		
		$world = new Config($this->getDataFolder()."DATA/".$result.".yml", Config::YAML);
		$world->set("ARENA",$this->mapp[$player->getName()]);
		$world->set("CAGE",$this->cagee[$player->getName()]);
		$world->set("AUTHOR",$player->getName());
		$world->save();
		
		$ares = new Config($this->getDataFolder()."DATA/MM".$this->mapp[$player->getName()].".yml", Config::YAML);
		$ares->set("CAGE",$this->cagee[$player->getName()]);
		$ares->set("AUTHOR",$player->getName());
		$ares->save();
		
		$pr = new Config($this->getDataFolder()."USERS/".$player->getName().".yml", Config::YAML);
		$pr->set("ARENA",$this->mapp[$player->getName()]);
		$pr->set("CODE",$hack);
		$pr->set("AUTHOR",$player->getName());
		$pr->save();
		
		$up[] = $this->mapp[$player->getName()];
		$maps->set("LIST",$up);
		$maps->save();
		   if(isset($this->mapp[$player->getName()])) {
            $player->sendMessage("§8[§6!§8]§f you've created this private code§6 ".$hack);
			$player->sendMessage("§8[§6!§8]§fThe hard code just gets off the server.!");
			$player->sendMessage("§8[§6!§8]§fThe hard code is over the game!");
			$player->sendMessage("§8[§6!§8]§Once the game has started you will no longer be able to participate!");
			$player->sendMessage("§8[§6!§8]§fYou can start the game with the clock item");
			
			         unset($this->mapp[$player->getName()]);
			unset($this->cagee[$player->getName()]);
			                  }
			                } else {
				
			   unset($this->mapp[$player->getName()]);
			unset($this->cagee[$player->getName()]);
				$player->sendMessage("§8[§cERROR§8]§f Sorry this code already exists!");
				        }
			        } else {
			
			   unset($this->mapp[$player->getName()]);
			unset($this->cagee[$player->getName()]);
				$player->sendMessage("§8[§cERROR§8]§f Sorry this map is in used try a new one");
				}
				} else {
					$player->sendMessage("§8[§cERROR§8]§f Code too short max 7");
					$this->createMapPrivate($player);
					}
			} else {
					$player->sendMessage("§8[§cERROR§8]§f Code has numbers, remove them!§6 ");
					}
			});
		$form->setTitle(TE::BOLD . "§6CREATE CODE MAP");
		$form->addInput("code unique define!");
		$form->sendToPlayer($player);
		}
         
         
         
         public function deleteCode(Player $pl) : void {
         $pr = new Config($this->getDataFolder()."USERS/".$pl->getName().".yml", Config::YAML);
         $code = $pr->get("CODE");
         $map = $pr->get("ARENA");
        if($code!=null) {
        $config = new Config($this->getDataFolder()."DATA/".strtolower("codes").".yml", Config::YAML);
	    $maps = new Config($this->getDataFolder()."DATA/".strtolower("maps").".yml", Config::YAML);
        $array = $config->get("CODES", []);
        unset($array[array_search($code, $array)]);
        $config->set("CODES", $array);
        $config->save();
        $arr = $maps->get("LIST", []);
        unset($arr[array_search($map, $arr)]);
        $maps->set("LIST", $arr);
        $maps->save();
        $pr->set("CODE",null);
        $pr->save();
        $this->delTool($code);
        $this->delToolMap($map);
        $this->removeCache($pl->getName());
        $this->antiLag($pl->getName());
            }
        }
        
        public function deleteCrasts(string $name) : void {
         $pr = new Config($this->getDataFolder()."USERS/".$name.".yml", Config::YAML);
         $code = $pr->get("CODE");
         $map = $pr->get("ARENA");
        if($code!=null) {
        $config = new Config($this->getDataFolder()."DATA/".strtolower("codes").".yml", Config::YAML);
	    $maps = new Config($this->getDataFolder()."DATA/".strtolower("maps").".yml", Config::YAML);
        $array = $config->get("CODES", []);
        unset($array[array_search($code, $array)]);
        $config->set("CODES", $array);
        $config->save();
        $arr = $maps->get("LIST", []);
        unset($arr[array_search($map, $arr)]);
        $maps->set("LIST", $arr);
        $maps->save();
        $pr->set("CODE",null);
        $pr->save();
        $this->delTool($code);
        $this->delToolMap($map);
        $this->antiLag($name);
            }
        }
    
    
    public function delTool(string $diretorio) {
		 $files = "plugin_data/UHC-MEETUP/DATA/".$diretorio.".yml";
            if (is_file($files))
                unlink($files);
        }
        
        public function removeCache(string $name) {
		 $files = "plugin_data/UHC-MEETUP/DATA/".$name.".yml";
            if (is_file($files))
                unlink($files);
        }
        
        public function antiLag(string $name) {
		 $files = "plugin_data/UHC-MEETUP/USERS/".$name.".yml";
            if (is_file($files))
                unlink($files);
        }
        
        public function delToolMap(string $arena) {
		 $files = "plugin_data/UHC-MEETUP/DATA/MM".$arena.".yml";
            if (is_file($files))
                unlink($files);
        }
        
        

         public function isCreate(Player $pl) : bool {
             $pr = new Config($this->getDataFolder()."USERS/".$pl->getName().".yml", Config::YAML);
              $code = $pr->get("CODE");
              if($code!=null) {
                  return true;
                  } else {
                      return false;
                      }
             }
             
             
             
             public function addCodePrivate(Player $player) : void {
		$form = $this->createCustomForm(function(Player $player,array $data = null){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$code = $result[0] ?? false;
			if($code==false) {
				$player->sendMessage("§c[ERROR]§6 You didnt put a code");
				} else if($this->isCodeValid($result)==true) {
					$config = new Config($this->getDataFolder()."/config.yml", Config::YAML);
					$world = new Config($this->getDataFolder()."DATA/".$result.".yml", Config::YAML);
		            $map = $world->get("ARENA");
		            $cage = $world->get("CAGE");
		            $rule = $config->get($map."Game");
				     if($rule==Settings::PRE_TO_START_1) {
					 $the = new Config($this->getDataFolder() . "TG/".$map.".yml",Config::YAML);
				      $slots = $the->get("slots"); 
				     $counter = $this->manager->hasArenaCount($map) ?? 0; 
				if($counter>=$slots) {
					$player->sendMessage("§6[FULL]§f Games are full. ");
					} else {
						
		            $this->joinSW($player,$map,$result);
		                       }
		             } else {
 $lob = $this->getServer()->getLevelByName($map);
 $player->teleport($this->getServer()->getLevelByName($map)->getSafeSpawn(),0,0); 
 $player->getInventory()->clearAll();
 $player->removeAllEffects();
 $player->setGamemode(3);
 $this->manager->setPlayerSpec($player->getName(),$player->getLevel()->getFolderName());
 foreach($lob->getPlayers() as $playersinarena){  
$playersinarena->sendMessage(Settings::GAME_PREFIX.TE::WHITE." new (spectator) ".TE::GRAY.$player->getNameTag().TE::AQUA." He has joined the match to watch."); }
			      }
               } else {
               	$player->sendMessage("§c[ERROR]§6 This code is in use!");
               	}
           });
		$form->setTitle(TE::BOLD . "§9JOIN PRIVATE");
		$form->addInput("here put the code!");
		$form->sendToPlayer($player);
		}
		
		
		
		public function speedStart(string $arena,int $slot,int $max) {
			if($slot>=$max) {
			$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
			$config->set($arena."Game",Settings::PRE_TELEPORT_2);
            $config->set($arena."ToStartime", Settings::TIME_TO_START_1);
            $config->save();
			    }
			}
         
         
         
    }

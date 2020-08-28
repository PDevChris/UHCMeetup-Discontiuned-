<?php



namespace UHCM\manager;



use pocketmine\utils\TextFormat as TE;



use pocketmine\utils\Config;



use pocketmine\level\Position;



use pocketmine\Player;



use UHCM\UHCM;



use UHCM\manager\{DropTrailt};



use pocketmine\tile\Sign;



use pocketmine\block\Block;



use pocketmine\math\{Vector3,Vector2};



use pocketmine\level\Level;



use pocketmine\item\Item;



use pocketmine\inventory\ChestInventory;



use pocketmine\network\mcpe\protocol\{AddEntityPacket,EntityEventPacket,PlaySoundPacket};



use pocketmine\tile\Chest;




use pocketmine\item\ItemFactory;



use pocketmine\entity\Entity;

use pocketmine\item\enchantment\EnchantmentInstance;



use pocketmine\item\enchantment\Enchantment;



class Settings {



	use DropTrailt;



	const GAME_PREFIX = TE::WHITE."[".TE::AQUA."Speed UHC".TE::WHITE."]";



	const GAME_JOIN = TE::WHITE."[".TE::AQUA."New".TE::WHITE."]";



	const GAME_STATUS = 1;



	const GAME_STATUS_DEFAULT = 1;



	



	



	const TIME_TO_START_1 = 41; //31 sg to starts



	const TIME_TELEPORT_2 = 5; // teleport is island time



	const TIME_START_3 = 60*10; // 10m finist game



	const TIME_END_4 = 11; //return to hub time



	



	const PRE_TO_START_1 = 1;



	const PRE_TELEPORT_2 = 2;



	const PRE_START_3 = 3;



	const PRE_END_4 = 4;



	



	public $players = [];



	public $spectators = [];



	public $cages = [];
	public $slots = [];



	



	public $myskins = [];



	public $mykits = [];



	public $myprojects = [];



	



	const SIGN_PREFIX = TE::BOLD.TE::DARK_AQUA."Speed UHC";



	const SIGN_COLOR1 = TE::BOLD;



	const SIGN_COLOR2 = TE::DARK_AQUA;



	



	const SIGN_PREFIX_TOSTART = TE::BOLD.TE::DARK_GREEN."Uhc";



	const SIGN_PREFIX_TELEPORT = TE::BOLD.TE::YELLOW."Uhc";



	const SIGN_PREFIX_START = TE::BOLD.TE::RED."Uhc";



	const SIGN_PREFIX_END = TE::BOLD.TE::LIGHT_PURPLE."Uhc";

	const BOSS_COLOR_1 = TE::BOLD.TE::GREEN."Spe".TE::DARK_GREEN."e".TE::GREEN."d".TE::DARK_GREEN."_".TE::GREEN."U".TE::DARK_GREEN."H".TE::GREEN."C";



	const BOSS_COLOR_2 = TE::BOLD.TE::YELLOW."S".TE::GOLD."K".TE::YELLOW."Y".TE::GOLD."W".TE::YELLOW."A".TE::GOLD."R".TE::YELLOW."S";



	const BOSS_COLOR_3 = TE::BOLD.TE::RED."S".TE::DARK_RED."K".TE::RED."Y".TE::DARK_RED."W".TE::RED."A".TE::DARK_RED."R".TE::RED."S";



	const BOSS_COLOR_4 = TE::BOLD.TE::LIGHT_PURPLE."S".TE::DARK_PURPLE."K".TE::LIGHT_PURPLE."Y".TE::DARK_PURPLE."W".TE::LIGHT_PURPLE."A".TE::DARK_PURPLE."R".TE::LIGHT_PURPLE."S";



	



	const SIGN_MAP_1 = TE::DARK_GREEN;



	const SIGN_MAP_2 = TE::GOLD;



	const SIGN_MAP_3 = TE::DARK_RED;



	const SIGN_MAP_4 = TE::DARK_PURPLE;



	



	const FIGURE_COLOR = ["§8","§f","§7"];



	const SLOT_TOP = "Available";



	const SLOT_TOP_KILLS = "not Available ";



	const FIGURE = "☆";



	const WIN_NOT = "§l§csɪɴ ᴛᴏᴘs ᴘᴏʀ ᴇʟ ᴍᴏᴍᴇɴᴛᴏ§3";



	public static $TITLE_TOPS = "";



	



	public $plugin;



	public $kills = [];



	public $kits = [];
	public $dataspect = [];



	public $changenick = true;



	public $namesign = "Offined";



	//public $rands = [Fireworks::COLOR_DARK_AQUA,Fireworks::COLOR_WHITE];



	//public $rands = [Fireworks::COLOR_GOLD,Fireworks::COLOR_PINK];



	//public $rands = [Fireworks::COLOR_GREEN,Fireworks::COLOR_RED,Fireworks::COLOR_GOLD,Fireworks::COLOR_PINK,Fireworks::COLOR_DARK_AQUA,Fireworks::COLOR_WHITE];



	



	public function __construct(UHCM $plugin){



		    $this->plugin = $plugin;



	}



	



	public function getPlayers(string $arena,string $name = null) {



				   return $this->hasArenaCount($arena);



		}



		



	 public function setPlayer(string $name,string $arena,int $type = 0) {
		if(!isset($this->players[$arena][$name])) {
		$this->players[$arena][$name] = $name;
		$this->addSpawn($name,$arena);
		 }      
	}
	
	public function addSpawn(string $name,string $arena) {
		if(isset($this->slots[$arena])!=false){
		    for($q=1; $q<=count($this->slots[$arena])+1; $q++) {
	          $s = array_values($this->slots[$arena]);
	          if(!in_array($q,$s)) {
		       $this->slots[$arena][$name] = $q;
		        break;
		         }
	        }
		 } else {
			$this->slots[$arena][$name] = 1;
			}
	}
	
	
	
	public function deleteSpawn(string $name,string $arena) {
		if(isset($this->slots[$arena][$name])) {
		   unset($this->slots[$arena][$name]);
		 }      
	}
	
	public function getSpawn(string $name,string $arena) : int {
		if(isset($this->slots[$arena][$name])) {
		   return $this->slots[$arena][$name] + 1;
		 } else {
			return 1;
			}
	}

public function addKit(string $name,string $kit) : void {
        $this->kits[$name] = $kit;
    }
    
    public function getKit(string $name) : string {
    if(isset($this->kits[$name])) {
        return $this->kits[$name];
        } else {
            return "NOKIT";
            }
    }

public function delKit(string $name) : void {
    if(isset($this->kits[$name])) {
        unset($this->kits[$name]);
        }
    }

public function loadKit(string $name,string $damage) : bool {
    $kit = $this->getKit($name);
    if($kit==$damage) {
        return true;
        } else {
                return false;
                }
    }
    
    public function setKitArmor(Player $nn,string $kit) : void {
        $pec = Item::get(Item::LEATHER_CHESTPLATE, 0, 1);
        
    switch($kit) {
        case "NOFALL":
        $pec->addEnchantment($prote);
        
          $nn->getInventory()->setItem(1, Item::get(Item::GOLDEN_APPLE, 0, 10));
          $nn->getInventory()->setItem(2, Item::get(Item::BREAD, 0, 10));
        break;
        case "FIRELESS":
		    
          $nn->getInventory()->setItem(3, $bow);
          $nn->getInventory()->setItem(4, Item::get(Item::ARROW, 0, 15));
        break;
        }
    }
	

public function addBorde(int $int,string $arena,int $next) {
               $up = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
               $up->set($arena."BORDE",$int);
               $up->set($arena."NEXT",$next);
               $up->save();
                }
     public function getBorde(string $arena) : int {
               $up = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
               $int = $up->get($arena."BORDE");
               return $int ?? 0;
                }
        public function getNext(string $arena) : int {
               $up = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
               $int = $up->get($arena."NEXT");
               return $int ?? 0;
                }
                


/*

$red = [14,6];
$yellow = [4,4];
$aqua = [9,11];
$green = [5,13];
$black = [7,15,12];
*/
public function setBorde(int $borde,string $arena,int $altura = 0,int $x = 0,int $gold = 0,int $z = 0,int $next,string $color,bool $update = false) {
                    $this->addBorde($borde,$arena,$next);
                    $levelArena = $this->plugin->getServer()->getLevelByName($arena);
			       if($levelArena instanceof Level) {
                    for($piso=1; $piso<=$borde; $piso++) {
                    	for($y=0; $y<=$altura; $y++) {
            $blocks = [241,236];
            $blockss = [7,7];
		    $levelArena->setBlock(new Vector3($x+$borde,$gold+$y,$z+$piso), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$borde,$gold+$y,$z+$piso), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$borde,$gold+$y,$z-$piso), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$borde,$gold+$y,$z-$piso), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$piso,$gold+$y,$z+$borde), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$piso,$gold+$y,$z+$borde), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$piso,$gold+$y,$z-$borde), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$piso,$gold+$y,$z-$borde), Block::get($blockss[mt_rand(0,1)]),$update,$update);
			if($color=="RED") { 
				$colors = [14,6];
				} else if($color=="BLUE") { 
				$colors = [9,11];
				} else if($color=="GREEN") { 
				$colors = [5,13];
				} else if($color=="BLACK") { 
				$colors = [7,15,12];
				} else if($color=="YELLOW") { 
				$colors = [4,4];
				} 
			$levelArena->setBlock(new Vector3($x+$borde-1,$gold+$y,$z+$piso-1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$borde+1,$gold+$y,$z+$piso-1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$borde+1,$gold+$y,$z-$piso+1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$borde-1,$gold+$y,$z-$piso+1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$piso-1,$gold+$y,$z+$borde-1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$piso+1,$gold+$y,$z+$borde-1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x-$piso+1,$gold+$y,$z-$borde+1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$piso-1,$gold+$y,$z-$borde+1), Block::get($blocks[mt_rand(0,1)],$colors[mt_rand(0,1)]),$update,$update);
			$levelArena->setBlock(new Vector3($x+$borde-1,$gold+$y,$z), Block::get(7),$update,$update);
			$levelArena->setBlock(new Vector3($x-$borde+1,$gold+$y,$z), Block::get(7),$update,$update);
			$levelArena->setBlock(new Vector3($x,$gold+$y,$z-$borde+1), Block::get(7),$update,$update);
			$levelArena->setBlock(new Vector3($x,$gold+$y,$z+$borde-1), Block::get(7),$update,$update);
			
			$levelArena->setBlock(new Vector3($x+$borde,$gold+$y,$z), Block::get(7),$update,$update);
			$levelArena->setBlock(new Vector3($x-$borde,$gold+$y,$z), Block::get(7),$update,$update);
			$levelArena->setBlock(new Vector3($x,$gold+$y,$z-$borde), Block::get(7),$update,$update);
			$levelArena->setBlock(new Vector3($x,$gold+$y,$z+$borde), Block::get(7),$update,$update);
			   
			} 
			} }
            }





	public function setPlayerSpec(string $name,string $arena,int $type = 0) {



		if(isset($this->spectators[$arena][$name])) {



		   		return "This player exists";



		   	} else {



		$this->spectators[$arena][$name] = $name;



		 }      



	}



	



	public function delPlayer(string $name,string $arena,int $type = 0) {



		if(isset($this->players[$arena][$name])) {



			    $pl = $this->plugin->getServer()->getPlayer($name);



			    $pl->setAllowFlight(false);  




                $pl->getInventory()->clearAll();


$pl->setImmobile(false);
                $pl->setGamemode(Player::ADVENTURE); 



                $this->delKill($pl->getName());

$this->delKit($pl->getName());
                  $this->deleteSpawn($name,$arena);
			    unset($this->players[$arena][$name]);



		   	}



	}



	



	public function delFake(string $name,string $arena,int $type = 0) {



		if(isset($this->players[$arena][$name])) {



			    unset($this->players[$arena][$name]);



		   	}



	}



	



	public function delFakeSpec(string $name,string $arena,int $type = 0) {



		if(isset($this->spectators[$arena][$name])) {



			    $pl = $this->plugin->getServer()->getPlayer($name);



			    $pl->setAllowFlight(false);  



                $pl->getInventory()->clearAll();



                $pl->setGamemode(Player::ADVENTURE); 



			    unset($this->spectators[$arena][$name]);



		   	}



	}



	



	public function hasArenaCount(string $arena,string $name = null) {



		if(isset($this->players[$arena])) {



		$d = count($this->players[$arena]);



	return $d;



	        } 



	}



	 
	
	
	
	public function loadTops(int $top,int $break,string $data = "Wins") : string {
            $tops = new Config($this->plugin->getDataFolder()."/".$data.".yml", Config::YAML);
            if($tops->getAll()!=null){
          	$all = $tops->getAll();
              $tt = 1;
              arsort($all);
              $p = []; 
foreach($all as $users => $tops){
if($tt==$top) { $p[$users] = $tops; } 
  $tt++; if($tt==$break) break; }
$maxp = $p == null ? 0 : max($p);
$topp = array_search($maxp, $p) == null ? TE::RED."lugar disponible" : array_search($maxp, $p);
 return TE::GOLD.$topp.TE::WHITE.": ".TE::YELLOW.$maxp;
	} else {
		return "No tops";
		}
}


public function getTopSWs(Player $pl) : void {
    $pl->sendMessage("     ".self::BOSS_COLOR_1.TE::WHITE." || ".TE::AQUA." TOPS-WINS");
    $pl->sendMessage(TE::AQUA.TE::BOLD." [☆1] ".$this->loadTops(1,2));
    $pl->sendMessage(TE::DARK_AQUA.TE::BOLD." [☆2] ".$this->loadTops(2,3));
    $pl->sendMessage(TE::RED.TE::BOLD." [☆3] ".$this->loadTops(3,4));
    $pl->sendMessage(TE::DARK_RED.TE::BOLD." [☆4] ".$this->loadTops(4,5));
    $pl->sendMessage(TE::DARK_RED.TE::BOLD." [☆5] ".$this->loadTops(5,6));
    }




	public function hasPlayers(string $arena,string $name = null) {



		if(isset($this->players[$arena])) {



		$d = $this->players[$arena];



	return $d;



	       } 



	}



	



	public function hasArenaCountSpec(string $arena,string $name = null) {



		if(isset($this->spectators[$arena])) {



		$d = count($this->spectators[$arena]);



	return $d;



	        } 



	}



	 



	public function hasPlayersSpec(string $arena,string $name = null) {



		if(isset($this->spectators[$arena])) {



		$d = $this->spectators[$arena];



	return $d;



	       } 



	}



	



	public function getArenas() {



		$arena = $this->plugin->arenas;



		return $arena;



	}



	



	public function kickGame(string $name,string $arena = "Speed") {



		  if(isset($this->players[$arena][$name])) {



				$pl = $this->plugin->getServer()->getPlayer($name);



				 if($pl instanceof Player) {



                if($pl->isOnline()){



                $pl->getInventory()->clearAll();              



                $this->delKill($pl->getName());  
$this->delKit($pl->getName());

                $pl->setImmobile(false);
                $pl->setGamemode(3); 



                } }



		       unset($this->players[$arena][$name]);



			} 



		}



		



	public function setKiller(string $name) {



			$this->kills[$name] = 0;



			}



			



			public function addKill(string $name) {
			$this->setKill($name);
			if(isset($this->kills[$name])) {
			$this->kills[$name] += 1;
			} else {
				$this->kills[$name] = 1;
				}
			}



			



			public function getKill(string $name) : int {



				if(isset($this->kills[$name])) {



			       $e = $this->kills[$name];



			          return $e;



			} else {



				   return 0;



				}



		}



		



		  public function delKill(string $name) {



				if(isset($this->kills[$name])) {



			       unset($this->kills[$name]);



			}



		}



	



	        public function getWins(string $name) {



			$tops = new Config($this->plugin->getDataFolder() . "/Wins.yml", Config::YAML);



			$get = $tops->get($name) == null ? 0 : $tops->get($name);



			return $get;



			}



			



			

			public function setWins(string $name) {



			$tops = new Config($this->plugin->getDataFolder() . "/Wins.yml", Config::YAML);



			$tops->set($name,$tops->get($name) + 1);



		    $tops->save();



			}



			



			public function setKill(string $name) {



			$tops = new Config($this->plugin->getDataFolder() . "/Kills.yml", Config::YAML);



			$tops->set($name,$tops->get($name) + 1);



		    $tops->save();



			}



			



			public function getKills(string $name) {



			$tops = new Config($this->plugin->getDataFolder() . "/Kills.yml", Config::YAML);



			$c = $tops->get($name) == null ? "§cSinDatos" : $tops->get($name);



		    return $c;



			}



			



			public function getLost(string $name) {



			$lost = new Config($this->plugin->getDataFolder() . "/Lost.yml", Config::YAML);



			$get = $lost->get($name) == null ? "§cSinDatos" : $lost->get($name);



		    return $get;



			}



			



			public function setLost(string $name) {



			$lost = new Config($this->plugin->getDataFolder() . "/Lost.yml", Config::YAML);



			$lost->set($name,$lost->get($name) + 1);



		    $lost->save();



			}



			



			public function getCoins(string $name) {



		    $coins = new Config($this->plugin->getDataFolder() . "/Coins.yml", Config::YAML);



			$c = $coins->get($name) == null ? 0 : $coins->get($name);



		    return $c;



			}



			



			public function setCoins(string $name,int $count = 5) {



			$coins = new Config($this->plugin->getDataFolder() . "/Coins.yml", Config::YAML);



			$coins->set($name,$coins->get($name) + $count);



		    $coins->save();



			}



			



			public function delCoins(string $name,int $count = 15) {



			$coins = new Config($this->plugin->getDataFolder() . "/Coins.yml", Config::YAML);



			$coins->set($name,$coins->get($name) - $count);



		    $coins->save();



			}



	



	public function setBlockSign(int $color = 3,string $world) : void {



   	$level = $this->plugin->getServer()->getDefaultLevel();



		$tiles = $level->getTiles();



		foreach($tiles as $t) {



			if($t instanceof Sign) {	



				$text = $t->getText();



				if($text[0]==self::SIGN_PREFIX_TOSTART || $text[0]==self::SIGN_PREFIX_TELEPORT || $text[0]==self::SIGN_PREFIX_START || $text[0]==self::SIGN_PREFIX_END) {



					$mapa = str_replace([self::SIGN_MAP_1,self::SIGN_MAP_2,self::SIGN_MAP_3,self::SIGN_MAP_4],"",$text[2]);



					if($mapa==$world) {



					$bll = new Config($this->plugin->getDataFolder() . "TG/$mapa.yml", Config::YAML);



                    $dire = $bll->get("Sign");



                    $ups = $dire[3]==null ? 0 : $dire[3];



					if($ups==0) {



                   $t->getLevel()->setBlock($t->add(1,0,0), Block::get(241,$color), false,false);



                  } else if($ups==1) {



                   $t->getLevel()->setBlock($t->add(0,0,1), Block::get(241,$color), false,false);



                  } else if($ups==2) {



                   $t->getLevel()->setBlock($t->add(-1,0,0), Block::get(241,$color), false,false);



                  } else if($ups==3) {



                   $t->getLevel()->setBlock($t->add(0,0,-1), Block::get(241,$color), false,false);



                  } 



       }    }



      



      } } }



      



      public function setWorlsGame() {




		$config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);



		if($config->get("arenas")!=null) { 



			   $this->plugin->arenas = $config->get("arenas");



               $this->plugin->getServer()->getLogger()->notice("§l§bAll maps are full!!"); 



             } else { $this->plugin->getServer()->getLogger()->notice("§l§cNo more maps!"); }



		foreach($this->plugin->arenas as $name) { 



			            if($name=="world") continue;



                        $this->reloadMap($name);



              //          $this->setBlockSign(5,$name);



                        $config->set($name."Game",self::GAME_STATUS_DEFAULT);



                        $config->set($name. "ToStartime", self::TIME_TO_START_1);


$config->set($name."BORDE",100);
$config->set($name."NEXT",80);
                        $config->set($name. "TeleportTime", self::TIME_TELEPORT_2);



                        $config->set($name. "PlayTime", self::TIME_START_3);



                        $config->set($name."EndTime", self::TIME_END_4);



						$config->save();



           }



		  $config->save();



		}



		



	public function reloadMap($lev) {



                if ($this->plugin->getServer()->isLevelLoaded($lev)) {



                $this->plugin->getServer()->unloadLevel($this->plugin->getServer()->getLevelByName($lev)); }



                $zip = new \ZipArchive;



                $zip->open($this->plugin->getDataFolder() . 'arenas/' . $lev . '.zip');



                $zip->extractTo($this->plugin->getServer()->getDataPath() . 'worlds');



                $zip->close();



                unset($zip);



                $rgb = ["§b","§3","§e"];



                $this->plugin->getServer()->getLogger()->notice("§l§bCargando mapas§f: ".$rgb[mt_rand(0,2)].$lev);



                $this->plugin->getServer()->loadLevel($lev);



                return true; }



                
public function setExpulse(Player $pl,string $arena) {
	   $form = $this->plugin->createSimpleForm(function(Player $pl, ?int $data){
		if( !is_null($data)) {
$all = $this->dataspect[$pl->getName()] ?? "value";
/*code*/
$vote = $all[$data];
$online = $this->plugin->getServer()->getPlayer($vote);
if($online!=null) {
$this->delPlayer($online->getName(),$online->getLevel()->getFolderName());
$online->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);
$online->sendMessage(TE::RED."[EXPELLED] ".TE::GOLD."No cuentas con los requisitos!");
$pl->sendMessage(TE::GREEN."[OKAY] ".TE::GOLD."User: ".TE::RED.$vote.TE::GOLD." was expelled!");
} else {
	$pl->sendMessage(TE::RED."Jugador desconectado");
	}
if(isset($this->dataspect[$pl->getName()])){ unset($this->dataspect[$pl->getName()]); }
}});
		$list = [];
		$allplayers = $this->hasPlayers($arena);
		foreach($allplayers as $names) {
		$form->addButton(TE::BOLD.TE::WHITE.$names.TE::GREEN."\nExpulsar Jugador");
          $list[] =  $names;
		 }
	     $form->setTitle(TE::GOLD.TE::BOLD."PLAY".TE::YELLOW."ERS");
	     $form->sendToPlayer($pl);
	     $this->dataspect[$pl->getName()] = $list;
	    } 


                


       

		public function setTime(int $value) {



		    $t = $value;



			$reddi = $t % 60;



            $jacket = ($t - $reddi) / 60;



            $valup = $jacket % 60;



            $s = str_pad($reddi, 2, "0", STR_PAD_LEFT);



            $m = str_pad($valup, 2, "0", STR_PAD_LEFT);



            return TE::WHITE.$m." §8:§f ".$s;



		}
		
		public function setSegs(int $value) {
		    $t = $value;
			$reddi = $t % 60;
            $jacket = ($t - $reddi) / 60;
            $valup = $jacket % 60;
            $s = str_pad($reddi, 2, "0", STR_PAD_LEFT);
            $m = str_pad($valup, 2, "0", STR_PAD_LEFT);
            return $s;
		}



	



	public function setColorBoss(int $game = 0) : string {



		switch($game) {



			case self::PRE_TO_START_1:



			return self::BOSS_COLOR_1;



			break;



			case self::PRE_TELEPORT_2:



			return self::BOSS_COLOR_2;



			break;



			case self::PRE_START_3:



			return self::BOSS_COLOR_3;



			break;



			case self::PRE_END_4:



			return self::BOSS_COLOR_4;



			break;



			default:



			return self::BOSS_COLOR_1;



			break;



			}



		}



	



	


      public function getTopsKills() {



            $tops = new Config($this->plugin->getDataFolder().'/Wins.yml', Config::YAML);



            if($tops->getAll()!=null){



          	$all = $tops->getAll();



              $tt = 1;



              arsort($all);



              $p = []; $s = []; $t = []; $c = []; $ci = [];



foreach($all as $users => $tops){



if($tt==1) { $p[$users] = $tops; }  



if($tt==2) { $s[$users] = $tops; }



if($tt==3) { $t[$users] = $tops; }



if($tt==4) { $c[$users] = $tops; }



if($tt==5) { $ci[$users] = $tops; } $tt++; if($tt==6) break; }



$maxp = $p == null ? 0 : max($p);



$topp = array_search($maxp, $p) == null ? TE::RED.self::SLOT_TOP : array_search($maxp, $p);



$maxs = $s == null ? 0 : max($s);



$tops = array_search($maxs, $s) == null ? TE::RED.self::SLOT_TOP : array_search($maxs, $s);



$maxt = $t == null ? 0 : max($t);



$topt= array_search($maxt, $t) == null ? TE::RED.self::SLOT_TOP : array_search($maxt, $t);



$maxc = $c == null ? 0 : max($c);



$topc = array_search($maxc, $c) == null ? TE::RED.self::SLOT_TOP : array_search($maxc, $c);



$maxci = $ci == null ? 0 : max($ci);



$topci = array_search($maxci, $ci) == null ? TE::RED.self::SLOT_TOP : array_search($maxci, $ci);



 return self::TITLE_TOPS."\n".



 TE::WHITE."[".TE::GRAY."#1".TE::WHITE."] ".TE::AQUA.$topp.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxp."\n".



 TE::WHITE."[".TE::GRAY."#2".TE::WHITE."] ".TE::AQUA.$tops.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxs."\n".



 TE::WHITE."[".TE::GRAY."#3".TE::WHITE."] ".TE::AQUA.$topt.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxt."\n".



 TE::WHITE."[".TE::GRAY."#4".TE::WHITE."] ".TE::AQUA.$topc.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxc."\n".



 TE::WHITE."[".TE::GRAY."#5".TE::WHITE."] ".TE::AQUA.$topci.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxci."\n";       



	} else {



		return self::WIN_NOT;



		}



}









public function getTopsWins(bool $nice = true) {



	   if(true==$nice) {



		    self::$TITLE_TOPS = "§f§l|§7|§f|§7|§7|§2S§ak§2y§aW§2a§ar§as§f|§7|§f|§7|§f|\n§l§3Leaderboard Game §aWins";



            $tops = new Config($this->plugin->getDataFolder().'/Wins.yml', Config::YAML);



            } else if(false==$nice) {



            self::$TITLE_TOPS = "§f§l|§7|§f|§7|§7|§bS§3k§by§bW§3a§br§3s§f|§7|§f|§7|§f|\n§l§3Leaderboard Game §bkills";



            $tops = new Config($this->plugin->getDataFolder().'/Kills.yml', Config::YAML);



            }



            if($tops->getAll()!=null){



          	$all = $tops->getAll();



              $tt = 1;



              arsort($all);



              $p = []; $s = []; $t = []; $c = []; $ci = []; $ce = []; $cie = []; $och = [];



foreach($all as $users => $tops){



if($tt==1) { $p[$users] = $tops; }  



if($tt==2) { $s[$users] = $tops; }



if($tt==3) { $t[$users] = $tops; }



if($tt==4) { $c[$users] = $tops; }



if($tt==5) { $ci[$users] = $tops; }



if($tt==6) { $ce[$users] = $tops; }



if($tt==7) { $cie[$users] = $tops; }



if($tt==8) { $och[$users] = $tops; } $tt++; if($tt==9) break; }



$maxp = $p == null ? 0 : max($p);



$topp = array_search($maxp, $p) == null ? TE::RED.self::SLOT_TOP : array_search($maxp, $p);



$maxs = $s == null ? 0 : max($s);



$tops = array_search($maxs, $s) == null ? TE::RED.self::SLOT_TOP : array_search($maxs, $s);



$maxt = $t == null ? 0 : max($t);



$topt= array_search($maxt, $t) == null ? TE::RED.self::SLOT_TOP : array_search($maxt, $t);



$maxc = $c == null ? 0 : max($c);



$topc = array_search($maxc, $c) == null ? TE::RED.self::SLOT_TOP : array_search($maxc, $c);



$maxci = $ci == null ? 0 : max($ci);



$topci = array_search($maxci, $ci) == null ? TE::RED.self::SLOT_TOP : array_search($maxci, $ci);



$maxce = $ce == null ? 0 : max($ce); //new



$topce = array_search($maxce, $ce) == null ? TE::RED.self::SLOT_TOP : array_search($maxce, $ce);



$maxcie = $cie == null ? 0 : max($cie);



$topcie = array_search($maxcie, $cie) == null ? TE::RED.self::SLOT_TOP : array_search($maxcie, $cie);



$maxoch = $och == null ? 0 : max($och);



$topoch = array_search($maxoch, $och) == null ? TE::RED.self::SLOT_TOP : array_search($maxoch, $och);



 return self::$TITLE_TOPS."\n".



 TE::WHITE."[".TE::AQUA."#1".TE::WHITE."] ".TE::AQUA.$topp.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxp."\n".



 TE::WHITE."[".TE::GOLD."#2".TE::WHITE."] ".TE::AQUA.$tops.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxs."\n".



 TE::WHITE."[".TE::GOLD."#3".TE::WHITE."] ".TE::AQUA.$topt.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxt."\n".



 TE::WHITE."[".TE::GOLD."#4".TE::WHITE."] ".TE::AQUA.$topc.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxc."\n".



 TE::WHITE."[".TE::RED."#5".TE::WHITE."] ".TE::AQUA.$topci.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxci."\n".



 TE::WHITE."[".TE::RED."#6".TE::WHITE."] ".TE::AQUA.$topce.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxce."\n".



 TE::WHITE."[".TE::RED."#7".TE::WHITE."] ".TE::AQUA.$topoch.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxcie."\n".



 TE::WHITE."[".TE::RED."#8".TE::WHITE."] ".TE::AQUA.$topoch.self::FIGURE_COLOR[mt_rand(0,2)]." .----- ".self::FIGURE.TE::GREEN.$maxoch."\n";       



	} else {



		return self::WIN_NOT;



		}



}



	



      



	}

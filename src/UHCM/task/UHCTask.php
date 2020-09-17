<?php





namespace UHCM\task;


use pocketmine\scheduler\Task;


use pocketmine\level\sound\{PopSound,GenericSound};


use pocketmine\level\sound\EndermanTeleportSound;


use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;


use pocketmine\network\mcpe\protocol\PlayStatusPacket;


use pocketmine\item\enchantment\Enchantment;


use pocketmine\level\sound\AnvilUseSound;


use pocketmine\utils\TextFormat as TE;


use pocketmine\utils\{Config,Color};


use pocketmine\level\Position;


use pocketmine\Player;


use pocketmine\level\Level;


use pocketmine\item\Item;


use pocketmine\level\particle\DustParticle;


use pocketmine\entity\Effect;


use pocketmine\entity\EffectInstance;


use pocketmine\math\Vector3;


use pocketmine\network\mcpe\protocol\RemoveEntityPacket;


use pocketmine\block\Block;


use pocketmine\network\mcpe\protocol\MoveEntityAbsolutePacket;


use pocketmine\network\mcpe\protocol\LevelEventPacket;


use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;


use pocketmine\network\mcpe\protocol\{AddEntityPacket,EntityEventPacket,PlaySoundPacket};


use UHCM\manager\Settings;


use UHCM\UHCM;
use Core\Core;

use UHCM\traits\ScoreTrait;


class UHCTask extends Task {
     use ScoreTrait;
	public $prefix = Settings::GAME_PREFIX;


    public $plugin;


	


	public function __construct(UHCM $plugin){


		$this->plugin = $plugin; }


		


		public function onRun(int $currentTick) : void {


		$config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);


		$arenas = $config->get("arenas");                


		if(!empty($arenas)) {


			foreach($arenas as $arena) {


				$level = $this->plugin->getServer()->getLevelByName($arena);


				if($level instanceof Level) {


					  $players = $level->getPlayers();


					  $counter = $this->plugin->manager->hasArenaCount($arena) ?? 0; 


					  $onlines = $this->plugin->manager->hasPlayers($arena)== is_null(null) ? $this->plugin->manager->hasPlayers($arena) : null;  


					  $resetM = $this->plugin->manager->hasArenaCount($arena) == null ? 0 : $this->plugin->manager->hasArenaCount($arena);


					  $the = new Config($this->plugin->getDataFolder() . "TG/".$arena.".yml",Config::YAML);


				      $slots = $the->get("slots"); 


					/*start xounter*/

                 if($this->plugin->isArenaUse($arena)==true) {
                     $slote = $slots+40; } else {
                         $slote = $this->plugin->startslot;
                         }
				if($counter>=$slote) { 





                      if(Settings::GAME_STATUS == $config->get($arena."Game")) {


						$start = $config->get($arena."ToStartime");


						$start--;


						$config->set($arena."ToStartime", $start);


                        $config->save();


                       


                        foreach($players as $pl) {

                        if($start>=11 && $start <= 40) {
                            $this->runningTo($pl,$start,$arena);
                        }
                     
                            if($start>=1 && $start <= 10) {


                            $m = $start>5 ? "§6".$start : "§c".$start;


                            $this->plugin->addSounds($pl,"note.chime",$start);


                         $pl->addTitle(" ","§l§aStarting in: ".$m,20,40,20);


                            } if($start==0) {
                           $this->plugin->score->remove($pl);
                            $this->plugin->addSounds($pl,"random.levelup");


                            }


                        } 


                        


                        if($resetM==0 || $resetM==null || count($players)==0 || (bool)$players == false) {


                         	foreach($players as $pl) { 

if($this->plugin->isArenaUse($arena)==true) {
    $ares = new Config($this->plugin->getDataFolder()."DATA/MM".$arena.".yml", Config::YAML);
	$author = $ares->get("AUTHOR");
	$this->plugin->deleteCrasts($author);
	}
                        $pl->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);


                        $pl->setGameMode(2); }


                        if($arena=="world") continue;


					    $this->plugin->manager->reloadMap($arena);


					   // $this->plugin->manager->setBlockSign(5,$arena);


						$config->set($arena."Game",Settings::GAME_STATUS_DEFAULT);


                        $config->set($arena."ToStartime", Settings::TIME_TO_START_1);


                        $config->set($arena."TeleportTime", Settings::TIME_TELEPORT_2);


                        $config->set($arena."PlayTime", Settings::TIME_START_3);
$config->set($arena."NEXT",300);
$config->set($arena."BORDE",145);
                        $config->set($arena."EndTime", Settings::TIME_END_4);


						$config->save(); } 


                        


                        if($start<=0) {


	                    $level->setTime(Level::TIME_DAY);


	                    $level->stopTime();


                        $spawns = 0;


                        foreach($players as $pl) {


                        $spawns++;


                      //  $this->plugin->manager->myCages($pl);


                        $this->plugin->manager->setKiller($pl->getName());


                        }


                     //   $this->plugin->manager->setBlockSign(4,$arena);


                        $config->set($arena."Game",Settings::PRE_TELEPORT_2);


                        $config->set($arena."ToStartime", Settings::TIME_TO_START_1);


                        $config->save();


                                } } } else {


                  	if(Settings::GAME_STATUS == $config->get($arena."Game")) {


                  	foreach($players as $pl) {


                  	//new score tap
                          $title = $this->plugin->manager->setColorBoss($config->get($arena."Game"));
                          $pl->sendTip($title." §rWaiting for more players to start:§a ".$counter."§f /§2 ".$slots);
                        
                    //end score tap


                  	  }


                  	}


  }  /*end counter*/


                        


                        /*start telwport*/


                      if(Settings::PRE_TELEPORT_2 == $config->get($arena."Game")) {


						$start = $config->get($arena."TeleportTime");


						$start--;


						$config->set($arena."TeleportTime", $start);


                        $config->save();


                       


                       if($resetM==0 || $resetM==null || count($players)==0 || (bool)$players == false) {


                         	foreach($players as $pl) { 

if($this->plugin->isArenaUse($arena)==true) {
    $ares = new Config($this->plugin->getDataFolder()."DATA/MM".$arena.".yml", Config::YAML);
	$author = $ares->get("AUTHOR");
	$this->plugin->deleteCrasts($author);
	}
                        $pl->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);


                        $pl->setGameMode(2); }


                        if($arena=="world") continue;


					    $this->plugin->manager->reloadMap($arena);


					  //  $this->plugin->manager->setBlockSign(5,$arena);


						$config->set($arena."Game",Settings::GAME_STATUS_DEFAULT);


                        $config->set($arena."ToStartime", Settings::TIME_TO_START_1);


                        $config->set($arena."TeleportTime", Settings::TIME_TELEPORT_2);


                        $config->set($arena."PlayTime", Settings::TIME_START_3);

$config->set($arena."BORDE",145);
$config->set($arena."NEXT",300);
                        $config->set($arena."EndTime", Settings::TIME_END_4);


						$config->save(); } 


       


                        foreach($players as $pl) {
                    if($start==1) {
                        $this->plugin->score->remove($pl);
                        $players2 = $this->plugin->manager->hasPlayers($arena)== is_null(null) ? $this->plugin->manager->hasPlayers($arena) : null;
                       if(isset($players2[$pl->getName()])) {
                        $pl->setGameMode(0);
                        $this->plugin->manager->getTopSWs($pl);
                        $this->plugin->removeItems($pl);
                             }
                        }
                            if($start>=1 && $start <= 5) {
                            $pl->getLevel()->addSound(new PopSound($pl));


                            $m = $start<=7 ? "§c".$start : "§6".$start;


                            $pl->sendTip(" §aSetting Up Match: ".$m);


                            }


                        } 


                        

                            if($start<=0) { 
                                $this->plugin->manager->setBorde(99,$arena,60,0,-1,0,105,"BLUE");
                            foreach($players as $pl) {
                                $pl->setImmobile(false);
                                $pl->addTitle(" §l§aFIGHT","§l§dBe the first to win!!",20,40,20);
                                $this->plugin->addSounds($pl,"mob.pillager.celebrate");
                                $pl->sendMessage("§l§c[Speed UHC]§r §6Every minute changes edge attentively!");
                             }
                        } 


                        


                        if($start<=0) {


                     //   $this->plugin->manager->setBlockSign(14,$arena);


                        $config->set($arena."Game",Settings::PRE_START_3);


                        $config->set($arena."TeleportTime", Settings::TIME_TELEPORT_2);


                        $config->save();


                                } }


                        /*end teleport*/


                        


                        /*start startgame*/


                      if(Settings::PRE_START_3 == $config->get($arena."Game")) {


						$start = $config->get($arena."PlayTime");


						$start--;


						$config->set($arena."PlayTime", $start);


                        $config->save();


                       


                       


                       


                       if($counter<=1) { 


                             foreach($players as $player) { 
          

                            if(isset($onlines[$player->getName()])) {
       
                            $player->addTitle("§l§6VICTORY","§r§7You were the last man standing",20,40,20);
                            //$this->plugin->addSounds($player,"mob.cat.beg");
                            
                            $core = $this->plugin->getServer()->getPluginManager()->getPlugin("Core");
                if($core instanceof Core){
                     	for($win=0; $win<=5; $win++) {
                    	$core->addFireworks($player,rand(1,3),rand(1,2));
                     	}
                     }
		                    $data = $this->plugin->manager;


		                    $name = $player->getName();


		                    $data->setWins($name);


		                    $data->setCoins($name);
               $this->plugin->addSounds($player,"random.levelup");

		                    $this->plugin->getServer()->broadcastMessage("§8» §f===§2§9SpeedUHC§r==="); 


                            $this->plugin->getServer()->broadcastMessage("§6Winner §8#1 §f".$player->getName()); 


                            $this->plugin->getServer()->broadcastMessage("§fWon the game in:§a ".$arena); 


                            $this->plugin->getServer()->broadcastMessage("§8» §f==============="); }


                            // $player->setGamemode(Player::ADVENTURE);


                             $player->setHealth(20);


                             $player->setFood(20);


                            // $player->teleport($this->plugin->getServer()->getLevelByName($arena)->getSafeSpawn(),0,0); 


                             $config->set($arena."Game",Settings::PRE_END_4);


						     $config->set($arena."PlayTime", Settings::TIME_START_3);
$config->set($arena."BORDER",145);
$config->set($arena."NEXT",300);

						     $config->save();


						   //  $this->plugin->manager->setBlockSign(10,$arena);


						     


                                }


                             }


                         


                         if($resetM==0 || $resetM==null || count($players)==0 || (bool)$players == false) {
if($this->plugin->isArenaUse($arena)==true) {
    $ares = new Config($this->plugin->getDataFolder()."DATA/MM".$arena.".yml", Config::YAML);
	$author = $ares->get("AUTHOR");
	$this->plugin->deleteCrasts($author);
	}

                         	foreach($players as $pl) { 


                        $pl->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);


                        $pl->setGameMode(2); }


                        if($arena=="world") continue;


					    $this->plugin->manager->reloadMap($arena);


					    //$this->plugin->manager->setBlockSign(5,$arena);


						$config->set($arena."Game",Settings::GAME_STATUS_DEFAULT);


                        $config->set($arena."ToStartime", Settings::TIME_TO_START_1);


                        $config->set($arena."TeleportTime", Settings::TIME_TELEPORT_2);


                        $config->set($arena."PlayTime", Settings::TIME_START_3);

$config->set($arena."BORDE",145);
$config->set($arena."NEXT",300);
                        $config->set($arena."EndTime", Settings::TIME_END_4);


						$config->save(); } 

if($start==60*10+5 || $start==60*10+4 || $start==60*10+3 || $start==60*10+2 || $start==60*10+1 ) { 
    foreach($players as $pl) {
        $tt = $this->plugin->manager->setSegs($start);
                      $pl->addTitle(" ","§l§cNEW EDGE IN: §a".$tt,20,40,20);
                      $this->plugin->addSounds($pl,"random.pop");
                      }
    }
    
    if($start==60*8+5 || $start==60*8+4 || $start==60*8+3 || $start==60*8+2 || $start==60*8+1 ) { 
    foreach($players as $pl) {
        $tt = $this->plugin->manager->setSegs($start);
                      $pl->addTitle(" ","§l§cNEW EDGE IN: §a".$tt,20,40,20);
                      $this->plugin->addSounds($pl,"random.pop");
                      }
    }
    
    if($start==60*7+5 || $start==60*7+4 || $start==60*7+3 || $start==60*7+2 || $start==60*7+1 ) { 
    foreach($players as $pl) {
        $tt = $this->plugin->manager->setSegs($start);
                      $pl->addTitle(" ","§l§cNEW EDGE IN: §a".$tt,20,40,20);
                      $this->plugin->addSounds($pl,"random.pop");
                      }
    }
    
    if($start==60*6+5 || $start==60*6+4 || $start==60*6+3 || $start==60*6+2 || $start==60*6+1 ) { 
    foreach($players as $pl) {
        $tt = $this->plugin->manager->setSegs($start);
                      $pl->addTitle(" ","§l§cNEW EDGE IN: §a".$tt,20,40,20);
                      $this->plugin->addSounds($pl,"random.pop");
                      }
    }
    
                        if($start==60*10) { 
                        $this->plugin->manager->setBorde(80,$arena,60,0,-1,0,60,"GREEN");
                            foreach($players as $pl) {
                                $pl->addTitle(" ","§l§cCHANGED EDGE: §680",20,40,20);
                                $this->plugin->addSounds($pl,"mob.witch.celebrate");
                                }
                            }
                            
                            if($start==60*8) { 
                        $this->plugin->manager->setBorde(60,$arena,60,0,-1,0,40,"YELLOW");
                            foreach($players as $pl) {
                                $pl->addTitle(" ","§l§cCHANGED EDGE: §660",20,40,20);
                                $this->plugin->addSounds($pl,"mob.witch.celebrate");
                                }
                            }
                            
                       if($start==60*7) { 
                        $this->plugin->manager->setBorde(40,$arena,60,0,-1,0,20,"RED");
                            foreach($players as $pl) {
                                $pl->addTitle(" ","§l§cCHANGED EDGE: §640",20,40,20);
                                $this->plugin->addSounds($pl,"mob.witch.celebrate");
                                }
                            }
                        
                        if($start==60*6) { 
                        $this->plugin->manager->setBorde(20,$arena,60,0,-1,0,10,"BLACK");
                            foreach($players as $pl) {
                                $pl->addTitle(" ","§l§cCHANGED EDGE: §620",20,40,20);
                                $this->plugin->addSounds($pl,"mob.witch.celebrate");
                                }
                            }
                            
                            if($start==60*6-2) { 
                            foreach($players as $pl) {
                                $pl->sendTip("§l§cNO MORE BORDER");
                                $this->plugin->addSounds($pl,"mob.villager.no");
                                }
                            }

                        foreach($players as $pl) {
                            
                        $this->runningStart($pl,$arena,$counter,$start);
                            if($start <= 10) {
                            $m = $start<=7 ? "§c".$start : "§6".$start;
                            $pl->sendTip(" §fEnd of the game in: ".$m);

                            }


                        } 


                        


                        if($start<=0) {


                  //      $this->plugin->manager->setBlockSign(10,$arena);


                        $config->set($arena."Game",Settings::PRE_END_4);


                        $config->set($arena."PlayTime", Settings::TIME_START_3);


                        $config->save();


                                } }


                        /*end startgame*/


                        


                        /*start endGame*/


                      if(Settings::PRE_END_4 == $config->get($arena."Game")) {


						$start = $config->get($arena."EndTime");


						$start--;


						$config->set($arena."EndTime", $start);


                        $config->save();


                       


                       

                        foreach($players as $pl) {
if(isset($onlines[$pl->getName()])) {
    if($start>=2 && $start<=8) {
                            $core = $this->plugin->getServer()->getPluginManager()->getPlugin("Core");
                if($core instanceof Core){
                    	$core->addFireworks($pl,rand(2,3),7);
                     	}
                    }
              }
                        	$pl->sendTip("§bComing back in:§f ".$start);


                        } 


                        


                        if($start<=0) {


                        	foreach($players as $pl) {                      	


                        $this->plugin->manager->delPlayer($pl->getName(),$pl->getLevel()->getFolderName());

$pl->setGameMode(2); 
                        $pl->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);


                            }


                  //      $this->plugin->manager->setBlockSign(5,$arena);


                        $config->set($arena."Game",Settings::GAME_STATUS_DEFAULT);


                        $config->set($arena."EndTime", Settings::TIME_END_4);

$config->set($arena."BORDER",145);
$config->set($arena."NEXT",300);
                        $config->save();


                        if($arena=="world") continue;

if($this->plugin->isArenaUse($arena)==true) {
    $ares = new Config($this->plugin->getDataFolder()."DATA/MM".$arena.".yml", Config::YAML);
	$author = $ares->get("AUTHOR");
	$this->plugin->deleteCrasts($author);
	}
					    $this->plugin->manager->reloadMap($arena);


                                } }


                        /*end endGame*/


                        


					} } } }


					


				}


						


						

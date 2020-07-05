<?php


namespace UHCM\events;


use pocketmine\event\entity\EntityDamageEvent;


use pocketmine\event\entity\EntityDamageByEntityEvent;


use pocketmine\utils\TextFormat as TE;


use pocketmine\item\Item;


use pocketmine\Player;


use pocketmine\entity\EntityFactory;


use pocketmine\level\Level;


use pocketmine\event\Listener;


use pocketmine\event\player\{PlayerDropItemEvent};


use pocketmine\tile\Sign;


use pocketmine\event\player\PlayerExhaustEvent;


use pocketmine\level\particle\DestroyBlockParticle;


use pocketmine\block\Block;


use pocketmine\utils\Config;


use pocketmine\math\{Vector3,Vector2};


use UHCM\entities\{FireworksRocket,NPCUHCm};


use pocketmine\network\protocol\LevelSoundEventPacket;
use pocketmine\network\multiversion\Entity as MultiversionEntity;

use UHCM\manager\Settings;


use UHCM\UHCM;


class DamageTouch implements Listener {





    public $plugin;
	public $author = "[TG]";
	public $version = 1.14;
	public $main = "events";


	


	public function __construct(UHCM $plugin){


		    $this->plugin = $plugin;


			$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);


	}



 public function onEventFall(EntityDamageEvent $event) {


  	$player = $event->getEntity();


      $level = $player->getLevel()->getFolderName();


  	$cause = $event->getCause();


      $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


      $rule = $config->get($level."Game");


 	if($cause===EntityDamageEvent::CAUSE_FALL) {


     	$players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


         if(isset($players2[$player->getName()])) {


         	if($rule==Settings::PRE_TO_START_1) {


 		$event->setCancelled(true);


           } else if($rule==Settings::PRE_TELEPORT_2) {


 		$event->setCancelled(true);


           } else if($rule==Settings::PRE_START_3) {


           	if($config->get($level."PlayTime")>=Settings::TIME_START_3-10 && $config->get($level."PlayTime")<= Settings::TIME_START_3) {


 		$event->setCancelled(true);


            } else {


            	if($player instanceof Player) {

$fire = $this->plugin->manager->loadKit($player->getName(),"NOFALL");
             	     $event->setCancelled($fire);
      if($fire==false) {
            if (($player->getHealth() - $event->getFinalDamage()) <= 0) {
              	    $this->plugin->manager->kickGame($player->getName(),$player->getLevel()->getFolderName());
             	     $event->setCancelled();
             foreach ($player->getLevel()->getPlayers() as $pl) {
                      $pl->sendMessage(TE::RED.$player->getName().TE::GOLD." Ha besado el suelo muy duro"); }
				      $player->setMaxHealth(20);
                      $player->setHealth(20);
                      $player->setFood(20);
                      $this->plugin->manager->setPlayerSpec($player->getName(),$player->getLevel()->getFolderName());
                      $this->plugin->manager->setLost($player->getName());
				      $player->sendTip(TE::RED."Has perdido");
				      
                       }
             }
             
   }


            


            	}


           } else if($rule==Settings::PRE_END_4) {


 		$event->setCancelled(true);


           } 


           


       }


     }


   }


 


 


 public function onEventDamage(EntityDamageEvent $event) {


  	$player = $event->getEntity();


      $level = $player->getLevel()->getFolderName();


  	$cause = $event->getCause();


      $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


      $rule = $config->get($level."Game");


 	if($cause===EntityDamageEvent::CAUSE_ENTITY_ATTACK) {


 	   if($event instanceof EntityDamageByEntityEvent) {


         	$damager = $event->getDamager();


             $entity = $event->getEntity();


             if($damager instanceof Player and $entity instanceof NPCUHCm) {
               $this->plugin->openSWTouch($damager);
             	$event->setCancelled(true);

             	}


             $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


         if(isset($players2[$player->getName()])) {


         	if($rule==Settings::PRE_TO_START_1) {


 		$event->setCancelled(true);


               } else if($rule==Settings::PRE_TELEPORT_2) {


 		$event->setCancelled(true);


               } else if($rule==Settings::PRE_END_4) {


 		$event->setCancelled(true);


               } 


            if($damager instanceof Player and $entity instanceof Player) {


            	if($rule==Settings::PRE_START_3) {


            	   if (($entity->getHealth() - $event->getFinalDamage()) <= 0) {


             	    


             	     $event->setCancelled();

                      foreach ($entity->getLevel()->getPlayers() as $pl) {
$this->plugin->addSounds($pl,"mob.fox.death");
                      $pl->sendMessage(TE::RED.$damager->getName().TE::WHITE." Ha asesinado ".TE::GOLD.$player->getName()); }


				      $entity->setMaxHealth(20);


                      $entity->setHealth(20);


                      $entity->setFood(20);


                      $this->plugin->manager->kickChest($entity);


                      $this->plugin->manager->kickGame($entity->getName(),$entity->getLevel()->getFolderName());


                      $entity->getLevel()->addParticle(new DestroyBlockParticle($entity->add(0,0,0), Block::get(152)));


                      $this->plugin->manager->setLost($entity->getName());


                      $this->plugin->manager->addKill($damager->getName());


                      $this->plugin->manager->setCoins($damager->getName());


                      $this->plugin->manager->setPlayerSpec($entity->getName(),$entity->getLevel()->getFolderName());

				      $damager->sendTip(TE::BOLD.TE::GREEN."+5 coins");


				      $damager->addTitle("  ",TE::BOLD.TE::GOLD."+1 Kill",20,40,20);


				      $entity->sendTip(TE::BOLD.TE::GOLD." Suerte para la proxima");


                     } 


             	}


           }


      }


   }


 }


}


 


 public function onEventVoid(EntityDamageEvent $event) {


  	$player = $event->getEntity();


      $level = $player->getLevel()->getFolderName();


  	$cause = $event->getCause();


      $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


      $rule = $config->get($level."Game");


 	if($cause===EntityDamageEvent::CAUSE_VOID) {


 	if($player instanceof Player) {


		$players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


            if($rule==Settings::PRE_TO_START_1) {


            	if(isset($players2[$player->getName()])) {


            	$player->sendTip(TE::RED."Has caido al vacio");


                $spawn = $config->get($level."Lobby");


                if($spawn!=null) {


                $player->teleport(new Vector3($spawn[0], $spawn[1], $spawn[2])); 


                      } else {



                      }


                   }


            	}


            


            if($rule==Settings::PRE_END_4) {


            	if(isset($players2[$player->getName()])) {


            	$player->sendTip(TE::RED."Has vaido al vicio xd");


                $spawn = $config->get($level."Lobby");


                if($spawn!=null) {


                $player->teleport(new Vector3($spawn[0], $spawn[1], $spawn[2])); 


                      } else {

                      }


                   }


            	}


            if($rule==Settings::PRE_START_3) {


            	   if(isset($players2[$player->getName()])) {


             	     $event->setCancelled();


             foreach ($player->getLevel()->getPlayers() as $pl) {


                      $pl->sendMessage(TE::RED.$player->getName().TE::GOLD." Ha caido vacio"); }


				      $player->setMaxHealth(20);


                      $player->setHealth(20);


                      $player->setFood(20);


                      $this->plugin->manager->kickGame($player->getName(),$player->getLevel()->getFolderName());


                      $player->setFood(20);


                      $this->plugin->manager->setLost($player->getName());

                      $this->plugin->manager->setPlayerSpec($player->getName(),$player->getLevel()->getFolderName());


				


                    }


            	}


            }


        }


   }


 


 


 public function noEventExhaus(PlayerExhaustEvent $exhaustEvent) {


        	$player = $exhaustEvent->getPlayer();


            $level = $player->getLevel()->getFolderName();


            $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


            if(isset($players2[$player->getName()])) {


            $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


            $rule = $config->get($level."Game");


            if($rule==Settings::PRE_TO_START_1) {


            $exhaustEvent->setCancelled(true);


            } else if($rule==Settings::PRE_END_4) {


            $exhaustEvent->setCancelled(true);


            }


       } 


  }


	


public function noDrops(PlayerDropItemEvent $ev) { 


    $player = $ev->getPlayer();


            $level = $player->getLevel()->getFolderName();


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


 public function onDamageSuffo(EntityDamageEvent $ev) { 


     $cause = $ev->getCause();


     $enty = $ev->getEntity();


     if($cause===EntityDamageEvent::CAUSE_SUFFOCATION) {


     $player = $ev->getEntity();


     if ($player instanceof Player) {


    	$level = $player->getLevel()->getFolderName();


        $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


        if(isset($players2[$player->getName()])) {


      $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


      $rule = $config->get($level."Game");


      if($rule==Settings::PRE_TELEPORT_2) {


     $player->teleport(new Vector3(intval($player->getX()),intval($player->getY()+1),intval($player->getZ())));


     $player->setMotion(new Vector3(0,1,0));


     $ev->setCancelled();


         } else if($rule==Settings::PRE_TO_START_1) {


     $player->teleport(new Vector3(intval($player->getX()),intval($player->getY()+1),intval($player->getZ())));


     $player->setMotion(new Vector3(0,1,0));


     $ev->setCancelled();


         } else if($rule==Settings::PRE_START_3) {


     $player->teleport(new Vector3(intval($player->getX()),intval($player->getY()+1),intval($player->getZ())));


     $player->setMotion(new Vector3(0,1,0));


     $ev->setCancelled();


         }


       }


     }


   }


 }


 


 public function onDamageArrow(EntityDamageEvent $ev) { 


     $cause = $ev->getCause();


     if($cause===EntityDamageEvent::CAUSE_PROJECTILE) {


     	if($ev instanceof EntityDamageByEntityEvent) {


           $damager = $ev->getDamager();


           $entity = $ev->getEntity();


        if($damager instanceof Player and $entity instanceof Player) {


    	$level = $entity->getLevel()->getFolderName();


       $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);


      $rule = $config->get($level."Game");


        $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;


        if(isset($players2[$entity->getName()])) {


        	


            	   if (($entity->getHealth() - $ev->getFinalDamage()) <= 0) {


             	    


             	     $ev->setCancelled();

                      foreach ($entity->getLevel()->getPlayers() as $pl) {
                     $this->plugin->addSounds($pl,"mob.fox.aggro");
                      $pl->sendMessage(TE::GOLD.$damager->getName().TE::WHITE." Ha asesinado con flechas ah ".TE::RED.$entity->getName()); }


				      $entity->setMaxHealth(20);


                      $entity->setHealth(20);


                      $entity->setFood(20);


                      $this->plugin->manager->kickGame($entity->getName(),$entity->getLevel()->getFolderName());


                      $this->plugin->manager->kickChest($entity);


                      $entity->getLevel()->addParticle(new DestroyBlockParticle($entity->add(0,0,0), Block::get(152)));


                      $this->plugin->manager->setLost($entity->getName());


                      $this->plugin->manager->addKill($damager->getName());


                      $this->plugin->manager->setCoins($damager->getName());


                      $this->plugin->manager->setPlayerSpec($entity->getName(),$entity->getLevel()->getFolderName());

				      $damager->sendTip(TE::BOLD.TE::GREEN."+5 coins");


				      $damager->addTitle("  ",TE::BOLD.TE::GOLD."+1 Kill",20,40,20);


				      $entity->sendTip(TE::BOLD.TE::GOLD." Suerte para la proxima");


				


                     } else {


                     	if($rule==Settings::PRE_TO_START_1) {


 		$ev->setCancelled(true);


               } else if($rule==Settings::PRE_TELEPORT_2) {


 		$ev->setCancelled(true);


               } else {


                     	$this->plugin->addSounds($damager,"random.orb");

                             }


                     	}


             	}


           }


         }


       }


     


   }


 


 public function noLave(EntityDamageEvent $ev) { 
     $cause = $ev->getCause();
     if($cause===EntityDamageEvent::CAUSE_FIRE || $cause===EntityDamageEvent::CAUSE_FIRE_TICK || $cause===EntityDamageEvent::CAUSE_LAVA) {
           $entity = $ev->getEntity();
        if($entity instanceof Player) {
    	$level = $entity->getLevel()->getFolderName();
       $config = new Config($this->plugin->getDataFolder()."/config.yml", Config::YAML);
      $rule = $config->get($level."Game");
        $players2 = $this->plugin->manager->hasPlayers($level)== is_null(null) ? $this->plugin->manager->hasPlayers($level) : null;
        if(isset($players2[$entity->getName()])) {
            $fire = $this->plugin->manager->loadKit($entity->getName(),"FIRELESS");
             $ev->setCancelled($fire);
            	   if (($entity->getHealth() - $ev->getFinalDamage()) <= 0) {
             	     $ev->setCancelled();
                      foreach ($entity->getLevel()->getPlayers() as $pl) {
                     $this->plugin->addSounds($pl,"mob.fox.aggro");
                      $pl->sendMessage(TE::GOLD.$entity->getName().TE::RED." Murio por quemaduras"); }
				      $entity->setMaxHealth(20);
                      $entity->setHealth(20);
                      $entity->setFood(20);
                      $this->plugin->manager->kickGame($entity->getName(),$entity->getLevel()->getFolderName());
                      $this->plugin->manager->kickChest($entity);
                      $entity->getLevel()->addParticle(new DestroyBlockParticle($entity->add(0,0,0), Block::get(152)));
                      $this->plugin->manager->setLost($entity->getName());
				      $entity->sendTip(TE::BOLD.TE::GOLD." Suerte para la proxima");
                     } 
                     
                     }
                  }
               }
                     
   }


 


 


 


 


 


 


 }
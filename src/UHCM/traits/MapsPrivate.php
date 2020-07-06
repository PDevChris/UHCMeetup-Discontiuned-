<?php
namespace UHCM\traits;

use pocketmine\utils\TextFormat as TE;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

trait MapsPrivate {
    
    public function loadMyCages(Player $pl) {
		$config = new Config($this->getDataFolder()."DATA/".$pl->getName().".yml", Config::YAML);
		$array = $config->get("LIST", []);
		$url = new Config($this->getDataFolder()."CAGE/url.yml", Config::YAML);
		if($array!=null) {
	   $form = $this->createSimpleForm(function(Player $pl, ?int $data){
		if( !is_null($data)) { 
$all = $this->listbox[$pl->getName()] ?? "value";
/*code*/
$vote = $all[$data];
$this->setCageDefault($pl,$vote);
$pl->sendMessage(TE::WHITE."Your default change box has: ".TE::AQUA.$vote);
/*end code*/
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }
}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/tipped_arrow_nightvision";
          $form->addButton(TE::BOLD."§f".strtoupper($name)."\n".TE::BOLD."§e[CAGE]§a•DISPONIBLE•",0,$urll);
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lME CAGES");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."No cages yet!");
		}
	}


    
    public function setCageDefault(Player $pl,string $box) : void {
        $my = new Config($this->getDataFolder()."DATA/".$pl->getName().".yml", Config::YAML);
        $my->set("DEFAULT",ucfirst($box));
        $my->save();
        }
    
    public function addCageNew(Player $pl,string $box,bool $reward = false) : void {
        $my = new Config($this->getDataFolder()."DATA/".$pl->getName().".yml", Config::YAML);
        $list = $my->get("LIST",[]);
        if(!in_array($box,$list)) {
            $cage = $my->get("LIST",[]);
               $cage[] = ucfirst($box);
                  $my->set("LIST",$cage);
                    $my->save();
                    $pl->sendMessage(TE::WHITE."You have unlock a cage ".TE::AQUA.$box.TE::WHITE." Pick it. in your warehousescojela. en tu almacen!");
            } else {
                if($reward==true) {
                $pl->sendMessage(TE::RED."You owned this cage ".TE::GOLD.$box.TE::GREEN.", You were refunded 10 coins!");
                $this->manager->setCoins($pl->getName(),10);
                     } else {
                $pl->sendMessage(TE::RED."You have owned this already ".TE::GOLD.$box);
                         }
                }
        }
    
    
    
    public function loadStored(Player $pl) {
		$config = new Config($this->getDataFolder()."CAGE/store.yml", Config::YAML);
		$me = new Config($this->getDataFolder()."DATA/".$pl->getName().".yml", Config::YAML);
		$load = $me->get("LIST",[]);
		$array = $config->get("CAGES", []);
		$url = new Config($this->getDataFolder()."CAGE/url.yml", Config::YAML);
		if($array!=null) {
	   $form = $this->createSimpleForm(function(Player $pl, ?int $data){
		if( !is_null($data)) { 
$all = $this->listbox[$pl->getName()] ?? "value";
/*code*/
$vote = $all[$data];
if($this->manager->getCoins($pl->getName())>=$this->pricestore) {
$this->addCageNew($pl,$vote);
} else {
    $pl->sendMessage(TE::RED."No money to buy this: ".TE::GOLD.$this->manager->getCoins($pl->getName()));
    }
/*end code*/
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }
}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/map_empty";
		  if(!in_array($name,$load)) {
          $form->addButton(TE::BOLD."§f[NEW] ".strtoupper($name)."\n".TE::BOLD."§a[$".$this->pricestore."]§b•DISPONIBLE•",0,$urll);
          } else {
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[STORE]§6•YOU ALREADY HAVE IT•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lSTORE CAGES");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."You don't have boxes in the store yet!");
		}
	}
	
	public function loadPrivate(Player $pl) {
		$config = new Config($this->getDataFolder()."CAGE/private.yml", Config::YAML);
		$me = new Config($this->getDataFolder()."DATA/".$pl->getName().".yml", Config::YAML);
		$load = $me->get("LIST",[]);
		$array = $config->get("CAGES", []);
		$url = new Config($this->getDataFolder()."CAGE/url.yml", Config::YAML);
		if($array!=null) {
	   $form = $this->createSimpleForm(function(Player $pl, ?int $data){
		if( !is_null($data)) { 
$all = $this->listbox[$pl->getName()] ?? "value";
/*code*/
$vote = $all[$data];
$this->cagee[$pl->getName()] = $vote;
$pl->sendMessage(TE::GREEN."[NEW]".TE::WHITE."Stablisity Box: ".TE::AQUA.$vote);
$this->createMapPrivate($pl);
/*end code*/
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }

}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/map_empty";
		  if(!in_array($name,$load)) {
          $form->addButton(TE::BOLD."§f[PRIVADA] ".strtoupper($name)."\n".TE::BOLD."§a[FREE]§3•DISPONIBLE•",0,$urll);
          } else {
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[BAYA]§6•YOU ALREADY HAVE IT•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lCAJA DEFAULT");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."There is no private cages yet!");
		}
	}
	
	public function loadPrivateVIP(Player $pl) {
		$config = new Config($this->getDataFolder()."CAGE/private.yml", Config::YAML);
		$me = new Config($this->getDataFolder()."DATA/".$pl->getName().".yml", Config::YAML);
		$load = $me->get("LIST",[]);
		$array = $config->get("CAGES", []);
		$url = new Config($this->getDataFolder()."CAGE/url.yml", Config::YAML);
		if($array!=null) {
	   $form = $this->createSimpleForm(function(Player $pl, ?int $data){
		if( !is_null($data)) { 
$all = $this->listbox[$pl->getName()] ?? "value";
/*code*/
$vote = $all[$data];
$this->addCageNew($pl,$vote);
$pl->sendMessage(TE::GREEN."[NEW]".TE::WHITE."You were given a very special box:".TE::AQUA.$vote);
/*end code*/
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }

}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/map_empty";
		  if(!in_array($name,$load)) {
          $form->addButton(TE::BOLD."§f[PRIVADA] ".strtoupper($name)."\n".TE::BOLD."§a[FREE]§3•DISPONIBLE•",0,$urll);
          } else {
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[BAYA]§6•YOU ALREADY OWNED IT•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§l VIPs");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."There is no cage yet owned!");
		}
	}
	
	public function loadPrivateMap(Player $pl) {
		$config = new Config($this->getDataFolder()."/config.yml", Config::YAML);
		$array = $config->get("arenas", []);
		$maps = new Config($this->getDataFolder()."DATA/".strtolower("maps").".yml", Config::YAML);
		$up = $maps->get("LIST",[]);
		$url = new Config($this->getDataFolder()."CAGE/mapsurl.yml", Config::YAML);
		if($array!=null) {
	   $form = $this->createSimpleForm(function(Player $pl, ?int $data){
		if( !is_null($data)) { 
$all = $this->listbox[$pl->getName()] ?? "value";
/*code*/
$vote = $all[$data];
if($this->isMapUse($vote)==true) {
$this->mapp[$pl->getName()] = $vote;
$this->cagee[$pl->getName()] = $vote;
$pl->sendMessage(TE::GREEN."[NEW]".TE::WHITE."Mapa stable: ".TE::AQUA.$vote);
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }
$this->createMapPrivate($pl);
} else {
    if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }
    $pl->sendMessage(TE::GOLD."[ERROR]".TE::WHITE."This map is in game: ".TE::RED.$vote);
    }
/*end code*/

}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/map_empty";
		  if($this->isMapUse($name)==true) {
			$the = new Config($this->getDataFolder() . "TG/".$name.".yml",Config::YAML);
            $slots = $the->get("slots"); 
			$form->addButton(TE::BOLD."§f[MAPA]§a ".strtoupper($name)."\n".TE::BOLD."§b[0/".$slots."]§d•FOUND•",0,$urll);
			} else {
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[OFFINE]§6•IS IN USE•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lMAPA LIST");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."No private maps yet");
		}
	}
	
	
	public function isMapUse(string $arena) : bool {
           $config = new Config($this->getDataFolder()."/config.yml", Config::YAML);
		if($config->get("arenas")!=null) { 
					 $counter = $this->manager->hasArenaCount($arena) ?? 0; 
					  if($counter<=0) {
					     return true;
					} else {
						return false;
						}
					} else {
						return false;
						}
			   }
			
			public function isArenaUse(string $arena) : bool {
           $config = new Config($this->getDataFolder()."/config.yml", Config::YAML);
           $maps = new Config($this->getDataFolder()."DATA/".strtolower("maps").".yml", Config::YAML);
           $list  = $maps->get("LIST",[]);
		        if($config->get("arenas")!=null) { 
						if(in_array($arena,$list)) {
							return true;
				            } else {
					   return false;
					        }
						} else {
							return false;
							}
			   }
			
			public function isCodeValid(string $code) : bool {
           $config = new Config($this->getDataFolder()."DATA/".strtolower("codes").".yml", Config::YAML);
           $list  = $config->get("CODES",[]);
		        if($config->get("CODES")!=null) { 
						if(in_array($code,$list)) {
							return true;
				            } else {
					   return false;
					        }
						} else {
							return false;
							}
			   }
           
         
	
	
	
    
    
    
    }

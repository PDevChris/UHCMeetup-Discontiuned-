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
$pl->sendMessage(TE::WHITE."Tu caja por default cambio ha: ".TE::AQUA.$vote);
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
		$pl->sendMessage(TE::RED."No dispones de cajas aun!");
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
                    $pl->sendMessage(TE::WHITE."Felicidades tienes una nueva caga ".TE::AQUA.$box.TE::WHITE." escojela. en tu almacen!");
            } else {
                if($reward==true) {
                $pl->sendMessage(TE::RED."Que mal ya tienes esta caja ".TE::GOLD.$box.TE::GREEN.", Toma estos 10 coins!");
                $this->manager->setCoins($pl->getName(),10);
                     } else {
                $pl->sendMessage(TE::RED."Que mal ya tienes esta caja ".TE::GOLD.$box);
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
    $pl->sendMessage(TE::RED."Que mal insuficiente dinero: ".TE::GOLD.$this->manager->getCoins($pl->getName()));
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
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[STORE]§6•YA LA DISPONES•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lSTORE CAGES");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."No dispones cajas en la tienda aun!");
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
$pl->sendMessage(TE::GREEN."[NEW]".TE::WHITE."Caja establisidad: ".TE::AQUA.$vote);
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
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[BAYA]§6•YA LA DISPONES•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lCAJA DEFAULT");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."No hay cajas privadas aun!");
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
$pl->sendMessage(TE::GREEN."[NEW]".TE::WHITE."Se te dio una caja muy especial:".TE::AQUA.$vote);
/*end code*/
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }

}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/map_empty";
		  if(!in_array($name,$load)) {
          $form->addButton(TE::BOLD."§f[PRIVADA] ".strtoupper($name)."\n".TE::BOLD."§a[FREE]§3•DISPONIBLE•",0,$urll);
          } else {
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[BAYA]§6•YA LA DISPONES•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lCAJAS VIPs");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."No hay cajas privadas aun!");
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
$pl->sendMessage(TE::GREEN."[NEW]".TE::WHITE."Mapa establisido: ".TE::AQUA.$vote);
if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }
$this->createMapPrivate($pl);
} else {
    if(isset($this->listbox[$pl->getName()])){ unset($this->listbox[$pl->getName()]); }
    $pl->sendMessage(TE::GOLD."[ERROR]".TE::WHITE."Este mapa ya esta en juego: ".TE::RED.$vote);
    }
/*end code*/

}});
		$list = [];
		foreach($array as $name) {
		  $urll = $url->get($name) != null ? $url->get($name) : "textures/items/map_empty";
		  if($this->isMapUse($name)==true) {
			$the = new Config($this->getDataFolder() . "TG/".$name.".yml",Config::YAML);
            $slots = $the->get("slots"); 
			$form->addButton(TE::BOLD."§f[MAPA]§a ".strtoupper($name)."\n".TE::BOLD."§b[0/".$slots."]§d•DISPONIBLE•",0,$urll);
			} else {
              $form->addButton(TE::BOLD."§4".strtoupper($name)."\n".TE::BOLD."§c[OFFINE]§6•ESTA EN USO•",0,$urll);
              }
          $list[] =  $name;
		 }
	     $form->setTitle(TE::GOLD."§lMAPA LISTA");
	     $form->sendToPlayer($pl);
	     $this->listbox[$pl->getName()] = $list;
	    } else {
		$pl->sendMessage(TE::RED."No hay mapas privados aun!");
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
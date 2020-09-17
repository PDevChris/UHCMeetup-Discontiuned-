<?php
namespace UHCM\traits;

use pocketmine\utils\TextFormat as TE;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

trait ScoreTrait {
    
    public function runningTo(Player $pl,int $time,string $arena) {
$api = $this->plugin->score;
$api->new($pl, $pl->getName(), " ".TE::BOLD.TE::DARK_BLUE."".TE::YELLOW."SPE".TE::YELLOW."E".TE::YELLOW."D".TE::YELLOW." U".TE::YELLOW."HC".TE::YELLOW.""." ");
$api->setLine($pl, 0,"                   ".TE::BLUE);
$api->setLine($pl, 1, "");	    
$api->setLine($pl, 2, TE::BOLD.TE::WHITE." Map: ");
$api->setLine($pl, 3, TE::GREEN."   ".$arena);
$api->setLine($pl, 4,"                     ".TE::BLUE);
$api->setLine($pl, 5, TE::BOLD.TE::WHITE." Starting in: ".TE::GREEN.$time);
$api->setLine($pl, 6,"                  ".TE::BLUE);
$api->setLine($pl, 7, "" );	  
$api->setLine($pl, 8, " §eplaywcserver.ddns.net ");
$api->getObjectiveName($pl);
}

public function runningStart(Player $pl,string $arena,int $live,int $time) {
 $kills = $this->plugin->manager->getKill($pl->getName());
 $tt = $this->plugin->manager->setTime($time);
 $borde = $this->plugin->manager->getBorde($arena);
 $next = $this->plugin->manager->getNext($arena);
$api = $this->plugin->score;
$api->new($pl, $pl->getName(), " ".TE::BOLD.TE::DARK_BLUE."".TE::YELLOW."SPE".TE::YELLOW."E".TE::YELLOW."D".TE::YELLOW." U".TE::YELLOW."H".TE::YELLOW."C"." ");
$api->setLine($pl, 0,"                       ".TE::BLUE);
$api->setLine($pl, 10, "");
$api->setLine($pl, 1, TE::BOLD.TE::WHITE." Map: ".TE::GREEN.$arena);
$api->setLine($pl, 2,"                     ".TE::BLUE);
$api->setLine($pl, 3, TE::BOLD.TE::WHITE." Alive: ".TE::GREEN.$live);
$api->setLine($pl, 4, TE::BOLD.TE::WHITE." Time: ".str_replace(TE::GREEN,TE::GREEN,$tt));
$api->setLine($pl, 5,"                         ".TE::BLUE);
$api->setLine($pl, 6, TE::WHITE.TE::WHITE." Kills: §b".TE::GREEN.$kills);
$api->setLine($pl, 7, TE::BOLD.TE::WHITE." Border: ".TE::GREEN.$borde);
$api->setLine($pl, 8, TE::BOLD.TE::WHITE." Next ".TE::GREEN.$next);
$api->setLine($pl, 9,"                  ".TE::BLUE);
$api->setLine($pl, 10, "");
$api->setLine($pl, 11, " §eplaywcserver.ddns.net ");
$api->getObjectiveName($pl);
}
    
    
    public function getTopWins(int $top,int $break) : string {
            $tops = new Config($this->plugin->getDataFolder().'/Wins.yml', Config::YAML);
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
		return "no tops winner yet";
		}
}
    
    
    
    
    }

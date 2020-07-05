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
$api->new($pl, $pl->getName(), " ".TE::BOLD.TE::YELLOW."U".TE::GOLD."H".TE::YELLOW."C".TE::GOLD."_".TE::YELLOW."MEE".TE::GOLD."TU".TE::YELLOW."P"." ");
$api->setLine($pl, 0,"                       ".TE::BLUE);
$api->setLine($pl, 1, TE::BOLD.TE::WHITE." Map to play: ");
$api->setLine($pl, 2, TE::YELLOW."   ".$arena);
$api->setLine($pl, 3,"                     ".TE::BLUE);
$api->setLine($pl, 4, TE::BOLD.TE::WHITE." Starting in: ".TE::GOLD.$time);
$api->setLine($pl, 5,"                  ".TE::BLUE);
$api->setLine($pl, 6, " §fpacmanlife.cf: 19132 ");
$api->getObjectiveName($pl);
}

public function runningStart(Player $pl,string $arena,int $live,int $time) {
 $kills = $this->plugin->manager->getKill($pl->getName());
 $tt = $this->plugin->manager->setTime($time);
 $borde = $this->plugin->manager->getBorde($arena);
 $next = $this->plugin->manager->getNext($arena);
$api = $this->plugin->score;
$api->new($pl, $pl->getName(), " ".TE::BOLD.TE::LIGHT_PURPLE."U".TE::DARK_PURPLE."H".TE::LIGHT_PURPLE."C".TE::DARK_PURPLE."_".TE::LIGHT_PURPLE."MEE".TE::DARK_PURPLE."T".TE::LIGHT_PURPLE."UP"." ");
$api->setLine($pl, 0,"                       ".TE::BLUE);
$api->setLine($pl, 1, TE::BOLD.TE::WHITE." Map: ".TE::LIGHT_PURPLE.$arena);
$api->setLine($pl, 2,"                     ".TE::BLUE);
$api->setLine($pl, 3, TE::BOLD.TE::WHITE." Alive: ".TE::DARK_PURPLE.$live);
$api->setLine($pl, 4, TE::BOLD.TE::WHITE." Time: ".str_replace(TE::WHITE,TE::LIGHT_PURPLE,$tt));
$api->setLine($pl, 5,"                         ".TE::BLUE);
$api->setLine($pl, 6, TE::WHITE.TE::BOLD." Kills: §r".TE::DARK_PURPLE.$kills);
$api->setLine($pl, 7, TE::BOLD.TE::WHITE." Borde: ".TE::DARK_PURPLE.$borde);
$api->setLine($pl, 8, TE::BOLD.TE::WHITE." Next ".TE::LIGHT_PURPLE.$next);
$api->setLine($pl, 9,"                  ".TE::BLUE);
$api->setLine($pl, 10, " §fpacmanlife.cf: 19132 ");
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
		return "sin tops";
		}
}
    
    
    
    
    }
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
$api->new($pl, $pl->getName(), " ".TE::BOLD.TE::DARK_BLUE."[".TE::DARK_BLUE."Spe".TE::DARK_BLUE."e".TE::DARK_BLUE."d_".TE::DARK_BLUE."U".TE::DARK_BLUE."HC".TE::DARK_BLUE."]"." ");
$api->setLine($pl, 0,"                   ".TE::BLUE);
$api->setLine($pl, 1, " §1■■■■■■■■■■■■■■■■■■");	    
$api->setLine($pl, 2, TE::BOLD.TE::DARK_BLUE." Map to play: ");
$api->setLine($pl, 3, TE::AQUA."   ".$arena);
$api->setLine($pl, 4,"                     ".TE::BLUE);
$api->setLine($pl, 5, TE::BOLD.TE::DARK_BLUE." Starting in: ".TE::AQUA.$time);
$api->setLine($pl, 6,"                  ".TE::BLUE);
$api->setLine($pl, 7, " §1■■■■■■■■■■■■■■■■■■");	  
$api->setLine($pl, 8, " §1playwcserver.ddns.net ");
$api->getObjectiveName($pl);
}

public function runningStart(Player $pl,string $arena,int $live,int $time) {
 $kills = $this->plugin->manager->getKill($pl->getName());
 $tt = $this->plugin->manager->setTime($time);
 $borde = $this->plugin->manager->getBorde($arena);
 $next = $this->plugin->manager->getNext($arena);
$api = $this->plugin->score;
$api->new($pl, $pl->getName(), " ".TE::BOLD.TE::DARK_BLUE."[".TE::DARK_BLUE."Spe".TE::DARK_BLUE."e".TE::DARK_BLUE."d_".TE::DARK_BLUE."U".TE::DARK_BLUE."H".TE::DARK_BLUE."C]"." ");
$api->setLine($pl, 0,"                       ".TE::BLUE);
$api->setLine($pl, 10, " §1■■■■■■■■■■■■■■■■■■ ");
$api->setLine($pl, 1, TE::BOLD.TE::DARK_BLUE." Map: ".TE::AQUA.$arena);
$api->setLine($pl, 2,"                     ".TE::BLUE);
$api->setLine($pl, 3, TE::BOLD.TE::DARK_BLUE." Alive: ".TE::AQUA.$live);
$api->setLine($pl, 4, TE::BOLD.TE::DARK_BLUE." Time: ".str_replace(TE::AQUA,TE::AQUA,$tt));
$api->setLine($pl, 5,"                         ".TE::BLUE);
$api->setLine($pl, 6, TE::WHITE.TE::DARK_BLUE." Kills: §b".TE::AQUA.$kills);
$api->setLine($pl, 7, TE::BOLD.TE::DARK_BLUE." Border: ".TE::AQUA.$borde);
$api->setLine($pl, 8, TE::BOLD.TE::DARK_BLUE." Next ".TE::AQUA.$next);
$api->setLine($pl, 9,"                  ".TE::BLUE);
$api->setLine($pl, 10, "§1 ■■■■■■■■■■■■■■■■■■ ");
$api->setLine($pl, 10, " §1playwcserver.ddns.net ");
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

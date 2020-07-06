<?php


namespace UHCM\entities;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\entity\{Entity,Creature};
use pocketmine\level\Position;
use pocketmine\Player; 
use pocketmine\level\Level;
use pocketmine\entity\EntityIds;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\item\Item;
use pocketmine\entity\object\PrimedTNT; 
use pocketmine\level\Location;
use pocketmine\math\{Vector3,Vector2};
use pocketmine\utils\Config;
use UHCM\UHCM;
use UHCM\Particles\FireWork;

class NPCUHCm extends Human {
    
	protected $gravity = 0.00;
    protected $attackDelay = 0;
	protected $drag = 0.00;
	protected $down;
	public $colors = ["§2","§a","§1"];
    protected $gravityEnabled = false;
    public $tg = false;
    


    


    public function __construct(Level $level, CompoundTag $nbt) {
         parent::__construct($level, $nbt);
         $this->initEntity();
    }


    public function saveNBT(): void {
        parent::saveNBT();
    }


    public function getName() : string{
        return "JOIN-UHC-MM";
    }


    


    public function getGlobal() {
    	$online = UHCM::getInstance();
        return $online;
    }


	


	public function initEntity(): void {
        $this->setGenericFlag(self::DATA_FLAG_AFFECTED_BY_GRAVITY, false);
        $this->setGenericFlag(self::DATA_FLAG_IMMOBILE, true);
        $this->setNameTagVisible(true);
		$this->setNameTagAlwaysVisible(true);
		$this->setScale(2);
        $this->applyDragBeforeGravity(false);
        parent::initEntity();
    }


public function entityBaseTick(int $tickDiff = 20) : bool{
		if(UHCM::getInstance()->isDisabled()!=true) {
		if($this->closed){
			return false;
		} $this->down++;
		
		    $hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->down%15==0) {
			for($y = 0; $y < 5; $y+=0.5){
	                  $x = 0.1*cos($y);
	                  $z = 0.1*sin($y);
	                 $this->getLevel()->addParticle(new FireWork($this->getPosition()->add($x,-0.1,$z),rand(0,55),rand(150,255),rand(0,55)));
	      }
			}
		if($this->down%30==0) {
			$on = $this->getGlobal();
			$online = $on->getOnline();
			$c = count($this->colors)-1;
			$cc = $this->colors[rand(0,$c)];
			$this->setNameTag(" §6§l||||§2UHC_§aMEETUP§6§l|||| §r§f(beta.v1)\n §fONLINE: ".$cc.$online."\n ".$cc."TAP TO JOIN");
			}
		return $hasUpdate;
		} else {
			return false;
			}
	}
	


}


		

<?php
namespace PushMe;

use pocketmine\plugin\PluginBase as Plugin;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\math\Vector3;

use pocketmine\entity\PrimedTNT;

use pocketmine\level\sound\FizzSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\block\Block;

use pocketmine\utils\Config;

class Loader extends Plugin implements Listener {
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info("PushMe Enabled");
        
        $this->cfg = new Config($this->getDataFolder() ."config.yml", Config::YAML, [
	    "ID" => 152,
    	"NoPerm_Msg" => "no permission",
        "Power" => 0.5
    ]);

    }

    public function onPlayerMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $direction = $player->getDirectionVector();
        $x = $direction->getX();
        $z = $direction->getZ();
        $block = $event->getBlock();
        if($block->getSide(Vector3::SIDE_DOWN)->getId() === $this->cfg->get("ID"))
            if($player->hasPermission("p.use")) {
                for($i = 1; $i <= 1000; $i++) {
                    $player->knockBack($player, 0, $x, $z, $this->cfg->get("Power"));
                }
                $player->getLevel()->addSound(new FizzSound(new Vector3($player->getX(), $player->getY(), $player->getZ())));
            } else {
                $player->sendTip($this->cfg->get("NoPerm_Msg"));
                $player->getLevel()->addSound(new ClickSound(new Vector3($player->getX(), $player->getY(), $player->getZ())));
		}
	}

    public function onDisable() {
        $this->getServer()->getLogger()->info("PushME Disabled");
    }
}

<?php

namespace saxavlax001\pecollide;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use saxavlax001\pecollide\PECollide;

class Main extends PluginBase {

    public function onEnable() : void
   {
    //register pecollide
    $this->getServer()->getPluginManager()->registerEvents(new PECollide($this), $this);
   }
}


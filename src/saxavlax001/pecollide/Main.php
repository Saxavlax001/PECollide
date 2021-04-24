<?php

namespace saxavlax001\pecollide;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use saxavlax001\pecollide\PECollide;

class Main extends PluginBase {
    public $dir;
    /** @var Main */
	private static $instance;

    public function onEnable() : void{
        self::$instance = $this;
        //register pecollide events
        $this->getServer()->getPluginManager()->registerEvents(new PECollide(), $this);
        //register default config settings of plugin
	    $this->dir = $this->getDataFolder();
	    if(!is_dir($this->dir)) @mkdir($this->dir);
	    if(!is_file($this->dir."config.yml")) $this->saveResource("config.yml");
        //register pecollide command
        $this->getServer()->getCommandMap()->register("pecollide", new PECollide());
   }

   public static function getInstance() : Main {
    return self::$instance;
}
}


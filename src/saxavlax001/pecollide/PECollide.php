<?php
namespace saxavlax001\pecollide;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use saxavlax001\pecollide\Main;

class PECollide extends Command implements Listener {

    public function __construct(Main $plugin) {
        parent::__construct("pecollide", "Enables/Disables players collision", "Usage: /pecollide [on/off]", ["pecollide"]);
        $this->setPermission('pecollide.command.use');
        $this->plugin = $plugin;
    }
  
      public function execute(CommandSender $sender, string $label, array $args) : bool
      {
      if(!$this->testPermission($sender)){
              return true;
       }
  
       if ($sender instanceof Player) {
           if($sender->hasPermission("pecollide.command.use") == true){
               $peconfig = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
               $peconfig->getAll();
               if($peconfig->get("pecollide") == false){
                $peconfig->set("pecollide", true);
                $peconfig->save();
                $sender->sendMessage("§aPeCollide Activated.");
               }else{
                $peconfig->set("pecollide", false);
                $peconfig->save();
                $sender->sendMessage("§aPeCollide Deactivated.");   
               }
           }else{
               $sender->sendMessage("§cYou don't have permission to use this command.");
           }
        return true;
      }
       if($sender instanceof ConsoleCommandSender) {
        $peconfig = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
        $peconfig->getAll();
        if($peconfig->get("pecollide") == false){
         $peconfig->set("pecollide", true);
         $peconfig->save();
         $sender->sendMessage("§aPeCollide Activated.");
        }else{
         $peconfig->set("pecollide", false);
         $peconfig->save();
         $sender->sendMessage("§aPeCollide Deactivated.");   
        }
       return true;
    }
   }

    public function onMove(PlayerMoveEvent $ev) {
        $peconfig = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
        $peconfig->getAll();
        if($peconfig->get("pecollide") == true){
            $player = $ev->getPlayer();
            foreach ($player->getViewers() as $viewer) {
                //If player too far, continue
                if ($player->distance($viewer) > 0.8) continue;
                //Get info of that close player
                $from = $ev->getFrom();
                $to = $ev->getTo();
                //distance de depart par rapport au viewer
                $DistanceFromViewer = $viewer->distance($from);
                //distance final par rapport au viewer
                $DistanceToViewer = $viewer->distance($to);
                //If i'm going to move in direction of the viewer
                if ($DistanceFromViewer > $DistanceToViewer) {
                    $speed = round($from->distance($to), 3);
                    // echo "My speed: $speed - ";
                    //Knock $Player harder, if running fast
                    if ($speed > 0.15) {
                        $knockvalue = $speed / 1.4;
                        $viewer->knockBack($player, 0, $viewer->x - $player->x, $viewer->z - $player->z, $knockvalue);
                        $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, $knockvalue);
                    } else $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, 0.1);
                }
            }
        }
    }
}
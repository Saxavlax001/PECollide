<?php
namespace saxavlax001\pecollide;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;
use pocketmine\lang\Language;
use pocketmine\command\Command;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use saxavlax001\pecollide\Main;

class PECollide extends Command implements Listener {

    public function __construct() {
        parent::__construct("pecollide", "Enables/Disables players collision", "Usage: /pecollide [on/off]", ["pecollide"]);
        $this->setPermission('pecollide.command.use');
    }
  
      public function execute(CommandSender $sender, string $label, array $args){
      if(!$this->testPermission($sender)){
              return true;
       }
  
       if ($sender instanceof Player) {
           if($sender->hasPermission("pecollide.command.use") == true){
               $peconfig = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
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
        $peconfig = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
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
        $peconfig = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $peconfig->getAll();
        if($peconfig->get("pecollide") == true){
            $player = $ev->getPlayer();
            foreach ($player->getViewers() as $viewer) {
                //If player too far, continue
                if ((abs($viewer->getPosition()->getX() - $player->getPosition()->getX()) + abs($viewer->getPosition()->getZ() - $player->getPosition()->getZ())) > 0.8) continue;
                //Get info of that close player
                $from = $ev->getFrom();
                $to = $ev->getTo();
                //distance de depart par rapport au viewer
                $DistanceFromViewer = $viewer->getPosition()->distanceSquared($from);
                //distance final par rapport au viewer
                $DistanceToViewer = $viewer->getPosition()->distanceSquared($to);
                //If i'm going to move in direction of the viewer
                if ($DistanceFromViewer > $DistanceToViewer) {
                    $speed = round($from->distanceSquared($to), 3);
                    // echo "My speed: $speed - ";
                    //Knock $Player harder, if running fast
                    if ($speed > 0.15) {
                        $knockvalue = $speed / 1.4;
                        $viewer->knockBack($viewer->getPosition()->getX() - $player->getPosition()->getX(), $viewer->getPosition()->getZ() - $player->getPosition()->getZ(), $knockvalue);
                        $player->knockBack($player->getPosition()->getX() - $viewer->getPosition()->getX(), $player->getPosition()->getZ() - $viewer->getPosition()->getZ(), $knockvalue);
                    } else $player->knockBack($player->getPosition()->getX() - $viewer->getPosition()->getX(), $player->getPosition()->getZ() - $viewer->getPosition()->getZ(), 0.1);
                }
            }
        }
    }
}
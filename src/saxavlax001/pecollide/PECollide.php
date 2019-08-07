<?php
namespace saxavlax001\pecollide;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

class PECollide implements Listener
{

    public function onMove(PlayerMoveEvent $ev)
    {
		
        $player = $ev->getPlayer();
		//Speed calc
		$from = $ev->getFrom();
		$to = $ev->getTo();
		echo "MyPos: $from - To: $to --- ";
		$speed = round($from->distance($to),3);

        foreach ($player->getViewers() as $viewer)
        {
            //If player too far, continue
            if ($player->distance($viewer) > 0.5) continue;
            //Get speed of that close player
            $speed = abs($player->getMotion()->x) + abs($player->getMotion()->z);
            //If player speed is superior to 0.1, they knockBack
            if ($speed > 0.1)
            {
				//Knock Him, you'r running too fast
				$knockvalue = $speed / 1.4;
                $viewer->knockBack($player, 0, $viewer->x - $player->x, $viewer->z - $player->z, $knockvalue);
                $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, $knockvalue);
            }
			
			//ToDo: Find if i'm going to hurt a player ? How ?
			// Use the $to to know where i go
			// Find the Viewer POS : $viewer->getX() ...
			// Find a way to calculate if going inside the player (The area defined arround the user ?)
			
          //  else
          //  {
				//Hey, don't move !
          //      $ev->setCancelled();
          //  }
        }
    }
}
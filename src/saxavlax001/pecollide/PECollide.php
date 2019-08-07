<?php
namespace saxavlax001\pecollide;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

class PECollide implements Listener
{

    public function onMove(PlayerMoveEvent $ev)
    {

        $player = $ev->getPlayer();
      
        foreach ($player->getViewers() as $viewer)
        {
            //If player too far, continue
            if ($player->distance($viewer) > 0.5) continue;
            //Get speed of that close player
            $speed = abs($player->getMotion()->x) + abs($player->getMotion->z);
            //If player speed is superior to X
            if ($speed > 2)
            {
				//Knock Him, you'r running too fast
                $viewer->knockBack($player, 0, $viewer->x - $player->x, $viewer->z - $player->z, 0.2);
                $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, 0.1);
            }
            else
            {
				//Hey, don't move !
                $ev->setCancelled();
            }
        }
    }
<<<<<<< HEAD
}
=======
    
}

    foreach($player->getViewers() as $viewer) {
        if($player->distance($viewer) > 0.5) continue;
            $viewer->knockBack($player, 0, $viewer->x - $player->x, $viewer->z - $player->z, 0.2);
            $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, 0.1);
            break;
         }
       }
    }
>>>>>>> e2385bc386d8245006d54ad4ed660b28acce5f5b

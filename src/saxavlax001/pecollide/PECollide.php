<?php
namespace saxavlax001\pecollide;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;

class PECollide implements Listener
{

    public function onMove(PlayerMoveEvent $ev)
    {
		
        $player = $ev->getPlayer();

        foreach ($player->getViewers() as $viewer)
        {
            //If player too far, continue
            if ($player->distance($viewer) > 0.8) continue;
            
			//Get speed of that close player
			$from = $ev->getFrom();
			$to = $ev->getTo();
			// ToDo: This method have some problem when falling, Do we still use it ?
			// Because (it's normal) we fall very fast, or up very fast (like in stair)
			// Maybe Custom Calc to do between Tick ?
			// Maye find the distance only for the Y Axis, and do some math ?
			
			//Speed calc
			
			//distance de depart par rapport au viewer
			$DistanceFromViewer = $viewer->distance($from);
			//distance final par rapport au viewer
			$DistanceToViewer = $viewer->distance($to);
			
			if ($DistanceFromViewer > $DistanceToViewer) {
				$speed = round($from->distance($to),3);            //If player speed is superior to 0.1, they knockBack
				echo "My speed: $speed - ";

				//Prendre $player $viewer $distance
				//Puis avec From To (Du player) verifier qu'il s'approche, si oui, alors on execute, sinon, on fait rien !
				round($from->distance($to),3); // 
				
				
				//Avoid some laggy thing
				//Todo: MAybereplace with a Task, and check if we are not already KnockBacked ?
				// $tick = Server::getInstance()->getTick();
				// if ($speed > 0.15 && (substr($tick, -1) == 1 || substr($tick, -1) == 4 || substr($tick, -1) == 7) )
				if ($speed > 0.15)
				{
					//Knock Him, you'r running too fast
					$knockvalue = $speed / 1.4;
					$viewer->knockBack($player, 0	, $viewer->x - $player->x, $viewer->z - $player->z, $knockvalue);
					$player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, $knockvalue);
				}
				else $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, 0.1);
				
				// ToDo: Block at small speed ? (Like collide a block)
				// Tried with below, but the calc of the speed to make a knockvalue goes crazy, 'cause of TP back, i jump so far.
				// To Try, only put that, an remove the knock, and add conf to choose if we knock or block ?
				// But if i'm too close, i'll be unable to move back, because i'm Stuck ..
				// Maybe calc if i'm Going to the player, or leaving the player ($to / $from)
				// ?? if ($viewer->distance($to) <= 8) ...
				// If i'm going to be closer of the viewer, don't move, if i'm going far of him, let me move
				// Take in consideration, if value are too small, because i'm not moving so fast, maybe add a little something to the TO, to be sur where we go, and let us move.
				// else {
						 /*Lock position but still allow to turn around*/
						 // $to2 = clone $ev->getFrom();
						 // $to2->yaw = $ev->getTo()->yaw;
						 // $to2->pitch = $ev->getTo()->pitch;
						 // $ev->setTo($to2);
					 // }
			}
        }
    }
}
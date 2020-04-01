<?php
namespace saxavlax001\pecollide;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;
class PECollide implements Listener {


    /** @var Main */
    private $pg;

    public function __construct()
    {

    }
    public function onMove(PlayerMoveEvent $ev) {
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

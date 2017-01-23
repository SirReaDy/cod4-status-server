<?php
$list_of_maps[] = 'mp_convoy Ambush';
$list_of_maps[] = 'mp_backlot Backlot';
$list_of_maps[] = 'mp_bloc Bloc';
$list_of_maps[] = 'mp_bog Bog';
$list_of_maps[] = 'mp_broadcast Broadcast'; //1.6
$list_of_maps[] = 'mp_carentan Chinatown'; //1.6
$list_of_maps[] = 'mp_countdown Countdown';
$list_of_maps[] = 'mp_crash Crash';
$list_of_maps[] = 'mp_creek Creek'; //1.6
$list_of_maps[] = 'mp_crossfire Crossfire';
$list_of_maps[] = 'mp_citystreets District';
$list_of_maps[] = 'mp_farm Downpour';
$list_of_maps[] = 'mp_killhouse Killhouse'; //1.6
$list_of_maps[] = 'mp_overgrown Overgrown';
$list_of_maps[] = 'mp_pipeline Pipeline';
$list_of_maps[] = 'mp_shipment Shipment';
$list_of_maps[] = 'mp_showdown Showdown';
$list_of_maps[] = 'mp_strike Strike';
$list_of_maps[] = 'mp_vacant Vacant';
$list_of_maps[] = 'mp_cargoship Wet Work';
$list_of_maps[] = 'mp_crash_snow Winter Crash'; //1.4

$curmap =$serverStatus['mapname'] ;
foreach ($list_of_maps as $map)
				{
			    $t = explode(' ',$map,2);
			    if ($t[0] == $curmap)
					{
					$curmap = $t[1];
					break;
					}
				}
			$fmapname = $curmap;
;?>
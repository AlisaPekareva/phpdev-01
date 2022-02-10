<?php
abstract class Component
{
protected $parent;

public function setParent(Component $parent)
{
$this->parent = $parent;
}

public function getParent(): Component
{
return $this->parent;
}

public function add(Component $component): void { }

public function remove(Component $component): void { }


public function isComposite(): bool
{
return false;
}


abstract public function operation();
}
interface Unit
{
public static function createArmy();
}

class calculate
{
public static function calc_army_damage_health (array $army, array $standartArmy, bool $rain, bool $ice) : array
{
$damage = 0;
$health = 0;
$pehota = $standartArmy['pehota'];
$luchniki = $standartArmy['luchniki'];
$konnica = $standartArmy['konnica'];

($rain == true) ? $luchniki = $luchniki['damage'] / 2 : $luchniki;

foreach ($army['units'] as $unit => $count)
{
$damage += ${$unit}['damage'] * $count;

if($ice == true) $health += ${$unit}['health'] * $count;
elseif($ice == false) $health += ${$unit}['health'] * $count + ${$unit}['armour'] * $count;

}
return ['damage' => $damage, 'health' => $health];
}

}

class Aleksandr extends Component implements Unit
{

public static function createArmy()
{
return [
'name' => 'Александр Ярославич',
'units' => [
'pehota' => 200,
'luchniki' => 30,
'konnica' => 15,
]
];
}
public function operation()
{



}
}

$standartArmy =
[
'pehota' => [
'health' => 100,
'armour' => 10,
'damage' => 10,
],
'luchniki' => [
'health' => 100,
'armour' => 5,
'damage' => 20,
],
'konnica' => [
'health' => 300,
'armour' => 30,
'damage' => 30,
]
];
class Ulf extends Component
{
public static function createArmy()
{
return [
'name' => 'Ульф Фасе',
'units' => [
'pehota' => 90,
'luchniki' => 65,
'konnica' => 25,
]
];
}

public function operation()
{

}
}


class Composite extends Component
{

protected $children;

public function __construct()
{
$this->children = new \SplObjectStorage();
}


public function add(Component $component): void
{
$this->children->attach($component);
$component->setParent($this);
}

public function remove(Component $component): void
{
$this->children->detach($component);
$component->setParent(null);
}

public function isComposite(): bool
{
return true;
}


public function operation(): string
{
$results = [];
foreach ($this->children as $child) {
$results[] = $child->operation();
}

return "Branch(" . implode("+", $results) . ")";
}
}

$health = [
calculate::calc_army_damage_health(Aleksandr::createArmy(), $standartArmy, true, true),
calculate::calc_army_damage_health(Ulf::createArmy(), $standartArmy, true, true)
];

print_r($health);
function clientCode(Component $component, $standartArmy, $rain, $ice)
{
$army = [Aleksandr::createArmy(), Ulf::createArmy()];
$healthAndDamage = [
calculate::calc_army_damage_health(Aleksandr::createArmy(), $standartArmy, $rain, $ice),
calculate::calc_army_damage_health(Ulf::createArmy(), $standartArmy, $rain, $ice)
];

echo "<table border='1'>
    <tr>
        <th></th>
        <th>".$army[0]['name']."</th>
        <th>".$army[1]['name']."</th>
    </tr>
    <tr>
        <th>Army units:</th>
        <td>unit1 (count), unit2(count), ...</td>
        <td>unit1 (count), unit2(count), ...</td>
    </tr>
    <tr>
        <th>Погибшие</th>
        <td>".$healthAndDamage[0]['health']."</td>
        <td>".$healthAndDamage[1]['health']."</td>
    </tr>
    <tr>
        <th>Выжившие</th>
        <td>".$healthAndDamage[0]['health']."</td>
        <td>".$healthAndDamage[1]['health']."</td>
    </tr>";

    $duration = 0;
    while ($healthAndDamage[0]['health'] >= 0 && $healthAndDamage[1]['health'] >= 0)
    {
    $healthAndDamage[0]['health'] -= $healthAndDamage[0]['damage'];
    $healthAndDamage[1]['health'] -= $healthAndDamage[0]['damage'];
    $duration++;
    }

    echo " <tr>
        <th>Health after $duration hits:</th>
        <td>" . $healthAndDamage[0]['health'] . "</td>
        <td>" . $healthAndDamage[1]['health'] . "</td>
    </tr>
    <tr>
        <th>Result</th>
        <td>" . ($healthAndDamage[0]['health'] > $healthAndDamage[1]['health'] ? 'WINNER' : 'LOOSER') . "</td>
        <td>" . ($healthAndDamage[1]['health'] > $healthAndDamage[0]['health'] ? 'WINNER' : 'LOOSER') . "</td>
    </tr>
</table>";


//print_r($component->operation());

}
$army = [
'army1' => [
'name' => 'Александр Ярославич',
'units' => [
'pehota' => 200,
'luchniki' => 30,
'konnica' => 15,
]
],
'army2' => [
'name' => 'Ульф Фасе',
'units' => [
'pehota' => 90,
'luchniki' => 65,
'konnica' => 25,
]
]
];
$simple = new Aleksandr();
//print_r(calculate::calc_army_damage_health(Aleksandr::createArmy(), $standartArmy, true, false));

//$simple->operation();
clientCode($simple, $standartArmy, false, true);
?>

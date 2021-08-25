<?php

namespace System\money;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

use System\SystemMain;

class SystemMoney extends Player
{

  private const TEXT = "[ BedrockMoney ] : ";


  public function getMain() : SystemMain{
    return $this->getServer()->getPluginManager()->getPlugin("BedrockMoney");
  }

  public function getMoney(String $type = "original") : Int
  {

    $original = (Int)$this->getMain()->getValue("users", strtolower($this->getName()));
    $format = (Int) number_format($original);

    return match($type){
            "original" => $original,
            "format" => $format,
            default => "Error"
    };

  }

  public function setMoney(Int | String | Float $money ): Void
  {
    $money = $money ?? $this->getMoney();
    $this->getMain()->setConfig("users", strtolower($this->getName()), $money);

  }

  public function getRank() : Int
  {
    $array = $this->getMain()->getData("users")->getAll();
    asort($array);
    $array = array_flip(array_keys($array));
    return (Int) ($array[strtolower($this->getName())] + 1);
  }

  public function getMaxMoney() : Int
  {
    return (Int) $this->getMain()->getData("config")->get("maxMoney");
  }

  public function subMoney(Int | String | Float $value) : Void
  {
    $this->getMain()->setConfig("users", strtolower($this->getName()), ($this->getMoney() - ((Int)$value)));
  }

  public function addMoney(Int | String | Float $value) : Void
  {
    $this->getMain()->setConfig("users", strtolower($this->getName()), ($this->getMoney() + ((Int)$value)));
  }

  public function payMoney(Player $player, Int | String | Float $value) : Void
  {
    if(($this->getMoney() - $value) <= 0)
    {

      $this->sendMessage(self::TEXT."금액이 부족합니다...");

    } else {
      $this->addMoney("users", strtolower($player->getName()), $value);
      $this->subMoney("users", strtolower($this->getName()), $value);

    }

  }

  public function isUser() : Bool
  {
    return $this->getMain()->getData("users")->exists(strtolower($this->getName()));
  }
}
?>

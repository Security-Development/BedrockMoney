<?php

namespace System;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\player\Player;
use System\money\SystemMoney;

class SystemMain extends PluginBase
{
  private function CreateConfig(String $name)
  {
    if(!file_exists($this->getDataFolder() . $name))
    {
      $this->saveResource($name);

    }

  }

  public function getData(?String $name = null) : Config
  {
    if($name == null)
    {
      return throw new \Exception("Error : getData is null in System\SystemMain line : ".__LINE__);

    }else{
      return new Config($this->getDataFolder(). $name.".yml", Config::YAML);

    }

  }

  public function setConfig(String $type, Int | Float | String $key, Int | Float | String $value) : Void
  {
    $config = $this->getData($type);
    $config->set($key, $value);
    $config->save();

  }

  public function getValue(String $type, Int | Float | String $key) : String
  {
    $config = $this->getData($type);
    return $config->get($key);

  }

  public function createData(Player $player) : Void
  {
    $this->setConfig("users", strtolower($player->getName()), $this->getValue("config", "startMoney"));

  }

  public function onEnable() : Void
  {
    @mkdir($this->getDataFolder());

    $this->CreateConfig("config.yml");
    $this->CreateConfig("users.yml");
    $system = $this;

    foreach(["PayMoneyCommand",
             "CheckMoneyCommand",
             "RankMoneyCommand",
             "SetMoneyCommand",
             "ControlMoneyCommand"
             ] as $class)
    {
      $class ="\\System\\command\\".$class;
      $this->getServer()->getCommandMap()->register("BedrockMoney", new $class($this));

    }

    $this->getServer()->getPluginManager()->registerEvents(new class($system) implements Listener {

      public function __construct(public SystemMain $system){}

      public function CreationEvent(PlayerCreationEvent $event) : Void
      {
        $event->setPlayerClass (SystemMoney::class);

      }

      public function LoginEvent(PlayerLoginEvent $event) : Void
      {
        $player = $event->getPlayer();

        if(!$player->isUser())
        {
          $this->system->createData($player);

        }

      }

      public function JoinEvent(PlayerJoinEvent $event) : Void
      {
        $player = $event->getPlayer();

        if($player->isUser())
        {
          $player->sendMessage("잔액 : ".$player->getMoney("format"));

        }

      }

      public function PacketSendEvent(DataPacketSendEvent $event) : Void
      {
        foreach($event->getPackets() as $key => $pk)
        {
          if($pk instanceof AvailableCommandsPacket)
          {
            $this->addParameter($pk, "돈송금", "다른 플레이어한테 송금합니다.", ["플레이어이름", "금액"], ["string", "int"]);
            $this->addParameter($pk, "돈확인", "당신의 돈 혹은 다른 플레이어의 돈을 확인할 수 있습니다.", ["플레이어이름"], ["string"]);
            $this->addParameter($pk, "돈순위", "당신의 돈 순위 혹은 다른 플레이어의 돈 순위를 확인할 수 있습니다.", ["플레이어이름"], ["string"]);
            $this->addParameter($pk, "돈설정", "플레이어의 돈을 설정합니다.", ["플레이어이름", "금액"], ["string", "int"], 1);
            $this->addParameter($pk, "돈조절", "플레이어의 돈을 조절합니다..", ["플레이어이름", "금액"], ["string", "int"], 1);
          }
        }

      }

      public function addParameter(AvailableCommandsPacket $pk, String $commandname = "", String $description = "", Array $param = [], Array $type = [], Int $permission = 0) : Void
      {
        $parType = null;
        $arr = [];

        for($i = 0; $i < count($param); $i++)
        {
          $parType = match($type[$i])
          {
            "int" => AvailableCommandsPacket::ARG_TYPE_INT,
            "string" => AvailableCommandsPacket::ARG_TYPE_STRING,
            "float" => AvailableCommandsPacket::ARG_TYPE_FLOAT
          };

          $arr[$i] = CommandParameter::standard(
            $param[$i], 
            $parType,
            0,
            true
          );

        }

        $aliases = [];
        $enum = null;
        if(count($aliases) > 0)
        {

          if(!in_array($commandname, $aliases, true))
          {
            $aliases[] = $commandname;
          }
          $enum = new CommandEnum(
            ucfirst($commandname) . "Aliases",
            array_values($aliases)
          );
        }

        $data = new CommandData(
          $commandname, 
          $description, 
          0, 
          0, 
          $enum, 
          [
            $arr
          ]
        );

        $pk->commandData[$commandname] = $data;

      }

    }, $this);

  }

  public function onLoad() : Void
  {
    $error = !$this->isEnabled();
    $console_message = fn (String $msg) => $this->getServer()->getLogger()->info($msg);

    match($error)
    {

      false => (fn() => (
                         $console_message("==================================").
                         $console_message("[§e*§f] ByteMoney §cDiable").
                         $console_message("==================================")
                       ))(),
      true => (fn() => (
                        $console_message("==================================").
                        $console_message("[§e*§f] ByteMoney §aEnable").
                        $console_message("==================================")
                      ))(),
    };

  }

}
?>

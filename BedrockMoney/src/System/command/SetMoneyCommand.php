<?php

namespace System\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use System\SystemMain;
use System\money\SystemMoney;

class SetMoneyCommand extends Command
{

  private const TEXT = "[ BedrockMoney ] : ";

  public function __construct(private SystemMain $system)
  {
    parent::__construct("돈설정");
    $this->setPermission("op");

  }

  public function execute(CommandSender $sender, string $label, array $args) : Bool
  {
    if(!$sender->hasPermission($this->getPermission())) return true;

    if(isset($args[0]) && isset($args[1]))
    {
      if(!is_numeric($args[1]))
      {
        $sender->sendMessage(self::TEXT."[1] 명령어 요소가 숫자가 아닙니다.");
        return ture;

      }

      $player = $sender->getServer()->getPlayer($args[0]);

      if($player == null)
      {
        $sender->sendMessage(self::TEXT."현재 플레이어는 서버에 접속중이지 않습니다.");


      } else{
        if($player->isUser())
        {
          $player->setMoney($args[1]);

          $sender->sendMessage(self::TEXT.$player->getName()."님의 돈을 ".((String) number_format((Int)$args[1]))."원으로 설정 하셨습니다.");

        } else {
          $sender->sendMessage(self::TEXT."데이터 목록에 없는 플레이어 입니다, 올바르게 입력했는지 다시 확인 해주세요.");

        }

      }

    } else {
      $sender->sendMessage(self::TEXT."명령어의 요소가 부족합니다, 올바르게 입력했는지 다시 확인 해주세요.");

    }

    return false;
  }

}
?>

<?php

namespace System\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use System\SystemMain;

class CheckMoneyCommand extends Command
{

  private const TEXT = "[ BedrockMoney ] : ";

  public function __construct(private SystemMain $System)
  {
    parent::__construct("돈확인");

  }

  public function execute(CommandSender $sender, string $label, array $args) : Bool
  {

    if($sender instanceof ConsoleCommandSender)
    {
      $sender->getServer()->getLogger()->info("[§e*§f] §c인게임에서만 실행 가능합니다.");
      return true;

    }

      if(isset($args[0]))
      {
        $player = $sender->getServer()->getPlayer($args[0]);

        if($player == null)
        {
          $sender->sendMessage(self::TEXT."현재 플레이어는 서버에 접속중이지 않습니다.");
        } else{
          if($player->isUser())
          {
            $sender->sendMessage(self::TEXT.$player->getName()."님께서 현재 자금 ".$player->getMoney("format")."원을 보유 중입니다.");

          } else {
            $sender->sendMessage(self::TEXT."데이터 목록에 없는 플레이어 입니다, 올바르게 입력했는지 다시 확인 해주세요.");
          }

        }

      } else {
        $sender->sendMessage(self::TEXT."당신께서는 현재 자금 ".$sender->getMoney("format")."원을 보유 중입니다.");

      }

      return false;

  }

}
?>

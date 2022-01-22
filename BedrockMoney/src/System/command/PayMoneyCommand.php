<?php

namespace System\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;

use System\SystemMain;

class PayMoneyCommand extends Command
{

  private const TEXT = "[ BedrockMoney ] : ";

  public function __construct(private SystemMain $System)
  {
    parent::__construct("돈송금");

  }

  public function execute(CommandSender $sender, string $label, array $args) : Bool
  {
    if($sender instanceof ConsoleCommandSender)
    {
      $sender->getServer()->getLogger()->info("[§e*§f] §c인게임에서만 실행 가능합니다.");
      return true;

    }

    if(isset($args[0]) && isset($args[1]))
    {
      if(!is_numeric($args[1]))
      {
        $sender->sendMessage(self::TEXT."[1] 명령어 요소의 데이터타입이 숫자가 아닙니다.");
        return true;

      }

      $player = $sender->getServer()->getPlayerByPrefix($args[0]);

      if($player == null)
      {
        $sender->sendMessage(self::TEXT."현재 플레이어는 서버에 접속중이지 않습니다.");

      } else{
        if($player->isUser())
        {
          if($player->getMaxMoney() < (int) $player->getMoney()){
            $sender->sendMessage(self::TEXT."송금시 해당 플레이어의 자금이 최대치를 넘어 보낼수 없습니다.");

          } else {
            $sender->payMoney($player, $args[1]);

          }

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

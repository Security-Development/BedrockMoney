# BedockMoney
Pocketmine-MP BedrockMoney Plugin

명령어 목록

| <center>명령어</center> | <center>기능</center> |
|--- | --- |
|**/돈확인 [플레이어이름:string] [금액:int]** | 당신의 돈 혹은 다른 플레이어의 돈을 확인할 수 있습니다.|
|**/돈송금 [플레이어이름:string] [금액:int]** | 다른 플레이어한테 송금합니다.|
|**/돈순위 [플레이어이름:string]** | 당신의 돈 순위 혹은 다른 플레이어의 돈 순위를 확인할 수 있습니다.|
|**/돈설정 [플레이어이름:string] [금액:int]** | 플레이어의 돈을 설정합니다.|
|**/돈조절 [플레이어이름:string] [금액:int]** | 플레이어의 돈을 조절합니다.|


개발자-API
| <center>코드</center> | <center>기능</center>| <center>반환 데이터 타입</center> |
|--- | --- | --- |
|**Player->getRank()** | 플레이어의 돈순위를 가져옵니다. | Int |
|**Player->getMoney(Str = "format" or "original") 기본값 original** | 플레이어의 돈을 가져옵니다. | Int |
|**Player->getMaxMoney()** | 서버의 돈 최대치를 가져옵니다. | Int |
|**Player->isUser(Int)** | 플레이어가 돈 데이터상에 있는지 확인합니다. | Bool |
|**Player->setMoney(Int)** | 플레이어의 돈을 설정합니다. | Void |
|**Player->addMoney(Int)** | 플레이어의 돈을 추가합니다. | Void |
|**Player->payMoney(Player, Int)** | parmPlayer에게 Player가 돈을 Int만큼 지불합니다. | Void |
|**Player->subMoney(Int)** | 플레이어의 돈을 추감합니다. | Void |



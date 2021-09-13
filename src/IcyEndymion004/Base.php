<?php

namespace IcyEndymion004;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use Stringable;

class Base extends PluginBase implements Listener {

    


    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function OnUse(PlayerInteractEvent $event){
        $item = $event->getItem();
        $tagcheck = $item->getNamedTag();
        if($tagcheck->hasTag("hpgive") && $tagcheck->hasTag("CustomFrozenhealthItem")){
            $val = $item->getNamedTag()->getTag("hpgive")->getValue();
            $player = $event->getPlayer();
            $player->setHealth($player->getHealth() + $val);
            $player->sendMessage("§cYou Have Been Healed §6$val §cHp");
            self::pop($player);
        }
    }
    public static function pop(Player $player): void{
        $index = $player->getInventory()->getHeldItemIndex();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->setItem($index, $item->setCount($item->getCount() - 1));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {  
        if($command->getName() === "customfood"){
            if(!$sender instanceof Player) return false;
            $item = ItemFactory::get($args[0], $args[1], $args[4]);
            $item->setCustomName("§o§9$args[2]");
            $item->setLore("§o§aThis Item Well Instantly Heal You §6$args[3]");
            $item->setNamedTagEntry(new ListTag("ench", []));
            $item->setNamedTagEntry(new StringTag("hpgive", $args[3]));
            $item->setNamedTagEntry(new StringTag("CustomFrozenhealthItem", $args[0]));
            $sender->getInventory()->addItem($item);
            $sender->sendMessage("§bYou Have Been Given §6$args[2] §bThat Heals §6$args[3] §bAnd You Got §6$args[4] §bof Them");
        }
        return true;
    }
    }
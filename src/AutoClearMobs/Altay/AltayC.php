<?php

namespace AutoClearMobs\Altay;

use AutoClearMobs\Altay\Task\AltayTask;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\VersionInfo;
use pocketmine\utils\Config;

use pocketmine\entity\Chicken;
use pocketmine\entity\Bee;
use pocketmine\entity\Cow;
use pocketmine\entity\Pig;
use pocketmine\entity\Sheep;
use pocketmine\entity\Wolf;
use pocketmine\entity\PolarBear;
use pocketmine\entity\Ocelot;
use pocketmine\entity\Cat;
use pocketmine\entity\Mooshroom;
use pocketmine\entity\Bat;
use pocketmine\entity\Parrot;
use pocketmine\entity\Rabbit;
use pocketmine\entity\Llama;
use pocketmine\entity\Horse;
use pocketmine\entity\Donkey;
use pocketmine\entity\Mule;
use pocketmine\entity\SkeletonHorse;
use pocketmine\entity\ZombieHorse;
use pocketmine\entity\TropicalFish;
use pocketmine\entity\Cod;
use pocketmine\entity\PufferFish;
use pocketmine\entity\Salmon;
use pocketmine\entity\Dolphin;
use pocketmine\entity\SeaTurtle;
use pocketmine\entity\Panda;
use pocketmine\entity\Fox;
use pocketmine\entity\Creeper;
use pocketmine\entity\Enderman;
use pocketmine\entity\SilverFish;
use pocketmine\entity\Skeleton;
use pocketmine\entity\WitherSkeleton;
use pocketmine\entity\Stray;
use pocketmine\entity\Slime;
use pocketmine\entity\Spider;
use pocketmine\entity\Zombie;
use pocketmine\entity\ZombifiedPiglin;
use pocketmine\entity\Husk;
use pocketmine\entity\Drowned;
use pocketmine\entity\Squid;
use pocketmine\entity\GlowSquid;
use pocketmine\entity\CaveSpider;
use pocketmine\entity\Witch;
use pocketmine\entity\Guardian;
use pocketmine\entity\ElderGuardian;
use pocketmine\entity\Endermite;
use pocketmine\entity\MagmaCube;
use pocketmine\entity\Strider;
use pocketmine\entity\Hoglin;
use pocketmine\entity\Piglin;
use pocketmine\entity\Zoglin;
use pocketmine\entity\PiglinBrute;
use pocketmine\entity\Goat;
use pocketmine\entity\Axolotl;
use pocketmine\entity\Warden;
use pocketmine\entity\Allay;
use pocketmine\entity\Frog;
use pocketmine\entity\Tadpole;
use pocketmine\entity\TraderLlama;
use pocketmine\entity\Ghast;
use pocketmine\entity\Blaze;
use pocketmine\entity\Shulker;
use pocketmine\entity\Vindicator;
use pocketmine\entity\Evoker;
use pocketmine\entity\Vex;
use pocketmine\entity\Villager;
use pocketmine\entity\WanderingTrader;
use pocketmine\entity\ZombieVillager;
use pocketmine\entity\Phantom;
use pocketmine\entity\Pillager;
use pocketmine\entity\Ravager;

class AltayC extends PluginBase implements Listener
{

    public static $instance;

    private array $mobs = [
        Ravager::class,
        Pillager::class,
        Phantom::class,
        ZombieVillager::class,
        WanderingTrader::class,
        Villager::class,
        Vex::class,
        Evoker::class,
        Vindicator::class,
        Shulker::class,
        Blaze::class,
        Ghast::class,
        TraderLlama::class,
        Tadpole::class,
        Frog::class,
        Allay::class,
        Warden::class,
        Axolotl::class,
        Goat::class,
        PiglinBrute::class,
        Zoglin::class,
        Piglin::class,
        Hoglin::class,
        Strider::class,
        MagmaCube::class,
        Endermite::class,
        ElderGuardian::class,
        Guardian::class,
        Witch::class,
        CaveSpider::class,
        GlowSquid::class,
        Squid::class,
        Drowned::class,
        Husk::class,
        ZombifiedPiglin::class,
        Zombie::class,
        Spider::class,
        Slime::class,
        Stray::class,
        WitherSkeleton::class,
        Skeleton::class,
        SilverFish::class,
        Enderman::class,
        Creeper::class,
        Fox::class,
        Panda::class,
        SeaTurtle::class,
        Dolphin::class,
        Salmon::class,
        PufferFish::class,
        Cod::class,
        TropicalFish::class,
        ZombieHorse::class,
        SkeletonHorse::class,
        Mule::class,
        Horse::class,
        Llama::class,
        Rabbit::class,
        Parrot::class,
        Bat::class,
        Mooshroom::class,
        Cat::class,
        Ocelot::class,
        PolarBear::class,
        Wolf::class,
        Sheep::class,
        Pig::class,
        Cow::class,
        Bee::class,
        Chicken::class

    ];

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function getAltayName()
    {
        return VersionInfo::NAME;
    }

    public function getAltayVersion()
    {
        return VersionInfo::ALTAY_VERSION;
    }

    public function getCleanTime(): int
    {
        $cleantimec = new Config($this->getDataFolder() . "Settings/" . "CleanTime" . ".json", Config::JSON);
        $seconds = $cleantimec->get("clean-time");
        return $seconds;
    }

    public function onLoad(): void
    {
        self::$instance = $this;

        $this->getLogger()->info("§cAutoClearMobs for " . $this->getAltayName() . " " . $this->getAltayVersion() . " §cis loading...");
    }

    public function onEnable(): void
    {
        $this->getLogger()->info("§cAutoClearMobs for " . $this->getAltayName() . " " . $this->getAltayVersion() . " §cis loaded!");
        $this->getScheduler()->scheduleRepeatingTask(new AltayTask($this->getCleanTime()), 20);

        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "Settings");

        foreach ($this->getResources() as $resource) {
            $this->saveResource($resource->getFilename());
            $this->saveResource("Settings/CleanTime.json", true);
        }
    }

    public function onDisable(): void
    {
        $this->getLogger()->info("§cAutoClearMobs for " . $this->getAltayName() . " " . $this->getAltayVersion() . " §cis disabled!");
    }

    public function clean(): void
    {
        foreach ($this->getServer()->getWorldManager()->getWorlds() as $world) {
            foreach ($world->getEntities() as $entity) {
                foreach ($this->mobs as $mob) {
                    if ($entity instanceof $mob) {
                        $entity->close();
                    }
                }
            }
        }
    }
}
<?php

/**
 * 武器名称一覧
 */
enum WeaponNames:string
{
    case Rock = 'グー';
    case Papper = 'パー';
    case Scissors = 'チョキ';
}


/**
 * 武器タイプ
 */
interface WeaponType
{
    public static function getName();
    public function isVictory(WeaponType $opponent_weapon);
    public function isDraw(WeaponType $opponent_weapon);
    public function isDefeat(WeaponType $opponent_weapon);
}

/**
 * グークラス
 */
class Rock implements WeaponType
{

    public static function getName()
    {
        return 'rock';
    }

    public function isVictory(WeaponType $opponent_weapon)
    {
        return Scissors::getName() === $opponent_weapon->getName();
    }

    public function isDraw(WeaponType $opponent_weapon)
    {
        return self::getName() === $opponent_weapon->getName();
    }

    public function isDefeat(WeaponType $opponent_weapon)
    {
        return Papper::getName() === $opponent_weapon->getName();
    }
}

/**
 * パークラス
 */
class Papper implements WeaponType
{

    public static function getName()
    {
        return 'papper';
    }

    public function isVictory(WeaponType $opponent_weapon)
    {
        return Rock::getName() === $opponent_weapon->getName();
    }

    public function isDraw(WeaponType $opponent_weapon)
    {
        return self::getName() === $opponent_weapon->getName();
    }

    public function isDefeat(WeaponType $opponent_weapon)
    {
        return Scissors::getName() === $opponent_weapon->getName();
    }
}

/**
 * チョキクラス
 */
class Scissors implements WeaponType
{

    public static function getName()
    {
        return 'scissors';
    }

    public function isVictory(WeaponType $opponent_weapon)
    {
        return Papper::getName() === $opponent_weapon->getName();
    }

    public function isDraw(WeaponType $opponent_weapon)
    {
        return self::getName() === $opponent_weapon->getName();
    }

    public function isDefeat(WeaponType $opponent_weapon)
    {
        return Rock::getName() === $opponent_weapon->getName();
    }
}

/**
 * 武器オブジェクト生成工場
 */
class WeaponFactory {

    public static function createByName($weapon_name)
    {
        foreach (WeaponNames::cases() as $weapon_name_obj) {
            if ($weapon_name_obj->value !== $weapon_name) {
                continue;
            }
            return self::create($weapon_name_obj);
        }
        throw new Exception('やり直し');
    }

    public static function createRandom()
    {
        $key = random_int(0, 2);
        $weapon_name_obj = WeaponNames::cases()[$key];
        return self::create($weapon_name_obj);
    }

    private static function create($weapon_name_obj)
    {
        return match($weapon_name_obj) {
            WeaponNames::Rock => new Rock(),
            WeaponNames::Papper => new Papper(),
            WeaponNames::Scissors => new Scissors()
        };
    }
}

/**
 * ジャンケン結果判定クラス
 */
class Judge
{
    const VICTORY = '勝ち';
    const DEFEAT = '負け';
    const DRAW = 'あいこ';

    public static function result(WeaponType $my_weapon, WeaponType $opponent_weapon): string
    {
        if ($my_weapon->isVictory($opponent_weapon)) {
            return self::VICTORY;
        }
        if ($my_weapon->isDefeat($opponent_weapon)) {
            return self::DEFEAT;
        }
        if ($my_weapon->isDraw($opponent_weapon)) {
            return self::DRAW;
        }
    }
}

/**
 * ジャンケンクラス
 */
class Jyanken
{

    public function _construct()
    {
        // 拡張する時に必要になりそう
    }

    public function pon(WeaponType $my_weapon)
    {
        $opponent_weapon = WeaponFactory::createRandom();
        return Judge::result($my_weapon, $opponent_weapon);
    }
}

/** 処理実行 */
echo jyankenpon($argv);

/**
 * じゃんけんぽん
 * 
 * @param array $params
 * @return string
 */
function jyankenpon(array $params): string
{
    // 武器名
    $weapon_name = $params[1];

    // 武器オブジェクト生成
    $my_weapon = createMyWeapon($weapon_name);

    // ジャンケン
    $jyanken = new Jyanken();
    return $jyanken->pon($my_weapon);
}


/**
 * 自分の武器オブジェクト生成
 * 
 * @param string $weapon_name
 * @return WeaponType
 */
function createMyWeapon(string $weapon_name)
{
    try {
        return WeaponFactory::createByName($weapon_name);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}
<?php

namespace Codense\TwitterListsExporter\Test;

class Faker
{
    public static $faker;

    public static function getFaker()
    {
        if (!self::$faker) {
            self::$faker = \Faker\Factory::create();
        }

        return self::$faker;
    }

    public static function getListMembersFromApi($n = 1)
    {
        $members = [];
        $faker = self::getFaker();
        for ($i = 0; $i < $n; $i++) {
            $id = $faker->randomNumber;
            $members[] = (object) array(
                'id' => $id,
                'id_str' => "$id",
                'screen_name' => $faker->userName,
                'name' => $faker->name,
                'following_count' => $faker->randomNumber
            );
        }

        return $members;
    }

}

<?php
    /**
     * @var $faker \Faker\Generator
     * @var $index integer
     */
    return [
        'task_id' => rand(1, 10),
        'file' => $faker->lexify('file_?????.???')
        // 'url' => $faker->url,
        // 'address' => $faker->address,
        // 'phone' => substr($faker->e164PhoneNumber, 1, 11)
    ];

<?php
    /**
     * @var $faker \Faker\Generator
     * @var $index integer
     */
    return [
        'task_id' => rand(1, 10),
        'file' => $faker->lexify('file_?????.???')
    ];

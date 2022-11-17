<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Country;
use App\Models\Label;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Continent;

class DatabaseSeeder extends Seeder
{
    protected $continentsCode = [
        'AF',
        'AN',
        'AS',
        'EU',
        'NA',
        'OC',
        'SA'
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $index = 0;

        $continents = Continent::factory(7)->make()->each(function ($continent) use (&$index) {
            $continent->code = $this->continentsCode[$index];
            $continent->save();
            $index++;
        });

        $countries = Country::factory(20)->make()->each(function ($country) use ($continents) {
            $country->continent_id = rand(1, count($continents));
            $country->save();
        });

        $users = User::factory(30)->make()->each(function ($user) use ($countries) {
            $user->country_id = rand(1, count($countries));
            $user->save();
        });

        $projects = Project::factory(100)->make()->each(function ($project) use ($users) {
            $project->author_id = rand(1, count($users));
            $project->save();
        });

        $labels = Label::factory(200)->make()->each(function ($label) use ($users, $countries) {
            $label->author_id = rand(1, count($users));
            $label->save();
        });


        // ...
        // Many to many
        // project_user, label_project
        // ...

    }
}

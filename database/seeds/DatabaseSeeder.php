<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Un Guard model
        Model::unguard();

        // disable fk constrain check
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {

            // Call the php artisan migrate:refresh using Artisan
            $this->command->call('migrate:refresh');

            $this->command->warn("Data cleared, starting from blank database.");

            $this->command->info("Running Passport install.");

            // Call the passport install
            $this->command->call('passport:install');
        }

        // How many users you need, defaulting to 20
        $numberOfUser = $this->command->ask('How many users you need ?', 20);

        $this->command->info("Creating {$numberOfUser} users, each will have a channel associated.");

        // Create the channel, it will create a user and assign the channel
        $channels = factory(App\Channel::class, intval($numberOfUser))->create();

        $this->command->info('Users Created!');

        // Seed the categories for video

        $categories = [
            'Film & Animation',
            'Cars & Vehicles',
            'Music',
            'Pets & Animals',
            'Travel & Events',
            'Gaming',
            'Comedy',
            'Entertainment',
            'News & Politics',
            'How-to & Style',
            'Education',
            'Science & Technology',
            'Non-profits & Activism'
        ];

        foreach ($categories as $category) {
            factory(App\Category::class)->create(['name' => $category]);
        }

        // How many posts per channel
        $postRange = $this->command->ask('How many posts per channel should have, give a range ?', '10-20');

        // Loop and create the post in range with channel id
        $channels->each(function($channel) use ($postRange){
            factory(App\Post::class, $this->getRandomRange($postRange))
                ->create(['channel_id' => $channel->id]);
        });

        $this->command->warn("Now all Channels have {$postRange} posts !");

        // Now how many comments per post
        $commentRange = $this->command->ask('Give a range for comments per post ?', '0-20');

        // Get all post and give each one some comment in asked range
        \App\Post::all()->each(function() use ($commentRange) {
            factory(App\Comment::class, $this->getRandomRange($commentRange))->create();
        });

        $this->command->warn("{$commentRange} Comment(s) added for posts !");

        $this->command->info("Hurrah! Database has been seeded.");

        $this->command->warn('Here is a login info to for test.');
        $this->command->info( sprintf('Email: %s and password secret.', App\User::inRandomOrder()->first()->email ) );

        // enable back fk constrain check
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Re Guard model
        Model::reguard();
    }

    /**
     * Return random value in given range
     *
     * @param $postRange
     * @return int
     */
    function getRandomRange($postRange)
    {
        return rand(...explode('-', $postRange));
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(100)->create();
        Category::factory(15)->create();
        Post::factory(250)->create();
        Tag::factory(15)->create();

        Post::all()->each(function ($post){
            $post->tags()->attach(Tag::inRandomOrder()->limit(rand(1,4))->get()->pluck('id'));
        });

        User::create([
            'name' => "hein htet zan",
            'email' => "admin@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('asdffdsa'), // password
            'remember_token' => Str::random(10),
            "role" => '0'
        ]);

    }
}

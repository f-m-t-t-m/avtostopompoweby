<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use PhpParser\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            DepartmentSeeder::class,
            GroupSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            SectionSeeder::class,
            CommentSeeder::class
        ]);
    }
}

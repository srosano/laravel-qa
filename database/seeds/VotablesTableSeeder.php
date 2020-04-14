<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Question;
use App\Answer;

class VotablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // calls method "table" of class "DB" from global scope
        // if class "DB" not found, it tries to autoload class "DB"
        \DB::table('votables')->delete();

        $users = User::all();
        $numberOfUsers = $users->count();
        $votes = [-1, 1];

        foreach(Question::all() as $question){
            for($i=0;$i < rand(1, $numberOfUsers); $i++){
                $user = $users[$i];
                $user->voteQuestion($question, $votes[rand(0, 1)]);
            }
        }

        foreach(Answer::all() as $answer){
            for($i=0;$i < rand(1, $numberOfUsers); $i++){
                $user = $users[$i];
                $user->voteAnswer($answer, $votes[rand(0, 1)]);
            }
        }

    }
}

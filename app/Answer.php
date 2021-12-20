<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Answer
 *
 * @property int $id
 * @property int $question_id
 * @property int $user_id
 * @property string $body
 * @property-read int|null $votes_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $body_html
 * @property-read mixed $created_date
 * @property-read mixed $is_best
 * @property-read mixed $status
 * @property-read \App\Question $question
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $votes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereVotesCount($value)
 * @mixin \Eloquent
 */
class Answer extends Model
{
    use VotableTrait;

    protected $fillable = ['body', 'user_id'];

    protected $appends = ['created_date', 'body_html'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute()
    {
        return clean(\Parsedown::instance()->text($this->body));
    }


    public static function boot()
    {
        parent::boot();

        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });

        static::deleted(function ($answer) {
            $answer->question->decrement('answers_count');
        });
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        return $this->isBest() ? 'vote-accepted' : '';
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function isBest()
    {
        return $this->id === $this->question->best_answer_id;
    }
}

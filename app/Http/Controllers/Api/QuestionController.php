<?php
namespace App\Http\Controllers\Api;
use App\Http\Requests\Request;
use DB;
use App\QuesAnswered;
use App\Question;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\ApiController;
use ValidationException;


class QuestionController extends ApiController
{

    public function  getQuestions($matchID)
    {
        $questions= DB::table('questions')
            ->where('match_id', $matchID)
            ->whereNotIn('id', function ($query)
            {
                $query->select('question_id')
                    ->from('user_answers')
                    ->whereRaw('user_answers.user_id ='.$this->user->id.'');
            })
            ->get();

        if (count($questions) < 1)
            throw new ValidationException(['No Questions available for the match.']);

        foreach($questions as $ques){

            if(isset($ques->quesAnswered[0]) && $ques->id==$ques->quesAnswered[0]->question_id &&
                     $ques->quesAnswered[0]->user_id==$this->user->id && $ques->quesAnswered[0]->is_correct==1
            )
            {
            }else{
                $response[]=[

                    'id'=>$ques->id,
                    'question'=>$ques->description,
                    'answers' =>[
                        [
                            'id'=>1,
                            'answer'=>$ques->option_1
                        ],
                        [
                            'id'=>2,
                            'answer'=>$ques->option_2
                        ],
                        [
                            'id'=>3,
                            'answer'=>$ques->option_3
                        ],
                        [
                            'id'=>4,
                            'answer'=>$ques->option_4
                        ]
                    ],
                ];
            }
        }

        return \Response::json(['data'=>$response]);
    }

    public function answerToQuestions()
    {
        $input = array_except(Input::all(), '_method');

        $quesID=$input['ques_id'];
        $answer=$input['answer_id'];
        $success=false;
        $ifExist=QuesAnswered::where('question_id',$quesID);
        $question = Question::findOrFail($quesID);
        $ifExist=QuesAnswered::where('question_id',$quesID);
        if(!$ifExist->first()) {

            $quesAnswerd = new QuesAnswered();
            $quesAnswerd->question_id = $quesID;
            $quesAnswerd->user_id = $this->user->id;
            $quesAnswerd->is_correct = 0;
            if ($question->answer == $answer) {
                $quesAnswerd->is_correct = 1;
               $success=true;
            }

            $quesAnswerd->save();
            return \Response::json(array('success' =>$success));
        }
        throw new ValidationException(['Already answered.']);



    }
}
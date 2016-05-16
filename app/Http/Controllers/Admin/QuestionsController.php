<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateQuestionRequest;
use App\Http\Requests\Admin\UpdateQuestionRequest;
use App\Match;
use App\Question;
use App\Utils\ImageUpload;

class QuestionsController extends AdminController
{
    protected $question;
    protected $match;
    protected $upload;

    public function __construct(Match $match, Question $question, ImageUpload $upload)
    {
        $this->match = $match;
        $this->question = $question;
        $this->upload = $upload;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $matchId
     * @return \Illuminate\Http\Response
     */
    public function index($matchId)
    {
        $data = $this->question->where('match_id', '=', $matchId)->get();

        $match = $this->match->find($matchId);

        return view('admin.questions.index', compact('data', 'matchId', 'match'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $matchId
     * @return \Illuminate\Http\Response
     */
    public function create($matchId)
    {
        $match = $this->match->find($matchId);

        $matches = $this->match->lists('name', 'id');
        return view('admin.questions.create', compact('matches', 'matchId', 'match'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateQuestionRequest $request
     * @param $matchId
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQuestionRequest $request, $matchId)
    {

        $input = $request->all();
        $input['match_id'] = $matchId;
        if ($request->hasFile('image'))
            $input['image'] = $this->upload->process($request->file('image'), 'questions');
        $this->question->create($input);

        return redirect()->route('admin.matches.questions.index', $matchId)->with('message', 'Successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($matchId, $id)
    {

        $item = $this->question->find($id);
        if(count($item) == 0)
            return "No Data Found!";

        $match = $this->match->find($matchId);

        return view('admin.questions.edit', compact('item', 'matchId', 'match'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionRequest $request
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, $matchId, $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->question->find($id);

        if ($request->hasFile('image'))
            $input['image'] = $this->upload->process($request->file('image'), 'questions');
        else
            $input['image'] =  $data->image;

        $data->update($input);

        return redirect()->route('admin.matches.questions.index', $matchId)->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($matchId, $id)
    {
        $data = $this->question->find($id);
        if(count($data) == 0)
            return "No Data Found!";

        $data->delete();

        return redirect()->route('admin.matches.questions.index', $matchId)->with('message', 'Successfully deleted');
    }

}
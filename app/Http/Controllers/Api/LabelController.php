<?php

namespace App\Http\Controllers\Api;

use App\Models\Label;
use App\Filters\LabelFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psy\Exception\ErrorException;

class LabelController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request): void
    {
        $request->validate([
            'name' => ['required'],
            'author_id' => ['required', 'integer']
        ]);

        Label::create($request->post());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function show(LabelFilter $filters): \Illuminate\Database\Eloquent\Collection
    {
        return Label::filter($filters)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function sync(Request $request, int $id): void
    {
        $label = Label::find($id);
        if (!$label) {
            throw new ErrorException('Label is NOT exits');
        }
        $label->projects()->sync($request->post());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id): void
    {
        $label = Label::find($id);
        $label->projects()->detach();
        $label->destroy($id);
    }
}


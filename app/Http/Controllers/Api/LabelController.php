<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Label\LabelRequest;
use App\Http\Resources\LabelResource;
use App\Models\Label;
use App\Filters\LabelFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Psy\Exception\ErrorException;

class LabelController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param LabelRequest $request
     */
    public function store(LabelRequest $request): void
    {
        Label::create($request->post());
    }

    /**
     * Display the specified resource.
     *
     * @param LabelFilter $filters
     * @return AnonymousResourceCollection
     */
    public function show(LabelFilter $filters): AnonymousResourceCollection
    {
        return LabelResource::collection(Label::filter($filters)->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Label $label
     */
    public function sync(Request $request, Label $label): void
    {
        $label->projects()->sync($request->post());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Label $label
     */
    public function destroy(Label $label): void
    {
        if ($label->author_id !== request()->user()->id) {
            throw new ErrorException('You are not the author');
        }

        $label->projects()->detach();
        $label->delete();
    }
}


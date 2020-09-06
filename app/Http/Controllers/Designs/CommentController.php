<?php

namespace App\Http\Controllers\Designs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IDesign;

class CommentController extends Controller
{
    protected $comments;
    protected $designs;

    public function __construct(IComment $comment, IDesign $designs)
    {
        $this->comments = $comment;
        $this->designs = $designs;
    }

    public function store(Request $request, $designId)
    {
        $this->validate($request, [
            'body' => ['required']
        ]);

        $comment = $this->designs->addComment($designId, [
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update', $comment); // Policyのチェック

        $this->validate($request, [
           'body' => ['required']
        ]);

        $comment = $this->comments->update($id, [
            'body' => $request->body
        ]);

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete', $comment); // Policyのチェック

        $this->comments->delete($id);

        return response()->json(['message' => 'Item deleted'], 200);
    }
}

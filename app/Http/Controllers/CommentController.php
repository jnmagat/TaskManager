<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

/**
 * Class CommentController
 *
 * Handles CRUD operations for Task comments:
 *   - Storing new comments
 *   - Showing the edit form
 *   - Updating existing comments
 *   - Deleting comments
 */
class CommentController extends Controller
{
    /**
     * Insert a new comment for a given task.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @param  \App\Models\Task          $task     The task to which the comment belongs
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $task->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $data['comment'],
            ]);

            return redirect()
                ->route('tasks.show', $task)
                ->with('success', 'Comment posted.');
        } catch (QueryException $e) {
            \Log::error('Comment store DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['comment' => 'Failed to post comment.'])
                ->onlyInput('comment');
        }
    }

    /**
     * Show the form for editing an existing comment.
     *
     * @param  \App\Models\Task      $task     The task to which the comment belongs
     * @param  \App\Models\Comment   $comment  The comment to edit
     * @return \Illuminate\View\View
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Task $task, Comment $comment): View
    {
        // Ensure the comment actually belongs to this task
        if ($comment->task_id !== $task->id) {
            abort(404);
        }

        // Authorize user action
        $this->authorize('update', $comment);

        return view('tasks.comments.edit', [
            'task'    => $task,
            'comment' => $comment,
        ]);
    }

    /**
     * Update an existing comment.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @param  \App\Models\Task          $task     The task to which the comment belongs
     * @param  \App\Models\Comment       $comment  The comment to update
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Task $task, Comment $comment): RedirectResponse
    {
        // Ensure the comment actually belongs to this task
        if ($comment->task_id !== $task->id) {
            abort(404);
        }

        // Authorize user action
        $this->authorize('update', $comment);

        $data = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $comment->update(['comment' => $data['comment']]);

            return redirect()
                ->route('tasks.show', $task)
                ->with('success', 'Comment updated.');
        } catch (QueryException $e) {
            \Log::error('Comment update DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['comment' => 'Failed to update comment.'])
                ->onlyInput('comment');
        }
    }

    /**
     * Remove a comment from storage.
     *
     * @param  \App\Models\Task      $task     The task to which the comment belongs
     * @param  \App\Models\Comment   $comment  The comment to delete
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Task $task, Comment $comment): RedirectResponse
    {
        // Ensure the comment actually belongs to this task
        if ($comment->task_id !== $task->id) {
            abort(404);
        }

        // Authorize user action
        $this->authorize('delete', $comment);

        try {
            $comment->delete();

            return redirect()
                ->route('tasks.show', $task)
                ->with('success', 'Comment deleted.');
        } catch (\Throwable $e) {
            \Log::error('Comment delete error: ' . $e->getMessage());

            return redirect()
                ->route('tasks.show', $task)
                ->withErrors(['comment' => 'Could not delete comment.']);
        }
    }
}

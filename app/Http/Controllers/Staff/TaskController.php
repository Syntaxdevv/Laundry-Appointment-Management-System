<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private function authorizeStaff(): void
    {
        abort_unless(Auth::user()?->role === 'staff', 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $tasks = Task::where('assigned_to', Auth::id())
            ->with('createdBy')
            ->latest()
            ->paginate(10);

        return view('staff.tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorizeStaff();

        abort_unless($task->assigned_to === Auth::id(), 403);

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'in_progress', 'completed']),
            ],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('staff.tasks.index')
            ->with('success', 'Task status successfully updated.');
    }
}

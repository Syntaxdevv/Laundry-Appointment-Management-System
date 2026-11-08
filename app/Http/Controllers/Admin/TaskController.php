<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        $tasks = Task::with(['assignedStaff', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        return view('admin.tasks.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'staff'),
            ],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date'],
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        Task::create($validated);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Task successfully assigned to staff.');
    }

    public function edit(Task $task)
    {
        $this->authorizeAdmin();

        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get();

        return view('admin.tasks.edit', compact('task', 'staff'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'staff'),
            ],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in([
                'pending',
                'in_progress',
                'completed',
            ])],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Task successfully updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeAdmin();

        $task->delete();

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Task successfully deleted.');
    }
}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Exams</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('instructor.exams.create') }}" class="btn btn-primary">Create Exam</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Band</th>
                    <th>Published</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($exams as $exam)
                    <tr>
                        <td>{{ $exam->title }}</td>
                        <td>{{ $exam->type }}</td>
                        <td>{{ $exam->band }}</td>
                        <td>{{ $exam->published ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('instructor.exams.edit', $exam) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('instructor.exams.destroy', $exam) }}" method="POST"
                                style="display:inline-block;" onsubmit="return confirm('Delete this exam?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $exams->links() }}
    </div>
@endsection
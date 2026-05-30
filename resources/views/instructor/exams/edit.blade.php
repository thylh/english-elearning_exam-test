@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Exam</h1>

        <form action="{{ route('instructor.exams.update', $exam) }}" method="POST">
            @method('PUT')
            @include('instructor.exams.form')
        </form>

    </div>
@endsection
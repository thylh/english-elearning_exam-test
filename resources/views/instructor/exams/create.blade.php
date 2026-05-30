@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Exam</h1>

        <form action="{{ route('instructor.exams.store') }}" method="POST">
            @include('instructor.exams.form')
        </form>

    </div>
@endsection
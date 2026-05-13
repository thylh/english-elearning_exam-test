@extends('layouts.app')

@section('content')

    <div class="card">

        <div class="card-body">

            <h3>
                Welcome {{ auth()->user()->name }}
            </h3>

            <p>
                Role: {{ auth()->user()->role }}
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="btn btn-danger">
                    Logout
                </button>
            </form>

        </div>

    </div>

@endsection
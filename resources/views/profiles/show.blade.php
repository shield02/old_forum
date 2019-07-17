@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-4">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                    </h1>
                </div>
            
                @foreach ($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>
                    
                    @foreach ($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @endforeach
        
            </div>
        </div>
    </div>
@endsection
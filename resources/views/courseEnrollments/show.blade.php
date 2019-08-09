<?php
/**
 * @var \App\CourseEnrollment $enrollment
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h2 class="card-header">Lessons</h2>
                    <div class="card-body">
                        <ol>
                            @foreach($enrollment->course->lessons as $lesson)
                                <li>
                                    <a href="{{ route('lessons.show', ['slug' => $enrollment->course->slug, 'number' => $lesson->number]) }}">
                                        {{ $lesson->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="card mt-4">
                    <h2 class="card-header">Statistics</h2>
                    <div class="card-body">

                        <p>
                            Your rankings improve every time you answer a question correctly.
                            Keep learning and earning course points to become one of our top learners!
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                {{--Replace this stub markup by your code--}}
                                <ul style="padding: 0px;">
                                    @foreach ($leaderboard['list'] as $key => $userList)
                                        <li class="courseRanking__rankItem"
                                            style="display: flex; flex-direction: row; padding: 10px;">
                                            <div class="position"
                                                 style="font-size: 28px; color: rgb(132, 132, 132); text-align: right; width: 80px; padding-right: 10px;">
                                                {{ $key+1 }}
                                            </div>
                                            <div class="info">
                                                <div style="font-size: 16px;">
                                                    @if($userList->userid === auth()->user()->id)
                                                        <strong>{{ $userList->name }}</strong>
                                                    @else()
                                                        {{ $userList->name }}
                                                    @endif
                                                </div>
                                                <div class="score" style="font-size: 10px; color: rgb(132, 132, 132);">
                                                    {{ $userList->score }} PTS
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <br>
                                    @foreach ($leaderboard['highest'] as $key => $userList)
                                        <li class="courseRanking__rankItem"
                                            style="display: flex; flex-direction: row; padding: 10px;">
                                            <div class="position"
                                                 style="font-size: 28px; color: rgb(132, 132, 132); text-align: right; width: 80px; padding-right: 10px;">
                                                {{ $key+1 }}
                                            </div>
                                            <div class="info">
                                                <div style="font-size: 16px;">
                                                    @if($userList->userid === auth()->user()->id)
                                                        <strong>{{ $userList->name }}</strong>
                                                    @else()
                                                        {{ $userList->name }}
                                                    @endif
                                                </div>
                                                <div class="score" style="font-size: 10px; color: rgb(132, 132, 132);">
                                                    {{ $userList->score }} PTS
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <br>
                                    @foreach ($leaderboard['lowest'] as $key => $userList)
                                        <li class="courseRanking__rankItem"
                                            style="display: flex; flex-direction: row; padding: 10px;">
                                            <div class="position"
                                                 style="font-size: 28px; color: rgb(132, 132, 132); text-align: right; width: 80px; padding-right: 10px;">
                                                {{ $userList->position }}
                                            </div>
                                            <div class="info">
                                                <div style="font-size: 16px;">
                                                    @if($userList->userid === auth()->user()->id)
                                                        <strong>{{ $userList->name }}</strong>
                                                    @else()
                                                        {{ $userList->name }}
                                                    @endif
                                                </div>
                                                <div class="score" style="font-size: 10px; color: rgb(132, 132, 132);">
                                                    {{ $userList->score }} PTS
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <br>
                                    @foreach ($leaderboard['neighbours'] as $key => $userList)
                                        <li class="courseRanking__rankItem"
                                            style="display: flex; flex-direction: row; padding: 10px;">
                                            <div class="position"
                                                 style="font-size: 28px; color: rgb(132, 132, 132); text-align: right; width: 80px; padding-right: 10px;">
                                                {{ $userList->position }}
                                            </div>
                                            <div class="info">
                                                <div style="font-size: 16px;">
                                                    @if($userList->userid === auth()->user()->id)
                                                        <strong>{{ $userList->name }}</strong>
                                                    @else()
                                                        {{ $userList->name }}
                                                    @endif
                                                </div>
                                                <div class="score" style="font-size: 10px; color: rgb(132, 132, 132);">
                                                    {{ $userList->score }} PTS
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

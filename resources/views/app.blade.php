{{-- resources/views/app.blade.php --}}
@extends('layouts.main')

@section('content')
    {{-- Main app container: semua partial halaman dimasukkan di sini --}}
    <div class="container">

        {{-- LOGIN PAGE --}}
        @include('pages.login')

        {{-- ADMIN PAGES --}}
        @include('pages.admin.dashboard')
        @include('pages.admin.books')
        @include('pages.admin.students')
        @include('pages.admin.transactions')
        @include('pages.admin.reports')

        {{-- STUDENT PAGES --}}
        @include('pages.student.browse')
        @include('pages.student.history')

        {{-- KEPALA PAGES --}}
        @include('pages.kepala.statistics')
        @include('pages.kepala.monthlyreport')

    </div>

    {{-- Push modal partials ke stack so they render at end of body --}}
    @stack('modals')

@endsection

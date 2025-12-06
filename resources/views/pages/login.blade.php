@extends('layouts.app')

@section('content')

<div class="w-full min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white shadow-2xl rounded-3xl p-10 w-full max-w-md">

        <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">
            Login Sistem Perpustakaan
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.web') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan email" required>
            </div>

            <div class="relative">
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan password" required>
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                </button>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg shadow-lg hover:scale-105 transform transition font-semibold">
                Login
            </button>

        </form>

    </div>

</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }
</script>

@endsection

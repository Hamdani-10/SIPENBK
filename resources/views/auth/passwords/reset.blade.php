<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - SIPENBK</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4 text-center">Atur Ulang Password</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                @error('email') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-bold mb-1">Password Baru</label>
                <input type="password" id="password" name="password" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                @error('password') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-bold mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>


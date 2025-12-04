<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SIPENBK</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4 text-center">Reset Password</h2>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-bold mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{old('email') ?? $email ?? ''}}" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                    Kirim Link Reset
                </button>
            </form>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Baca artikel, tips, dan informasi terbaru seputar manajemen RT/RW digital dan pemanfaatan aplikasi SmartRT Vision.">
    
    <title>Blog & Artikel — {{ config('app.name', 'SmartRT Vision') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Blog & Artikel — {{ config('app.name', 'SmartRT Vision') }}">
    <meta property="og:description" content="Kumpulan artikel dan tips terbaik untuk pengurus RT/RW modern.">
    <meta property="og:image" content="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; color: #0f172a; }
    </style>
</head>
<body>

    <!-- Header Sederhana -->
    <header class="bg-white border-b border-slate-100 py-4 px-6 md:px-12 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('marketing.home') }}" class="flex items-center gap-2 text-xl font-black tracking-tight text-indigo-600">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8 rounded-lg">
                SmartRT
            </a>
            <nav class="flex gap-6">
                <a href="{{ route('marketing.home') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Beranda</a>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Login</a>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-12 lg:py-20">
        <div class="text-center mb-16">
            <h1 class="text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">Blog & Artikel</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Temukan tips, trik, dan wawasan terbaru seputar digitalisasi rukun tetangga dan pemanfaatan aplikasi SmartRT Vision.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="aspect-video w-full overflow-hidden bg-slate-100">
                        @if($article->cover_image)
                            <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs font-semibold text-slate-500 mb-3">
                            <span>{{ $article->published_at->format('d M Y') }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>{{ $article->author->name ?? 'Tim SmartRT' }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-indigo-600 transition-colors leading-snug">{{ $article->title }}</h2>
                        <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Artikel</h3>
                    <p class="text-slate-500">Artikel akan segera tersedia di halaman ini.</p>
                </div>
            @endforelse
        </div>

        @if($articles->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $articles->links() }}
            </div>
        @endif
    </main>

    <footer class="bg-white border-t border-slate-100 py-8 text-center text-sm text-slate-500 mt-20">
        &copy; {{ date('Y') }} Sekawan Putra Pratama. All rights reserved.
    </footer>
</body>
</html>

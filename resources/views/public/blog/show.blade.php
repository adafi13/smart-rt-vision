<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $article->meta_description ?? $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <title>{{ $article->meta_title ?? $article->title }} — {{ config('app.name', 'SmartRT Vision') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}">
    <meta property="og:image" content="{{ $article->cover_image ? asset('storage/'.$article->cover_image) : asset('logo.png') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $article->title }}">
    <meta name="twitter:description" content="{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}">
    <meta name="twitter:image" content="{{ $article->cover_image ? asset('storage/'.$article->cover_image) : asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background: #fdfdfc; color: #1b1b18; }
        
        /* Typography for Rich Text Content */
        .prose-content { font-size: 1.125rem; line-height: 1.8; color: #334155; }
        .prose-content h2 { font-size: 1.875rem; font-weight: 800; color: #0f172a; margin-top: 2em; margin-bottom: 0.75em; letter-spacing: -0.025em; }
        .prose-content h3 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-top: 1.5em; margin-bottom: 0.75em; }
        .prose-content p { margin-bottom: 1.25em; }
        .prose-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.25em; }
        .prose-content ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1.25em; }
        .prose-content li { margin-bottom: 0.5em; }
        .prose-content a { color: #4f46e5; text-decoration: underline; font-weight: 500; }
        .prose-content img { border-radius: 0.75rem; max-width: 100%; height: auto; margin: 2em 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .prose-content blockquote { border-left: 4px solid #4f46e5; padding-left: 1em; font-style: italic; color: #475569; background: #f8fafc; padding: 1em; rounded: 0.5rem; margin: 1.5em 0; }
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
            <nav class="flex items-center gap-6">
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors hidden sm:block">Semua Artikel</a>
                <a href="{{ route('marketing.home') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Beranda</a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-bold rounded-lg hover:bg-indigo-100 transition-colors">Login</a>
            </nav>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-12 lg:py-16">
        
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Blog
        </a>

        <header class="mb-12">
            <h1 class="text-4xl lg:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">{{ $article->title }}</h1>
            
            <div class="flex items-center gap-4 text-sm font-medium text-slate-500 pb-8 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        {{ substr($article->author->name ?? 'S', 0, 1) }}
                    </div>
                    <span class="text-slate-800">{{ $article->author->name ?? 'Tim SmartRT' }}</span>
                </div>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                <span>{{ $article->published_at->format('d M Y') }}</span>
            </div>
        </header>

        @if($article->cover_image)
            <div class="aspect-video w-full rounded-2xl overflow-hidden mb-12 shadow-sm border border-slate-100">
                <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <article class="prose-content">
            {!! $article->content !!}
        </article>

        <div class="mt-16 pt-8 border-t border-slate-100 flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-500">Bagikan artikel ini:</p>
            <div class="flex gap-2">
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-[#1DA1F2] hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-[#25D366] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-slate-100 py-8 text-center text-sm text-slate-500 mt-20">
        &copy; {{ date('Y') }} Sekawan Putra Pratama. All rights reserved.
    </footer>
</body>
</html>

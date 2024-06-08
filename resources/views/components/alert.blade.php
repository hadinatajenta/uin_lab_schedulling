@if (session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 wedusbg-gray-800 wedustext-green-400"
        role="alert">
        <span class="font-medium">Success alert!</span> {{ session('success') }}
    </div>
@elseif(session('error'))
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 wedusbg-gray-800 wedustext-red-400" role="alert">
        <span class="font-medium">Danger alert!</span> {{ session('error') }}
    </div>
@endif

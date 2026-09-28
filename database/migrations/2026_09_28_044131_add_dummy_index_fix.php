<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $path = resource_path('views/data-sk/index.blade.php');
        $content = file_get_contents($path);
        
        $target = '<a href="{{ route(\'data-sk.create\') }}" class="btn btn-primary" title="Tambah">';
        $replacement = '<div class="dropdown me-2">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPembaruanSk" data-bs-toggle="dropdown" aria-expanded="false" title="Pembaruan SK">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownPembaruanSk">
                                    <li>
                                        <a class="dropdown-item" href="{{ route(\'data-sk.pembaruan\') }}">
                                            <i class="bi bi-calendar-check me-2"></i> Pembaruan SK
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route(\'data-sk.create\') }}" class="btn btn-primary" title="Tambah">';
                            
        if (str_contains($content, $target) && !str_contains($content, 'dropdownPembaruanSk')) {
            $content = str_replace($target, $replacement, $content);
            file_put_contents($path, $content);
        }
    }

    public function down(): void
    {
        //
    }
};

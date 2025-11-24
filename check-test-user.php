<?php

/**
 * Script untuk mengecek dan membuat user test pelapor
 * Jalankan dengan: php check-test-user.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Hash;

echo "================================\n";
echo "Checking Test User for Pelapor\n";
echo "================================\n\n";

// Check if Pelapor level exists
echo "1. Checking Level Pelapor...\n";
$levelPelapor = Level::where('id_level', 4)->orWhere('nama_level', 'Pelapor')->first();

if (!$levelPelapor) {
    echo "   ❌ Level Pelapor not found!\n";
    echo "   Creating Level Pelapor...\n";

    $levelPelapor = Level::create([
        'id_level' => 4,
        'nama_level' => 'Pelapor'
    ]);

    echo "   ✅ Level Pelapor created with ID: {$levelPelapor->id_level}\n\n";
} else {
    echo "   ✅ Level Pelapor exists with ID: {$levelPelapor->id_level}\n\n";
}

// Check if test user exists
echo "2. Checking User pelapor@jti.com...\n";
$user = User::where('email', 'pelapor@jti.com')->first();

if (!$user) {
    echo "   ❌ User not found!\n";
    echo "   Creating test user...\n";

    $user = User::create([
        'nama' => 'Pelapor Test',
        'email' => 'pelapor@jti.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'id_level' => $levelPelapor->id_level
    ]);

    echo "   ✅ User created successfully!\n\n";
} else {
    echo "   ✅ User exists!\n";
    echo "   User ID: {$user->id}\n";
    echo "   Name: {$user->nama}\n";
    echo "   Email: {$user->email}\n";
    echo "   Level ID: {$user->id_level}\n";

    // Update password to make sure it's correct
    $user->password = Hash::make('password');
    $user->id_level = $levelPelapor->id_level;
    $user->save();

    echo "   ✅ Password updated to: password\n\n";
}

// Summary
echo "================================\n";
echo "Summary\n";
echo "================================\n";
echo "Email: pelapor@jti.com\n";
echo "Password: password\n";
echo "Level: {$levelPelapor->nama_level} (ID: {$levelPelapor->id_level})\n";
echo "\n✅ Ready for E2E testing!\n";
echo "================================\n";

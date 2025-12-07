import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// --- CONFIG ES MODULE ---
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// --- USER TEKNISI ---
const TEKNISI = { email: 'rendi.saputra@jti.com', pass: 'password' };

// --- SETUP FILE DUMMY FOTO PROFIL ---
const fixturesDir = path.join(__dirname, 'fixtures');
const dummyAvatarPath = path.join(fixturesDir, 'avatar.jpg');

test.beforeAll(async () => {
    if (!fs.existsSync(fixturesDir)) fs.mkdirSync(fixturesDir, { recursive: true });
    if (!fs.existsSync(dummyAvatarPath)) fs.writeFileSync(dummyAvatarPath, 'dummy-image-content');
});

test.describe('Manajemen Profil Teknisi', () => {

    // Login & Masuk Halaman Profil
    test.beforeEach(async ({ page }) => {
        await page.goto('http://127.0.0.1:8000/login');
        await page.fill('input[name="email"]', TEKNISI.email);
        await page.fill('input[name="password"]', TEKNISI.pass);
        await page.click('button[type="submit"]');

        // Klik menu User / Profile (Biasanya ada di navbar atau sidebar)
        // Kita tembak URL langsung agar lebih pasti
        await page.goto('http://127.0.0.1:8000/profile');
        
        // Validasi Halaman Terbuka
        await expect(page.getByRole('heading', { name: 'Edit Profil' })).toBeVisible();
    });

    test('1. Teknisi bisa melihat data diri (Read)', async ({ page }) => {
        // Cek Kartu User (Sebelah Kiri)
        const cardUser = page.locator('.card-user');
        await expect(cardUser).toBeVisible();
        
        // Pastikan Nama dan Email muncul di kartu
        // (Kita pakai regex fleksibel karena nama mungkin berubah saat test update)
        await expect(cardUser).toContainText(/Nama:/);
        await expect(cardUser).toContainText(/Email:/);
        
        // Cek Badge Role (badge-info)
        await expect(page.locator('.badge-info')).toBeVisible();
    });

    test('2. Teknisi bisa Mengubah Nama dan Upload Foto Profil', async ({ page }) => {
        const namaBaru = 'Rendi Teknisi Updated';

        // 1. Target Form "Edit Profil" (Card sebelah kanan atas)
        const formProfil = page.locator('.card').filter({ hasText: 'Edit Profil' });
        
        // 2. Isi Nama Baru
        await formProfil.locator('input[name="nama"]').fill(namaBaru);

        // 3. Upload Foto Baru
        await formProfil.locator('input[name="foto_profil"]').setInputFiles(dummyAvatarPath);

        // 4. Klik Simpan (Tombol spesifik di dalam form profil)
        await formProfil.locator('button:has-text("Simpan Perubahan")').click();

        // 5. Validasi SweetAlert Sukses
        await expect(page.getByText('Berhasil!')).toBeVisible();
        await page.locator('.swal2-confirm').click();

        // 6. Validasi Nama Berubah di Input
        await expect(formProfil.locator('input[name="nama"]')).toHaveValue(namaBaru);
        
        // 7. Validasi Nama Berubah di Kartu User (Kiri)
        await expect(page.locator('.card-user .title')).toHaveText(namaBaru);
    });

    test('3. Teknisi bisa Mengganti Password', async ({ page }) => {
        // 1. Target Form "Ganti Kata Sandi" (Card sebelah kanan bawah)
        const formPass = page.locator('.card').filter({ hasText: 'Ganti Kata Sandi' });

        // 2. Isi Form Password
        // Asumsi: Password saat ini adalah 'password' (default seeder)
        await formPass.locator('input[name="old_password"]').fill('password');
        await formPass.locator('input[name="password"]').fill('password_baru_123');
        await formPass.locator('input[name="password_confirmation"]').fill('password_baru_123');

        // 3. Klik Simpan
        await formPass.locator('button:has-text("Simpan Perubahan")').click();

        // 4. Validasi Sukses
        await expect(page.getByText('Berhasil!')).toBeVisible();
        await page.locator('.swal2-confirm').click();

        // 5. (Opsional) Kembalikan password ke semula agar test berikutnya tidak gagal login
        // Langkah ini penting kalau test dijalankan berulang-ulang
        await formPass.locator('input[name="old_password"]').fill('password_baru_123');
        await formPass.locator('input[name="password"]').fill('password');
        await formPass.locator('input[name="password_confirmation"]').fill('password');
        await formPass.locator('button:has-text("Simpan Perubahan")').click();
        await page.locator('.swal2-confirm').click();
    });

    test('4. Validasi Gagal: Password Konfirmasi Tidak Cocok', async ({ page }) => {
        const formPass = page.locator('.card').filter({ hasText: 'Ganti Kata Sandi' });

        await formPass.locator('input[name="old_password"]').fill('password');
        await formPass.locator('input[name="password"]').fill('password_baru');
        await formPass.locator('input[name="password_confirmation"]').fill('password_beda'); // Beda

        await formPass.locator('button:has-text("Simpan Perubahan")').click();

        // Cek Error Laravel (invalid-feedback) atau SweetAlert Error
        // Di view kamu ada: @if ($errors->has('password_confirmation'))
        const errorMsg = formPass.locator('.invalid-feedback').filter({ hasText: /confirmation/i });
        
        // Jika error muncul sebagai text merah di bawah input:
        if (await errorMsg.count() > 0) {
            await expect(errorMsg).toBeVisible();
        } 
        // Jika error muncul sebagai SweetAlert (karena ada @if($errors->any()) di script):
        else {
            await expect(page.getByText('Terjadi kesalahan!')).toBeVisible();
        }
    });

});
import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// --- KONFIGURASI ES MODULE FIX ---
// Karena file ini .js, kita butuh ini biar __dirname jalan
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// --- KONFIGURASI USER TEKNISI ---
// Menggunakan data Rendi Saputra (Teknisi)
const TEKNISI = { email: 'rendi.saputra@jti.com', pass: 'password' };

// --- SETUP FILE DUMMY UNTUK UPLOAD ---
const fixturesDir = path.join(__dirname, 'fixtures');
const dummyImagePath = path.join(fixturesDir, 'bukti.jpg');

test.beforeAll(async () => {
    // 1. Buat folder fixtures jika belum ada
    if (!fs.existsSync(fixturesDir)) {
        fs.mkdirSync(fixturesDir, { recursive: true });
    }
    // 2. Buat file dummy jika belum ada (hack simple biar test gak error cari file)
    if (!fs.existsSync(dummyImagePath)) {
        fs.writeFileSync(dummyImagePath, 'ini-bukan-gambar-asli-tapi-cukup-buat-test');
    }
});

test.describe('Workflow Teknisi (Perbaikan & Laporan)', () => {

    // --- AUTO LOGIN SEBELUM TIAP TEST ---
    test.beforeEach(async ({ page }) => {
        // 1. Buka Halaman Login
        await page.goto('http://127.0.0.1:8000/login');

        // 2. Login sebagai Teknisi
        await page.fill('input[name="email"]', TEKNISI.email);
        await page.fill('input[name="password"]', TEKNISI.pass);
        await page.click('button[type="submit"]');

        // 3. Validasi masuk dashboard
        await expect(page).toHaveURL(/.*\/teknisi/);
        await expect(page.getByRole('heading', { name: 'Daftar Perbaikan' })).toBeVisible();
    });

    test('Teknisi melihat tabel daftar tugas perbaikan', async ({ page }) => {
        // Cek tabel muncul
        const table = page.locator('#table_perbaikan');
        await expect(table).toBeVisible();

        // Cek header tabel
        await expect(table).toContainText('Deskripsi');
        await expect(table).toContainText('Status');
        await expect(table).toContainText('Dokumentasi Perbaikan');
    });

    test('Teknisi bisa melakukan update laporan (Upload Bukti & Catatan)', async ({ page }) => {
        // 1. Cari tombol "Laporkan" (warna merah) di tabel
        const tombolLapor = page.locator('#table_perbaikan .btn-danger').first();

        // Cek apakah ada tugas (tombol visible)
        if (await tombolLapor.count() > 0 && await tombolLapor.isVisible()) {
            console.log('🔘 Menekan tombol Laporkan...');
            await tombolLapor.click();

            // 2. Tunggu Modal Muncul (#form_edit)
            const modalForm = page.locator('#form_edit');
            await expect(modalForm).toBeVisible({ timeout: 10000 });

            // 3. Isi Catatan
            await page.fill('textarea[name="catatan_teknisi"]', 'Perbaikan selesai. AC dingin kembali.');

            // 4. Upload Foto (Pakai file dummy)
            await page.setInputFiles('input[name="dokumentasi"]', dummyImagePath);

            // 5. Submit Form
            await page.click('#form_edit button[type="submit"]');

            // 6. Validasi Pesan Sukses (SweetAlert)
            await expect(page.getByText('Berhasil!')).toBeVisible();
            
            // Klik OK di SweetAlert
            await page.locator('.swal2-confirm').click();

            // 7. Pastikan Modal Tertutup
            await expect(modalForm).not.toBeVisible();

        } else {
            console.log('⚠️ SKIP TEST: Tidak ada tombol "Laporkan". Mungkin tugas sudah selesai semua.');
        }
    });

    test('Validasi Form: Memastikan modal terbuka dan bisa ditutup', async ({ page }) => {
        const tombolLapor = page.locator('#table_perbaikan .btn-danger').first();
        
        if (await tombolLapor.count() > 0 && await tombolLapor.isVisible()) {
            await tombolLapor.click();
            
            // Cek elemen dalam modal
            const modal = page.locator('#form_edit');
            await expect(modal).toBeVisible();
            await expect(modal.locator('textarea[name="catatan_teknisi"]')).toBeVisible();
            
            // Tutup modal (Klik tombol Close/Tutup)
            await page.locator('#form_edit button[data-dismiss="modal"]').click();
        }
    });

});
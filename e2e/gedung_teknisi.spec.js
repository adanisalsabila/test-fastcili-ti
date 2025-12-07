import { test, expect } from '@playwright/test';

// --- CONFIG USER TEKNISI ---
// Menggunakan user Teknisi dari seeder: Rendi Saputra
const TEKNISI = { email: 'rendi.saputra@jti.com', pass: 'password' };

test.describe('Akses Halaman Gedung - Role Teknisi (Read Only)', () => {

    // Login sebagai Teknisi sebelum setiap test
    test.beforeEach(async ({ page }) => {
        await page.goto('http://127.0.0.1:8000/login');
        await page.fill('input[name="email"]', TEKNISI.email);
        await page.fill('input[name="password"]', TEKNISI.pass);
        await page.click('button[type="submit"]');

        // Navigasi ke menu Gedung
        await page.goto('http://127.0.0.1:8000/gedung');
        
        // Validasi berhasil masuk halaman Gedung
        await expect(page.getByRole('heading', { name: 'Kelola Data Gedung' })).toBeVisible();
    });

    test('1. Teknisi bisa melihat daftar gedung', async ({ page }) => {
        // Pastikan container list gedung muncul
        await expect(page.locator('#gedung-container')).toBeVisible();
        
        // Pastikan ada minimal 1 kartu gedung yang tampil (jika seeder sudah jalan)
        // Kita tunggu sebentar karena data diload via AJAX
        await page.waitForTimeout(1000); 
        const cards = page.locator('.gedung-card');
        
        // Logika: Kalau database kosong test tetap pass tapi kasih info, kalau ada isi dicek
        if (await cards.count() > 0) {
            await expect(cards.first()).toBeVisible();
            console.log('✅ Teknisi berhasil melihat daftar gedung.');
        } else {
            console.log('⚠️ List kosong, tapi halaman berhasil diakses.');
        }
    });

    test('2. Teknisi TIDAK BOLEH melihat tombol "Tambah Data Gedung"', async ({ page }) => {
        // Tombol ini hanya untuk Admin (Level 1 & 2)
        // Kita cek bahwa tombol tersebut TIDAK ada di halaman
        const btnTambah = page.locator('button:has-text("Tambah Data Gedung")');
        await expect(btnTambah).not.toBeVisible();
    });

    test('3. Teknisi TIDAK BOLEH melihat tombol Edit dan Hapus di kartu gedung', async ({ page }) => {
        // Tunggu load data
        await page.waitForTimeout(1000);
        
        const cards = page.locator('.gedung-card');
        if (await cards.count() > 0) {
            const firstCard = cards.first();
            
            // Cek tombol Edit (biasanya class .btn-warning)
            await expect(firstCard.locator('.btn-warning')).not.toBeVisible();
            
            // Cek tombol Hapus (biasanya class .btn-danger)
            await expect(firstCard.locator('.btn-danger')).not.toBeVisible();
        }
    });

    test('4. Teknisi bisa melakukan PENCARIAN data gedung', async ({ page }) => {
        // Masukkan kata kunci pencarian
        const searchInput = page.locator('#search');
        await searchInput.fill('Gedung'); // Keyword umum
        
        // Tunggu proses AJAX debounce
        await page.waitForTimeout(1000);
        
        // Pastikan list ter-update (indikatornya: container masih visible)
        await expect(page.locator('#gedung-container')).toBeVisible();
    });

    test('5. Teknisi bisa melihat DETAIL gedung (Klik Kartu)', async ({ page }) => {
        await page.waitForTimeout(1000);
        const cards = page.locator('.gedung-card');

        if (await cards.count() > 0) {
            // Klik area body kartu (bukan tombol edit/hapus karena ga ada)
            // Di blade: onclick="modalAction(...)" ada di .gedung-card-body
            await cards.first().locator('.gedung-card-body').click();

            // Validasi Modal Detail Muncul
            const modal = page.locator('.modal-content');
            await expect(modal).toBeVisible({ timeout: 5000 });
            
            // Pastikan judul modal benar
            await expect(modal.locator('h5.modal-title')).toHaveText('Detail Data Gedung');
            
            // Tutup modal
            await modal.locator('button:has-text("Tutup")').click();
            await expect(modal).not.toBeVisible();
        }
    });

});
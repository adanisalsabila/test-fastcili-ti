import { test, expect } from '@playwright/test';

// --- CONFIG USER TEKNISI ---
// Menggunakan user Teknisi dari seeder: Rendi Saputra
const TEKNISI = { email: 'rendi.saputra@jti.com', pass: 'password' };

test.describe('Akses Halaman Ruangan - Role Teknisi (Read Only)', () => {

    // Login sebagai Teknisi sebelum setiap test
    test.beforeEach(async ({ page }) => {
        await page.goto('http://127.0.0.1:8000/login');
        await page.fill('input[name="email"]', TEKNISI.email);
        await page.fill('input[name="password"]', TEKNISI.pass);
        await page.click('button[type="submit"]');

        // Navigasi ke menu Ruangan
        await page.goto('http://127.0.0.1:8000/ruangan');
        
        // Validasi berhasil masuk halaman Ruangan
        await expect(page.getByRole('heading', { name: 'Kelola Data Ruangan' })).toBeVisible();
    });

    test('1. Teknisi bisa melihat daftar ruangan (Read)', async ({ page }) => {
        // Pastikan container ruangan muncul
        await expect(page.locator('#ruangan-container')).toBeVisible();
        
        // Tunggu sebentar karena data diload via AJAX
        await page.waitForTimeout(1000); 
        const cards = page.locator('.ruangan-card');
        
        // Cek apakah kartu ruangan tampil
        if (await cards.count() > 0) {
            await expect(cards.first()).toBeVisible();
            console.log(`✅ Teknisi berhasil melihat ${await cards.count()} kartu ruangan.`);
        } else {
            console.log('⚠️ List kosong (mungkin database belum di-seed), tapi halaman berhasil diakses.');
        }
    });

    test('2. Teknisi TIDAK BOLEH melihat tombol Aksi Admin (Tambah & Import)', async ({ page }) => {
        // Cek tombol "Tambah Data Ruangan" (Harus Hilang)
        const btnTambah = page.locator('button:has-text("Tambah Data Ruangan")');
        await expect(btnTambah).not.toBeVisible();

        // Cek tombol "Impor Data Ruangan" (Harus Hilang)
        const btnImport = page.locator('button:has-text("Impor Data Ruangan")');
        await expect(btnImport).not.toBeVisible();
    });

    test('3. Teknisi TIDAK BOLEH melihat tombol Edit dan Hapus di kartu ruangan', async ({ page }) => {
        await page.waitForTimeout(1000);
        const cards = page.locator('.ruangan-card');

        if (await cards.count() > 0) {
            const firstCard = cards.first();
            
            // Cek tombol Edit (class .btn-warning)
            await expect(firstCard.locator('.btn-warning')).not.toBeVisible();
            
            // Cek tombol Hapus (class .btn-danger)
            await expect(firstCard.locator('.btn-danger')).not.toBeVisible();
        }
    });

    test('4. Teknisi BISA melihat tombol "Lihat Fasilitas"', async ({ page }) => {
        await page.waitForTimeout(1000);
        const cards = page.locator('.ruangan-card');

        if (await cards.count() > 0) {
            const firstCard = cards.first();
            // Tombol ini akses umum, harusnya ada
            const btnLihat = firstCard.locator('a:has-text("Lihat Fasilitas")');
            await expect(btnLihat).toBeVisible();
        }
    });

    test('5. Teknisi bisa menggunakan Fitur Pencarian dan Filter', async ({ page }) => {
        // 1. Cek Input Search
        const searchInput = page.locator('#search');
        await expect(searchInput).toBeVisible();
        await searchInput.fill('Lab'); // Coba cari sesuatu

        // 2. Cek Dropdown Filter Gedung
        const filterGedung = page.locator('#id_gedung');
        await expect(filterGedung).toBeVisible();
        
        // Coba pilih opsi jika ada (selain default)
        const options = await filterGedung.locator('option');
        if (await options.count() > 1) {
            await filterGedung.selectOption({ index: 1 }); // Pilih gedung pertama di list
        }

        // Tunggu AJAX reload
        await page.waitForTimeout(1000);
        
        // Pastikan container masih ada (tidak crash)
        await expect(page.locator('#ruangan-container')).toBeVisible();
    });

    test('6. Navigasi: Klik kartu mengarah ke halaman Fasilitas', async ({ page }) => {
        await page.waitForTimeout(1000);
        const cards = page.locator('.ruangan-card');

        if (await cards.count() > 0) {
            // Klik body kartu (onclick event di blade)
            await cards.first().locator('.ruangan-card-body').click();

            // Validasi URL berpindah ke /fasilitas
            await expect(page).toHaveURL(/.*\/fasilitas/);
        }
    });

});
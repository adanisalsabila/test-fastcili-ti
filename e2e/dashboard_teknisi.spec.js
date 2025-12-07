import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// --- CONFIG ES MODULE ---
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// --- CONFIG USER TEKNISI ---
const TEKNISI = { email: 'rendi.saputra@jti.com', pass: 'password' };

// --- SETUP FILE DUMMY ---
const fixturesDir = path.join(__dirname, 'fixtures');
const dummyImagePath = path.join(fixturesDir, 'bukti.jpg');

test.beforeAll(async () => {
    if (!fs.existsSync(fixturesDir)) fs.mkdirSync(fixturesDir, { recursive: true });
    if (!fs.existsSync(dummyImagePath)) fs.writeFileSync(dummyImagePath, 'dummy-image-content');
});

test.describe('Halaman Dashboard Teknisi', () => {

    // Login & Masuk Dashboard
    test.beforeEach(async ({ page }) => {
        await page.goto('http://127.0.0.1:8000/login');
        await page.fill('input[name="email"]', TEKNISI.email);
        await page.fill('input[name="password"]', TEKNISI.pass);
        await page.click('button[type="submit"]');

        // Validasi masuk ke dashboard teknisi
        // URL biasanya /teknisi sesuai routes yang kamu kirim sebelumnya
        await expect(page).toHaveURL(/.*\/teknisi/);
        
        // Pastikan Jumbotron Selamat Datang muncul
        await expect(page.locator('.jumbotron')).toBeVisible();
        await expect(page.getByText(/Selamat Datang/)).toBeVisible();
    });

    test('1. Memastikan Kartu Statistik dan Grafik Muncul', async ({ page }) => {
        // 1. Cek 4 Kartu Statistik (Total Laporan, Diajukan, Diproses, Selesai)
        const statsCards = page.locator('.card-stats');
        await expect(statsCards).toHaveCount(4);
        
        // Cek Label pada kartu
        await expect(page.getByText('Total Laporan')).toBeVisible();
        await expect(page.getByText('Laporan Sedang Dikerjakan')).toBeVisible();

        // 2. Cek Keberadaan Grafik (Canvas Elements)
        // Chart Penugasan Per Bulan
        await expect(page.locator('#perbaikanPerBulan')).toBeVisible();
        // Chart Penugasan Per Gedung
        await expect(page.locator('#penugasanGedungChart')).toBeVisible();
    });

    test('2. Interaksi Shortcut "Penugasan Perbaikan" (Jika Ada Tugas Aktif)', async ({ page }) => {
        // Mencari tombol "Laporkan" atau "Edit Laporan" di area dashboard
        // Tombol ini hanya muncul jika ada variabel $penugasan di view
        const btnLapor = page.locator('.jumbotron ~ .card').locator('button:has-text("Laporkan"), button:has-text("Edit Laporan")');

        if (await btnLapor.count() > 0 && await btnLapor.isVisible()) {
            console.log('🔘 Tugas aktif ditemukan di dashboard. Menguji tombol Laporkan...');
            
            // 1. Klik Tombol Laporkan
            await btnLapor.click();

            // 2. Validasi Modal Feedback Terbuka
            const modal = page.locator('.modal-dialog');
            await expect(modal).toBeVisible();
            
            // 3. Cek Elemen dalam Modal Feedback
            // Header: Feedback Teknisi
            await expect(modal.locator('.modal-title')).toContainText('Feedback Teknisi');
            
            // Input: Dokumentasi & Catatan
            await expect(modal.locator('input[name="dokumentasi"]')).toBeVisible();
            await expect(modal.locator('input[name="catatan_teknisi"]')).toBeVisible();

            // 4. (Opsional) Coba Simpan Feedback
            await page.fill('input[name="catatan_teknisi"]', 'Laporan cepat dari dashboard.');
            await page.setInputFiles('input[name="dokumentasi"]', dummyImagePath);
            
            await modal.locator('button:has-text("Simpan")').click();

            // 5. Validasi Sukses (SweetAlert)
            await expect(page.getByText('Berhasil!')).toBeVisible();
            await page.locator('.swal2-confirm').click();

        } else {
            console.log('⚠️ Info: Tidak ada kartu "Penugasan Perbaikan" aktif di dashboard saat ini.');
            
            // Jika tidak ada tugas aktif, kita pastikan setidaknya tombol "Lihat Daftar Perbaikan" di Jumbotron bisa diklik
            const btnLihatList = page.locator('a:has-text("Lihat Daftar Perbaikan")');
            await expect(btnLihatList).toBeVisible();
            await btnLihatList.click();
            
            // Pastikan pindah ke halaman list perbaikan
            await expect(page).toHaveURL(/.*\/perbaikan/);
        }
    });

    test('3. Cek Grafik Responsif (Tidak Crash)', async ({ page }) => {
        // Kita tunggu sebentar untuk memastikan Chart.js selesai render
        await page.waitForTimeout(1000);
        
        // Pastikan canvas memiliki ukuran (artinya chart ter-render)
        const chart1 = page.locator('#perbaikanPerBulan');
        const box = await chart1.boundingBox();
        expect(box.width).toBeGreaterThan(0);
        expect(box.height).toBeGreaterThan(0);
    });

});
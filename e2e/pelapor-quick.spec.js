// @ts-check
import { test, expect } from "@playwright/test";
import { loginAsPelapor, logout } from "./helpers/auth.helper.js";
import {
    fillLaporanForm,
    fillRatingForm,
    submitForm,
} from "./helpers/form.helper.js";

/**
 * E2E Test untuk Role Pelapor
 * Menggunakan helper functions untuk kode yang lebih clean
 */

const BASE_URL = "http://127.0.0.1:8000";

// Setup: Login sebelum setiap test
test.beforeEach(async ({ page }) => {
    await loginAsPelapor(page, BASE_URL);
});

test.describe("Pelapor - Quick Tests", () => {
    test("Login dan akses dashboard", async ({ page }) => {
        await expect(page).toHaveURL(/.*pelapor/);
        await expect(
            page.locator('h2:has-text("Selamat Datang")')
        ).toBeVisible();
    });

    test("Membuat laporan dengan helper", async ({ page }) => {
        await page.goto(`${BASE_URL}/pelapor/create`);

        await fillLaporanForm(page, {
            gedungIndex: 1,
            ruanganIndex: 1,
            fasilitasIndex: 1,
            jumlahKerusakan: "2",
            deskripsi: "Test laporan menggunakan helper function",
            deskripsiTambahan: "Perlu ditangani segera",
        });

        await submitForm(page);

        // Verify redirect
        const url = page.url();
        expect(url).toContain("/pelapor");
    });

    test("Memberikan rating dengan helper", async ({ page }) => {
        const ratingLinks = page.locator(
            'a:has-text("Beri Rating"), a:has-text("Rating")'
        );
        const count = await ratingLinks.count();

        if (count > 0) {
            await ratingLinks.first().click();
            await page.waitForLoadState("networkidle");

            await fillRatingForm(page, {
                rating: 5,
                feedback: "Sangat puas dengan perbaikan yang dilakukan!",
            });

            await submitForm(page);
        } else {
            console.log("⚠ Tidak ada laporan yang bisa diberi rating");
        }
    });
});

test.describe("Pelapor - Navigation", () => {
    test("Navigate between pages", async ({ page }) => {
        // Dashboard
        await page.goto(`${BASE_URL}/pelapor`);
        await expect(page).toHaveURL(/.*pelapor/);

        // Create
        await page.goto(`${BASE_URL}/pelapor/create`);
        await expect(page).toHaveURL(/.*create/);

        // Back to dashboard
        await page.goto(`${BASE_URL}/pelapor`);
        await expect(page).toHaveURL(/.*pelapor/);
    });
});

// Cleanup: Logout setelah semua test
test.afterAll(async ({ browser }) => {
    const page = await browser.newPage();
    await loginAsPelapor(page, BASE_URL);
    await logout(page);
    await page.close();
});

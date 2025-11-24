// @ts-check
import { expect } from "@playwright/test";

/**
 * Helper functions untuk operasi form dalam testing
 */

/**
 * Fill laporan kerusakan form
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {Object} data - Data laporan
 * @param {number} [data.gedungIndex=1] - Index gedung to select
 * @param {number} [data.ruanganIndex=1] - Index ruangan to select
 * @param {number} [data.fasilitasIndex=1] - Index fasilitas to select
 * @param {string} [data.jumlahKerusakan='1'] - Jumlah kerusakan
 * @param {string} data.deskripsi - Deskripsi kerusakan
 * @param {string} [data.deskripsiTambahan] - Deskripsi tambahan
 * @param {string} [data.fotoPath] - Path to photo file
 */
export async function fillLaporanForm(page, data) {
    const {
        gedungIndex = 1,
        ruanganIndex = 1,
        fasilitasIndex = 1,
        jumlahKerusakan = "1",
        deskripsi,
        deskripsiTambahan,
        fotoPath,
    } = data;

    // Select gedung
    const gedungSelect = page.locator('select[name="id_gedung"]');
    if (await gedungSelect.isVisible({ timeout: 5000 })) {
        await gedungSelect.selectOption({ index: gedungIndex });
        await page.waitForTimeout(1000); // Wait for cascade load
    }

    // Select ruangan
    const ruanganSelect = page.locator('select[name="id_ruangan"]');
    if (await ruanganSelect.isVisible({ timeout: 5000 })) {
        await ruanganSelect.selectOption({ index: ruanganIndex });
        await page.waitForTimeout(1000); // Wait for cascade load
    }

    // Select fasilitas
    const fasilitasSelect = page.locator('select[name="id_fasilitas"]');
    if (await fasilitasSelect.isVisible({ timeout: 5000 })) {
        await fasilitasSelect.selectOption({ index: fasilitasIndex });
    }

    // Fill jumlah kerusakan
    const jumlahField = page.locator('input[name="jumlah_kerusakan"]');
    if (await jumlahField.isVisible({ timeout: 5000 })) {
        await jumlahField.fill(jumlahKerusakan);
    }

    // Fill deskripsi
    const deskripsiField = page.locator('textarea[name="deskripsi"]');
    if (await deskripsiField.isVisible({ timeout: 5000 })) {
        await deskripsiField.fill(deskripsi);
    }

    // Fill deskripsi tambahan if provided
    if (deskripsiTambahan) {
        const deskripsiTambahanField = page.locator(
            'textarea[name="deskripsi_tambahan"]'
        );
        if (await deskripsiTambahanField.isVisible({ timeout: 5000 })) {
            await deskripsiTambahanField.fill(deskripsiTambahan);
        }
    }

    // Upload photo if provided
    if (fotoPath) {
        const fileInput = page.locator('input[type="file"]');
        if (await fileInput.isVisible({ timeout: 5000 })) {
            await fileInput.setInputFiles(fotoPath);
        }
    }
}

/**
 * Fill rating and feedback form
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {Object} data - Rating data
 * @param {string|number} data.rating - Rating value (1-5)
 * @param {string} data.feedback - Feedback text
 */
export async function fillRatingForm(page, data) {
    const { rating, feedback } = data;

    // Fill rating
    const ratingInput = page.locator('input[name="rating_pengguna"]');
    if (await ratingInput.isVisible({ timeout: 5000 })) {
        await ratingInput.fill(String(rating));
    } else {
        // Try star rating
        const stars = page.locator(
            '.star, input[type="radio"][name="rating_pengguna"]'
        );
        const starCount = await stars.count();
        if (starCount > 0) {
            const numericRating =
                typeof rating === "number"
                    ? rating
                    : parseInt(String(rating), 10) || 1;
            const starIndex = Math.min(
                Math.max(numericRating - 1, 0),
                starCount - 1
            );
            await stars.nth(starIndex).click();
        }
    }

    // Fill feedback
    const feedbackField = page.locator('textarea[name="feedback_pengguna"]');
    if (await feedbackField.isVisible({ timeout: 5000 })) {
        await feedbackField.fill(feedback);
    }
}

/**
 * Submit a form and wait for response
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {number} [timeout=3000] - Timeout to wait after submit
 */
export async function submitForm(page, timeout = 3000) {
    const submitButton = page.locator('button[type="submit"]');
    await submitButton.click();
    await page.waitForTimeout(timeout);
}

/**
 * Check if form has validation errors
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @returns {Promise<boolean>} True if validation errors exist
 */
export async function hasValidationErrors(page) {
    const errorMessages = page.locator(
        ".invalid-feedback, .error-message, .text-danger"
    );
    const count = await errorMessages.count();
    return count > 0;
}

/**
 * Get first validation error message
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @returns {Promise<string|null>} Error message or null
 */
export async function getFirstValidationError(page) {
    const errorMessages = page.locator(
        ".invalid-feedback, .error-message, .text-danger"
    );
    const count = await errorMessages.count();

    if (count > 0) {
        return await errorMessages.first().textContent();
    }

    return null;
}

/**
 * Clear all form fields
 * @param {import('@playwright/test').Page} page - Playwright page object
 */
export async function clearForm(page) {
    // Clear text inputs
    const textInputs = page.locator(
        'input[type="text"], input[type="number"], textarea'
    );
    const count = await textInputs.count();

    for (let i = 0; i < count; i++) {
        await textInputs.nth(i).clear();
    }
}

/**
 * Wait for select dropdown to be populated
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {string} selector - Selector for the select element
 * @param {number} [minOptions=1] - Minimum number of options expected
 * @param {number} [timeout=5000] - Timeout in milliseconds
 */
export async function waitForSelectOptions(
    page,
    selector,
    minOptions = 1,
    timeout = 5000
) {
    const select = page.locator(selector);

    await page.waitForFunction(
        ({ sel, min }) => {
            const element = document.querySelector(sel);
            return (
                element instanceof HTMLSelectElement &&
                element.options.length > min
            );
        },
        { sel: selector, min: minOptions },
        { timeout }
    );
}

/**
 * Handle alert/confirm dialogs
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {boolean} accept - Whether to accept or dismiss the dialog
 * @returns {Promise<string>} Dialog message
 */
export async function handleDialog(page, accept = true) {
    return new Promise((resolve) => {
        page.once("dialog", async (dialog) => {
            const message = dialog.message();
            if (accept) {
                await dialog.accept();
            } else {
                await dialog.dismiss();
            }
            resolve(message);
        });
    });
}

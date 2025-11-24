import { chromium, expect } from '@playwright/test';

const LOGIN_URL = 'http://127.0.0.1:8000/login';
const USER_EMAIL = 'admin@jti.com';
const USER_PASSWORD = 'password';
const AUTH_FILE = 'playwright/.auth/user.json';

export default async function level() {
    const browser = await chromium.launch();
    const page = await browser.newPage();

    console.log('Melakukan Login dan Menyimpan Sesi.');

    await page.goto(LOGIN_URL, {timeout: 60000});

    await page.fill('input')
}
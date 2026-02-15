import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
    test('can render login page', async ({ page }) => {
        await page.goto('/login');
        await expect(page.locator('input[type="email"]')).toBeVisible();
        await expect(page.locator('input[type="password"]')).toBeVisible();
    });

    test('can render registration page', async ({ page }) => {
        await page.goto('/register');
        await expect(page.locator('input[name="name"]')).toBeVisible();
        await expect(page.locator('input[type="email"]')).toBeVisible();
    });

    test('can register a new user', async ({ page }) => {
        await page.goto('/register');

        await page.fill('input[name="name"]', 'E2E Test User');
        await page.fill('input[type="email"]', `e2e-${Date.now()}@test.com`);
        await page.fill('input[name="password"]', 'password123');
        await page.fill('input[name="password_confirmation"]', 'password123');

        await page.locator('button[type="submit"]').click();

        // Should redirect to dashboard -> browse
        await page.waitForURL('**/browse');
        await expect(page).toHaveURL(/\/browse/);
    });

    test('can login with valid credentials', async ({ page }) => {
        // First register
        await page.goto('/register');
        const email = `login-${Date.now()}@test.com`;
        await page.fill('input[name="name"]', 'Login Test');
        await page.fill('input[type="email"]', email);
        await page.fill('input[name="password"]', 'password123');
        await page.fill('input[name="password_confirmation"]', 'password123');
        await page.locator('button[type="submit"]').click();
        await page.waitForURL('**/browse');

        // Logout - use the desktop nav dropdown (first visible one)
        // Open dropdown first
        const dropdownTrigger = page.locator('.hidden.sm\\:flex button').first();
        await dropdownTrigger.click();
        await page.locator('.hidden.sm\\:flex button', { hasText: /Log Out/i }).first().click();
        await page.waitForURL('/');

        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', email);
        await page.fill('input[type="password"]', 'password123');
        await page.locator('button[type="submit"]').click();

        await page.waitForURL('**/browse');
        await expect(page).toHaveURL(/\/browse/);
    });

    test('unauthenticated users are redirected to login', async ({ page }) => {
        await page.goto('/browse');
        await expect(page).toHaveURL(/\/login/);

        await page.goto('/convert');
        await expect(page).toHaveURL(/\/login/);

        await page.goto('/history');
        await expect(page).toHaveURL(/\/login/);
    });
});

import { test, expect } from '@playwright/test';

test.describe('Admin Panel', () => {
    test('admin login page renders', async ({ page }) => {
        await page.goto('/admin/login');
        await expect(page.locator('input[type="email"]')).toBeVisible();
    });

    test('admin can login and see dashboard', async ({ page }) => {
        await page.goto('/admin/login');

        await page.fill('input[type="email"]', 'sergio.nader@gmail.com');
        await page.fill('input[type="password"]', 'test1234');
        await page.locator('button[type="submit"]').click();

        await page.waitForURL('**/admin');
        await expect(page).toHaveURL(/\/admin/);
    });

    test('non-admin cannot access admin panel', async ({ page }) => {
        // Register a regular user
        await page.goto('/register');
        const email = `nonadmin-${Date.now()}@test.com`;
        await page.fill('input[name="name"]', 'Regular User');
        await page.fill('input[type="email"]', email);
        await page.fill('input[name="password"]', 'password123');
        await page.fill('input[name="password_confirmation"]', 'password123');
        await page.locator('button[type="submit"]').click();
        await page.waitForURL('**/browse');

        // Try to access admin - should get a 403 page
        const response = await page.goto('/admin');
        expect(response?.status()).toBe(403);
    });
});

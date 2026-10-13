import { defineConfig } from 'astro/config';

const repository = process.env.GITHUB_REPOSITORY?.split('/')[1];
const owner = process.env.GITHUB_REPOSITORY?.split('/')[0];
const customDomain = process.env.ASTRO_CUSTOM_DOMAIN?.trim();
const isProjectPage = repository && !repository.endsWith('.github.io') && !customDomain;

export default defineConfig({
  output: 'static',
  trailingSlash: 'always',
  // Listen on both IPv4 and IPv6. Some browsers resolve localhost to
  // 127.0.0.1 while Astro's default may bind only to ::1 (or vice versa).
  server: {
    host: true,
  },
  site: customDomain
    ? `https://${customDomain}`
    : owner
      ? `https://${owner}.github.io`
      : 'https://yuzuback.com',
  ...(isProjectPage ? { base: `/${repository}` } : {}),
});

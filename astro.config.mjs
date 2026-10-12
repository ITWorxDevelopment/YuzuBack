import { defineConfig } from 'astro/config';

const repository = process.env.GITHUB_REPOSITORY?.split('/')[1];
const owner = process.env.GITHUB_REPOSITORY?.split('/')[0];
const customDomain = process.env.ASTRO_CUSTOM_DOMAIN?.trim();
const isProjectPage = repository && !repository.endsWith('.github.io') && !customDomain;

export default defineConfig({
  output: 'static',
  trailingSlash: 'always',
  site: customDomain
    ? `https://${customDomain}`
    : owner
      ? `https://${owner}.github.io`
      : 'https://backupist.app',
  ...(isProjectPage ? { base: `/${repository}` } : {}),
});

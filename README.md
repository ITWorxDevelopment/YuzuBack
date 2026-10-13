# YuzuBack website (Astro)

An Astro static site for YuzuBack. The project keeps the landing page, downloads, changelog, contact, and donation pages together with reusable components and the supplied app screenshots.

## Run locally

Install Node.js 24, then run:

```sh
npm install
npm run dev
```

The development server listens on both IPv4 and IPv6 so
`http://localhost:4321/` works regardless of which loopback address your
browser uses. It stays attached to the terminal; press `Ctrl+C` to stop it.

Create a production build with `npm run build`. The generated static website is in `dist/` and can be uploaded to any static web host.

## Publish with GitHub Pages

1. Create a GitHub repository and upload the contents of this folder to its `main` branch.
2. In the repository, open **Settings → Pages** and select **GitHub Actions** as the publishing source.
3. The included workflow builds the site and deploys `dist/` after each push to `main`.

The Astro configuration reads `GITHUB_REPOSITORY` in Actions and sets the correct base path for a project repository. A repository named `username.github.io` is handled as a root site.

### Optional custom domain

For the existing `yuzuback.com` domain, configure it as the custom domain in **Settings → Pages**, then add the DNS records GitHub provides at your domain provider. Also create the repository Actions variable `ASTRO_CUSTOM_DOMAIN` with the value `yuzuback.com`; this makes Astro build links for the domain root rather than a repository subpath. You can omit this variable if you want to use the default GitHub Pages URL.

GitHub Pages is free for public repositories. A custom domain is separate and must already be registered; domain renewal remains with its registrar.

## Edit the site

- `src/pages/` contains the five pages.
- `src/components/` contains the shared header and footer.
- `src/config/site.ts` contains shared product identity and navigation data.
- `src/layouts/SiteLayout.astro` provides the shared page shell and metadata.
- `src/styles/global.css` contains the site styles.
- `public/images/` contains the screenshots used by the landing page.

Replace a screenshot in `public/images/` and keep its filename, or update the corresponding `<img>` path in `src/pages/index.astro`.

## Content still to confirm

The download URLs, donation link, contact channel, and confirmed release dates/version numbers are placeholders pending the official project details.

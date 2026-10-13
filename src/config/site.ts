export const SITE = {
  name: "YuzuBack",
  description:
    "YuzuBack is a free desktop backup manager for Windows and Linux.",
  shortDescription: "A free desktop backup manager for Windows and Linux.",
  legal: "Freeware · Proprietary software",
} as const;

export const navigation = [
  { key: "overview", label: "Overview", path: "" },
  { key: "downloads", label: "Downloads", path: "downloads/" },
  { key: "changelog", label: "Changelog", path: "changelog/" },
  { key: "contact", label: "Contact", path: "contact/" },
] as const;

export type NavigationKey = (typeof navigation)[number]["key"];
